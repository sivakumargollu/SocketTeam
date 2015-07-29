
<?php

//$team = new Team();
//$team->getIdentifier();
//$team->getName();
//$team->getLogoUri();
$url = 'http://' . $_SERVER['HTTP_HOST'] . 'service/SoccerTeamDemo/SocketTeam/defaultController.php';
$str = '<table border="1">
<tr>
<th>Player ID</th>
<th>Player Team</th>
<th>First Name</th>
<th>Last Name</th>
<th>Player Image</th>
</tr>
';
global $playersList;
$recordExists = false;
foreach ($playersList as $index => $playerData)
{
    if (is_a($playerData, 'Player'))
    {
        $recordExists = true;
        $str .= '<tr>';
        $str .= '<td>' . $playerData->getIdentifier() . '</td>';
        $str .= '<td>'. $playerData->getTeam().'</td>';
        $str .= '<td>' . $playerData->getFirstName() . '</td>';
        $str .= '<td>' . $playerData->getLastName() . '</td>';
        $str .= '<td>' . $playerData->getImageUri() . '</td>';
        $str .= '</tr>';
    }
}
$str .= '</table>';
if($recordExists)
    echo $str;
else
    echo '<font color=red size=2.5>No Players in the team</font>';
