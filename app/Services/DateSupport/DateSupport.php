<?php

namespace App\Services\DateSupport;

use App\Contracts\DateSupport\DateSupport as DateSupportContract;
use Carbon\Carbon;

class DateSupport implements DateSupportContract
{
    /**
     * Основной формат даты 11 янв 2000.
     *
     * @param mixed $date
     * @param bool $space_nbsp - пробел между днем и месяцем в виде &nbsp;
     * @param bool $full_month
     * @return string
     */
    public function mainFormat($date, $space_nbsp = false, $full_month = false)
    {
        if(($date = $this->checkDate($date)) === false) {
            return '';
        }

        $space = $space_nbsp ? '&nbsp;' : ' ';

        $month = $full_month ?  $this->getFullMonthNameInCase($date->month, 'genitive') : $this->getMonthName($date->month);

        return $date->format('j').$space.$month.' '.$date->format('Y');
    }

    /**
     * Получение названия месяца по номеру.
     *
     * @param int $number
     * @return string
     */
    public function getMonthName($number)
    {
        if ($number < 1 || $number > 12)
            return '';

        $months = ['янв.', 'февр.', 'мар.', 'апр.', 'мая', 'июня', 'июля', 'авг.', 'сент.', 'окт.', 'нояб.', 'дек.'];

        return $months[$number - 1];
    }

    /**
     * Получение полного названия месяца, опционально в сколнении и без.
     *
     * @param int $number
     * @param string $case
     * @return string
     */
    public function getFullMonthNameInCase($number, $case = 'prepositional')
    {
        if($number < 1 || $number > 12) {
            return '';
        }

        switch ($case) {

            case 'sumple':
                $textMonth = [
                    'январь',
                    'февраль',
                    'март',
                    'апрель',
                    'май',
                    'июнь',
                    'июль',
                    'август',
                    'сентябрь',
                    'октябрь',
                    'ноябрь',
                    'декабрь',
                ];
                break;

            case 'prepositional':
                $textMonth = [
                    "январе",
                    "феврале",
                    "марте",
                    "апреле",
                    "мае",
                    "июне",
                    "июле",
                    "августе",
                    "сентябре",
                    "октябре",
                    "ноябре",
                    "декабре",
                ];
                break;

            case 'genitive':
                $textMonth = [
                    'января',
                    'февраля',
                    'марта',
                    'апреля',
                    'мая',
                    'июня',
                    'июля',
                    'августа',
                    'сентября',
                    'октября',
                    'ноября',
                    'декабря',
                ];

                break;

            case 'eng':
                return lcfirst((\DateTime::createFromFormat('!m', $number))->format('F'));

            default: return '';
        }

        return $textMonth[$number-1];
    }

    /**
     * Проверка даты.
     *
     * @param string|Carbon $date
     * @return bool|Carbon
     */
    protected function checkDate($date)
    {
        if(empty($date)) {
            return false;
        }

        /**
         * Если карбон, проверка на правильную дату.
         */
        if($date instanceof Carbon) {

            if($date->timestamp <= 0) {
                return false;
            }

            return $date;
        }

        /**
         * Если не карбон, попытка распарсить дату.
         */
        $date = Carbon::parse($date);

        if($date->timestamp <= 0) {
            return false;
        }

        return $date;
    }

}