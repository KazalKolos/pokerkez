<?php

namespace AppBundle\Entity;
use Exception;
use AppBundle\Entity\Lap;

/*
 * A pakli osztály az eredeti kártyakészlet, amibőla lapot húzzuk.
 * A pakli egy vagy több 52 lapos csomagból áll össze.
 * A csomagszámot példányosításkor kell megadni. Akkor automatikusan feltölti magát.
 * 
 * Az EgyVeletlenLapotHuz public methodus kiválaszt egy lapot a csomagból és visszaadja. 
 */
class Pakli  extends Kartyakeszlet implements iPakliTemplate
{
    
    /**
     * @csomagszam int
     */
    private $csomagszam;
    
    /*
     * Konstruktor
     * paraméterként megkapja a csomagszámot, ennyi csomagból áll majd a pakli
     */
    function __construct(int $csomagszam) 
    {
        parent::__construct();
        if($csomagszam<1 || $csomagszam>10)
        {
            throw new Exception("A jatekban legalabb 1, legfeljebb 10 csomag vehet reszt!");
        }
        $this->csomagszam=$csomagszam;
        Lap::SzamlaloNullazas();
        $this->PakliFeltoltese();
    }
    
    
    /*
     * Itt tölti fel a paklit. A $csomagszámnak megfelelő számú csomagot tesz
     * a pakliba.
     */
    private function PakliFeltoltese()
    {
        for ($i=0;$i<$this->csomagszam;$i++)
        {
            $this->CsomagHozzaadasa();
        }
    }
    
    
    /*
     * Egy csomagot tesz a pakliba
     */
    private function CsomagHozzaadasa()
    {
        foreach (Lap::Nevkeszlet() as $nev)
        {
            foreach (Lap::Szinkeszlet() as $szin)
            {
                $this->lapok[]=new Lap($szin,$nev);
            }
        }
    }
    

    /*
     * Egy véletlen lapot kiválaszt és eredményként visszaad
     * A kivalasztott lapot torli a pakliból, így nem fordulhat elő, hogy 
     * ugyanazt a lapot kétszer adja át
     * Természetesen több pakli esetén átadhat több ugyanolyan lapot is, 
     * de nem ugyanazt többször
     */
    public function EgyVeletlenLapotHuz():Lap 
    {
        $this->VanMegLapEllenor();
        $veletlenszam= rand(0, count($this->lapok)-1);
        $egylap=$this->lapok[$veletlenszam];
        array_splice($this->lapok,$veletlenszam, 1);
        return $egylap;
    }
    
    
    /*
     * Ellenőrzi, hogy van-e még lap
     * Ha nincs, hibauzenettel leall.
     */
    private function VanMegLapEllenor()
    {
        if(!$this->VanMegLapAPakliban())
        {
            throw new Exception("Ures paklibol nem lehet huzni!");
        }    
    }
    
    
    /*
     * Ha van meg lap a pakliban, true-t, 
     * ha nincs false-ot ad vissza
     */
    public function VanMegLapAPakliban():bool
    {
        return ($this->getLapokSzama()>0);
    }
}
