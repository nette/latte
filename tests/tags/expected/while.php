<?php
%A%
		while ($i++ < 10) /* pos 2:1 */ {
			echo '	';
			echo LR\Filters::escapeHtmlText($i) /* pos 3:2 */;
			echo "\n";

		}
		echo '

';
		do /* pos 7:1 */ {
			echo '	';
			echo LR\Filters::escapeHtmlText($i) /* pos 8:2 */;
			echo "\n";

		}
		while ($i++ < 10);
		echo '

';
		while ($i++ < 10) /* pos 12:1 */ {
			if (true) /* pos 13:2 */ break;
			if (true) /* pos 14:2 */ continue;
			echo '	';
			echo LR\Filters::escapeHtmlText($i) /* pos 15:2 */;
			echo "\n";

		}
%A%
