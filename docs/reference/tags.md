# Tags

The agent-facing catalog of tags: it maps each tag to its implementation. The main
table covers the default engine (`CoreExtension` + `SandboxExtension`, see
`Engine::__construct`); tags of the optional extensions shipped with the package
follow below. Usage and examples live in the public manual at latte.nette.org — do
not duplicate them here.

Table rows are generated: run `php docs/reference/generate.php` after adding or
removing a tag. Only the *Notes* column is hand-maintained and survives
regeneration.

<!-- generated: tags -->
| Tag | Implementation | Notes |
|---|---|---|
| `{=}` | `PrintNode::create` | the print tag: `{$x}`, `{expr}` |
| `{block}` | `BlockNode::create` |  |
| `{breakIf}` | `JumpNode::create` |  |
| `{capture}` | `CaptureNode::create` |  |
| `{contentType}` | `ContentTypeNode::create` |  |
| `{continueIf}` | `JumpNode::create` |  |
| `{debugbreak}` | `DebugbreakNode::create` |  |
| `{default}` | `VarNode::create` |  |
| `{define}` | `DefineNode::create` |  |
| `{do}` | `DoNode::create` |  |
| `{dump}` | `DumpNode::create` |  |
| `{embed}` | `EmbedNode::create` | loose content becomes an implicit `{block default}` |
| `{exitIf}` | `JumpNode::create` |  |
| `{extends}` | `ExtendsNode::create` | optional explicit variables for the parent template: `{extends file, var: value}` |
| `{first}` | `FirstLastSepNode::create` |  |
| `{for}` | `ForNode::create` |  |
| `{foreach}` | `ForeachNode::create` |  |
| `{if}` | `IfNode::create` |  |
| `{ifchanged}` | `IfChangedNode::create` |  |
| `{ifset}` | `IfNode::create` |  |
| `{import}` | `ImportNode::create` |  |
| `{include}` | `CoreExtension::includeSplitter` | dispatches to `IncludeBlockNode`/`IncludeFileNode` by inspecting the name; expression names need a comma before arguments (see [compilation internals](../internals/compilation.md)) |
| `{iterateWhile}` | `IterateWhileNode::create` |  |
| `{l}` | `closure in CoreExtension` | prints a literal `{` |
| `{last}` | `FirstLastSepNode::create` |  |
| `{layout}` | `ExtendsNode::create` | alias of `{extends}` |
| `n:attr` | `NAttrNode::create` |  |
| `n:class` | `NClassNode::create` |  |
| `n:else` | `NElseNode::create` | paired with its sibling element's n:if by the `nElse` pass |
| `n:elseif` | `NElseNode::create` | paired with its sibling element's n:if by the `nElse` pass |
| `n:ifcontent` | `IfContentNode::create` |  |
| `n:tag` | `NTagNode::create` |  |
| `{parameters}` | `ParametersNode::create` |  |
| `{php}` | `DoNode::create` | obsolete alias of `{do}` |
| `{r}` | `closure in CoreExtension` | prints a literal `}` |
| `{rollback}` | `RollbackNode::create` |  |
| `{sandbox}` | `SandboxNode::create` | tag is always registered; the sandbox pass itself activates only when a policy is set |
| `{sep}` | `FirstLastSepNode::create` |  |
| `{skipIf}` | `JumpNode::create` |  |
| `{spaceless}` | `SpacelessNode::create` | streaming minifier (see [whitespace-minifier internals](../internals/whitespace-minifier.md)) |
| `{switch}` | `SwitchNode::create` |  |
| `{syntax}` | `CoreExtension::parseSyntax` | switches the lexer syntax on the fly (also as n:syntax) |
| `{templatePrint}` | `TemplatePrintNode::create` |  |
| `{templateType}` | `TemplateTypeNode::create` |  |
| `{trace}` | `TraceNode::create` |  |
| `{try}` | `TryNode::create` |  |
| `{var}` | `VarNode::create` |  |
| `{varPrint}` | `VarPrintNode::create` |  |
| `{varType}` | `VarTypeNode::create` |  |
| `{while}` | `WhileNode::create` |  |
<!-- /generated -->

## Optional extensions

<!-- generated: tags-optional -->
| Tag | Extension | Implementation | Notes |
|---|---|---|---|
| `{_}` | `TranslatorExtension` | `TranslatorExtension::parseTranslate` | translates an expression |
| `{php}` | `RawPhpExtension` | `RawPhpNode::create` | raw PHP in templates; overrides Core's obsolete `{php}` (last-registered-wins) |
| `{translate}` | `TranslatorExtension` | `closure in TranslatorExtension` |  |
<!-- /generated -->
