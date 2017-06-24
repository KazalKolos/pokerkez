<?php

namespace AppBundle\Entity;


interface iLapTemplate
{
    function __construct(string $szin, string $nev);
    public function getNev():string;
    public function getSzin():string;
    public function getLapertekek():array;
    public function Leiras():string;
    public function Egyforma(Lap $paramLap): bool;
    public static function Szinkeszlet():array;
    public static function Nevkeszlet():array;
}
