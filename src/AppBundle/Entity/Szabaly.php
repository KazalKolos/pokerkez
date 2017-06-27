<?php

namespace AppBundle\Entity;
use AppBundle\Entity\Kartyakombinacio;


/*
 * A Szabály osztály tartalmazza az ertekelés szabályait, azaz minden kártyakombinációt.
 */
class Szabaly implements iSzabalyTemplate
{
    /*
     * Statikus változó
     * A $kombinaciok változó a Kartyakombinaciok osztály példányainak tömbje
     * Minden szabály szerinti kártyakombináció egy példányban kap helyet
     */
    private static $kombinaciok;
    
    /*
     * Példányosításkor megvizsgálja, hogy a $kombinaciok már fel va-e töltve
     * Ha nem, akkor feltölti
     */
    function __construct() 
    {
        if(count(self::$kombinaciok)!=10)
        {
            self::$kombinaciok= self::KombinacioErtekadas();
        }
    }
    
    
    /*
     * Itt tortenik a tömb kialakítása, majd a kész tömböt eredmenykent visszadva
     * megtörténik a $kombinaciok feltoltese
     */
    private static function KombinacioErtekadas():array
    {
        $eredmeny=array();
        foreach (self::KombinaciokMeghatarozas() as $komb) 
        {
            $eredmeny[]=new Kartyakombinacio($komb['nev'],$komb['ertek'],$komb['leiras']);
        }
        return $eredmeny;
    }
    
    
    /*
     * A parameterkent kapott $lapsor Lapokbol álló tömböt
     * összevetjük a létező kártyakombinaciokkal
     * Fontos, hogy csak a kártyáknak csak ezt a sorrendjét vizsgáljuk.
     * Más sorrendekre újra meg kell hivni a fuggvenyt
     * A kereses a legmagasabb kombinaciotol indul, így az első találatnál 
     * visszadaja az eredmenyt Kezertek osztaly egy peldanyaban
     */
    public function KezErtekKereses(array $lapsor):Kezertek
    {
        foreach (self::$kombinaciok as $kombinacio) 
        {
            if($kombinacio->MegfelelASzabalynak($lapsor))
            {
                return new Kezertek($kombinacio, $lapsor);
            }
        }
        
        //Helyes működés esetén ez a sor sosem hajtódik végre
        return new Kartyakombinacio("Magas lap",1,"*,*;*,*;*,*;*,*;*,*");
    }
    
    
    /*
     * Ebben a fuggvnyben taroljuk az egyes kombinaciok meghatarozasait
     */
    private static function KombinaciokMeghatarozas():array
    {
        $kombinacio=array();
        $kombinacio[]=[   'nev'=>"Royal Flush",
                            'ertek'=>10,
                            'leiras'=>"*,10;=,+;=,+;=,+;=,+" ,
                        ];
        $kombinacio[]=[   'nev'=>"straight flush",
                            'ertek'=>9,
                            'leiras'=>"*,*;=,+;=,+;=,+;=,+" ,
                        ];
        $kombinacio[]=[   'nev'=>"póker",
                            'ertek'=>8,
                            'leiras'=>"*,*;*,=;*,=;*,=;*,*" ,
                        ];
        $kombinacio[]=[   'nev'=>"full",
                            'ertek'=>7,
                            'leiras'=>"*,*;*,=;*,=;*,*;*,=" ,
                        ];
        $kombinacio[]=[   'nev'=>"flush",
                            'ertek'=>6,
                            'leiras'=>"*,*;=,*;=,*;=,*;=,*" ,
                        ];
        $kombinacio[]=[   'nev'=>"sor",
                            'ertek'=>5,
                            'leiras'=>"*,*;*,+;*,+;*,+;*,+" ,
                        ];
        $kombinacio[]=[   'nev'=>"drill",
                            'ertek'=>4,
                            'leiras'=>"*,*;*,=;*,=;*,*;*,*" ,
                        ];
        $kombinacio[]=[   'nev'=>"két pár",
                            'ertek'=>3,
                            'leiras'=>"*,*;*,=;*,*;*,=;*,*" ,
                        ];
        $kombinacio[]=[   'nev'=>"pár",
                            'ertek'=>2,
                            'leiras'=>"*,*;*,=;*,*;*,*;*,*" ,
                        ];
        $kombinacio[]=[   'nev'=>"magas lap",
                            'ertek'=>1,
                            'leiras'=>"*,*;*,*;*,*;*,*;*,*" ,
                        ];
        
        return $kombinacio;
    }
}
