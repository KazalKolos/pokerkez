<?php

namespace AppBundle\Entity;


interface iKartyakombinacioTemplate 
{
    public function Bemutatas():string;
    public function MegfelelASzabalynak(array $lapsor):bool;
}
