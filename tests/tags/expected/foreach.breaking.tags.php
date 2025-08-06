<?php
%A%
		foreach ([0, 1] as $i) /* line %d%:%d% */ {
			echo '<div>';
			try {
				echo '
	<span>';
				try {
					if (true) /* line %d%:%d% */ break;
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
		foreach ([0, 1] as $i) /* line %d%:%d% */ {
			echo '		<span>';
			try {
				if (true) /* line %d%:%d% */ break;
			} finally {
				echo '</span>';
			}
			echo "\n";

		}

		echo '</div>
';
%A%
