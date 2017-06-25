<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Pakli;
use AppBundle\Entity\Kartyakombinacio;
use AppBundle\Entity\Kez;
use PHPUnit\Framework\TestCase;

class KartyasorTest extends TestCase
{
    
    /** 
     * A Kartyasor osztály feltöltését tesztelő függvény 
     * 
     *  
     */  
    public function testBemutatas()
    {
        $testElements=$this->TestBemutatasElemekGeneralasa();
        
        foreach ($testElements as $elem) 
        {
            $sor=new Kartyakombinacio($elem['nev'],$elem['ertek'],$elem['leiras']);
            $result=$sor->Bemutatas();
            $this->assertEquals($elem['elvart'], $result);
        }
    }
    
    /** 
     * A testBemutatas függvénynek készít teszt adatokat 
     * 
     * @see testBemutatas 
     */ 
    public function TestBemutatasElemekGeneralasa():array
    {
        $testelements=array();
        $testelements[]=[   'nev'=>"Royal Flush",
                            'ertek'=>10,
                            'leiras'=>"*,10;=,+;=,+;=,+;=,+" ,
                            'elvart'=>"nev: Royal Flush, ertek: 10, 1:*-10, 2:=-+, 3:=-+, 4:=-+, 5:=-+",
                        ];
        $testelements[]=[   'nev'=>"Straight Flush",
                            'ertek'=>9,
                            'leiras'=>"*,*;=,+;=,+;=,+;=,+" ,
                            'elvart'=>"nev: Straight Flush, ertek: 9, 1:*-*, 2:=-+, 3:=-+, 4:=-+, 5:=-+",
                        ];
        $testelements[]=[   'nev'=>"Poker",
                            'ertek'=>8,
                            'leiras'=>"*,*;*,=;*,=;*,=;*,*" ,
                            'elvart'=>"nev: Poker, ertek: 8, 1:*-*, 2:*-=, 3:*-=, 4:*-=, 5:*-*",
                        ];
        $testelements[]=[   'nev'=>"Full",
                            'ertek'=>7,
                            'leiras'=>"*,*;*,=;*,=;*,*;*,=" ,
                            'elvart'=>"nev: Full, ertek: 7, 1:*-*, 2:*-=, 3:*-=, 4:*-*, 5:*-=",
                        ];
        $testelements[]=[   'nev'=>"Flush",
                            'ertek'=>6,
                            'leiras'=>"*,*;=,*;=,*;=,*;=,*" ,
                            'elvart'=>"nev: Flush, ertek: 6, 1:*-*, 2:=-*, 3:=-*, 4:=-*, 5:=-*",
                        ];
        $testelements[]=[   'nev'=>"Sor",
                            'ertek'=>5,
                            'leiras'=>"*,*;+,*;+,*;+,*;+,*" ,
                            'elvart'=>"nev: Sor, ertek: 5, 1:*-*, 2:+-*, 3:+-*, 4:+-*, 5:+-*",
                        ];
        $testelements[]=[   'nev'=>"Drill",
                            'ertek'=>4,
                            'leiras'=>"*,*;*,=;*,=;*,*;*,*" ,
                            'elvart'=>"nev: Drill, ertek: 4, 1:*-*, 2:*-=, 3:*-=, 4:*-*, 5:*-*",
                        ];
        $testelements[]=[   'nev'=>"Ket par",
                            'ertek'=>3,
                            'leiras'=>"*,*;*,=;*,*;*,=;*,*" ,
                            'elvart'=>"nev: Ket par, ertek: 3, 1:*-*, 2:*-=, 3:*-*, 4:*-=, 5:*-*",
                        ];
        $testelements[]=[   'nev'=>"Egy par",
                            'ertek'=>2,
                            'leiras'=>"*,*;*,=;*,*;*,*;*,*" ,
                            'elvart'=>"nev: Egy par, ertek: 2, 1:*-*, 2:*-=, 3:*-*, 4:*-*, 5:*-*",
                        ];
        $testelements[]=[   'nev'=>"Magas lap",
                            'ertek'=>1,
                            'leiras'=>"*,*;*,*;*,*;*,*;*,*" ,
                            'elvart'=>"nev: Magas lap, ertek: 1, 1:*-*, 2:*-*, 3:*-*, 4:*-*, 5:*-*",
                        ];
        
        
        return $testelements;
    }   


}
