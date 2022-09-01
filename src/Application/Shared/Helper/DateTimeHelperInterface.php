<?php

declare(strict_types=1);

namespace App\Application\Shared\Helper;

interface DateTimeHelperInterface
{
    public function getFormatDate(): string;
    public function getDateTimeFromString(string $dateTime): ?\DateTime;
    public function getDateStringFromDateTime(\DateTime $dateTime): string;
    public function getSearchDatesSeparator(): string;
}
