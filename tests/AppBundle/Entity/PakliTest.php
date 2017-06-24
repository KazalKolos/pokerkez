<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Pakli;
use AppBundle\Entity\Lap;
use PHPUnit\Framework\TestCase;

class PakliTest extends TestCase
{
    
    /** 
     * A Pakli osztály működését tesztelő függvény 
     * 
     *  
     */  
    public function testGetTeljesPakli()
    {
        $testelements=$this->TestGetTeljesPakliElemekGeneralasa();
        
        foreach ($testelements as $elem) 
        {
            $pakli= new Pakli($elem['csomag']);
            $result = $pakli->getLapokSzama();
            $this->assertEquals($elem['elvart'], $result);
        }
    }
    
    /** 
     * A testGetTeljesPakli függvénynek készít teszt adatokat 
     * 
     * @see testGetTeljesPakli 
     */ 
    public function TestGetTeljesPakliElemekGeneralasa():array
    {
        $testelements=array();
        //$testelements[]=['csomag'=>0, 'elvart'=>0,];
        //$testelements[]=['csomag'=>11, 'elvart'=>594,];
        $testelements[]=['csomag'=>1, 'elvart'=>52,];
        $testelements[]=['csomag'=>4, 'elvart'=>208,];
        $testelements[]=['csomag'=>7, 'elvart'=>364,];
        
        return $testelements;
    }   

    
    
    
    
        /** 
     * A Pakli osztály működését tesztelő függvény 
     * 
     *  
     */  
    
    public function testEgyVeletlenLapotHuz()
    {
        $testelements=$this->TestEgyVeletlenLapotHuzElemekGeneralasa();
        
        foreach ($testelements as $elem) 
        {
            $pakli= new Pakli($elem['csomag']);
                
            $lap=$pakli->EgyVeletlenLapotHuz();
            
            $result=$pakli->UgyanilyenLapEbbenAKeszletben($lap);

            $this->assertEquals($elem['elvart'], $result);
        }
    }
    
    /** 
     * A testGetTeljesPakli függvénynek készít teszt adatokat 
     * 
     * @see testGetTeljesPakli 
     */ 
    public function TestEgyVeletlenLapotHuzElemekGeneralasa():array
    {
        $testelements=array();
        $testelements[]=['csomag'=>1, 'elvart'=>0,];
        $testelements[]=['csomag'=>4, 'elvart'=>3,];
        $testelements[]=['csomag'=>7, 'elvart'=>6,];
        $testelements[]=['csomag'=>5, 'elvart'=>4,];
        $testelements[]=['csomag'=>9, 'elvart'=>8,];
        $testelements[]=['csomag'=>2, 'elvart'=>1,];
        
        return $testelements;
    }   

}
