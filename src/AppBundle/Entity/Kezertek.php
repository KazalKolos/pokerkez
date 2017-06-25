<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Lap;

class Kezertek implements iKezertekTemplate
{
    private $kombinacio;
    private $lapsor;
    
    function __construct(Kartyakombinacio $kombinacio, array $lapsor) 
    {
        $this->kombinacio=$kombinacio;
        $this->lapsor=$lapsor;
    }
    
       
    public function getKombinacio():Kartyakombinacio
    {
        return $this->kombinacio;
    }
    
    public function getMagaslap(): Lap
    {
        return $this->lapsor[0];
    }
    
    public function Leiras():string
    {
        return Lap::LapsorLeiras($this->lapsor)
                . "Kez erteke: "
                .$this->lapsor[0]->getNev()." "
                .$this->kombinacio->getNev()
                ."\n";
    }
    






    public static function NagyobbKezertek(Kezertek $egyik, Kezertek $masik) : Kezertek
    {
        if(empty($egyik)) {return $masik;}
        if(empty($masik)) {return $egyik;}
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
