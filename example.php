<?php

/** @noinspection PhpUndefinedClassInspection */

namespace Evapotrans;

use Evapotrans\ValueObjects\Temperature;
use Evapotrans\ValueObjects\Wind2m;

date_default_timezone_set('Europe/Paris');
error_reporting(E_ALL);

spl_autoload_register(
    function ($class) {
        $file = str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';
        if (file_exists($file)) {
            require $file;

            return true;
        }

        return false;
    }
);

$location = new Location(43.29504, 5.3865, 35);

$data = new MeteoData($location, new \DateTime('2019-02-15'));
$data->setTmin(new Temperature(2.7));
$data->setTmax(new Temperature(61, 'F'));
$data->setActualSunnyHours(7.2); // mesured full sunny hours
$data->setWind2(new Wind2m(20, 'km/h', 2));

// Température dewpoint (point rosée) : Facultatif si RHmax/min ou RHmoyen
//$data->setTdew(9);

// RHmax, RHmin facultatif si Tdew
$data->setRHmax(0.90);
$data->setRHmin(0.38);

$ETcalc = new PenmanCalc();
$ETo = $ETcalc->EToPenmanMonteith($data);

echo 'ETo = '.$ETo.' mm/day';

include 'example_crop.php';
