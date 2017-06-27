<?php

namespace AppBundle\Entity;
use AppBundle\Entity\Lap;
use AppBundle\Entity\Pakli;

/*
 * Ebben az osztályban tároljuk a kézben lévő kartyak adatait
 * 
 * LapotKap() public method a parameterkent kapott lapot hozzáfüzi a már 
 * kezben levő lapokhoz
 * 
 * KezErteke() public method elinditja a lapoknak megfelelo kombinaciokeresest, 
 * es visszaadja a kombinacio leirasat
 * 
 */
class Kez extends Kartyakeszlet implements iKezTemplate
{
    /*
     * A parameterkent kapott lapot hozzafuzi a $lapok tömbjéhez
     * A lapok tömbjét az osztaly ose (Kartyakeszlet) definialta
     */
    public function LapotKap(Lap $lap)
    {
        $this->lapok[]=$lap;
    }
        
    /*
     * Elinditja a megfeleo kombinacio kereseset, és visszaadja az értéket
     */
    public function KezErteke():Kezertek
    {
        $torzs=array();
        $ertek= $this->FaKereses($torzs);
        return $ertek;
    }
    
    /*
     * Felallitja a keresesi fat.
     * A fán minden egyes gyökértől levélig tarto ut a lapok egy elrendezesenek 
     * felel meg.
     * Minden egyes elrendezést összevet a szabalyrendszerrel, és a legértékesebb 
     * talalt Kezerteket adja vissza
     * A kezerteket a kombinació értéke határozza meg, azonos ertekek eseten 
     * pedig a magasabb kezdolap
     */
    private function Fakereses(array $torzs): Kezertek
    {
        /*
         * a gyökértol az adott csomopontig tarto uton nem szereplo lapok
         * osszegyüjtese. Beloluk alakitjuk ki a kovetkezo csomopontokat
         */
        $nemHasznalt=$this->NemHasznaltElemek($torzs);
        
        /*
         * Ha mar nincs több felhasznalhato lap, akkor 
         * az ertekeles következik. Az értékelés visszaadja
         * a Kézértéket és ezt felkuldjuk a rekurziv függvenyhivasok tetejere
         */
        if (count($nemHasznalt)==0)
        {
            return $this->Ertekel($torzs);
        }
        
        /*
         * Letrehozunk egy ertektelen kezerteket
         * amit az elso osszehasonlitashoz hasznalunk. Ez utana eltunik.
         */
        $ertek=new Kezertek(new Kartyakombinacio("ures", 0, "*,*;*,*;*,*;*,*;*,*"),[new Lap("Treff","2"),new Lap("Treff","2")]);
        
        /*
         * Létrehozzuk a csomopontok vagy levelek kovetkezo szintjet
         * Az Asz eseteben a lapnak ket erteke lehet, ekkor mindket ertek
         * külön csomopontként jelenik meg.
         */
        foreach ($nemHasznalt as $csomopont) 
        {
            foreach ($csomopont->getLapertekek() as $lapertek) 
            {
                //az Asz miatt ki kell jeloni, hogy epp melyik ertekkel dolgozunk
                $csomopont->setVizsgalatbanHasznaltLapertek($lapertek);
                
                /*
                 * Rekurziv modon a fuggveny meghivja sajat magat, 
                 * és visszakapja az adott ág legmagasabb kezerteket
                 */
                $ujertek= $this->Fakereses($this->Array_hozzafuz($torzs, $csomopont));
                
                //kivalasztja ennek a szintnek a legmagasabb kezerteket
                $ertek=Kezertek::NagyobbKezertek($ertek, $ujertek);
            }
        }
        
        /*
         * a legmagasabb kezerteket visszaadja egy szinttel feljebb
         */
        return $ertek;
    }
    
    /*
     * Visszaadja a parameterkent kapott tömb masolatat, hozzafuzve a 
     * parameterkent kapott lapot
     */
    private function Array_hozzafuz(array $arr, Lap $lap):array
    {
        $arr[]=$lap;
        return $arr;
    }
    
    
    /*
     * Az adott agben nem szereplo elemek listaja
     * Ebbol lesz a csomopontok jelenlegi szintje
     */
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
    
    
    /*
     * Szerepel-e a parameter lap a parameter tömbben?
     */ 
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
    
    /*
     * Az adott lapsorhoz megfelelo kombinaciot keres
     */
    private function Ertekel(array $lapsor): Kezertek
    {
        return (new Szabaly())->KezErtekKereses($lapsor);
    }
    
    
    
    /*
     * Statikus függvény, mely uj Kez peldanyt allit elo
     * A parameterkent kapott $pakliból
     * 5 lapot húz és azt a kézbe adja
     */
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
