<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of defaultController
 *
 * @author sivakumar
 */
include_once getcwd() . '/Models/Player.php';
include_once getcwd() . '/Models/Team.php';
include_once getcwd() . '/Models/Identifyble.php';
//include your soccer data model here

function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++)
    {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$players = array();
$availableTeams = array();
for ($i = 100; $i < 120; $i++)
{
    $players[$i]['firstName'] = generateRandomString(8);
    $players[$i]['LastNameName'] = generateRandomString(8);
    $players[$i]['TeamID'] = rand(100, 120);
    $players[$i]['ID'] = rand(1000, 1200);
}
for ($i = 100; $i < 120; $i++)
{
    $availableTeams[] = new Team(generateRandomString(8), 'TestLogo', rand(100, 120));
}
foreach ($players as $teamID => $teamMember)
{
    $playersList[] = new Player($teamMember['firstName'], $teamMember['LastNameName'], 'test', $teamMember['TeamID'], $teamMember['ID']);
}
//    echo '<pre>';
//        print_r(var_dump($availableTeams));
//    echo'</pre>';
//if ($_SERVER['REQUEST_METHOD'] == 'GET')
try
{
    if (!isset($_REQUEST['id']))
    {

        if (class_exists('SoccerData') && method_exists('SoccerData', 'getListTeams'))
        {
            $soccerData = new SoccerData();
            $availableTeams = $soccerData->getListTeams();
        }
        include_once getcwd() . '/views/ViewTeams.php';
    } else
    {

        if (class_exists('SoccerData') && method_exists('SoccerData', 'getPlayers'))
        {
            $soccerData = new SoccerData();
            $playersList = $soccerData->getPlayers();
            include_once getcwd() . '/views/ViewPlayers.php';
        }
        include_once getcwd() . '/views/ViewPlayers.php';
    }
} catch (Exception $e)
{
    $message = $e->getMessage();
    include_once getcwd() . '/views/ErrorMessage.php';
}
?>
