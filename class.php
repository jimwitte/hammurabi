<?php

class Game {
    public $year;
    public $landValue;
    public $acresOwned;
    public $population;
    public $grainStored;
    public $totalStarved;

    function __construct() {
        $this->year = $_SESSION['year'];
        $this->landValue = $_SESSION['landValue'];
        $this->acresOwned = $_SESSION['acresOwned'];
        $this->population = $_SESSION['population'];
        $this->grainStored = $_SESSION['grainStored'];
        $this->totalStarved = $_SESSION['totalStarved'];
    }

	function getGameState($field) {
		$status = $this->$field;
		return $status;
	}
	
	function validateTurn($acresBought, $acresSold, $grainFed, $acresPlanted) {
		$error = '';
		if ($acresSold < 0 or $acresBought < 0 or $grainFed < 0 or $acresPlanted < 0) {$error=$error."Values must be greater than 0. <br />";}
		$bushelsUsed = ($acresBought * $this->landValue) + $grainFed + (2 * $acresPlanted);
		$landSaleProceeds = $acresSold * $this->landValue;
		if ($bushelsUsed > ($this->grainStored + $landSaleProceeds)) {$error=$error."Impossible. Not enough grain! <br />";}
		if ($acresSold > $this->acresOwned){$error=$error."O Great One, you only have $this->acresOwned acres. <br />";}		
		if ($acresPlanted > 10 * $this->population) {$error=$error."Not enough people to plant $acresPlanted acres! <br />";}
		if ($error != '') {return $error; } else {return TRUE; }
	}
	
	function advanceTurn($acresBought, $acresSold, $grainFed, $acresPlanted) {
		$harvest = $acresPlanted * rand(1, 6);
		$bushelsUsed = ($acresBought * $this->landValue) + $grainFed + (2 * $acresPlanted);
		$ratLoss = intval(rand(0,1) * $this->grainStored / rand(7,10));
		
		$peopleFed = intval($grainFed / 20);
		$peopleStarved = $this->population - $peopleFed;
		if ($peopleStarved < 0) { $peopleStarved = 0; }
		
		
		if (rand(1, 20) < 4) {
			//15% chance of plague, kills half the population
			$plagueDeaths = intval($this->population / 2);
        } else {$plagueDeaths = 0;}
        
    	$totalDeaths = $plagueDeaths + $peopleStarved;
    	echo "total deaths: $totalDeaths\n";
		if ($totalDeaths < 1 ) {
            $immigration = intval((20 * $this->acresOwned + $this->grainStored) / (100 * $this->population) + 1);
        } else {
            $immigration = 0;
        }
	
	
		$impeach = FALSE;
		if ($peopleStarved > .45 * $this->population) { $impeach = TRUE; }
		
		$results = array('impeach'=>$impeach, 'harvest'=>$harvest, 'ratLoss'=>$ratLoss, 'peopleStarved'=>$peopleStarved, 'plagueDeaths'=>$plagueDeaths, 'immigration'=>$immigration);
	
		// update game state 
		$this->population = $this->population - $totalDeaths;
		$this->grainStored = intval($this->grainStored + $harvest - $ratLoss - ($acresSold * $this->landValue) + ($acresBought * $this->landValue));
		// $this->year++; //increment by 1
		$this->landValue = rand(17, 23);
		$this->acresOwned = $this->acresOwned + $acresBought - $acresSold;
		$this->totalStarved = $this->totalStarved + $totalDeaths;
		
		return $results;
	}
	
	function writeGameState () {
		$_SESSION['year'] = $this->year;
		$_SESSION['landValue'] = $this->landValue;
		$_SESSION['acresOwned'] = $this->acresOwned;
		$_SESSION['population'] = $this->population;
		$_SESSION['grainStored'] = $this->grainStored;
		$_SESSION['totalStarved'] = $this->totalStarved;
	}
	
	function resetGameState () {
		$_SESSION['year'] = '1';
		$_SESSION['landValue'] = '19';
		$_SESSION['acresOwned'] = '1000';
		$_SESSION['population'] = '100';
		$_SESSION['grainStored'] = '2800';
		$_SESSION['totalStarved'] = '0';
		
		$this->year = $_SESSION['year'];
        $this->landValue = $_SESSION['landValue'];
        $this->acresOwned = $_SESSION['acresOwned'];
        $this->population = $_SESSION['population'];
        $this->grainStored = $_SESSION['grainStored'];
        $this->totalStarved = $_SESSION['totalStarved'];

	}
	
	function statusReport($events) {
		$status = "You are in year ".$this->year." of your 10 year reign. ";
		$status .= "You have ".$this->acresOwned." acres. ";
		$status .= "Population is ".$this->population." people. ";
		$status .= "You have  $this->grainStored bushels in storage. ";
		$status .= "Land is trading at ".$this->landValue." bushels/acre. ";
		$status .= "Last year you harvested ".$events['harvest']." bushels. ";
		if ($events['ratLoss'] > 0 ) {
			$status .= "Rats ate ".$events['ratLoss']." bushels. ";
		}
		if ($events['peopleStarved'] > 0) {
			$status .= $events['peopleStarved']." people starved. ";
		}
		if ($events['impeach']) {
			$status .= "You have been impeached and thrown out of office!";
		}
		
		return $status;
	}
	
}

session_start();
if(!isset($_SESSION['year'])) {
	$_SESSION['year'] = '1';
	$_SESSION['landValue'] = '19';
	$_SESSION['acresOwned'] = '1000';
	$_SESSION['population'] = '100';
	$_SESSION['grainStored'] = '2800';
	$_SESSION['totalStarved'] = '0';
}

$game = new Game();
if ($game->validateTurn(0,0,2000,400)) {
	$turn = $game->advanceTurn(0,0,1000,100);
	// echo $game->statusReport ($turn);
	include 'status.inc';
}

?>