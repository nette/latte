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
		echo $ʟ_tmp = LR\Filters::safeTag($tag) /* line %d% */;
		$ʟ_tag[1] = '</' . $ʟ_tmp . '>' . $ʟ_tag[1];
		echo '>...';
		echo $ʟ_tag[1];
		echo '

';
		$ʟ_tag[2] = '';
		echo '<';
		echo $ʟ_tmp = LR\Filters::safeTag('a') /* line %d% */;
		$ʟ_tag[2] = '</' . $ʟ_tmp . '>' . $ʟ_tag[2];
		echo '>';
		$ʟ_tag[3] = '';
		echo '<';
		echo $ʟ_tmp = LR\Filters::safeTag('b') /* line %d% */;
		$ʟ_tag[3] = '</' . $ʟ_tmp . '>' . $ʟ_tag[3];
		echo '>...';
		echo $ʟ_tag[3];
		echo $ʟ_tag[2];
		echo ' ';
	}
%A%
}
