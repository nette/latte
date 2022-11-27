<?php
%A%
		echo 'vars

';
		echo LR\Filters::escapeHtmlText($var['x']) /* line %d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->prop($var[ '' . change( 10 + inner() ) ], 'prop')->prop) /* line %d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->call([$var[ 0 + 1], 'method'])()) /* line %d% */;
		echo '
-';
%A%
