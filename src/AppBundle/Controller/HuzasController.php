<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Pakli;
use AppBundle\Entity\Kez;

class HuzasController extends Controller
{
    /**
     * @Route("huzas", name="huzas")
     */
    public function huzasAction(Request $request)
    {
        $csomagszam=$request->request->get('csomagszam');
        
        $pakli= new Pakli($csomagszam);
        $kez=Kez::KezFactory($pakli);
        $ertek=$kez->KezErteke();
        
        
        return $this->render('huzas/huzas.html.twig', [
            'kez' => $kez,
            'ertek'  => $ertek,  
            'csomagszam'=>$csomagszam,
        ]);
    }
}
