<?php

namespace AppBundle\Entity;
use Exception;
use AppBundle\Entity\iKartyakeszletTemplate;
use AppBundle\Entity\Lap;
use AppBundle\Entity\Pakli;
use AppBundle\Entity\Kartyakombinacio;

class Szabaly implements iSzabalyTemplate
{
    private static $kombinaciok;
    
    function __construct() 
    {
//        if(count(self::$kombinaciok)!=10)
//        {
            self::$kombinaciok= self::KombinacioErtekadas();
//        }
    }
    
    private static function KombinacioErtekadas():array
    {
        $eredmeny=array();
        foreach (self::KombinaciokMeghatarozas() as $komb) 
        {
            $eredmeny[]=new Kartyakombinacio($komb['nev'],$komb['ertek'],$komb['leiras']);
        }
        return $eredmeny;
    }
    
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
    
    private static function KombinaciokMeghatarozas():array
    {
        $kombinacio=array();
        $kombinacio[]=[   'nev'=>"Royal Flush",
                            'ertek'=>10,
                            'leiras'=>"*,10;=,+;=,+;=,+;=,+" ,
                        ];
        $kombinacio[]=[   'nev'=>"Straight Flush",
                            'ertek'=>9,
                            'leiras'=>"*,*;=,+;=,+;=,+;=,+" ,
                        ];
        $kombinacio[]=[   'nev'=>"Poker",
                            'ertek'=>8,
                            'leiras'=>"*,*;*,=;*,=;*,=;*,*" ,
                        ];
        $kombinacio[]=[   'nev'=>"Full",
                            'ertek'=>7,
                            'leiras'=>"*,*;*,=;*,=;*,*;*,=" ,
                        ];
        $kombinacio[]=[   'nev'=>"Flush",
                            'ertek'=>6,
                            'leiras'=>"*,*;=,*;=,*;=,*;=,*" ,
                        ];
        $kombinacio[]=[   'nev'=>"Sor",
                            'ertek'=>5,
                            'leiras'=>"*,*;*,+;*,+;*,+;*,+" ,
                        ];
        $kombinacio[]=[   'nev'=>"Drill",
                            'ertek'=>4,
                            'leiras'=>"*,*;*,=;*,=;*,*;*,*" ,
                        ];
        $kombinacio[]=[   'nev'=>"Ket par",
                            'ertek'=>3,
                            'leiras'=>"*,*;*,=;*,*;*,=;*,*" ,
                        ];
        $kombinacio[]=[   'nev'=>"Egy par",
                            'ertek'=>2,
                            'leiras'=>"*,*;*,=;*,*;*,*;*,*" ,
                        ];
        $kombinacio[]=[   'nev'=>"Magas lap",
                            'ertek'=>1,
                            'leiras'=>"*,*;*,*;*,*;*,*;*,*" ,
                        ];
        
        
        return $kombinacio;
    }
}
