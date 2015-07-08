<?php

/**
 *
 * @param $d timestamp
 * @return string
 */
function dammiData($d){
    $datec = new DateTime($d);
    $datec->setTimezone(new DateTimeZone('Europe/Rome'));
    return $datec->format('d/m/Y');
}