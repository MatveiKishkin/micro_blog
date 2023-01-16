<?php

//***************************************************************************
//*************************** Работа с датами *******************************
//***************************************************************************

if (! function_exists('date_support')) {

    /**
     * Хелпер по датам.
     *
     * @return \App\Contracts\DateSupport\DateSupport
     */
    function date_support()
    {
        return app(\App\Contracts\DateSupport\DateSupport::class);
    }
}