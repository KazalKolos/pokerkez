<?php

namespace AppBundle\Entity;
use Exception;


class Lap
{
        
    /**
     * @szin string
     */
    private $szin="***";
        
    /**
     * @nev string
     */
    private $nev="***";
        
    /**
     * @lapertekek array
     */
    private $lapertekek;

    
    function __construct(string $szin, string $nev)
    {
        $this->setSzin($szin);
        $this->setNev($nev);

    }
    
    
    
    public function getNev():string
    {
        return $this->nev;
    }
    
    public function setNev(string $nev)
    {
        $this->NevEllenor($nev);
        
        $this->nev = $nev;
        $this->lapertekek=$this->LapertekekSzamitasa();
        

        return $this;
    }
    

    
 
    public function getSzin():string
    {
        return $this->szin;
    }
    
    public function setSzin(string $szin)
    {
        $this->SzinEllenor($szin);
        $this->szin = $szin;
        return $this;
    }
    
    
    
    public function getLapertekek():array
    {
        return $this->lapertekek;
    }
    
    
    public function Leiras():string
    {
        $eredmeny="";
        
        foreach ($this->lapertekek as $ertek)
        {
            $eredmeny.=($eredmeny!="" ? ", " : "");
            $eredmeny.=(string)$ertek;
        }
        return "$this->szin $this->nev, A lap erteke: ($eredmeny)";
    }
    
    

    
    private function Ellenor(array $keszlet, string $vizsgaltElem, string $hibauzenet)
    {
        $vizsgaltElem=",".$vizsgaltElem.",";
        $elfogadhato=" ,".implode(",",$keszlet).", ";
        if(!strpos($elfogadhato, $vizsgaltElem))
        {
            throw new Exception("$hibauzenet [$elfogadhato]");
        }
    }
    
    
    
    private function NevEllenor(string $nev)
    {
        $this->Ellenor(
                Lap::Nevkeszlet(),
                $nev,
                "Nem elfogadhato nev! ($nev)"
            );
    }
    

    private function LapertekekSzamitasa():array
    {
        switch ($this->nev)
        {
            case "Jumbo":
                return [11];
            case "Dama":
                return [12];
            case "Kiraly":
                return [13];
            case "Asz":
                return [1, 14];
            default:
                return [(int)$this->nev];
        }
    }
    
    private function SzinEllenor(string $szin)
    {
        $this->Ellenor(
                Lap::Szinkeszlet(),
                $szin,
                "Nem elfogadhato szin! ($szin)"
            );
    }
    
    
    public static function Szinkeszlet():array
    {
        return ["Pikk","Treff","Kor","Karo"];
    }
    
    public static function Nevkeszlet():array
    {
        return ["2","3","4","5","6","7","8","9","10","Jumbo","Dama","Kiraly","Asz"];
    }

    public function Egyforma(Lap $paramLap): bool
    {
        return ($this->szin==$paramLap->szin && $this->nev==$paramLap->nev);
    }
}
