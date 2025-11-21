<?php

declare(strict_types=1);

use Latte\Runtime\HtmlHelpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


// truthy
Assert::same('checked', HtmlHelpers::formatBoolAttribute('checked', true));
Assert::same('checked', HtmlHelpers::formatBoolAttribute('checked', 1));
Assert::same('checked', HtmlHelpers::formatBoolAttribute('checked', [1]));
Assert::same('checked', HtmlHelpers::formatBoolAttribute('checked', 'foo'));

// falsey
Assert::same('', HtmlHelpers::formatBoolAttribute('checked', false));
Assert::same('', HtmlHelpers::formatBoolAttribute('checked', 0));
Assert::same('', HtmlHelpers::formatBoolAttribute('checked', []));
Assert::same('', HtmlHelpers::formatBoolAttribute('checked', ''));
Assert::same('', HtmlHelpers::formatBoolAttribute('checked', null));
