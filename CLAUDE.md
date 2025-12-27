# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Latte is a secure, fast templating engine for PHP. The project is a compiler-based template system that parses Latte template syntax and generates optimized PHP classes with context-sensitive escaping.

**Key Features:**
- Context-sensitive automatic escaping (HTML, XML, JavaScript, CSS)
- Compiles templates to native PHP classes for performance
- Extensible via Extension system for custom tags, filters, and functions
- Built-in linter for template validation

## Essential Commands

### Running Tests

```bash
# Run all tests
vendor/bin/tester . -C -s -C

# Run specific test directory
vendor/bin/tester tests/filters/ -C -s -C

# Run single test file
php tests/common/Compiler.errors.phpt

# Via composer
composer run tester
```

### Code Quality

```bash
# Run PHPStan static analysis
composer run phpstan

# Or directly (Windows)
phpstan.bat

# Lint Latte templates
vendor/bin/latte-lint path/to/templates
vendor/bin/latte-lint path/to/templates --strict
vendor/bin/latte-lint path/to/templates --debug
```

### Code Style

Follows Nette Coding Standard (based on PSR-12). See `ncs.xml` for configuration.

## Architecture Overview

### Core Compilation Pipeline

The template compilation follows this flow:

1. **TemplateLexer** (`src/Latte/Compiler/TemplateLexer.php`) - Tokenizes template source
2. **TemplateParser** (`src/Latte/Compiler/TemplateParser.php`) - Parses tokens into AST nodes
3. **NodeTraverser** (`src/Latte/Compiler/NodeTraverser.php`) - Traverses and transforms AST
4. **TemplateGenerator** (`src/Latte/Compiler/TemplateGenerator.php`) - Generates PHP class code
5. **Cache** (`src/Latte/Cache.php`) - Stores compiled templates

**Entry point:** `Engine::render()` or `Engine::renderToString()` in `src/Latte/Engine.php`

### Directory Structure

```
src/
├── Latte/
│   ├── Engine.php              ← Main API entry point
│   ├── Extension.php           ← Base class for extensions
│   ├── Compiler/               ← Template compilation
│   │   ├── TemplateLexer.php   ← Tokenization
│   │   ├── TemplateParser.php  ← AST parsing
│   │   ├── TagParser.php       ← Tag expression parsing
│   │   ├── TemplateGenerator.php ← PHP code generation
│   │   └── Nodes/              ← AST node classes
│   ├── Essential/              ← Core tags and filters
│   │   ├── CoreExtension.php   ← Built-in tags ({if}, {foreach}, etc.)
│   │   ├── Filters.php         ← Built-in filters (|upper, |date, etc.)
│   │   └── Nodes/              ← Node implementations for tags
│   ├── Runtime/                ← Template runtime support
│   │   ├── FilterExecutor.php  ← Filter execution
│   │   └── Helpers.php         ← Runtime escaping/helpers
│   └── Sandbox/                ← Security policy enforcement
├── Tools/
│   ├── Linter.php              ← Template linter
│   └── LinterExtension.php     ← Validation of filters/functions/classes
└── Bridges/
	└── Tracy/                  ← Tracy debugger integration
```

### Node System

All template constructs are represented as nodes inheriting from `Compiler\Node`:

- **TemplateNode** - Root node containing head and main fragments
- **FragmentNode** - Container for child nodes
- **StatementNode** - Executable statements (loops, conditions, etc.)
- **ExpressionNode** - PHP expressions in Latte syntax
- **PrintNode** - Output expressions
- **TextNode** - Static text content

Nodes are organized in `src/Latte/Compiler/Nodes/`:
- `Php/` - PHP-like expressions (arrays, variables, operators)
- `Html/` - HTML-specific nodes (tags, attributes)
- Tag implementations in `Essential/Nodes/` (IfNode, ForeachNode, etc.)

### Extension System

Extensions add functionality via `Extension` base class:

- **getTags()** - Register custom tag parsers (`{myTag}`)
- **getFilters()** - Register filters (`|myFilter`)
- **getFunctions()** - Register functions (`myFunction()`)
- **getPasses()** - Register AST transformation passes
- **beforeCompile()** - Hook before compilation
- **beforeRender()** - Hook before template rendering

Core extensions:
- **CoreExtension** - Essential tags ({if}, {foreach}, {var}, etc.)
- **SandboxExtension** - Security policy enforcement
- **TranslatorExtension** - Translation support
- **RawPhpExtension** - Raw PHP code blocks

### Essential Directory Structure

The `src/Latte/Essential/` directory contains core functionality for Latte templates.

#### Root Level Files

**Extensions:**
- **CoreExtension.php** - Main extension registering all built-in tags, filters, and functions
- **TranslatorExtension.php** - Extension for translations ({_} tag, |translate filter)
- **RawPhpExtension.php** - Extension for {php} tag with raw PHP code

**Filters and Passes:**
- **Filters.php** - Implementation of all built-in filters (|upper, |lower, |truncate, |date, etc.)
- **Passes.php** - Compiler passes (customFunctionsPass, checkUrlsPass, forbiddenVariablesPass, etc.)

**Runtime Support:**
- **CachingIterator.php** - Enhanced iterator for {foreach} with properties ($first, $last, $counter, $odd, $even, etc.)
- **AuxiliaryIterator.php** - Helper iterator for key-value pairs
- **Tracer.php** - Runtime debugging/trace support for {trace} tag
- **RollbackException.php** - Exception type used by {rollback} tag

**Code Generation:**
- **Blueprint.php** - Generates blueprint/skeleton of template parameter classes ({templatePrint})

#### Node Implementations (Essential/Nodes/)

**Control Flow:**
- **IfNode.php** - {if}, {elseif}, {else}, {ifset}, {elseifset}
- **ForeachNode.php** - {foreach} loops with {else}
- **ForNode.php** - {for} loops
- **WhileNode.php** - {while} loops
- **IterateWhileNode.php** - {iterateWhile} - combines iteration with condition
- **SwitchNode.php** - {switch}, {case}, {default}
- **JumpNode.php** - {breakIf}, {continueIf}, {skipIf}, {exitIf}
- **TryNode.php** - {try} exception handling blocks
- **RollbackNode.php** - {rollback} - abort template rendering

**Block and Template Structure:**
- **BlockNode.php** - {block} definitions for template inheritance
- **DefineNode.php** - {define} local block definitions
- **EmbedNode.php** - {embed} template embedding with blocks
- **ExtendsNode.php** - {extends}/{layout} template inheritance
- **IncludeBlockNode.php** - {include block #name}
- **IncludeFileNode.php** - {include 'file.latte'}
- **ImportNode.php** - {import} blocks from other templates

**HTML/Attribute Tags:**
- **NAttrNode.php** - {n:attr} dynamic HTML attributes
- **NClassNode.php** - {n:class} conditional CSS classes
- **NTagNode.php** - {n:tag} dynamic tag names
- **NElseNode.php** - {n:else}/{n:elseif} attribute variants

**Type System:**
- **ParametersNode.php** - {parameters Type $var} declarations
- **TemplateTypeNode.php** - {templateType ClassName} declarations
- **TemplatePrintNode.php** - {templatePrint} blueprint generation
- **VarTypeNode.php** - {varType Type $var} local variable types
- **VarPrintNode.php** - {varPrint} print variable information

**Variables and Output:**
- **VarNode.php** - {var}/{default} variable declarations
- **DoNode.php** - {do}/{php} execute code without output (obsolete: use {do} instead)
- **CaptureNode.php** - {capture $var} capture output to variable
- **CustomFunctionCallNode.php** - AST wrapper for custom function calls

**Debugging:**
- **DumpNode.php** - {dump} variable dumping
- **DebugbreakNode.php** - {debugbreak} debugger breakpoint
- **TraceNode.php** - {trace} template location tracking

**Content Manipulation:**
- **ContentTypeNode.php** - {contentType html|xml|js|css|ical|text} context switching
- **SpacelessNode.php** - {spaceless} remove whitespace between HTML tags
- **TranslateNode.php** - {translate} translation blocks

**Loop Helpers:**
- **FirstLastSepNode.php** - {first}, {last}, {sep} in loops
- **IfChangedNode.php** - {ifchanged} detect value changes in loops
- **IfContentNode.php** - {n:ifcontent} conditional rendering based on content

**Raw PHP:**
- **RawPhpNode.php** - {php} raw PHP code blocks (requires RawPhpExtension)

### Content Types and Escaping

`ContentType` enum defines context-aware escaping:
- **Html** - HTML context (default)
- **Xml** - XML context
- **JavaScript** - JavaScript context
- **Css** - CSS context
- **ICal** - iCalendar context
- **Text** - Plain text (no escaping)

Escaping logic in `Compiler/Escaper.php` and runtime escaping in `Runtime/Helpers.php`.

## Testing Guidelines

Tests use **Nette Tester** with `.phpt` extension. Each test file is standalone.

### Test Structure

```php
<?php
declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

test('description of what is tested', function () {
	$object = new SomeClass();
	$result = $object->doSomething();

	Assert::same('expected', $result);
});
```

### Test Organization

```
tests/
├── bootstrap.php           ← Test initialization
├── helpers.php             ← Test helper functions
├── common/                 ← Core engine and compiler tests
├── filters/                ← Filter tests
├── phpParser/              ← PHP expression parser tests
├── phpPrint/               ← PHP code generation tests
├── runtime/                ← Runtime helper tests
├── sandbox/                ← Security sandbox tests
└── tags/                   ← Tag implementation tests
```

### Testing Exceptions

```php
testException('description', function () {
	$mapper = new SomeClass();
	$mapper->throwingMethod();
}, ExpectedException::class, 'Expected message pattern %a%');
```

## Development Patterns

### Adding a New Tag

1. Create node class in `src/Latte/Essential/Nodes/` extending `StatementNode`
2. Implement `print()` method to generate PHP code
3. Register parser in extension's `getTags()` method
4. Add tests in `tests/tags/`

### Adding a Filter

1. Add function to `src/Latte/Essential/Filters.php` or custom extension
2. Register in extension's `getFilters()` method
3. Add tests in `tests/filters/`

### Working with AST

Use `NodeTraverser` to traverse/transform nodes:

```php
$traverser = new Compiler\NodeTraverser;
$traverser->traverse($node, function ($node) {
	// Transform or inspect nodes
});
```

### Parser vs Generator

- **Parser** (`TemplateParser`, `TagParser`) - Converts template source to AST nodes
- **Generator** (`TemplateGenerator`) - Converts AST nodes to PHP code
- **PrintContext** - Manages code generation context (escaping, content type)

## Code Conventions

- PHP 8.2+ required
- Use `declare(strict_types=1)` in every file
- Two empty lines between methods
- Document shut-up operator: `@mkdir($dir); // @ - directory may already exist`
- Use tabs for indentation
- Return type and opening brace on separate lines for methods
- Use PascalCase for classes, camelCase for methods/properties

### Documentation Style

- Skip phpDoc when signature is self-explanatory
- Use `/** @var Type[] */` for arrays of specific types
- Document only when adding value beyond type hints
- Start method docs with 3rd person singular verb (Returns, Formats, Creates)

## Key Files to Understand

When working on specific features:

**Template Parsing:**
- `src/Latte/Compiler/TemplateLexer.php` - Token definitions
- `src/Latte/Compiler/TemplateParser.php` - Template structure parsing
- `src/Latte/Compiler/TagParser.php` - Expression parsing within tags

**Code Generation:**
- `src/Latte/Compiler/TemplateGenerator.php` - PHP class generation
- `src/Latte/Compiler/PrintContext.php` - Code generation context
- `src/Latte/Compiler/PhpHelpers.php` - PHP code manipulation

**Runtime:**
- `src/Latte/Runtime/Template.php` - Base template class
- `src/Latte/Runtime/FilterExecutor.php` - Filter execution
- `src/Latte/Runtime/Helpers.php` - Escaping and runtime utilities

**Security:**
- `src/Latte/Sandbox/` - Sandbox implementation
- `src/Latte/Policy.php` - Security policy interface

## Common Pitfalls

- Don't modify generated templates in `tmp/` - they're auto-generated
- When adding escaping, update both `Compiler/Escaper.php` (compile-time) and `Runtime/Helpers.php` (runtime)
- Tag parsers must handle both opening and closing tags (paired tags)
- Position tracking is crucial for error messages - preserve `Position` objects
- Extension order matters - use `Extension::order()` to control execution sequence

## Extension System Deep Dive

### Extension Lifecycle Methods

Extensions must extend `Latte\Extension` and can implement these methods:

**Compilation Phase:**
- `beforeCompile(Engine $engine): void` - Called before template compilation starts
- `getTags(): array` - Returns array of tag parsers: `['tagName' => callable]`
- `getPasses(): array` - Returns array of compiler passes: `['passName' => callable]`
- `getCacheKey(Engine $engine): mixed` - Returns value that affects cache file versioning

**Runtime Phase:**
- `beforeRender(Runtime\Template $template): void` - Called before each template render
- `getFilters(): array` - Returns filters: `['filterName' => callable]`
- `getFunctions(): array` - Returns functions: `['functionName' => callable]`
- `getProviders(): array` - Returns runtime providers: `['providerName' => value]`

### Registration and Ordering

Register extensions with `Engine::addExtension()`. If multiple extensions define the same tag/filter/function, the last registered wins.

Use `Extension::order()` to control execution order for tags and passes:

```php
public function getTags(): array
{
	return [
		'foo' => self::order(FooNode::create(...), before: 'bar'),
		'bar' => self::order(BarNode::create(...), after: ['block', 'snippet']),
	];
}

public function getPasses(): array
{
	return [
		'sandbox' => self::order($this->sandboxPass(...), before: '*'), // before all
		'optimize' => self::order($this->optimizePass(...), after: '*'), // after all
	];
}
```

### Direct Registration (Simple Cases)

For quick additions without creating an Extension:

```php
$latte = new Latte\Engine;
$latte->addFilter('truncate', fn(string $s, int $len = 50) => mb_substr($s, 0, $len));
$latte->addFunction('hasPermission', fn(string $resource, string $action) => /* ... */);
```

## Creating Custom Tags

### Core Concepts

**Tags vs Nodes Analogy:**
- **Tags** = Syntax in `.latte` files (like HTML tags in source)
- **Nodes** = AST representation (like DOM nodes in browser)
- Tags are parsed into Nodes which generate PHP code

### The Three Components

**1. Node Class** (extends `StatementNode`):
- Represents tag's logical function in AST
- Public properties store parsed arguments/content (other Node instances)
- `print(PrintContext $context): string` - Generates PHP code
- `getIterator(): \Generator` - Yields child nodes for compiler passes (MUST yield references!)

**2. Tag Parsing Function** (static method, typically `create()`):
- Receives `Tag` object with parser and metadata
- Uses `TagParser` (`$tag->parser`) to parse arguments
- Uses `yield` for paired tags to parse inner content
- Returns Node instance

**3. Registration** (via Extension):
```php
public function getTags(): array
{
	return [
		'mytag' => MyTagNode::create(...),
		'n:myattr' => MyAttrNode::create(...), // pure n:attribute
	];
}
```

### Minimal Tag Example

```php
class DatetimeNode extends StatementNode
{
	public ?ExpressionNode $format = null;

	public static function create(Tag $tag): self
	{
		$node = $tag->node = new self;
		if (!$tag->parser->isEnd()) {
			$node->format = $tag->parser->parseExpression();
		}
		return $node;
	}

	public function print(PrintContext $context): string
	{
		$formatNode = $this->format ?? new StringNode('Y-m-d H:i:s');
		return $context->format(
			'echo date(%node) %line;',
			$formatNode,
			$this->position,
		);
	}

	public function &getIterator(): \Generator
	{
		if ($this->format) {
			yield $this->format; // MUST yield reference!
		}
	}
}
```

### Parsing Tag Arguments with TagParser

`TagParser` (`$tag->parser`) provides methods for parsing tag content:

- `parseExpression(): ExpressionNode` - Parses PHP-like expression
- `parseUnquotedStringOrExpression(): ExpressionNode` - Parses expression or unquoted string
- `parseArguments(): ArrayNode` - Parses comma-separated args with optional keys
- `parseModifier(): ModifierNode` - Parses filters (|upper|truncate:10)
- `parseType(): ?SuperiorTypeNode` - Parses PHP type hints
- `isEnd(): bool` - Checks if all arguments consumed
- `stream: TokenStream` - Low-level token stream access

Important: `TagParser` must consume ALL tokens. Use `$tag->expectArguments()` to require arguments.

### Paired Tags with `yield`

Use `yield` to parse content between start and end tags:

```php
public static function create(Tag $tag): \Generator
{
	$node = $tag->node = new self;

	// Yield to parse inner content until {/tag}
	[$node->content, $endTag] = yield;

	return $node;
}
```

**With intermediate tags** (like {if}...{else}...{/if}):

```php
public static function create(Tag $tag): \Generator
{
	$node = $tag->node = new self;

	// Yield and expect {else} or {/tag}
	[$node->thenContent, $nextTag] = yield ['else'];

	if ($nextTag?->name === 'else') {
		// Parse content between {else} and {/tag}
		[$node->elseContent, $endTag] = yield;
	}

	return $node;
}
```

### Providers - Runtime Data Access

Providers give tags access to runtime data/services. Registered in Extension:

```php
public function getProviders(): array
{
	return [
		'appDevMode' => $this->isDevelopmentMode,
		'appAuthz' => $this->authorizator,
	];
}
```

Access in tag's `print()` method via `$this->global->providerName`:

```php
public function print(PrintContext $context): string
{
	return $context->format(
		'if ($this->global->appDevMode) { %node }',
		$this->content,
	);
}
```

**Best Practice:** Use vendor prefix for provider names to avoid collisions (e.g., `appDevMode` not `devMode`).

### Temporary Variables and Nesting

When generating PHP code that needs temporary variables:

- Use `$ʟ_` prefix to avoid collisions with user variables
- Use `$context->generateId()` for unique variable names when tags can be nested:

```php
public function print(PrintContext $context): string
{
	$id = $context->generateId();
	$countVar = '$ʟ_count_' . $id;  // e.g., $ʟ_count_1, $ʟ_count_2
	$iterVar = '$ʟ_i_' . $id;       // e.g., $ʟ_i_1, $ʟ_i_2

	return $context->format(
		'%raw = (int)(%node); for (%2.raw = 0; %2.raw < %0.raw; %2.raw++) { %node }',
		$countVar,      // %0
		$this->count,   // %1
		$iterVar,       // %2
		$this->content, // %3
	);
}
```

### PrintContext::format() Placeholders

The `format()` method assembles PHP code with these placeholders:

- `%node` - Calls node's `print()` method, inserts resulting PHP code
- `%dump` - Exports PHP value to valid PHP code (for scalars, arrays)
- `%raw` - Inserts raw string without escaping (for variable names, PHP snippets)
- `%args` - Formats ArrayNode as function arguments (comma-separated)
- `%line` - Inserts `/* pos X:Y */` comment from Position object
- `%escape(...)` - Generates runtime escaping code for expression
- `%modify(...)` - Applies ModifierNode filters to expression
- `%modifyContent(...)` - Like %modify but for blocks of captured content

**Positional references:** Use `%0.node`, `%1.dump`, `%2.raw` to reference arguments by index.

### AuxiliaryNode - Hidden Code Generation

`AuxiliaryNode` allows generating PHP code that's hidden from compiler passes (like Sandbox):

```php
use Latte\Compiler\Nodes\Php\Expression\AuxiliaryNode;

// Code in closure is hidden from passes, but inputs are still traversable
$wrappedNode = new AuxiliaryNode(
	fn(PrintContext $context, $userExpr) => $context->format(
		'myInternalSanitize(%node)',
		$userExpr,
	),
	[$userExpr], // IMPORTANT: Must pass all nodes used by closure for traversal
);
```

Use for internal helper calls that shouldn't be validated by security passes.

### Tag Development Checklist

1. ✓ Node class extends `StatementNode`
2. ✓ Public properties for all parsed arguments/content
3. ✓ `create()` method returns node instance, assigns to `$tag->node`
4. ✓ `print()` returns PHP code string via `$context->format()`
5. ✓ `getIterator()` yields references (`&`) to ALL child nodes
6. ✓ Use `$this->position` for `%line` comments
7. ✓ Register in Extension's `getTags()`
8. ✓ Write tests

## Creating Custom Filters

Filters transform input values using pipe syntax: `{$var|filterName:arg1:arg2}`

### Registration Methods

**Direct:**
```php
$latte->addFilter('truncate', fn(string $s, int $len = 50) => mb_substr($s, 0, $len));
```

**Via Extension:**
```php
public function getFilters(): array
{
	return [
		'truncate' => $this->truncateFilter(...),
	];
}
```

**Via Attribute (on parameter class):**
```php
class TemplateParameters
{
	#[Latte\Attributes\TemplateFilter]
	public function truncate(string $s, int $len = 10): string { /* ... */ }
}
```

### Argument Passing

Value left of `|` is first argument. Values after `:` are subsequent arguments:
- `{$text|truncate}` → `truncate($text)`
- `{$text|truncate:30}` → `truncate($text, 30)`

### Contextual Filters

Filters needing content type awareness must have `FilterInfo` as first parameter:

```php
use Latte\Runtime\FilterInfo;
use Latte\ContentType;

$latte->addFilter('money', function (FilterInfo $info, float $amount): string {
	// Check input content type
	if (!in_array($info->contentType, [null, ContentType::Text], true)) {
		throw new \RuntimeException("Filter |money expects text, got {$info->contentType}");
	}

	// Generate HTML
	$html = '<i>' . htmlspecialchars(number_format($amount, 2) . ' EUR') . '</i>';

	// Declare output is HTML (disables auto-escaping)
	$info->contentType = ContentType::Html;

	return $html;
});
```

**Warning:** If filter generates HTML, YOU must escape input data. Setting `contentType = Html` disables auto-escaping.

**Block filters:** Filters applied to `{block}` MUST be contextual (receive `FilterInfo`).

## Creating Custom Functions

Functions provide reusable logic callable in template expressions: `{functionName($arg1, $arg2)}`

### Registration Methods

**Direct:**
```php
$latte->addFunction('hasPermission', fn(string $resource, string $action) => /* ... */);
```

**Via Extension:**
```php
public function getFunctions(): array
{
	return [
		'hasPermission' => $this->hasPermission(...),
	];
}
```

**Via Attribute (on parameter class):**
```php
class TemplateParameters
{
	#[Latte\Attributes\TemplateFunction]
	public function hasPermission(string $resource, string $action): bool { /* ... */ }
}
```

### When to Use Functions vs Filters vs Tags

- **Functions:** Calculations, generation, multi-argument logic (e.g., `clamp($val, $min, $max)`)
- **Filters:** Single value transformation (e.g., `{$text|upper}`)
- **Tags:** New language constructs, complex markup, control flow (e.g., `{mytag}...{/mytag}`)

Functions can access application services via closures or dependency injection in Extension instances.

## Compiler Passes

Compiler passes analyze/modify AST after parsing, before PHP code generation.

### Pass Structure

A pass is a callable accepting `TemplateNode`:

```php
public function getPasses(): array
{
	return [
		'myPass' => $this->myCompilerPass(...),
	];
}

public function myCompilerPass(Nodes\TemplateNode $templateNode): void
{
	// Use NodeTraverser to walk AST
	(new NodeTraverser)->traverse(
		$templateNode,
		enter: fn($node) => /* ... */,
		leave: fn($node) => /* ... */,
	);
}
```

### NodeTraverser - AST Traversal

`NodeTraverser` implements Visitor pattern for systematic AST traversal:

```php
use Latte\Compiler\NodeTraverser;

(new NodeTraverser)->traverse(
	$templateNode,

	enter: function (Node $node) {
		// Called before visiting node's children
		// Return values:
		// - null (or nothing): continue normally
		// - Node instance: replace current node
		// - NodeTraverser::RemoveNode: remove node
		// - NodeTraverser::DontTraverseChildren: skip children
		// - NodeTraverser::StopTraversal: stop entire traversal
	},

	leave: function (Node $node) {
		// Called after visiting node's children
		// Same return values as enter
	},
);
```

### Common Patterns

**Find all nodes of type:**
```php
use Latte\Compiler\NodeHelpers;

$variableNodes = NodeHelpers::find(
	$templateNode,
	fn($node) => $node instanceof VariableNode,
);
```

**Find first match:**
```php
$parametersNode = NodeHelpers::findFirst(
	$templateNode->head,
	fn($node) => $node instanceof ParametersNode,
);
```

**Modify node properties:**
```php
(new NodeTraverser)->traverse($templateNode, enter: function ($node) {
	if ($node instanceof TextNode) {
		$node->content = mb_strtoupper($node->content); // Modify in place
	}
});
```

**Replace nodes:**
```php
(new NodeTraverser)->traverse($templateNode, leave: function ($node) {
	if ($node instanceof ConstantFetchNode && (string) $node->name === 'PHP_VERSION') {
		$newNode = new StringNode(PHP_VERSION); // Compile-time constant inlining
		$newNode->position = $node->position;
		return $newNode; // Return replacement
	}
});
```

**Remove nodes:**
```php
(new NodeTraverser)->traverse($templateNode, enter: function ($node) {
	if ($node instanceof CommentNode) {
		return NodeTraverser::RemoveNode;
	}
});
```

### NodeHelpers Utilities

- `NodeHelpers::find(Node $start, callable $filter): array` - Find all matching nodes
- `NodeHelpers::findFirst(Node $start, callable $filter): ?Node` - Find first match
- `NodeHelpers::toValue(ExpressionNode $node, bool $constants = false): mixed` - Evaluate node at compile-time (literals only)
- `NodeHelpers::toText(?Node $node): ?string` - Extract plain text from TextNode/FragmentNode

### Pass Best Practices

- **Order matters:** Use `Extension::order()` to define pass dependencies
- **Single responsibility:** One pass = one task
- **Performance:** Use `DontTraverseChildren` and `StopTraversal` when possible
- **Error handling:** Throw `CompileException` or `SecurityViolationException` with `$node->position`
- **Idempotency:** Pass should produce same result when run multiple times (if possible)

## Type System

Latte supports type declarations for better IDE autocomplete and static analysis.

### Declaring Template Parameter Types

Create a parameter class and use `{templateType}`:

```php
class CatalogTemplateParameters
{
	public function __construct(
		public string $lang,
		/** @var ProductEntity[] */
		public array $products,
		public Address $address,
	) {}
}

$latte->render('template.latte', new CatalogTemplateParameters(/* ... */));
```

In template:
```latte
{templateType App\Templates\CatalogTemplateParameters}

{$lang} {* IDE knows this is string *}
{foreach $products as $product} {* IDE knows $product is ProductEntity *}
```

### Declaring Local Variable Types

```latte
{varType Nette\Security\User $user}
{varType string $lang}

{* Or inline with {var} *}
{var string $name = 'John'}
```

### Generating Type Declarations

**Generate parameter class:**
```latte
{templatePrint}
{* Renders class code instead of template - copy to your project *}
```

**Generate {varType} tags:**
```latte
{varPrint}     {* Lists local variables *}
{varPrint all} {* Lists all variables including parameters *}
```

## Development Workflow

### Template Compilation Process

You can manually step through compilation for debugging:

```php
$latte = new Latte\Engine;
$source = $latte->getLoader()->getContent($file);
$ast = $latte->parse($source);        // Parse to AST
$latte->applyPasses($ast);            // Run compiler passes
$code = $latte->generate($ast, $file); // Generate PHP code
```

### Custom Linter for Extensions

When using custom extensions, create a custom linter:

```php
#!/usr/bin/env php
<?php
require __DIR__ . '/vendor/autoload.php';

$path = $argv[1] ?? '.';

$linter = new Latte\Tools\Linter;
$latte = $linter->getEngine();

// Register custom extensions
$latte->addExtension(new MyCustomExtension);

$ok = $linter->scanDirectory($path);
exit($ok ? 0 : 1);
```

### Performance Tips

- Templates compile once, then cached - only first request is slow
- Use `$latte->setAutoRefresh(false)` in production
- Latte has built-in cache stampede prevention
- Generated PHP code is readable - you can step through it in debugger

### Tracy Integration

```php
Tracy\Debugger::enable();
$latte->addExtension(new Latte\Bridges\Tracy\TracyExtension);
```

Provides:
- Error screen with template line numbers
- Tracy Bar panel showing rendered templates
- Click-through to template source and compiled PHP

### Important Runtime Classes

- `Latte\Runtime\Template` - Base class for compiled templates, provides `$this->global` for providers
- `Latte\Runtime\FilterExecutor` - Executes filters at runtime
- `Latte\Runtime\Helpers` - Runtime escaping functions (use `LR\Helpers` alias in generated code)
- `Latte\Runtime\Html` - Wrapper for HTML strings to skip auto-escaping
