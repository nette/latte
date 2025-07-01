<?php
%A%
		if (true) /* line 2:1 */ {
			echo '	a
';
		} elseif ($b) /* line 4:2 */ {
			echo '	b
';
		} elseif (isset($c)) /* line 6:2 */ {
			echo '	c
';
		} else /* line 8:2 */ {
			echo '	d
';
		}


		echo '
--

';
		ob_start(fn() => '') /* line 14:1 */;
		try {
			echo '	a
';

		} finally {
			$ʟ_ifA = ob_get_clean();
		}
		if (true) /* line 14:1 */ {
			echo $ʟ_ifA;
		}
		echo '
--

';
		ob_start(fn() => '') /* line 20:1 */;
		try {
			echo '	a
';

			ob_start(fn() => '') /* line 22:2 */;
			try {
				echo '	d
';

			} finally {
				$ʟ_ifB = ob_get_clean();
			}
		} finally {
			$ʟ_ifA = ob_get_clean();
		}
		echo (true) ? $ʟ_ifA : $ʟ_ifB /* line 20:1 */;

		echo '
--

';
		if (isset($a)) /* line 28:1 */ {
			echo '	a
';
		} elseif ($b) /* line 30:2 */ {
			echo '	b
';
		} elseif (isset($c)) /* line 32:2 */ {
			echo '	c
';
		} else /* line 34:2 */ {
			echo '	d
';
		}
%A%
