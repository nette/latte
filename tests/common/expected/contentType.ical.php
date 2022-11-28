<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{
	public const ContentType = 'ical';


	public function main(array $ʟ_args): void
	{
		extract($ʟ_args);
		unset($ʟ_args);

		echo 'BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//example.org//iCal 4.0.3//CS
METHOD:PUBLISH
BEGIN:VEVENT
DTSTAMP:';
		echo LR\Filters::escapeIcal(($this->filters->date)($start, 'Ymd\\THis')) /* line %d% */;
		echo '
DTSTART;TZID=Europe/Prague:';
		echo LR\Filters::escapeIcal(($this->filters->date)($start, 'Ymd\\THis')) /* line %d% */;
		echo '
DTEND;TZID=Europe/Prague:';
		echo LR\Filters::escapeIcal(($this->filters->date)($end, 'Ymd\\THis')) /* line %d% */;
		echo '
SUMMARY;LANGUAGE=cs:';
		echo LR\Filters::escapeIcal($info) /* line %d% */;
		echo '
DESCRIPTION:
CLASS:PUBLIC
END:VEVENT
END:VCALENDAR
';
	}


	public function prepare(): array
	{
		extract($this->params);

		if (empty($this->global->coreCaptured) && in_array($this->getReferenceType(), ['extends', null], true)) {
			header('Content-Type: text/calendar; charset=utf-8') /* line %d% */;
		}
		$start = '2011-06-06';
		$end = '2011-06-07';
		$info = 'Hello "hello",
World' /* line %d% */;
		return get_defined_vars();
	}
}
