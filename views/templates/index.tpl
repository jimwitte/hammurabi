{include 'header.tpl'}

{if $view eq 'start'}
	<p>Try your hand at governing ancient Sumeria for a ten-year term of office.</p>
{/if}

<div class="well well-sm {if $view neq 'start' AND empty($game->turn->errors)}animated rotateIn{/if} {if !empty($game->turn->errors)}animated shake{/if}">

	{if !empty($game->turn->errors)}
		<div class="alert alert-danger" role="alert">
			{foreach from=$game->turn->errors item=thisError}
    			<p>{$thisError}</p>
			{/foreach}
		</div>
	{/if}

	<p>Hammurabi: I beg to report to you, in <strong>Year {$game->year}</strong> of your glorious reign:

	<p>
		{$game->turn->peopleStarved} people starved. <br />
		{$game->turn->immigration} people came to the city. <br />
		{if !empty($game->turn->plagueDeaths)}A horrible plague struck! {$game->turn->plagueDeaths} people became sick and died.<br />{/if}
		Population is now {$game->population} people.
	</p>
	
	<p>
		You harvested {$game->turn->harvest} bushels last year. The rats ate {$game->turn->ratLoss} bushels.<br />
		You now have {$game->grainStored} bushels in store.<br />
	</p>
	<p>
		{if !empty($game->turn->acresSold)}You sold {$game->turn->acresSold} acres last year.<br />{/if}
		{if !empty($game->turn->acresBought)}You bought {$game->turn->acresBought} acres last year.<br />{/if}
		You own {$game->acresOwned} acres.<br />
		Land is trading at {$game->landValue} bushels per acre.<br />
	</p>
</div>
   
{include 'form.tpl'}

{include 'footer.tpl'}
