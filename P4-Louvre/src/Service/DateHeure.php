<?php

namespace App\Service;


class DateHeure
{
    private $dateHeure;

    public function __construct()
    {
        $this->dateHeure = new \DateTime();
        $this->dateHeure->setTimezone(new \DateTimeZone('Europe/Paris'));
    }

    public function control()
    {
        if ($this->dateHeure->format("H") > 13) {

            return true;
        }

        return false;
    }

}