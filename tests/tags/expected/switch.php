<?php
%A%
		$ʟ_switch = (0) /* line 2:1 */;
		if (in_array($ʟ_switch, [''], true)) /* line 3:1 */ {
			echo 'string
';
		} elseif (in_array($ʟ_switch, [0.0], true)) /* line 5:1 */ {
			echo 'flot
';
		} else {
			echo 'def
';
		}
		echo '
---

';
		$ʟ_switch = ('a') /* line 10:1 */;
		if (in_array($ʟ_switch, [1, 2, 'a'], true)) /* line 11:1 */ {
			echo 'a
';
		}
		echo '
---

';
		$ʟ_switch = ('a') /* line 16:1 */;
		echo 'def

---

';
		$ʟ_switch = ('a') /* line 22:1 */;
	}
}