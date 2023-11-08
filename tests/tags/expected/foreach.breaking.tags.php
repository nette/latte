<?php
%A%
		foreach ([0, 1] as $i) /* line %d% */ {
			echo '<div>';
			try {
				echo '
	<span>';
				try {
					if (true) /* line %d% */ break;
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
		foreach ([0, 1] as $i) /* line %d% */ {
			echo '		<span>';
			try {
				if (true) /* line %d% */ break;
			} finally {
				echo '</span>';
			}
			echo "\n";

		}

		echo '</div>
';
%A%
