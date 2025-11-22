<?php
%A%
		if (true) /* pos 2:1 */ {
			echo '	a
';
		} elseif ($b) /* pos 4:2 */ {
			echo '	b
';
		} elseif (isset($c)) /* pos 6:2 */ {
			echo '	c
';
		} else /* pos 8:2 */ {
			echo '	d
';
		}


		echo '
--

';
		ob_start(fn() => '') /* pos 14:1 */;
		try {
			echo '	a
';

		} finally {
			$ʟ_ifA = ob_get_clean();
		}
		if (true) /* pos 14:1 */ {
			echo $ʟ_ifA;
		}
		echo '
--

';
		ob_start(fn() => '') /* pos 20:1 */;
		try {
			echo '	a
';

			ob_start(fn() => '') /* pos 22:2 */;
			try {
				echo '	d
';

			} finally {
				$ʟ_ifB = ob_get_clean();
			}
		} finally {
			$ʟ_ifA = ob_get_clean();
		}
		echo (true) ? $ʟ_ifA : $ʟ_ifB /* pos 20:1 */;

		echo '
--

';
		if (isset($a)) /* pos 28:1 */ {
			echo '	a
';
		} elseif ($b) /* pos 30:2 */ {
			echo '	b
';
		} elseif (isset($c)) /* pos 32:2 */ {
			echo '	c
';
		} else /* pos 34:2 */ {
			echo '	d
';
		}
%A%
