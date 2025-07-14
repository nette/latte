<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{

	public function main(array $ʟ_args): void
	{
%A%
		echo "\n";
		$ʟ_tag[0] = '';
		$ʟ_tmp = LR\HtmlHelpers::validateTagChange($tag, '');
		echo '<', $ʟ_tmp /* line %d% */;
		$ʟ_tag[0] = '</' . $ʟ_tmp . '>' . $ʟ_tag[0];
		echo '>...';
		echo $ʟ_tag[0];
		echo '

';
		$ʟ_tag[1] = '';
		$ʟ_tmp = LR\HtmlHelpers::validateTagChange($ns . ':' . $tag, '');
		echo '<', $ʟ_tmp /* line %d% */;
		$ʟ_tag[1] = '</' . $ʟ_tmp . '>' . $ʟ_tag[1];
		echo '>...';
		echo $ʟ_tag[1];
		echo '

';
		$ʟ_tag[2] = '';
		$ʟ_tmp = LR\HtmlHelpers::validateTagChange('h' . 1, '');
		echo '<', $ʟ_tmp /* line %d% */;
		$ʟ_tag[2] = '</' . $ʟ_tmp . '>' . $ʟ_tag[2];
		echo '>...';
		echo $ʟ_tag[2];
	}
%A%
}
