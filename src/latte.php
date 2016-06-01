<?php

spl_autoload_register(function ($className) {
	static $classMap = [
		'Latte\\CompileException' => 'exceptions.php',
		'Latte\\Compiler' => 'Compiler/Compiler.php',
		'Latte\\Engine' => 'Engine.php',
		'Latte\\Helpers' => 'Helpers.php',
		'Latte\\HtmlNode' => 'Compiler/HtmlNode.php',
		'Latte\\ILoader' => 'ILoader.php',
		'Latte\\IMacro' => 'IMacro.php',
		'Latte\\Loaders\\FileLoader' => 'Loaders/FileLoader.php',
		'Latte\\Loaders\\StringLoader' => 'Loaders/StringLoader.php',
		'Latte\\MacroNode' => 'Compiler/MacroNode.php',
		'Latte\\Macros\\BlockMacros' => 'Macros/BlockMacros.php',
		'Latte\\Macros\\CoreMacros' => 'Macros/CoreMacros.php',
		'Latte\\Macros\\MacroSet' => 'Macros/MacroSet.php',
		'Latte\\MacroTokens' => 'Compiler/MacroTokens.php',
		'Latte\\Parser' => 'Compiler/Parser.php',
		'Latte\\PhpHelpers' => 'Compiler/PhpHelpers.php',
		'Latte\\PhpWriter' => 'Compiler/PhpWriter.php',
		'Latte\\RegexpException' => 'exceptions.php',
		'Latte\\Runtime\\CachingIterator' => 'Runtime/CachingIterator.php',
		'Latte\\Runtime\\FilterExecutor' => 'Runtime/FilterExecutor.php',
		'Latte\\Runtime\\FilterInfo' => 'Runtime/FilterInfo.php',
		'Latte\\Runtime\\Filters' => 'Runtime/Filters.php',
		'Latte\\Runtime\\Html' => 'Runtime/Html.php',
		'Latte\\Runtime\\IHtmlString' => 'Runtime/IHtmlString.php',
		'Latte\\Runtime\\ISnippetBridge' => 'Runtime/ISnippetBridge.php',
		'Latte\\Runtime\\SnippetDriver' => 'Runtime/SnippetDriver.php',
		'Latte\\Runtime\\Template' => 'Runtime/Template.php',
		'Latte\\RuntimeException' => 'exceptions.php',
		'Latte\\Strict' => 'Strict.php',
		'Latte\\Token' => 'Compiler/Token.php',
		'Latte\\TokenIterator' => 'Compiler/TokenIterator.php',
		'Latte\\Tokenizer' => 'Compiler/Tokenizer.php',
	];

	if (isset($classMap[$className])) {
		require __DIR__ . '/Latte/' . $classMap[$className];
	}
});
