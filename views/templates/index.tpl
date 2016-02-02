{include 'header.tpl'}

{if $view eq 'start'}
	<p>Try your hand at governing ancient Sumeria for a ten-year term of office.</p>
{/if}

<div class="well well-sm">
	<p>Hammurabi: I beg to report to you, in year {$game->year} of your glorious reign:
	
		{foreach from=$game->turn->errors item=thisError}
    		<div class="alert alert-danger" role="alert">{$thisError}</div>
		{/foreach}


	<ul>
		<li>{$game->turn->peopleStarved} people starved</li>
		<li>{$game->turn->immigration} people came to the city</li>
		{if !empty($game->turn->plagueDeaths)}<li>A horrible plague struck! {$game->turn->plagueDeaths} people became sick and died.</li>{/if}
		<li>Population is now {$game->population}</li>
	</ul>
	
	<ul>
		<li>You harvested {$game->turn->harvest} bushels last year. The rats ate {$game->turn->ratLoss} bushels.</li>
		<li>You now have {$game->grainStored} bushels in store.</li>
		<li>You own {$game->acresOwned} acres.</li>
		<li>Land is trading at {$game->landValue} bushels per acre.</li>
	
	
	</ul>

   
</div>
   
{include 'form.tpl'}

{include 'footer.tpl'}
