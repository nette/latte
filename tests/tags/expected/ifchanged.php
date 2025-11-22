<?php
%A%
		foreach ([1, 1, 2, 3, 3, 3] as $i) /* pos 2:1 */ {
			echo ' ';
			if (($ʟ_loc[0] ?? null) !== ($ʟ_tmp = [$i])) {
				$ʟ_loc[0] = $ʟ_tmp;
				echo ' ';
				echo LR\Filters::escapeHtmlText($i) /* pos 2:51 */;
				echo ' ';

			}

			echo ' ';
			if (($ʟ_loc[1] ?? null) !== ($ʟ_tmp = ['a', 'b'])) {
				$ʟ_loc[1] = $ʟ_tmp;
				echo ' const ';

			}

			echo ' ';

		}

		echo '

--

';
		foreach ([1, 1, 2, 3, 3, 3] as $i) /* pos 6:1 */ {
			echo ' ';
			if (($ʟ_loc[2] ?? null) !== ($ʟ_tmp = [$i])) {
				$ʟ_loc[2] = $ʟ_tmp;
				echo ' ';
				echo LR\Filters::escapeHtmlText($i) /* pos 6:51 */;
				echo ' ';

			} else /* pos 6:56 */ {
				echo ' else ';

			}

			echo ' ';

		}

		echo '

--

';
		foreach ([1, 1, 2, 3, 3, 3] as $i) /* pos 10:1 */ {
			echo ' ';
			ob_start(fn() => '');
			try /* pos 10:36 */ {
				echo ' -';
				echo LR\Filters::escapeHtmlText($i) /* pos 10:49 */;
				echo '- ';

			} finally {
				$ʟ_tmp = ob_get_clean();
			}
			if (($ʟ_loc[3] ?? null) !== $ʟ_tmp) {
				echo $ʟ_loc[3] = $ʟ_tmp;
			}

			echo ' ';

		}

		echo '

--

';
		foreach ([1, 1, 2, 3, 3, 3] as $i) /* pos 14:1 */ {
			echo ' ';
			ob_start(fn() => '');
			try /* pos 14:36 */ {
				echo ' -';
				echo LR\Filters::escapeHtmlText($i) /* pos 14:49 */;
				echo '- ';

			} finally {
				$ʟ_tmp = ob_get_clean();
			}
			if (($ʟ_loc[4] ?? null) !== $ʟ_tmp) {
				echo $ʟ_loc[4] = $ʟ_tmp;
			} else /* pos 14:55 */ {
				echo ' else ';

			}

			echo ' ';

		}

		echo '

--

';
		foreach ([1, 1, 2, 3, 3, 3] as $i) /* pos 18:1 */ {
			echo ' ';
			ob_start(fn() => '');
			try /* pos 18:42 */ {
				echo '<span>';
				echo LR\Filters::escapeHtmlText($i) /* pos 18:54 */;
				echo '</span>';
			} finally {
				$ʟ_tmp = ob_get_clean();
			}
			if (($ʟ_loc[5] ?? null) !== $ʟ_tmp) {
				echo $ʟ_loc[5] = $ʟ_tmp;
			}

			echo ' ';

		}

		echo '

--

';
		foreach ([1, 1, 2, 3, 3, 3] as $i) /* pos 22:1 */ {
			echo ' ';
			ob_start(fn() => '');
			try /* pos 22:55 */ {
				echo '<span class="';
				echo LR\Filters::escapeHtmlAttr($i) /* pos 22:49 */;
				echo '"></span>';
			} finally {
				$ʟ_tmp = ob_get_clean();
			}
			if (($ʟ_loc[6] ?? null) !== $ʟ_tmp) {
				echo $ʟ_loc[6] = $ʟ_tmp;
			}

			echo ' ';

		}
%A%
