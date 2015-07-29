
<?php

//$team = new Team();
//$team->getIdentifier();
//$team->getName();
//$team->getLogoUri();
$url = 'http://' . $_SERVER['HTTP_HOST'] . '/service/SoccerTeamDemo/SocketTeam/defaultController.php';
$str = '<table border="1">
<tr>
<th>Team ID</th>
<th>Team Name</th>
<th>Team Logo</th>
</tr>';
$recordExists = false;
global $availableTeams;
foreach ($availableTeams as $teamID => $teamData)
{
    if (is_a($teamData, 'Team'))
    {
        $recordExists = true;
        $str .= '<tr>';
        $str .= '<td><a href="' . $url . '?id=' . $teamData->getIdentifier() . '">' . $teamData->getIdentifier() . '</a></td>';
        $str .= '<td>' . $teamData->getName() . '</td>';
        $str .= '<td>' . $teamData->getLogoUri() . '</td>';
        $str .= '</tr>';
    }
}
if ($recordExists)
    echo $str;
else
{
    echo '<font color=red size=2.5>No Teams Created Yet</font>';
}
