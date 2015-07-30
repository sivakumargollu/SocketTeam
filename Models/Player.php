<?php



include_once '/www/htdocs/service/SoccerTeamDemo/SocketTeam/Models/Identifyble.php';
class Player extends Identifyble
{

    private $firstName;
    private $lastName;
    private $imageUri;
    private $team;

    public function getTeam()
    {
        return $this->team;
    }

    public function setTeam($team)
    {
        $this->team = $team;
    }

    function __construct($firstName, $lastName, $imageUri, $team,$id)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->imageUri = $imageUri;
        $this->team = $team;
        $this->setIdentifier($id);
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function getImageUri()
    {
        return $this->imageUri;
    }

    public function setImageUri($imageUri)
    {
        $this->imageUri = $imageUri;
    }

}

?>
