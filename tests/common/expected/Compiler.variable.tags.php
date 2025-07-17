<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{

	public function main(array $ʟ_args): void
	{
%A%
		echo "\n";
		$ʟ_tag[0] = '';
		$ʟ_tmp = LR\HtmlHelpers::validateTagChange($tag);
		$ʟ_tag[0] = '</' . $ʟ_tmp . '>' . $ʟ_tag[0];
		echo '<', $ʟ_tmp /* line %d% */;
		echo '>...';
		echo $ʟ_tag[0];
		echo '

';
		$ʟ_tag[1] = '';
		$ʟ_tmp = LR\HtmlHelpers::validateTagChange($ns . ':' . $tag);
		$ʟ_tag[1] = '</' . $ʟ_tmp . '>' . $ʟ_tag[1];
		echo '<', $ʟ_tmp /* line %d% */;
		echo '>...';
		echo $ʟ_tag[1];
		echo '

';
		$ʟ_tag[2] = '';
		$ʟ_tmp = LR\HtmlHelpers::validateTagChange('h' . 1);
		$ʟ_tag[2] = '</' . $ʟ_tmp . '>' . $ʟ_tag[2];
		echo '<', $ʟ_tmp /* line %d% */;
		echo '>...';
		echo $ʟ_tag[2];
	}
%A%
}
