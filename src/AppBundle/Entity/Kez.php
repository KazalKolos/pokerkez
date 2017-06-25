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
        echo $ertek->Leiras();
    }
    
    private function Fakereses(array $torzs): Kezertek
    {
        $nemHasznalt=$this->NemHasznaltElemek($torzs);
        if (count($nemHasznalt)==0)
        {
            return $this->Ertekel($torzs);
        }
        
        $ertek=new Kezertek(new Kartyakombinacio("ures", 0, "*,*;*,*;*,*;*,*;*,*"),[new Lap("Treff","2"),new Lap("Treff","2")]);
        
        foreach ($nemHasznalt as $csomopont) 
        {
            foreach ($csomopont->getLapertekek() as $lapertek) 
            {
                $csomopont->setVizsgalatbanHasznaltLapertek($lapertek);
                $ujertek= $this->Fakereses($this->Array_hozzafuz($torzs, $csomopont));
                $ertek=Kezertek::NagyobbKezertek($ertek, $ujertek);
            }
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
    
    
    private function Ertekel(array $torzs): Kezertek
    {
        return (new Szabaly())->KezErtekKereses($torzs);
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
