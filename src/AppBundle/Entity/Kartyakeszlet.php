<?php

namespace AppBundle\Entity;
use Exception;
use AppBundle\Entity\iKartyakeszletTemplate;
use AppBundle\Entity\Lap;
use AppBundle\Entity\Pakli;

class Kartyakeszlet implements iKartyakeszletTemplate
{
    /**
     * @lapok Lap array
     */
    protected $lapok;
    
    function __construct() 
    {
        $this->lapok=array();
    }
    
    public function getLapokSzama():int
    {
        return count($this->lapok);
    }
    
    public function getMindenLap():array
    {
        return $this->lapok;
    }
    
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
