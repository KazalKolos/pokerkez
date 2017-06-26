<?php

use AppBundle\Entity\iKartyakeszletTemplate;
use AppBundle\Entity\Lap;

namespace AppBundle\Entity;

/*
 * A Kartyakeszlet osztalyt kozvetlenul nem használjuk,
 * csak a Pakli és a Kez osztalyok kozos ose.
 * 
 * A $lapok protected property-ben tároljuk a keszletben levo lapokat.
 * A Leiras() public methódusban stringben kérhetjuk a csomag leirasat
 * A getLapokSzama() public methodusban a készletben szereplo lapok szamat kerdezhetjük le.
 * A getMindenLap() public methodusban arrayként kérhetjuk le a lapokat.
 * A UgyanilyenLapEbbenAKeszletben() public methodusban paraméterként átadok egy
 * lapot, eredmenykent pedig visszakapok egy egész szamot, mely megmutatja, 
 * hány ilyen lap van a csomagban
 */
class Kartyakeszlet implements iKartyakeszletTemplate
{
    /**
     * @lapok Lap array
     */
    protected $lapok;
    
    /*
     * A konstruktorban létrehozzuk a $lapok tömböt, üres állapotban.
     */
    function __construct() 
    {
        $this->lapok=array();
    }
    
    /*
     * Visszaadja a $lapok tömbben szereplo lapok szamat
     */
    public function getLapokSzama():int
    {
        return count($this->lapok);
    }
    
    /*
     * Az egész lapok tömböt visszaadja
     */
    public function getMindenLap():array
    {
        return $this->lapok;
    }
    
    /*
     * Szöveges leirast keszit a keszlethez az egyes lapok Leiras methodusanak
     * hasznalataval. A lapok leirasat vesszovel valasztja el egymastol.
     */
    public Function Leiras():string
    {
        $eredmeny="";
        foreach ($this->lapok as $lap) 
        {
            $eredmeny.=($eredmeny!="" ? ", ": "");
            $eredmeny.=$lap->Leiras();
        }
        return $eredmeny;
    }
    
    /*
     * Megszamolja, hogy a keszletben hány ugyanolyan la van, mint a 
     * parameterben atadott lap.
     * Az egyeztetéshez a Lap példány Egyforma methodusát használja.
     * Az egyeztetés alapja az azonos szin és azonos ertek.
     * Az egyedi lapid-t nem veszi figyelembe
     */
    public function UgyanilyenLapEbbenAKeszletben(Lap $lap):int 
    {
        $szamlalo=0;
        foreach ($this->lapok as $pakliLap)
        {
            if($pakliLap->Egyforma($lap))
            {
                $szamlalo++;
            }
        }
        return $szamlalo;
    }
}
