<?php
%A%
		echo '<ul>
	<li>';
		echo LR\Filters::escapeHtmlText($hello->{'item'}) /* line %d% */;
		echo '</li>
	<li>';
		echo LR\Filters::escapeHtmlText(function(){}) /* line %d% */;
		echo '</li>
	<li>';
		echo LR\Filters::escapeHtmlText(function(){return;
		}) /* line %d% */;
		echo '</li>
	<li>';
		echo LR\Filters::escapeHtmlText(function() {
			return;
		}) /* line %d% */;
		echo '</li>
	<li>';
		echo LR\Filters::escapeHtmlText(($this->filters->upper)(function(){return;
		})) /* line %d% */;
		echo '</li>
	<li>';
		echo LR\Filters::escapeHtmlText(($this->filters->upper)(function() {
			return;
		})) /* line %d% */;
		echo '</li>
	<li>';
		echo LR\Filters::escapeHtmlText(($this->filters->upper)(function() {
			return;
		}, $item->{10})) /* line %d% */;
		echo '</li>
</ul>
%A%
