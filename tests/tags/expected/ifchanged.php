<?php
%A%
		foreach ([1, 1, 2, 3, 3, 3] as $i) /* line 2:1 */ {
			echo ' ';
			if (($ʟ_loc[0] ?? null) !== ($ʟ_tmp = [$i])) {
				$ʟ_loc[0] = $ʟ_tmp;
				echo ' ';
				echo LR\Filters::escapeHtmlText($i) /* line 2:51 */;
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
		foreach ([1, 1, 2, 3, 3, 3] as $i) /* line 6:1 */ {
			echo ' ';
			if (($ʟ_loc[2] ?? null) !== ($ʟ_tmp = [$i])) {
				$ʟ_loc[2] = $ʟ_tmp;
				echo ' ';
				echo LR\Filters::escapeHtmlText($i) /* line 6:51 */;
				echo ' ';

			} else /* line 6:56 */ {
				echo ' else ';

			}

			echo ' ';

		}

		echo '

--

';
		foreach ([1, 1, 2, 3, 3, 3] as $i) /* line 10:1 */ {
			echo ' ';
			ob_start(fn() => '');
			try /* line 10:36 */ {
				echo ' -';
				echo LR\Filters::escapeHtmlText($i) /* line 10:49 */;
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
		foreach ([1, 1, 2, 3, 3, 3] as $i) /* line 14:1 */ {
			echo ' ';
			ob_start(fn() => '');
			try /* line 14:36 */ {
				echo ' -';
				echo LR\Filters::escapeHtmlText($i) /* line 14:49 */;
				echo '- ';

			} finally {
				$ʟ_tmp = ob_get_clean();
			}
			if (($ʟ_loc[4] ?? null) !== $ʟ_tmp) {
				echo $ʟ_loc[4] = $ʟ_tmp;
			} else /* line 14:55 */ {
				echo ' else ';

			}

			echo ' ';

		}

		echo '

--

';
		foreach ([1, 1, 2, 3, 3, 3] as $i) /* line 18:1 */ {
			echo ' ';
			ob_start(fn() => '');
			try /* line 18:42 */ {
				echo '<span>';
				echo LR\Filters::escapeHtmlText($i) /* line 18:54 */;
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
		foreach ([1, 1, 2, 3, 3, 3] as $i) /* line 22:1 */ {
			echo ' ';
			ob_start(fn() => '');
			try /* line 22:55 */ {
				echo '<span ';
				echo LR\AttributeHandler::formatHtmlAttribute('class', $i) /* line %d%:%d% */;
				echo '></span>';
			} finally {
				$ʟ_tmp = ob_get_clean();
			}
			if (($ʟ_loc[6] ?? null) !== $ʟ_tmp) {
				echo $ʟ_loc[6] = $ʟ_tmp;
			}

			echo ' ';

		}
%A%
