<?php

namespace Skel;

trait DateTrait
{
    function getDates($userData)
    {
        $formatOpalDate = 'Y M d D';
        $formatOpalDateTime = 'Y M d D H:i';
        $dayInSeconds = 86_400;
        $dateDue = date($formatOpalDate, \time() + (10 * $dayInSeconds));

        $defaults = [
        'date_created' => date($formatOpalDate),
        'date_due' => $dateDue,
        'date_year' => date('Y'),
        'iso_date' => date('Y-m-d'),
        'iso_datetime' => date('Y-m-dTH:i'),
        'iso_timestamp' => date('Y-m-dTH:i:sP'),
        'now_date' => date($formatOpalDate),
        'now_datetime' => date($formatOpalDateTime),
        ];

        $data = array_replace($defaults, $userData);

        return $data;
    }
}
