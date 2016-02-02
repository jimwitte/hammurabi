<?php
//======================================================================
// CONTROLLER file
//======================================================================

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/model.class.php';
require __DIR__ . '/view.class.php';

$game = new Game();
$render = new Renderer();
$render->assignGameToTemplate($game);
$render->displayWithTemplate('index.tpl');


?>