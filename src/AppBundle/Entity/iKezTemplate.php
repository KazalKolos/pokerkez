<?php

namespace AppBundle\Entity;


interface iKezTemplate 
{
    public function LapotKap(Lap $lap);
    public function KezErteke();
    public static function KezFactory(Pakli $pakli): iKezTemplate;
}
