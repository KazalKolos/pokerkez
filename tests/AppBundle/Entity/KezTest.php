<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Pakli;
use AppBundle\Entity\Lap;
use AppBundle\Entity\Kez;
use PHPUnit\Framework\TestCase;

class KezTest extends TestCase
{
    
    /** 
     * A Kez osztály működését tesztelő függvény 
     * 
     *  
     */  
    public function testKezLeiras()
    {
        $testElements=$this->TestKezLeirasElemekGeneralasa();
        
        foreach ($testElements as $elem) 
        {
            $pakli= new Pakli($elem['csomag']);
            $kez=Kez::KezFactory($pakli);
            
            $leiras=$kez->Leiras();
            $result = $kez->getLapokSzama();

            $this->assertEquals($elem['elvart'], $result);
        }
    }
    
    /** 
     * A testKezLeiras függvénynek készít teszt adatokat 
     * 
     * @see testKezLeiras 
     */ 
    public function TestKezLeirasElemekGeneralasa():array
    {
        $testelements=array();
        $testelements[]=['csomag'=>1, 'elvart'=>5,];
        $testelements[]=['csomag'=>2, 'elvart'=>5,];
        $testelements[]=['csomag'=>3, 'elvart'=>5,];
        $testelements[]=['csomag'=>4, 'elvart'=>5,];
        $testelements[]=['csomag'=>5, 'elvart'=>5,];
        $testelements[]=['csomag'=>6, 'elvart'=>5,];
        $testelements[]=['csomag'=>7, 'elvart'=>5,];
        $testelements[]=['csomag'=>8, 'elvart'=>5,];
        $testelements[]=['csomag'=>9, 'elvart'=>5,];
        
        return $testelements;
    }   

    
    
     /** 
     * A Kez osztály KezErteke függvény működését tesztelő függvény 
     * 
     *  
     */  
    public function testKezErteke()
    {
        $pakli= new Pakli(1);
        $kez=Kez::KezFactory($pakli);

        //$leiras=$kez->Leiras();
        echo"\n";
        $kez->KezErteke();
        $result = $kez->getLapokSzama();

        $this->assertEquals(5, $result);
    }

}
