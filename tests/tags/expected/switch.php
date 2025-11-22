<?php
%A%
		$ʟ_switch = (0) /* pos 2:1 */;
		if (in_array($ʟ_switch, [''], true)) /* pos 3:1 */ {
			echo 'string
';
		} elseif (in_array($ʟ_switch, [0.0], true)) /* pos 5:1 */ {
			echo 'flot
';
		} else {
			echo 'def
';
		}
		echo '
---

';
		$ʟ_switch = ('a') /* pos 10:1 */;
		if (in_array($ʟ_switch, [1, 2, 'a'], true)) /* pos 11:1 */ {
			echo 'a
';
		}
		echo '
---

';
		$ʟ_switch = ('a') /* pos 16:1 */;
		echo 'def

---

';
		$ʟ_switch = ('a') /* pos 22:1 */;
	}
}