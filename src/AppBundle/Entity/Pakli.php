<?php

namespace AppBundle\Entity;
use Exception;
use AppBundle\Entity\iKartyakeszletInterface;
use AppBundle\Entity\Lap;

class Pakli  extends Kartyakeszlet implements iPakliTemplate
{
    
    /**
     * @csomagszam int
     */
    private $csomagszam;
    
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
    
    
    
    private function PakliFeltoltese()
    {
        for ($i=0;$i<$this->csomagszam;$i++)
        {
            $this->CsomagHozzaadasa();
        }
    }
    
    
    
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
    

    
    public function EgyVeletlenLapotHuz():Lap 
    {
        $veletlenszam= rand(0, count($this->lapok)-1);
        $egylap=$this->lapok[$veletlenszam];
        array_splice($this->lapok,$veletlenszam, 1);
        return $egylap;
    }
    
}
