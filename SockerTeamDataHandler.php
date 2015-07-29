<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SockerTeamDataHandler
 *
 * @author sivakumar
 */
class SockerTeamDataHandler
{

    private $teamsList;
    private $playersList;

    public function __construct()
    {
       // require ;
    }

    public function getTeams()
    {

        $soccerData = new SoccerData();
        return $soccerData->getListTeams();
    }

    public function getPlayers($teamId)
    {
        $soccerData = new SoccerData();
        return $soccerData->getPlayers();
    }

}

?>
