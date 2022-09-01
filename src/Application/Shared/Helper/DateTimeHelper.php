<?php

declare(strict_types=1);

namespace App\Application\Shared\Helper;

use App\Application\Shared\Config\ParametersConfigInterface;

class DateTimeHelper implements DateTimeHelperInterface
{
    private string $formatDate;
    private string $searchDatesSeparator;

    public function __construct(
        ParametersConfigInterface $parametersConfig
    ) {
        $this->formatDate = $parametersConfig->get('app.date_format');
        $this->searchDatesSeparator = $parametersConfig->get('app.search_dates_separator');
    }

    public function getFormatDate(): string
    {
        return $this->formatDate;
    }

    public function getDateTimeFromString(string $dateTime): ?\DateTime
    {
        $dateTime = \DateTime::createFromFormat(
            $this->formatDate,
            $dateTime
        );

        if (!$dateTime) {
            return null;
        }

        return $dateTime;
    }

    public function getDateStringFromDateTime(\DateTime $dateTime): string
    {
        return $dateTime->format($this->formatDate);
    }

    public function getSearchDatesSeparator(): string
    {
        return $this->searchDatesSeparator;
    }
}
