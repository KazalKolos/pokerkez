<?php

namespace AppBundle\Entity;


interface iKartyakeszletTemplate 
{
    public function getLapokSzama():int;
    Public Function Leiras():string;
    public function getMindenLap():array;
    public function UgyanilyenLapEbbenAKeszletben(Lap $lap):int;
}
