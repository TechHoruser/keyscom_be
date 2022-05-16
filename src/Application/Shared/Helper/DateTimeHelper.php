<?php

declare(strict_types=1);

namespace App\Application\Shared\Helper;

use App\Application\Shared\Config\ParametersConfigInterface;

class DateTimeHelper implements DateTimeHelperInterface
{
    private string $formatDateTime;
    private string $searchDatesSeparator;

    public function __construct(
        ParametersConfigInterface $parametersConfig
    ) {
        $this->formatDateTime = $parametersConfig->get('app.date_format');
        $this->searchDatesSeparator = $parametersConfig->get('app.search_dates_separator');
    }

    public function getFormatDateTime(): string
    {
        return $this->formatDateTime;
    }

    public function getDateTimeFromString(string $dateTime): ?\DateTime
    {
        $dateTime = \DateTime::createFromFormat(
            $this->formatDateTime,
            $dateTime
        );

        if (!$dateTime) {
            return null;
        }

        return $dateTime;
    }

    public function getStringFromDateTime(\DateTime $dateTime): string
    {
        return $dateTime->format($this->formatDateTime);
    }

    public function getSearchDatesSeparator(): string
    {
        return $this->searchDatesSeparator;
    }
}
