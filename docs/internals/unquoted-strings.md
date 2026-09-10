# Unquoted strings

Several tags take a **name** where PHP would demand a string literal:
`{include foo.latte}`, `{link Product:detail}`, `{control cart:small}`,
`{block #sidebar}`, `{extends ../layout.latte}`. None of those arguments is valid
PHP, yet all of them must parse — and the *same* argument position must still
accept an arbitrary PHP expression (`{include $file}`, `{link $dest}`).

That dual reading is implemented by `TagParser::parseUnquotedStringOrExpression()`
on top of `TagLexer::tokenizeUnquotedString()`. It is the single most
back-compatibility-loaded corner of the parser, and its failure mode is silent, so
read this before touching either.

## Why it exists

Latte 1 and 2 had no tokenizer and no AST: tags were parsed by regular expressions,
at first ad hoc per tag, later unified — and the unification already bought
compromises, because one expression had to serve names and PHP alike. Latte 3
replaced all of it with a PHP-like lexer and an LALR parser, which made the
compromise structural: a PHP grammar cannot accept `foo.latte` as a name, because
PHP reads it as a concatenation.

Rewriting the grammar was never the constraint — **the installed base was**. Millions
of templates contain `{include foo.latte}`, `{link Foo: 123}`, `{block #x}`, and they
had to keep compiling. So the unquoted string is not a language feature that was
designed; it is a compatibility surface that was *reconstructed* on a foundation that
does not natively support it.

The reconstruction is not complete, and where it could not reach, the surface was
narrowed on purpose — a variable inside a name being the notable case. Those cuts are
listed in the Latte 2 → 3 migration guide, and the limits at the end of this file are
the same boundary seen from the implementation side. Every rule below follows from
that tension.

## The two views of a tag, and why it matters

`TagParser::__construct()` keeps two representations of the same tag, built together
in `filterTokens()`:

- **`$text`** — the raw text, concatenated from **all** tokens, whitespace included.
- **`$stream`** — the tokens with whitespace and comments **removed**.

The unquoted-string decision runs a regex over `$text`; everything afterwards runs
over `$stream`. **Whitespace therefore exists only during that regex.** This is a
hard invariant with a direct consequence: any rule that wants to distinguish `foo +1`
from `foo + 1` must live in that regex, because by the time the parser sees the
tokens, the spaces are gone. It also explains why, after the regex declines, spacing
stops mattering entirely.

## How a name becomes a string

`tokenizeUnquotedString()` matches a name and then does something unobvious: it
**wraps the match in double quotes and re-tokenizes it as a PHP string literal**.

That single trick buys interpolation for free — `{$dir}/foo.latte` becomes the PHP
literal `"{$dir}/foo.latte"`, handled by the ordinary string machinery, which is why
`\{\$…}` appears inside the name character class. The returned `Position` is shifted
back by one column and one offset to compensate for the added opening quote, so error
messages and node positions still point into the original template.

Two guards sit around it:

- **`!is_numeric($match[0])`** — digits are name characters, so without it `{include 5}`
  would compile to `'5'` instead of the number `5`.
- **`::` is excluded** from the name class (`:(?!:)`), so `Foo::CONST` stays a class
  constant instead of being swallowed as a name. This guard exists only in the
  `colon: true` variant; the other one has no colon in the name class at all, so it
  has nothing to exclude.

Back in `parseUnquotedStringOrExpression()`, the match is a text offset that must be
translated into stream positions: `strlen($parser->text) - 2` removes the two added
quotes, and the resulting `$end` drives a loop that consumes tokens from the main
stream until it passes that offset. This is the seam where the whitespace-less stream
is re-synchronized with the whitespace-carrying text.

## The decision rule

The regex, and only the regex, decides. It has two halves:

1. **What a name may contain** — `[./@_a-z0-9#!-]`, plus `:(?!:)` and `{$…}`. Note
   that `.`, `-`, `@`, `#` and `!` are *name* characters, which is what makes
   `foo-bar.latte`, `@layout.latte`, `#sidebar` and `change!` single names.
2. **What may follow it** — a lookahead listing the characters that may legally begin
   whatever comes after the name.

The second half is a **whitelist**, and it is where the traps live. It has three
alternatives:

- `\s+[!"'$(\[{,\\|~\w-]` — whitespace, then a character that can start an argument.
  The list is **incomplete on purpose**; see Limits.
- `[,|]` — a comma or a filter pipe, with **no whitespace required**. This is why a
  comma always works and always terminates the name, whatever follows it.
- `\s*$` — end of tag.

**The match is all-or-nothing.** The quantifier is possessive, but that is incidental;
what matters is that there is no "try a shorter name" fallback. If the lookahead does
not fit, `null` is returned and the whole argument is re-read as a PHP expression from
the *untouched* stream.

## Why failure is silent

The fallback should be loud, and it is not, because of an independent decision in the
grammar (`grammar/grammar/php.y`):

```
scalar: T_IDENTIFIER    { $$ = Scalar\String[$1]; }
```

**A bareword is a string in Latte, not a constant.** (`ConstantFetch` requires
`T_CONSTANT`, which `TagLexer::tokenizeCode()` only produces for ALL-CAPS
identifiers.) So when the unquoted-string path declines, the expression path produces
a `StringNode` too — just a *different* one. Two independent routes lead to a string,
and the second one masks the first one's refusal. Had the fallback yielded a constant
fetch, every one of these cases would have blown up at runtime on an undefined
constant instead of quietly generating a wrong link.

## What decides the shape of the damage

When the regex declines, the outcome depends on a single property of the first token
the expression parser stops at:

| first token after the name | effect | example |
|---|---|---|
| **prefix operator** (`!`) | expression ends before it; the operator becomes the start of the *arguments* | `change! +1` → `link('change', [!+1])` |
| **infix operator** (`+`, `%`, `*`) | expression continues and swallows the argument; arguments end up empty | `change +1` → `link('change' + 1)` |
| **neither** (`:`) | expression ends, arguments cannot start there | `Foo:bar +1` → `CompileException` |

The first row is the dangerous one: nothing is lost or reported, the destination
merely loses its trailing `!` and the argument silently becomes a negation. `!+1` is
`false`, so the link is generated with an argument of `0` and the template looks like
it works. That the destination is still accepted downstream (a component treats a
destination without `!` as a signal anyway) is what lets it reach production.

## `$colon`: the same name, two readings

The `$colon` parameter switches between the two regex variants because a colon means
opposite things in different tags:

- **`colon: true`** (default; `{link}`, `n:href`) — the colon is **part of the name**.
  `Presenter:action` is one destination.
- **`colon: false`** (`{control}` in nette/application) — the colon is a **separator**.
  `ControlNode` parses the component name, then does `tryConsume(':')` and parses the
  method itself, so `cart:small` must arrive as `cart` + `:` + `small`.

Mechanically the second variant drops `:` from the name class and adds it to the
no-whitespace alternative (`[,:|]`), which ends the name immediately before the colon.

| input | `colon: true` | `colon: false` |
|---|---|---|
| `Foo:bar` | `'Foo:bar'` | `'Foo'` + method `'bar'` |
| `cart:small` | `'cart:small'` | `'cart'` + method `'small'` |

## Limits

These are known and, for the most part, deliberate. Do not "fix" one without checking
it against the compatibility surface above.

- **A bare `$var` is never part of a name**, wherever it stands — not just at the
  start. `$` is not in the name class, so the name ends there and PHP takes over:
  `{include path/$name.latte}` compiles to `'path' / $name . 'latte'`, a division.
  This is the one place where the compatibility surface was deliberately **narrowed**
  rather than preserved: Latte 2 accepted these, Latte 3 cannot, and the migration
  guide prescribes quotes (`{block "foo-$var"}`) or compound brackets
  (`{block foo-{$var}}`). Interpolation inside a name is available **only** in the
  `{$var}` form, which is the whole reason `\{\$…}` is in the name class.
- **The whitelist omits several unary forms, and that is not going to be fixed by
  extending it.** `+`, `@`, a float written `.5` and the spread `...` are all missing,
  so `{link change! +1}` silently compiles to `link('change', [!+1])`: the destination
  loses its trailing `!`, the argument becomes a negation, and the generated link
  carries `0`. `-` is in the list, which is why `{link change! -1}` works and makes the
  omission look arbitrary. Enumerating what may follow a name is a bottomless task, and
  every addition only moves the next silent failure somewhere else; the decision is to
  leave the list as it is and push the unambiguous spelling instead. See the
  `unquoted-string-needs-comma` decision.
- **`-` ignores spacing, by necessity.** `foo -1` and `foo - 1` both yield an
  argument, because `-` is unconditionally in the whitelist and narrowing it would
  break existing templates. Whitespace therefore cannot be used to tell a unary from a
  binary reading elsewhere either, without making the two signs disagree.
- **Without any whitespace the name simply absorbs the character**, since these are
  name characters: `change-1`, `change.5`, `change@1` and `change!-1` are all single
  names. `change!+1` is the odd one out — `+` is *not* a name character, so it is
  neither absorbed nor accepted, and it degrades to `[!+1]` as well.
- **The whitelist does not reach past the name.** In `{control cart:small +1}` the
  regex has already finished its job on `cart`; the method after the colon is parsed by
  a full `parseExpression()`, so it absorbs any infix operator — `'small' + 1`, and
  `'small' - 1` too. `ControlNode`'s `consumeCommaBeforeArguments(strict: false)`
  guard cannot report it, because the expression consumed the rest and the stream is
  already at the end.
- **A comma bypasses all of it.** `{link change!, +1}` and `{control cart:small, +1}`
  are unambiguous and always correct, which is why the tag signatures are documented
  as `{link destination [,] [params]}`.

## Do not extend the whitelist

The obvious repair for any of the above is to add the missing character to the
lookahead. **Don't.** The list enumerates what may follow a name, that enumeration can
never be complete, and each addition merely relocates the next silent failure — while
none of them reaches the second half of the problem at all, where the damage happens
past the name (`{control cart:small +1}`, whose method is parsed by a full expression).

The unambiguous spelling already exists and covers every case at once:

```latte
{link change!, +1}
{control cart:small, +1}
```

A comma is matched by the `[,|]` alternative, which needs no whitespace and is reached
before the whitelist is ever consulted. It is also where the tags are heading anyway:
`TagParser::consumeCommaBeforeArguments()` exists so that tags can make the comma
mandatory without a BC break, and `{extends}` and `{control}` already use it.

The reasoning, and the plan to have the linter insert those commas automatically, are
in the `unquoted-string-needs-comma` decision. If you are here because a template
behaves oddly, the answer is a comma, not a wider regex.
