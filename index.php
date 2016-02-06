<?php
//======================================================================
// CONTROLLER file
//======================================================================

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/model.class.php';
require __DIR__ . '/view.class.php';

$game = new Game();
$render = new Renderer();

// choose view
if (!empty($game->turn->errors)) {
	$render->viewName = 'error';
} elseif ($game->year == 1) {
	$render->viewName = 'start';
} elseif ($game->year > 10) {
	$render->viewName = 'end';
} else {
	$render->viewName ='midgame';
}


$render->assignGameToTemplate($game);
$render->displayWithTemplate('index.tpl');


?>