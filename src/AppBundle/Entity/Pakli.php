<?php

namespace AppBundle\Entity;
use Exception;
use AppBundle\Entity\Lap;

class Pakli
{
    /**
     * @lapok Lap array
     */
    private $lapok;
    
    /**
     * @csomagszam int
     */
    private $csomagszam;
    
    function __construct(int $csomagszam) 
    {
        if($csomagszam<1 || $csomagszam>10)
        {
            throw new Exception("A jatekban legalabb 1, legfeljebb 10 csomag vehet reszt!");
        }
        $this->csomagszam=$csomagszam;
        $this->PakliFeltoltese();
    }
    
    private function PakliFeltoltese()
    {
        $this->lapok=array();
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
    
    public function getTeljesPakli():array
    {
        return $this->lapok;
    }

    
    public function EgyVeletlenLapotHuz():Lap 
    {
        $veletlenszam= rand(0, count($this->lapok)-1);
        $egylap=$this->lapok[$veletlenszam];
        array_splice($this->lapok,$veletlenszam, 1);
        return $egylap;
    }
    
    public function UgyanilyenLapAPakliban(Lap $lap):int 
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
