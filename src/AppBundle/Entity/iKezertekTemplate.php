<?php

namespace AppBundle\Entity;


interface iKezertekTemplate 
{
    public function getKombinacio():Kartyakombinacio;
    public function getMagaslap(): Lap;    
    public static function NagyobbKezertek(Kezertek $egyik, Kezertek $masik) : Kezertek;
}
