<?php
%A%
		for ($i = 0;
		$i < 10;
		$i++) /* line 2:1 */ {
			echo '	';
			echo LR\Filters::escapeHtmlText($i) /* line 3:2 */;
			echo "\n";

		}
		echo '

';
		for (;
		;
		) /* line 7:1 */ {
			echo '	';
			echo LR\Filters::escapeHtmlText($i) /* line 8:2 */;
			echo "\n";

		}
		echo '

';
		for ($i = 0, $a = 1;
		$i < 10;
		$i++, $a++) /* line 12:1 */ {
			echo '	';
			echo LR\Filters::escapeHtmlText($i) /* line 13:2 */;
			echo "\n";

		}
		echo '

';
		for ($i = 0;
		$i < 10;
		$i++) /* line 17:1 */ {
			if (true) /* line 18:2 */ break;
			if (true) /* line 19:2 */ continue;
			echo '	';
			echo LR\Filters::escapeHtmlText($i) /* line 20:2 */;
			echo "\n";

		}
%A%
