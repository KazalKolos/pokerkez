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
    /**
     * Ez a property tarolja a kombinacio nevet
     * pl: Royal Flush
     * 
     * @nev string
     */
    private $nev;
    
    
    /**
     * Ez a property tárolja a kombinació értékét
     * 1: magas lap
     * 10: Royal Flush
     * 
     * @ertek int 
     */
    private $ertek;
    
    
    /**
     * Ebben a tömbben taroljuk a kombináció szabályát
     * A tömb felépítése:
     * Array(5)=
     * [
     *      Array(2)=['nevkod','szinkod'],
     *      Array(2)=['nevkod','szinkod'],
     *      Array(2)=['nevkod','szinkod'],
     *      Array(2)=['nevkod','szinkod'],
     *      Array(2)=['nevkod','szinkod'],
     * ];
     * A tömb x. eleme a lapsorozat x. elemére vonatkozó szabályt tartalmazza
     * A nevkod és a szinkod tartalmát, szintaktikáját lentebb részletezem.
     * 
     * @szabaly array of string arrays
     * 
     */
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
    
    /*
     * visszaadja a kombinacio nevet
     */
    public function getNev() : string
    {
        return $this->nev;
    }
            
    /*
     * Visszaadja a kombinacio erteket
     */
    public function getErtek() :int
    {
        return $this->ertek;
    }
    
    
    /*
     * Ezt a függvényt a konstruktor hívja meg
     * paraméterként a szabály meghatározott szintaktikájú
     * leirasat adja át. 
     * A parameter $leiras string 4 ;-t tartalmaz, így 5 részre bontható.
     * Az öt részt foreach használatával járjuk végig
     * Az egyes részek egy , mentén két részre bonthatóak. Ez a $paramarr tömb.
     * A $paramarr tömb 0. elem a szinkodot, 1. eleme a nevkodot tartalmazza.
     * A függvény eredményként a fenti $szabaly tömb leirasanak megfelelő tömböt
     * ad vissza, tehát 5 darab kételemű tömböt tartalmazó tömböt.
     */
    private function Szabalykepzes(string $leiras):array
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
    
    /*
     * a szinkod szintaktikáját ellenorzo methodus
     */
    private function SzinkodEllenor(string $szinkod) 
    {
        if ($szinkod!="*" && $szinkod!="=") 
        {
            throw new Exception("A szinkod erteke hibas! ($szinkod)");
        }
    }
    
    /*
     * A nevkod szintaktikajat ellenorzo methodus
     */
    private function NevkodEllenor(string $nevkod) 
    {
        if ($nevkod!="*" && $nevkod!="=" && $nevkod!="+" && $nevkod!="10") 
        {
            throw new Exception("A nevkod erteke hibas!");
        }
    }
    
    /*
     * A kombinacio szöveges leirasat adja vissza
     */
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
    
    /*
     * Ellenorzi, hogy a paraméterként kapott Lap array megfelel-e
     * a szabály leirasanak
     */
    public function MegfelelASzabalynak(array $lapsor):bool
    {
        $this->LapsorEllenor($lapsor);
        
        /*
         * Egyedül a Royal Flush esetében van megszabva, hogy a sorozat
         * 10-es lappal kezdodik
         * Ennek a feltetelnek a vizsgalata itt tortenik
         */
        if($this->szabaly[0]['nevkod']=="10" 
                && $lapsor[0]->getVizsgalatbanHasznaltLapertek()!=10)
            {return false;}
        
        /*
         * Az alábbi ciklus bejarja a lapokat 1-től 4-ig 
         * 0-alapu tömb
         * A 0. lapot nem vizsgalja mert az a Royal Flusht kiveve
         * mindig bármi lehet.
         * A soron kovetkezo lapot mindig az elotte allo lappal hasonlitja ossze
         * Ha az adott lap elbukik a vizsgan, akkor azonnal falsot ad vissza,
         * hiszen a lapsorozatra ez a kombinacio nem ervenyes
         * Ha minden lap atmegy a vizsgalaton, akkor true-t ad vissza
         */
        for ($i=1;$i<count($this->szabaly);$i++)
        {
            if (!$this->Lapvizsgalat($lapsor[$i-1],$lapsor[$i],$this->szabaly[$i]))
            {
                return false;
            }
        }
        
        return true;
    }
    
     /*
     * A lapsort ellenorzo methodus
     */
    private function LapsorEllenor(array $lapsor) 
    {
        if (count($lapsor)!=5) 
        {
            throw new Exception("A laposor tomb nem 5 elemu!");
        }
    }
    
    
    /*
     * Egy lap es a lapra vonatkozo szabaly ellenorzese
     * 
     */
    private function Lapvizsgalat(Lap $elozoLap,Lap $ezALap, array $szabaly):bool
    {
       /*
        * Ha a szinkod '=' akkor az elozo es a jelenlegi lap 
        * szinenek meg kell egyeznie 
        */
        if($szabaly['szinkod']=="=" && 
                !$elozoLap->EgyformaSzin($ezALap))
        {return false;}
        
        /*
         * Ha a nevkod "=", akkor az elozo es a jelenlegi lap
         * ertekenek meg kell egyeznie
         */
        if($szabaly['nevkod']=="=" && 
                !$elozoLap->EgyformaErtek($ezALap))
        {return false;}        
            
        
        /*
         * Ha a nevkod '+' akkor a jelenlegi lap ertekenek
         * eggyel nagyobbnak kell lennie, mint az elozo lap erteke
         */
        if($szabaly['nevkod']=="+" && 
                !$elozoLap->AParameterlapErtekeEggyelNagyobb($ezALap))
        {return false;}
        
        /*
         * Ha idaig eljutott, akkor ez a lap megfelel a szabalynak
         */
        return true;
    }
}
