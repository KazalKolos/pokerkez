<?php

namespace AppBundle\Entity;


interface iSzabalyTemplate 
{
    public function KezErtekKereses(array $lapsor):Kezertek;
}
