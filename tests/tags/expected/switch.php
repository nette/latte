<?php declare(strict_types=1);
%A%
		$ʟ_switch = (0) /* pos 2:1 */;
		if ($ʟ_switch === '') /* pos 3:1 */ {
			echo 'string
';
		} elseif (in_array($ʟ_switch, [...['a']], true)) /* pos 4:1 */ {
			echo 'a
';
		} elseif ($ʟ_switch === 0.0) /* pos 6:1 */ {
			echo 'flot
';
		} else {
			echo 'def
';
		}
		echo '
---

';
		$ʟ_switch = ('a') /* pos 11:1 */;
		if (in_array($ʟ_switch, [1, 2, 'a'], true)) /* pos 12:1 */ {
			echo 'a
';
		}
		echo '
---

';
		$ʟ_switch = ('a') /* pos 17:1 */;
		echo 'def

---

';
		$ʟ_switch = ('a') /* pos 23:1 */;
	}
}