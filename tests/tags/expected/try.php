<?php
%A%
		$ʟ_try[0] = [$ʟ_it ?? null];
		ob_start(fn() => '');
		try /* line 1 */ {
			echo '	a
';
			throw new Latte\Essential\RollbackException;
			echo '	b
';

		} catch (Throwable $ʟ_e) {
			ob_end_clean();
			if (!($ʟ_e instanceof Latte\Essential\RollbackException) && isset($this->global->coreExceptionHandler)) {
				($this->global->coreExceptionHandler)($ʟ_e, $this);
			}
			echo '	c
';

			ob_start();
		} finally {
			echo ob_get_clean();
			$iterator = $ʟ_it = $ʟ_try[0][0];
		}
%A%
