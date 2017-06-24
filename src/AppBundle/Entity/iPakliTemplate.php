<?php

namespace AppBundle\Entity;


interface iPakliTemplate 
{
    function __construct(int $csomagszam);
    public function EgyVeletlenLapotHuz():Lap; 
}
