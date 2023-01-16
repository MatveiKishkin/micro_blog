<?php

namespace App\Contracts\DateSupport;

interface DateSupport
{
    /**
     * Основной формат даты 11 янв 2000.
     *
     * @param mixed $date
     * @param bool $space_nbsp - пробел между днем и месяцем в виде &nbsp;
     * @param bool $full_month
     * @return string
     */
    public function mainFormat($date, $space_nbsp = false, $full_month = false);

    /**
     * Получение названия месяца по номеру.
     *
     * @param int $number
     * @return string
     */
    public function getMonthName($number);

    /**
     * Получение полного названия месяца, опционально в сколнении и без.
     *
     * @param int $number
     * @param string $case
     * @return string
     */
    public function getFullMonthNameInCase($number, $case = 'prepositional');
}