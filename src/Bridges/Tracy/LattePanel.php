<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Bridges\Tracy;

use Latte\Engine;
use Latte\Extension;
use Latte\Runtime\Template;
use Tracy;
use function count;


/**
 * Bar panel for Tracy 2.x
 * @internal
 */
class LattePanel implements Tracy\IBarPanel
{
	public bool $dumpParameters = true;

	/** @var Template[] */
	private array $templates = [];

	/** @var array<int, int|float> spl_object_id => start time from hrtime() */
	private array $started = [];

	/** @var array<int, float> spl_object_id => elapsed seconds */
	private array $elapsed = [];

	/** @var \stdClass[] */
	private array $list;
	private ?string $name = null;


	#[\Deprecated('use TracyExtension see https://bit.ly/46flfDi')]
	public static function initialize(Engine $latte, ?string $name = null, ?Tracy\Bar $bar = null): void
	{
		$bar ??= Tracy\Debugger::getBar();
		$bar->addPanel(new self($latte, $name));
	}


	/** @deprecated use TracyExtension see https://bit.ly/46flfDi */
	public function __construct(?Engine $latte = null, ?string $name = null)
	{
		$this->name = $name;
		if ($latte) {
			trigger_error('Replace LattePanel with TracyExtension; see https://bit.ly/46flfDi', E_USER_DEPRECATED);
			$latte->addExtension(
				new class ($this->templates) extends Extension {
					public function __construct(
						private array &$templates,
					) {
					}


					public function beforeRender(Template $template): void
					{
						$this->templates[] = $template;
					}
				},
			);
		}
	}


	public function addTemplate(Template $template): void
	{
		$this->templates[] = $template;
		$this->started[spl_object_id($template)] = hrtime(true);
	}


	/**
	 * Records how long the template took to render, including its children.
	 */
	public function templateRendered(Template $template): void
	{
		$id = spl_object_id($template);
		if (isset($this->started[$id])) {
			$this->elapsed[$id] = (hrtime(true) - $this->started[$id]) / 1e9;
			unset($this->started[$id]);
		}
	}


	/**
	 * Renders tab.
	 */
	public function getTab(): ?string
	{
		if (!$this->templates) {
			return null;
		}

		return Tracy\Helpers::capture(function () {
			$first = reset($this->templates);
			$name = $this->name ?? ($first ? basename($first->getName()) : '');
			require __DIR__ . '/dist/tab.phtml';
		});
	}


	/**
	 * Renders panel.
	 */
	public function getPanel(): string
	{
		$this->list = [];
		$children = [];
		foreach ($this->templates as $t) {
			if ($parent = $t->getReferringTemplate()) {
				$children[spl_object_id($parent)][$t->getName()][] = $t;
			}
		}
		$this->buildList($children, $this->templates[0]);

		return Tracy\Helpers::capture(function () {
			$list = $this->list;
			$dumpParameters = $this->dumpParameters;
			require __DIR__ . '/dist/panel.phtml';
		});
	}


	/**
	 * @param  array<int, array<string, Template[]>>  $children
	 * @param  Template[]  $instances
	 */
	private function buildList(array $children, Template $template, int $depth = 0, array $instances = []): void
	{
		$instances = $instances ?: [$template];
		$groups = [];
		foreach ($instances as $instance) {
			foreach ($children[spl_object_id($instance)] ?? [] as $name => $templates) {
				$groups[$name] = array_merge($groups[$name] ?? [], $templates);
			}
		}

		$time = array_sum(array_map($this->totalTime(...), $instances));
		$childrenTime = array_sum(array_map($this->totalTime(...), array_merge([], ...array_values($groups))));

		$this->list[] = (object) [
			'template' => $template,
			'depth' => $depth,
			'count' => count($instances),
			'phpFile' => (new \ReflectionObject($template))->getFileName(),
			'time' => $time,
			'selfTime' => max($time - $childrenTime, 0.0),
		];

		foreach ($groups as $group) {
			$this->buildList($children, $group[0], $depth + 1, $group);
		}
	}


	/**
	 * Returns the rendering time of the instance including nested templates.
	 */
	private function totalTime(Template $template): float
	{
		return $this->elapsed[spl_object_id($template)] ?? 0.0;
	}
}
