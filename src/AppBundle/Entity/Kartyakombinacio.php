<?php

namespace AppBundle\Entity;
use Exception;
use AppBundle\Entity\iKartyasorTemplate;
use AppBundle\Entity\Lap;
use AppBundle\Entity\Pakli;

/*
 * Ebben az osztályban tároljuk az egy kártyasor (pl: póker)
 * felismeréséhez szükséges adatokat
 */
class Kartyakombinacio implements iKartyakombinacioTemplate
{
    private $nev;
    private $ertek;
    private $szabaly;
    
    /*
     * itt történik a szabály beallitasa
     * A név a kártyasor megnevezése, Pl: Színsor
     * az érték a kártyasor erőssége: Magas lap: 1, Royal Flush: 10
     * 
     * A leírás formátuma:
     * "szinkod,ertekkod;szinkod,ertekkod;szinkod,ertekkod;szinkod,ertekkod;szinkod,ertekkod"
     * 
     * A szinkód és az értékkód a sorban előző lappal való összehasonlításra szolgál
     * Szinkód értékei:
     *      * - mindegy, mi a szín
     *      = - a szín megegyezik az előző lap színével
     * 
     * Az értékkód értékei:
     *      * - mindegy, mi az érték
     *      10 - a lap értéke 10 kell legyen. Csak az első lapnál használható a Royal Flush miatt
     *      = - az érték megyegyezik az előző lap értékével
     *      + - az érték eggyel nagyobb az előző lap értékénél
     */
    function __construct(string $nev, int $ertek, $leiras) 
    {
        
        $this->nev=$nev;
        $this->ertek=$ertek;
        $this->szabaly=$this->Szabalykepzes($leiras);
    }
    
    public function getNev() 
    {
        return $this->nev;
    }
            
    public function getErtek() 
    {
        return $this->ertek;
    }
    
    private function Szabalykepzes(string $leiras)
    {
        $eredmeny=array();
        foreach (explode(";",$leiras) as $p)
        {
            $paramarr=explode(",",$p);
            $this->SzinkodEllenor($paramarr['0']);
            $this->NevkodEllenor($paramarr['1']);
            $sor=['szinkod'=>$paramarr[0],'nevkod'=>$paramarr[1]];
            $eredmeny[]=$sor;
        }
        return $eredmeny;
    }
    
    private function SzinkodEllenor($szinkod) 
    {
        if ($szinkod!="*" && $szinkod!="=") 
        {
            throw new Exception("A szinkod erteke hibas! ($szinkod)");
        }
    }
    
    private function NevkodEllenor($nevkod) 
    {
        if ($nevkod!="*" && $nevkod!="=" && $nevkod!="+" && $nevkod!="10") 
        {
            throw new Exception("A nevkod erteke hibas!");
        }
    }
    
    public function Bemutatas():string
    {
        $eredmeny= "nev: $this->nev, "
                . "ertek: $this->ertek";
        for ($i=0;$i<count($this->szabaly);$i++)
        {
            $eredmeny.=", ";
            $eredmeny.=($i+1).":".$this->szabaly[$i]['szinkod']."-".$this->szabaly[$i]['nevkod'];
        }
        return $eredmeny;
    }
    
    public function MegfelelASzabalynak(array $lapsor):bool
    {
        //echo "\n\n\nLapsor: ".Lap::LapsorLeiras($lapsor);
        //echo $this->Bemutatas();
        if($this->szabaly[0]['nevkod']==10 && $lapsor[0]->getVizsgalatbanHasznaltLapertek()!=10)
            {return false;}
        
        for ($i=1;$i<5;$i++)
        {
            if (!$this->Lapvizsgalat($lapsor[$i-1],$lapsor[$i],$this->szabaly[$i]))
            {
                return false;
            }
        }
        
        return true;
    }
    
    private function Lapvizsgalat(Lap $elozoLap,Lap $ezALap, array $szabaly):bool
    {
        //echo"\n".$elozoLap->Leiras().", ".$ezALap->Leiras(). ". Szin: ".$szabaly['szinkod']. ", Ertek: ".$szabaly['nevkod']."\n";
        
        if($szabaly['szinkod']=="=" && 
                !$elozoLap->EgyformaSzin($ezALap))
            {
                //echo "Egyforma szin false\n";
                return false;
            }
        
        if($szabaly['nevkod']=="=" && 
                !$elozoLap->EgyformaErtek($ezALap))
            {
                //echo "Egyforma ertek false\n";
                return false;
            }        
        if($szabaly['nevkod']=="+" && 
                !$elozoLap->AParameterlapErtekeEggyelNagyobb($ezALap))
            {
                //echo "Eggyel nagyobb false\n";
                return false;
            }        
            
        //Echo "Megfelel a szabalynak.\n"    ;
        return true;
    }
    
    
            
    
}
