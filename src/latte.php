<?php

declare(strict_types=1);

spl_autoload_register(function ($className) {
	static $classMap = [
		Latte\CompileException::class => 'exceptions.php',
		Latte\Compiler::class => 'Compiler/Compiler.php',
		Latte\Engine::class => 'Engine.php',
		Latte\Helpers::class => 'Helpers.php',
		Latte\HtmlNode::class => 'Compiler/HtmlNode.php',
		Latte\ILoader::class => 'ILoader.php',
		Latte\IMacro::class => 'IMacro.php',
		Latte\Loaders\FileLoader::class => 'Loaders/FileLoader.php',
		Latte\Loaders\StringLoader::class => 'Loaders/StringLoader.php',
		Latte\MacroNode::class => 'Compiler/MacroNode.php',
		Latte\Macros\BlockMacros::class => 'Macros/BlockMacros.php',
		Latte\Macros\CoreMacros::class => 'Macros/CoreMacros.php',
		Latte\Macros\MacroSet::class => 'Macros/MacroSet.php',
		Latte\MacroTokens::class => 'Compiler/MacroTokens.php',
		Latte\Parser::class => 'Compiler/Parser.php',
		Latte\PhpHelpers::class => 'Compiler/PhpHelpers.php',
		Latte\PhpWriter::class => 'Compiler/PhpWriter.php',
		Latte\RegexpException::class => 'exceptions.php',
		Latte\Runtime\CachingIterator::class => 'Runtime/CachingIterator.php',
		Latte\Runtime\FilterExecutor::class => 'Runtime/FilterExecutor.php',
		Latte\Runtime\FilterInfo::class => 'Runtime/FilterInfo.php',
		Latte\Runtime\Filters::class => 'Runtime/Filters.php',
		Latte\Runtime\Html::class => 'Runtime/Html.php',
		Latte\Runtime\IHtmlString::class => 'Runtime/IHtmlString.php',
		Latte\Runtime\ISnippetBridge::class => 'Runtime/ISnippetBridge.php',
		Latte\Runtime\SnippetDriver::class => 'Runtime/SnippetDriver.php',
		Latte\Runtime\Template::class => 'Runtime/Template.php',
		Latte\RuntimeException::class => 'exceptions.php',
		Latte\Strict::class => 'Strict.php',
		Latte\Token::class => 'Compiler/Token.php',
		Latte\TokenIterator::class => 'Compiler/TokenIterator.php',
		Latte\Tokenizer::class => 'Compiler/Tokenizer.php',
	];

	if (isset($classMap[$className])) {
		require __DIR__ . '/Latte/' . $classMap[$className];
	}
});
