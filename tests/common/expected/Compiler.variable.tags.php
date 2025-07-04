<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{

	public function main(array $ʟ_args): void
	{
%A%
		echo "\n";
		$ʟ_tag = '';
		$ʟ_tmp = LR\HtmlHelpers::validateTagChange($tag, '');
		$ʟ_tag = '</' . $ʟ_tmp . '>' . $ʟ_tag;
		echo '<', $ʟ_tmp /* line 3 */;
		echo '>';
		$ʟ_tags[0] = $ʟ_tag;
		echo '...';
		echo $ʟ_tags[0];
		echo '

';
		$ʟ_tag = '';
		$ʟ_tmp = LR\HtmlHelpers::validateTagChange($ns . ':' . $tag, '');
		$ʟ_tag = '</' . $ʟ_tmp . '>' . $ʟ_tag;
		echo '<', $ʟ_tmp /* line 5 */;
		echo '>';
		$ʟ_tags[1] = $ʟ_tag;
		echo '...';
		echo $ʟ_tags[1];
		echo '

';
		$ʟ_tag = '';
		$ʟ_tmp = LR\HtmlHelpers::validateTagChange('h' . 1, '');
		$ʟ_tag = '</' . $ʟ_tmp . '>' . $ʟ_tag;
		echo '<', $ʟ_tmp /* line 7 */;
		echo '>';
		$ʟ_tags[2] = $ʟ_tag;
		echo '...';
		echo $ʟ_tags[2];
	}
%A%
}
