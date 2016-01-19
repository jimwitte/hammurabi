<?php 
session_start();
// set defaults for new game
if(!isset($_SESSION['year'])) {
	$_SESSION['year'] = '1';
	$_SESSION['landValue'] = '19';
	$_SESSION['acresOwned'] = '1000';
	$_SESSION['population'] = '100';
	$_SESSION['grainStored'] = '2800';
	$_SESSION['totalStarved'] = '0';
}

function printState () {
	echo "Year:".$_SESSION['year'];
	echo " LandValue:".$_SESSION['landValue'];
	echo " AcresOwned:".$_SESSION['acresOwned'];
	echo " Population:".$_SESSION['population'];
	echo " GrainStored:".$_SESSION['grainStored'];
	echo " TotalStarved:".$_SESSION['totalStarved'];
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Hammurabi: The Game</title>
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

        <div class="container">
            <h1>Hammurabi: The Game</h1>
            <p>Inspired by the BASIC game, <a href="http://en.wikipedia.org/wiki/Hamurabi">http://en.wikipedia.org/wiki/Hamurabi</a></p>
            <div class="well well-sm">
                <p class="lead">Hammurabi, I beg to report to you:</p>
                <p><?php printState(); ?></p>
                <?php 
					if ($_SESSION['year'] == 1) {
						echo '<p>Try your hand at governing ancient Sumaria for a ten-year term of office.<br />
						You are in the first year of your ten-year reign.</p>';
					} elseif ($_SESSION['year'] < 10) {
						echo "In year ".$_SESSION['year'].", Y people starved, Z came to the city.<br />";
					}
				?>
                <p><?php include 'status.inc'; ?></p>
                <?php if ($_SESSION['year'] < 10) { include 'form.inc'; } ?>
            </div>
        </div>
    </body>
</html>
