# Blocks & template inheritance

Blocks are organized into **layers** on the runtime `Template`, and the way they
merge up an `{extends}` chain determines a subtle, non-local availability model.

## Layers and resolution

The runtime layer keys are `LayerTop` (**the integer `0`**, not a string),
`LayerLocal` (`'local'`), and `LayerSnippet` (`'snippet'`). Numbered layers also
exist, but only as compile-time ids: each `{embed}` allocates one, it appears as a
key of the generated `Blocks` constant, and at render time `enterBlockLayer()`
swaps it in as the active `LayerTop`. A block lookup resolves **local before top**:
`blocks[LayerLocal][$name] ?? blocks[LayerTop][$name]`.
Both `{block}` and `{define}` register into the parser's *current block layer* —
`LayerTop` at template top level, the embed's numbered layer inside `{embed}`;
**only the `local` keyword** (`{block local name}`, `{define local name}`) targets
`LayerLocal`. The block/define difference lies elsewhere: `{block}` also renders in
place, `{define}` only registers.

## Merging up the extends chain (by reference)

When a template `{extends}` a parent, `createTemplate` copies the **parent's**
`LayerTop` blocks into the child's registry (`addBlock`, appending to `functions` so
the child's override stays first, checking content-type compatibility via the
convertor) and then **binds the parent's `LayerTop` by reference to the child's**, so
the whole chain shares one top-block registry. The same branch merges and binds
`LayerSnippet` too, and it runs for the `extends`/`includeblock`/`import`/`embed`
relations — notably **not** for a plain `include`. `LayerLocal` is **not** merged up.

## The availability model when rendering a single block

This is the emergent, empirically-verified fact:
`{include X from 'target'}` runs `target->render('X')` — **only that block, not
`main()`**. So availability is exactly the runtime's, not a safe superset:

- **`{import}` counts only from the template head.** A head `{import}` compiles
  into `prepare()`, which `render($block)` executes even for a single block — so
  its blocks *are* available through `include ... from`. But any content before it
  pushes the `{import}` into `main()`, which does not run then, and the include
  fails at runtime.
- **A local block of an *intermediate* template is NOT available by lookup.** Only
  `LayerTop` merges up, and the final lookup runs on the topmost parent, so only
  *its* `LayerLocal` applies. (It remains reachable from inside a block *body*
  declared in that same template — the block function executes bound to its
  defining instance.)

## {include parent}/{include this} in dynamically named blocks

Inside a block with a **static** name, `{include parent}`/`{include this}`
resolve the name at compile time from the closest enclosing block. Inside a
**dynamically named** block the compiler emits `null` and the runtime takes the
name from `Template::$renderingBlocks` — a stack of names of blocks currently
being rendered, pushed/popped by `renderBlock()` and **shared by reference
along the inheritance chain** (`createTemplate`, like the block registry; a
block function executes bound to its defining instance while `renderBlock` runs
on another one). Note the closest-block search skips dynamic blocks only when the
tag has a `from` clause (a compile-time name is required there); without `from`,
`{include parent}` nested in a dynamic block inside a static one refers to the
dynamic block's runtime name, not silently to the outer static block.
