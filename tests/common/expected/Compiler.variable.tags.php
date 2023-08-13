<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{

	public function main(array $ʟ_args): void
	{
%A%
		echo "\n";
		$ʟ_tag[0] = '';
		echo '<';
		echo $ʟ_tmp = LR\Filters::safeTag($tag) /* line %d% */;
		$ʟ_tag[0] = '</' . $ʟ_tmp . '>' . $ʟ_tag[0];
		echo '>...';
		echo $ʟ_tag[0];
		echo '

';
		$ʟ_tag[1] = '';
		echo '<';
		echo $ʟ_tmp = LR\Filters::safeTag($ns . ':' . $tag) /* line %d% */;
		$ʟ_tag[1] = '</' . $ʟ_tmp . '>' . $ʟ_tag[1];
		echo '>...';
		echo $ʟ_tag[1];
		echo '

';
		$ʟ_tag[2] = '';
		echo '<';
		echo $ʟ_tmp = LR\Filters::safeTag('h' . 1) /* line %d% */;
		$ʟ_tag[2] = '</' . $ʟ_tmp . '>' . $ʟ_tag[2];
		echo '>...';
		echo $ʟ_tag[2];
	}
%A%
}
