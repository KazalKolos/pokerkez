<?php

namespace AppBundle\Entity;
use Exception;
use AppBundle\Entity\ILapTemplate;



class Lap implements iLapTemplate
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
    
    /** 
     * Egyedi lapid - Ha több paklival játszunk, akkor 
     * a nev és a szin alapján nem lehet egyertelmuen megkülönböztetni
     * ket lepot egymástol
     * 
     * @lapid int
     */
    private $lapid;
    
    /** 
     * Statikus változó egyedi lapid eloallitasahoz
     * 
     * @szamlalo int
     */
    private static $szamlalo=0;

    
    /** 
     * Ezt a property-t csak a kéz értékének 
     * vizsgálata során használjuk. Az Ász aktuális 
     * értékének tárolására szolgál.
     * 
     * @vizsgalatbanHasznaltLapertek int
     */
    private $vizsgalatbanHasznaltLapertek;
    
    function __construct(string $szin, string $nev)
    {
        $this->setSzin($szin);
        $this->setNev($nev);
        $this->lapid=self::getSzamlalo();
    }
    
    
    
    public function getNev():string
    {
        return $this->nev;
    }
    
    private function setNev(string $nev)
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
    
    private function setSzin(string $szin)
    {
        $this->SzinEllenor($szin);
        $this->szin = $szin;
        return $this;
    }
    
    
    public function getVizsgalatbanHasznaltLapertek():int
    {
        return $this->vizsgalatbanHasznaltLapertek;
    }
    
    public function setVizsgalatbanHasznaltLapertek(int $ertek)
    {
        $this->vizsgalatbanHasznaltLapertek = $ertek;
        return $this;
    }
    
    
    public function getLapertekek():array
    {
        return $this->lapertekek;
    }
        
    public function getLapid():int
    {
        return $this->lapid;
    }
    
    
    public function Leiras(bool $rovid=false):string
    {
        $eredmeny="";
        
        foreach ($this->lapertekek as $ertek)
        {
            $eredmeny.=($eredmeny!="" ? ", " : "");
            $eredmeny.=(string)$ertek;
        }
        if($rovid){return "$this->lapid($this->vizsgalatbanHasznaltLapertek)";}
        return "$this->szin $this->nev";
    }
    
    public static function LapsorLeiras(array $lapsor):string
    {
        $eredmeny="";
        foreach ($lapsor as $lap) 
        {
            $eredmeny.=($eredmeny==""?"":", ");
            $eredmeny.=$lap->Leiras();
        }
        return "\n".$eredmeny."\n";
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
    
    public function EgyformaSzin(Lap $paramLap): bool
    {
        return ($this->szin==$paramLap->szin);
    }
    
    
    public function EgyformaErtek(Lap $paramLap): bool
    {
        return ($this->vizsgalatbanHasznaltLapertek==$paramLap->vizsgalatbanHasznaltLapertek);
    }    
    
    public function AParameterlapErtekeEggyelNagyobb(Lap $paramLap): bool
    {
        return ($paramLap->vizsgalatbanHasznaltLapertek==$this->vizsgalatbanHasznaltLapertek+1);
    }
    
    private static function getSzamlalo():int
    {
        return self::$szamlalo++;
    }
    
    public static function SzamlaloNullazas()
    {
        self::$szamlalo=0;
    }
}
