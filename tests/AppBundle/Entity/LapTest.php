<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Lap;
use PHPUnit\Framework\TestCase;

class LapTest extends TestCase
{
    
    /** 
     * A Lap osztály működését tesztelő függvény 
     * 
     *  
     */  
    public function testLeiras()
    {
        $testelements=$this->TesztLeirasElemekGeneralasa();
        
        foreach ($testelements as $elem) 
        {
            $lap= new Lap($elem['szin'],$elem['nev']);
                
            $result = $lap->Leiras();

            $this->assertEquals($elem['remelt'], $result);
        }
    }
    
    /** 
     * A testLeiras függvénynek készít teszt adatokat 
     * 
     * @see testLeiras 
     */ 
    public function TesztLeirasElemekGeneralasa():array
    {
        $testelements=array();
        //$testelements[]=['nev'=>"Esz",'szin'=>"Kor", 'remelt'=>"Kor Esz (1, 14)"];
        //$testelements[]=['nev'=>"Asz",'szin'=>"Okor", 'remelt'=>"Okor Asz (1, 14)"];
        $testelements[]=['nev'=>"2",'szin'=>"Karo", 'remelt'=>"Karo 2 (2)"];
        $testelements[]=['nev'=>"3",'szin'=>"Treff", 'remelt'=>"Treff 3 (3)"];
        $testelements[]=['nev'=>"5",'szin'=>"Pikk", 'remelt'=>"Pikk 5 (5)"];
        $testelements[]=['nev'=>"Jumbo",'szin'=>"Kor", 'remelt'=>"Kor Jumbo (11)"];
        $testelements[]=['nev'=>"Dama",'szin'=>"Karo", 'remelt'=>"Karo Dama (12)"];
        $testelements[]=['nev'=>"Asz",'szin'=>"Pikk", 'remelt'=>"Pikk Asz (1, 14)"];
        $testelements[]=['nev'=>"Asz",'szin'=>"Kor", 'remelt'=>"Kor Asz (1, 14)"];
        
        return $testelements;
    }  
    
    
    
    
    public function testEgyforma()
    {
        $testelements=$this->TesztEgyformaElemekGeneralasa();
        
        foreach ($testelements as $elem) 
        {
            $egyik= new Lap($elem['szin1'],$elem['nev1']);
            $masik= new Lap($elem['szin2'],$elem['nev2']);
                
            $result = $egyik->Egyforma($masik);

            $this->assertEquals($elem['remelt'], $result);
        }
    }
    
    /** 
     * A testEgyforma függvénynek készít teszt adatokat 
     * 
     * @see testLeiras 
     */ 
    public function TesztEgyformaElemekGeneralasa():array
    {
        $testelements=array();
        $testelements[]=[   'nev1'=>"Asz",'nev2'=>"Asz",
                            'szin1'=>"Kor",'szin2'=>"Kor", 
                            'remelt'=>true,
                        ];

        $testelements[]=[   'nev1'=>"3",'nev2'=>"3",
                            'szin1'=>"Pikk",'szin2'=>"Pikk", 
                            'remelt'=>true,
                        ];

        $testelements[]=[   'nev1'=>"Kiraly",'nev2'=>"Kiraly",
                            'szin1'=>"Karo",'szin2'=>"Karo", 
                            'remelt'=>true,
                        ];

        $testelements[]=[   'nev1'=>"10",'nev2'=>"10",
                            'szin1'=>"Treff",'szin2'=>"Treff", 
                            'remelt'=>true,
                        ];

        $testelements[]=[   'nev1'=>"Asz",'nev2'=>"2",
                            'szin1'=>"Kor",'szin2'=>"Kor", 
                            'remelt'=>false,
                        ];
        
        
        $testelements[]=[   'nev1'=>"3",'nev2'=>"3",
                            'szin1'=>"Kor",'szin2'=>"Karo", 
                            'remelt'=>false,
                        ];

        $testelements[]=[   'nev1'=>"8",'nev2'=>"Jumbo",
                            'szin1'=>"Pikk",'szin2'=>"Treff", 
                            'remelt'=>false,
                        ];

        return $testelements;
    }  

}
