<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Identifyble
{

    private $identifier;

    public function __construct($identifier)
    {
        $this->identifier = $identifier;
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

}

?>
