<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Team
 *
 * @author sivakumar
 */
include_once '/www/htdocs/service/SoccerTeamDemo/SocketTeam/Models/Identifyble.php';
class Team extends Identifyble
{

    private $name;
    private $logoUri;

    function __construct($name, $logoUri,$id)
    {
        $this->name = $name;
        $this->logoUri = $logoUri;
        $this->setIdentifier($id);
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getLogoUri()
    {
        return $this->logoUri;
    }

    public function setLogoUri($logoUri)
    {
        $this->logoUri = $logoUri;
    }

}

?>
