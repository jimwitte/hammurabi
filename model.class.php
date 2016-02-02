<?php

//======================================================================
// MODEL objects
//======================================================================


class Turn {
	//player inputs
	public $acresBought = 0;
	public $acresSold = 0;
	public $grainFed = 0;
	public $acresPlanted = 0;
	
	//player input errors
	public $errors = [];
	
	//turn events
	public $impeach = FALSE;
	public $harvest = 0;
	public $ratLoss = 0;
	public $peopleStarved = 0;
	public $plagueDeaths = 0;
	public $immigration = 0;
	
	function __construct() {
		// get player inputs from form if available
		$this->acresBought = 100;
		$this->acresSold = 0;
		$this->grainFed = 4000;
		$this->acresPlanted = 400;
	}
	
}

class Game {
    public $year = 0;
    public $landValue = 0;
    public $acresOwned = 0;
    public $population = 0;
    public $grainStored = 0;
    public $totalStarved = 0;
    
    public $turn;

    function __construct() {
    
    	session_start();
    	//session_unset(); // clear out session
    	
    	// create a new turn for the game
        $this->turn = new Turn();
    	
    	// if prior game data available, create game object with game in progress
		if(isset($_SESSION['year'])) {
        	$this->year = $_SESSION['year'];
        	$this->landValue = $_SESSION['landValue'];
        	$this->acresOwned = $_SESSION['acresOwned'];
        	$this->population = $_SESSION['population'];
        	$this->grainStored = $_SESSION['grainStored'];
        	$this->totalStarved = $_SESSION['totalStarved'];

			if ($this->year <= 10) {
        		$this->advanceTurn($this->turn); // game in progress, advance turn
        	} else {
        		$this->gameOver();
        	}
        	
        } else {
        	// default values for new game 
            $this->year = 1;
        	$this->landValue = 19;
        	$this->acresOwned = 1000;
        	$this->population = 100;
        	$this->grainStored = 2000;
        	$this->totalStarved = 0;
        	// do not advance game turn, we are starting a new game
        	$this->writeGameState(); // write starting values to game state	 
        }
    }

	function getGameState($field) {
		$status = $this->$field;
		return $status;
	}
	
	function setGameState($field,$value) {
		$this->$field = $value;
	}
	
	function validateTurn($turn) {
		$errors = [];
		if ($turn->acresSold < 0 or $turn->acresBought < 0 or $turn->grainFed < 0 or $turn->acresPlanted < 0) {$errors[]="Values must be greater than 0.";}
		
		$landSaleProceeds = intval($turn->acresSold * $this->landValue);
		$landPurchaseCost = intval($turn->acresBought * $this->landValue);
		$bushelsPlanted = intval($turn->acresPlanted * 2);
		$totalBushelsUsed = intval($bushelsPlanted + $landPurchaseCost + $turn->grainFed - $landSaleProceeds);
		if ($totalBushelsUsed > $this->grainStored) {$errors[]="O Great One, you don't have enough grain! Your edict requires $totalBushelsUsed bushels.";}
		if ($turn->acresSold > $this->acresOwned){$errors[]="O Great One, I am unable to sell as you request. You have $this->acresOwned acres to sell.";}		
		$peopleRequired = intval($turn->acresPlanted * 10);
		if ($peopleRequired >  $this->population) {$errors[]="O Great One, not enough people to plant as you request. $peopleRequired people are needed to plant $turn->acresPlanted acres.";}
		$turn->errors = $errors;
	}
	
	function advanceTurn($turn) {
		//validate game turn inputs
		$turn->errors = $this->validateTurn($turn);
	
		// harvest is 1-6 bushels per acre
		$turn->harvest = intval($turn->acresPlanted * rand(1,6)); 
		
		// 50/50 chance of losing 10%-14% of stored grain
		$turn->ratLoss = intval(rand(0,1) * $this->grainStored / rand(7,10)); 
		
		// 20 bushels required to feed each person. Can't feed more people than current population
		$peopleFed = intval(min(($turn->grainFed / 20),$this->population));
		$turn->peopleStarved = intval($this->population - $peopleFed);

		$landPurchaseCost = intval($turn->acresBought * $this->landValue);
		$landSaleProceeds = intval($turn->acresSold * $this->landValue);
		$bushelsPlanted = intval($turn->acresPlanted * 2);
		$totalBushelsUsed = intval($bushelsPlanted + $landPurchaseCost + $turn->grainFed - $landSaleProceeds);
		
		//15% chance of plague, kills half the population that didn't starve first
		if (rand(1, 20) < 4) {
			$turn->plagueDeaths = intval(($this->population - $turn->peopleStarved) / 2);
        } else {
        	$turn->plagueDeaths = 0;
        }
		
		$totalDeaths = $turn->plagueDeaths + $turn->peopleStarved;
		
		//chance of immigration if nobody died
		if ($totalDeaths < 1 ) {
            $immigration = intval((20 * $this->acresOwned + $this->grainStored) / (100 * $this->population) + 1);
        } else {
            $immigration = 0;
        }
        
        //impeachment if more than 45% of the people starve in a single Turn
        if ($turn->peopleStarved > .45 * $this->population) {
        	$impeach = TRUE;
        } else {
        	$impeach = FALSE;
        }
        
        // update game state 
		$this->population = intval($this->population - $totalDeaths + $immigration);
		$this->grainStored = intval($this->grainStored - $totalBushelsUsed - $turn->ratLoss + $turn->harvest);
		// $this->year++; //increment by 1
		$this->landValue = rand(15, 25);
		$this->acresOwned = $this->acresOwned + $turn->acresBought - $turn->acresSold;
		$this->totalStarved = $this->totalStarved + $turn->peopleStarved;
		
		$this->writeGameState(); // write game state to session
	}
	
	function gameOver() {
		echo "game over!";
	}
	
	
	function writeGameState() {
		$_SESSION['year'] = $this->year;
		$_SESSION['landValue'] = $this->landValue;
		$_SESSION['acresOwned'] = $this->acresOwned;
		$_SESSION['population'] = $this->population;
		$_SESSION['grainStored'] = $this->grainStored;
		$_SESSION['totalStarved'] = $this->totalStarved;
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

?>