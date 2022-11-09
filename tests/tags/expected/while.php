<?php
%A%
		while ($i++ < 10) /* line 2 */ {
			echo '	';
			echo LR\Filters::escapeHtmlText($i) /* line 3 */;
			echo "\n";

		}
		echo '

';
		do /* line 7 */ {
			echo '	';
			echo LR\Filters::escapeHtmlText($i) /* line 8 */;
			echo "\n";

		}
		while ($i++ < 10);
		echo '

';
		while ($i++ < 10) /* line 12 */ {
			if (true) /* line 13 */ break;
			if (true) /* line 14 */ continue;
			echo '	';
			echo LR\Filters::escapeHtmlText($i) /* line 15 */;
			echo "\n";

		}
%A%
