# Latte internals

How Latte works underneath, for agents editing it. Independent emergent models with
their own non-local invariants, split by seam:

- **[compilation.md](compilation.md)** — the lexer state machine, code generation
  (`PrintContext::format`), the `getIterator` reference rule, `AuxiliaryNode`,
  feature flags, and the compiled-template cache.
- **[escaping.md](escaping.md)** — context-sensitive escaping, the two-place
  invariant, the `PrintContext` escaper stack, and the dynamic-attribute formatters.
- **[blocks.md](blocks.md)** — template inheritance, the block layers, and the exact
  runtime availability model.
- **[sandbox-and-passes.md](sandbox-and-passes.md)** — the sandbox pass, its known
  `__toString` gap, and how extensions/passes are ordered.
- **[whitespace-minifier.md](whitespace-minifier.md)** — the streaming minifier and
  its chunk-safety invariant.
