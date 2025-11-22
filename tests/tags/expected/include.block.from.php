<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{
%A%
		echo 'before ';
		$this->createTemplate('inc.ext', ['var' => 1] + $this->params, "include")->renderToContentType('html', 'bl') /* pos 1:8 */;
		echo ' after';
%A%
}
