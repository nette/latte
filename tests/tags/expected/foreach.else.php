<?php
%A%
		foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator(['a'], $ʟ_it ?? null) as $item) /* line 2:1 */ {
			echo '	item
';
		}
		if ($iterator->isEmpty()) /* line 4:2 */ {
			echo '	empty
';

		}
		$iterator = $ʟ_it = $ʟ_it->getParent();
%A%
