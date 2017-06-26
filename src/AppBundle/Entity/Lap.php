<?php

namespace AppBundle\Entity;
use Exception;
use AppBundle\Entity\ILapTemplate;


/*
 * Ebben az osztályban tároljuk a lapok adatait: szín, név. érték (Asz esetében értékek)
 */
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
    
    
    /*
     * Visszaadja a Nev erteket
     */
    public function getNev():string
    {
        return $this->nev;
    }
    
    /*
     * Beallitja a ev erteket
     */
    private function setNev(string $nev)
    {
        $this->NevEllenor($nev);
        
        $this->nev = $nev;
        $this->lapertekek=$this->LapertekekSzamitasa();

        return $this;
    }
    

    
    /*
     * Visszaadja a színt
     */
    public function getSzin():string
    {
        return $this->szin;
    }
    
    /*
     * Beallitja a szint
     */
    private function setSzin(string $szin)
    {
        $this->SzinEllenor($szin);
        $this->szin = $szin;
        return $this;
    }
    
    /*
     * Ez a property a lap adott vizsgálatban való értékét tartalmazza.
     * A tulajdonság értéke mindig ugyanannyi, mint a lapértékek tömbben
     * felsorolt egy darab érték.
     * Csak az Ász esetében van két érték, itt a property értéke a kettő közül 
     * veszi fel valamelyiket
     * Ennek a propertynek csak a kombinációkereses során van jelentosege

     * 
     * Visszaadja a vizsgáatban használt lapértéket
     */     
    public function getVizsgalatbanHasznaltLapertek():int
    {
        return $this->vizsgalatbanHasznaltLapertek;
    }
    
    
    
    /* 
     * Beállitja a avizsgalatban hasznalt értéket
     */
    public function setVizsgalatbanHasznaltLapertek(int $ertek)
    {
        $this->vizsgalatbanHasznaltLapertek = $ertek;
        return $this;
    }
    
    /*
     * Visszaadja a lapertekek tömböt
     */
    public function getLapertekek():array
    {
        return $this->lapertekek;
    }
        
    /*
     * visszaadja az egyedi lapid-t
     */
    public function getLapid():int
    {
        return $this->lapid;
    }
    
    /*
     * Szöveges leírast eszit a laphoz
     * 
     */
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
    
    
    /* 
     * Statikus függvény!
     * A paraméterként kapott Lap array elemeiből észít szöveges leírast
     */
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
    
    

    /*
     * Megvizsgálja, hogy a paraméterként kapott elem szerepel e a
     * 
     */
    private function Ellenor(array $keszlet, string $vizsgaltElem, string $hibauzenet)
    {
        $vizsgaltElem=",".$vizsgaltElem.",";
        $elfogadhato=" ,".implode(",",$keszlet).", ";
        if(!strpos($elfogadhato, $vizsgaltElem))
        {
            throw new Exception("$hibauzenet [$elfogadhato]");
        }
    }
    
    
    /*
     * Megvizsgálja, hogy a $nev szerepel-e az engedélyezett készletben
     */
    private function NevEllenor(string $nev)
    {
        $this->Ellenor(
                Lap::Nevkeszlet(),
                $nev,
                "Nem elfogadhato nev! ($nev)");
    }
    
    /*
     * a név alapján kiszámítja a lap értékét
     */
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
    
    /*
     * Megvizsgálja, hogy a $szin szerepel-e az engedélyezett készletben
     */
    private function SzinEllenor(string $szin)
    {
        $this->Ellenor(
                Lap::Szinkeszlet(),
                $szin,
                "Nem elfogadhato szin! ($szin)"
            );
    }
    
    /*
     * Statikus függvény!
     * A színek engedélyezett készlete
     */
    public static function Szinkeszlet():array
    {
        return ["Pikk","Treff","Kor","Karo"];
    }
    
    /*
     * Statikus függvény!
     * A nevek engedélyezett készlete
     */
    public static function Nevkeszlet():array
    {
        return ["2","3","4","5","6","7","8","9","10","Jumbo","Dama","Kiraly","Asz"];
    }

    /*
     * Megvizsgálja, hogy a paraméterként kapott lap egyforma-e ezzel a példánnyal
     * Öszehasonlítás alapja a név és a szín
     */
    public function Egyforma(Lap $paramLap): bool
    {
        return ($this->szin==$paramLap->szin && $this->nev==$paramLap->nev);
    }
    
    /*
     * megvizsgálja, egyforma szinu-e a példány és a parameter lap
     */
    public function EgyformaSzin(Lap $paramLap): bool
    {
        return ($this->szin==$paramLap->szin);
    }
    
    /*
     * megvizsgalja, egyforma erteku-e a peldany és a parameter lap
     */
    public function EgyformaErtek(Lap $paramLap): bool
    {
        return ($this->vizsgalatbanHasznaltLapertek==$paramLap->vizsgalatbanHasznaltLapertek);
    }    
    
    /*
     * Megvizsgalja, hogy a parameter lap értéke eggyel nagyobb-e, 
     * mint a peldany erteke
     */
    public function AParameterlapErtekeEggyelNagyobb(Lap $paramLap): bool
    {
        return ($paramLap->vizsgalatbanHasznaltLapertek==$this->vizsgalatbanHasznaltLapertek+1);
    }
    
    /*
     * Statikus függvény
     * A lap egyedi azonositasahoz egyedi id-t keszit, mely minden 
     * leolvasas utan ugrik
     */
    private static function getSzamlalo():int
    {
        return self::$szamlalo++;
    }
    
    /*
     * Statikus fuggveny
     * Nullazza az id-szamlalot
     */
    public static function SzamlaloNullazas()
    {
        self::$szamlalo=0;
    }
}
