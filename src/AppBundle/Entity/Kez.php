<?php

namespace AppBundle\Entity;
use Exception;
use AppBundle\Entity\iKartyakeszletTemplate;
use AppBundle\Entity\Lap;
use AppBundle\Entity\Pakli;

class Kez extends Kartyakeszlet implements iKezTemplate
{
    public function LapotKap(Lap $lap)
    {
        $this->lapok[]=$lap;
    }
        
    public function KezErteke()
    {
        //$agak= $this->lapok;
        $torzs=array();
        $ertek= $this->FaKereses($torzs);
    }
    
    private function Fakereses(array $torzs):string
    {
        $nemHasznalt=$this->NemHasznaltElemek($torzs);
        if (count($nemHasznalt)==0)
        {
            return $this->Ertekel($torzs);
        }
        
        foreach ($nemHasznalt as $csomopont) 
        {
            $ertek= $this->Fakereses($this->Array_hozzafuz($torzs, $csomopont));
        }
        return $ertek;
    }
    
    private function Array_hozzafuz(array $arr, Lap $lap):array
    {
        $arr[]=$lap;
        return $arr;
    }
    
    private function NemHasznaltElemek(array $torzs): array
    {
        $nemHasznalt=array();
        foreach ($this->lapok as $csomopont) 
        {
            if(!$this->BenneVan($torzs,$csomopont))
            {
                $nemHasznalt[]=$csomopont;
            }
        }
        return $nemHasznalt;
    }
    
    function BenneVan(array $haystack, Lap $needle):bool
    {
        foreach ($haystack as $h) 
        {
            if($needle->getLapid()===$h->getLapid())
            {
                return true;
            }
        }
        return false;
    }
    
    
    private function Ertekel(array $torzs):string
    {
        $eredmeny="";
        foreach ($torzs as $lap) 
        {
            $eredmeny.=($eredmeny==""?"":", ");
            $eredmeny.=$lap->Leiras();
        }
        echo"$eredmeny\n";
        return"";
    }
    
    
    public static function KezFactory(Pakli $pakli): iKezTemplate
    {
        $kez=new kez();
        while($kez->getLapokSzama()<5)
        {
            $kez->LapotKap($pakli->EgyVeletlenLapotHuz());
        }
        return $kez;
    }
}
