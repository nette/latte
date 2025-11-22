<?php
%A%
		foreach ([0, 1] as $i) /* pos %d%:%d% */ {
			echo '<div>';
			try {
				echo '
	<span>';
				try {
					if (true) /* pos %d%:%d% */ break;
				} finally {
					echo '</span>';
				}
				echo "\n";
			} finally {
				echo '</div>';
			}
			echo "\n";

		}

		echo '
<div>
';
		foreach ([0, 1] as $i) /* pos %d%:%d% */ {
			echo '		<span>';
			try {
				if (true) /* pos %d%:%d% */ break;
			} finally {
				echo '</span>';
			}
			echo "\n";

		}

		echo '</div>
';
%A%
