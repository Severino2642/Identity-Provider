<?php

namespace App\Service;

use DateTimeImmutable;

class DateService
{
    public function getCurrentDate(): DateTimeImmutable
    {
        return new DateTimeImmutable(); // Retourne la date actuelle immuable
    }
}