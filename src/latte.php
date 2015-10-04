<?php

spl_autoload_register(function ($className) {
	static $classMap = [
		Latte\CompileException::class => 'exceptions.php',
		Latte\Compiler::class => 'Compiler.php',
		Latte\Engine::class => 'Engine.php',
		Latte\Helpers::class => 'Helpers.php',
		Latte\HtmlNode::class => 'HtmlNode.php',
		Latte\ILoader::class => 'ILoader.php',
		Latte\IMacro::class => 'IMacro.php',
		Latte\Loaders\FileLoader::class => 'Loaders/FileLoader.php',
		Latte\Loaders\StringLoader::class => 'Loaders/StringLoader.php',
		Latte\MacroNode::class => 'MacroNode.php',
		Latte\MacroTokens::class => 'MacroTokens.php',
		Latte\Macros\BlockMacros::class => 'Macros/BlockMacros.php',
		Latte\Macros\BlockMacrosRuntime::class => 'Macros/BlockMacrosRuntime.php',
		Latte\Macros\CoreMacros::class => 'Macros/CoreMacros.php',
		Latte\Macros\MacroSet::class => 'Macros/MacroSet.php',
		Latte\Strict::class => 'Strict.php',
		Latte\Parser::class => 'Parser.php',
		Latte\PhpWriter::class => 'PhpWriter.php',
		Latte\RegexpException::class => 'exceptions.php',
		Latte\RuntimeException::class => 'exceptions.php',
		Latte\Runtime\CachingIterator::class => 'Runtime/CachingIterator.php',
		Latte\Runtime\Filters::class => 'Runtime/Filters.php',
		Latte\Runtime\Html::class => 'Runtime/Html.php',
		Latte\Runtime\IHtmlString::class => 'Runtime/IHtmlString.php',
		Latte\Template::class => 'Template.php',
		Latte\Token::class => 'Token.php',
		Latte\TokenIterator::class => 'TokenIterator.php',
		Latte\Tokenizer::class => 'Tokenizer.php',
	];

	if (isset($classMap[$className])) {
		require __DIR__ . '/Latte/' . $classMap[$className];
	}
});
