<p>You are in year <?php echo $game->year;?> of your ten year reign. </p>
<p>You have <?php echo $game->acresOwned; ?> acres. </p>
<p><?php if ($turn['peopleStarved'] > 0) {echo 'Last year, '.$turn['peopleStarved'].' people starved! '; }?>
<?php if ($turn['plagueDeaths'] > 0) {echo 'Last year, '.$turn['plagueDeaths'].' people died from a terrible plague! '; }?>
<?php if ($turn['immigration'] > 0) {echo 'Last year, '.$turn['immigration'].' new people came to the city. '; }?>
Population is now <?php echo $game->population; ?> people.</p>
<p>Last year, <?php echo $turn['harvest']; ?> bushels were harvested.
<?php if ($turn['ratLoss'] > 0) {echo ' Rats ate '.$turn['ratLoss'].' bushels. ';}?>
You now have <?php echo $game->grainStored; ?> bushels in storage.</p>
<p>Land is trading at <?php echo $game->landValue; ?> bushels/acre. </p>