{include 'header.tpl'}

{if $view eq 'start'}
	<p>Try your hand at governing ancient Sumeria for a ten-year term of office.</p>
{/if}

<div class="well well-sm {if $view neq 'start'}animated rotateIn{/if}">

	{foreach from=$game->turn->errors item=thisError}
    	<div class="alert alert-danger" role="alert">{$thisError}</div>
	{/foreach}

	<p>Hammurabi: I beg to report to you, in year {$game->year} of your glorious reign:

	<p>
		{$game->turn->peopleStarved} people starved. <br />
		{$game->turn->immigration} people came to the city. <br />
		{if !empty($game->turn->plagueDeaths)}A horrible plague struck! {$game->turn->plagueDeaths} people became sick and died.<br />{/if}
		Population is now {$game->population} people.
	</p>
	
	<p>
		You harvested {$game->turn->harvest} bushels last year. The rats ate {$game->turn->ratLoss} bushels.<br />
		You now have {$game->grainStored} bushels in store.<br />
		
		{if !empty($game->turn->acresSold)}You sold {$game->turn->acresSold} acres last year.<br />{/if}
		{if !empty($game->turn->acresBought)}You bought {$game->turn->acresBought} acres last year.<br />{/if}
		You own {$game->acresOwned} acres.<br />
		Land is trading at {$game->landValue} bushels per acre.<br />
	</p>
</div>
   
{include 'form.tpl'}

{include 'footer.tpl'}
