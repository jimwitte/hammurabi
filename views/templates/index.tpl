{include 'header.tpl'}

{if $view eq 'start'}
	<p>Try your hand at governing ancient Sumeria for a ten-year term of office.</p>
{/if}

<div class="well well-sm">
	<p>Hammurabi: I beg to report to you, in year {$game->year} of your glorious reign:
	<ul>
		<li>{$game->turn->peopleStarved} people starved</li>
		<li>{$game->turn->immigration} people came to the city</li>
		{if !empty($game->turn->plagueDeaths)}<li>A horrible plague struck! {$game->turn->plagueDeaths} people became sick and died.</li>{/if}
		<li>Population is now {$game->population}</li>
	</ul>

   
</div>
   
{include 'form.tpl'}

{include 'footer.tpl'}
