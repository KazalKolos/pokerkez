<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Lap;

/*
 * A Kezertek class a kézben lévő kártyák kombinációkeresésének 
 * visszatérő értékét tárolja
 * A $kombinacio tarolja azt a kombinációt, mely megfelelt 
 * a $lapsor tömbben tárolt kártyasorozatnak
 */
class Kezertek implements iKezertekTemplate
{
     /**
     * @kombinacio Kartyakombinacio
     */
    private $kombinacio;
    
    /**
     * @lapsor Lap array
     */
    private $lapsor;
    
    /*
     * Példányosításkor meg kell adni a kombinációt és a lapsort.
     */
    function __construct(Kartyakombinacio $kombinacio, array $lapsor) 
    {
        $this->kombinacio=$kombinacio;
        $this->lapsor=$lapsor;
    }
    
    /*
     * Visszaadja a kombinációt
     */   
    public function getKombinacio():Kartyakombinacio
    {
        return $this->kombinacio;
    }
    
    
    /*
     * Visszaadja a lapsor tömb kezdő lapját. 
     * Ennek a lapnak az értéke dönti el, hogy két azonos értékű kombináció
     * (pl: két póker) közül melyik a győztes
     */
    public function getMagaslap(): Lap
    {
        return $this->lapsor[0];
    }
    
    
    /*
     * A kombináció szöveges leírását adja vissza.
     */
    public function Leiras():string
    {
        return Lap::LapsorLeiras($this->lapsor)
                . "<br>"
                .$this->lapsor[0]->getNev()." "
                .$this->kombinacio->getNev()
                ."\n";
    }
    
    /*
     * A lapsort adja vissza.
     */
    public function getLapsor() :array
    {
        return $this->lapsor;
    }

    /*
     * A kombináció szöveges értékét adja vissza.
     */
    public function ErtekNev():string
    {
        return $this->lapsor[0]->getNev()." ".$this->kombinacio->getNev();
    }

    /*
     * Statikus függvény!
     * A parameterkent kapott két Kezerték objektum közül a nagyobbat adja vissza
     */
    public static function NagyobbKezertek(Kezertek $egyik, Kezertek $masik) : Kezertek
    {
        if($egyik->kombinacio->getErtek() > $masik->kombinacio->getErtek()){return $egyik;}
        if($egyik->kombinacio->getErtek() < $masik->kombinacio->getErtek()){return $masik;}
        
        //ha a két kombináció egyenlő, akkor a magasabb lap dönt
        if($egyik->lapsor[0]->getVizsgalatbanHasznaltLapertek() > $masik->lapsor[0]->getVizsgalatbanHasznaltLapertek())
        {
            return $egyik;
        }
        
        /*ha nem az $egyik a nagyobb, akkor vagy a $masik a nagyobb,
         * vagy a két kombináció egyenlő. Mindkét esetben visszaadható
         * a $másik, tehát több vizsgálatra nincs szükség
         */
        return $masik;
    }
}
