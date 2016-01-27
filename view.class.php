<?php
//======================================================================
// VIEW objects
//======================================================================


require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/model.class.php';


class Renderer {
	public $template;

	function __construct() {
		$this->template = new Smarty();
		$this->template->setTemplateDir('./views/templates');
		$this->template->setCompileDir('./views/templates_c');
		$this->template->setCacheDir('./views/cache');
		$this->template->setConfigDir('./views/configs');
	}
	
	function createPlaceholder($placeholderName, $content) {
		$this->template->assign($placeholderName, $content);
	}
	
	function displayWithTemplate($templateName) {
		$this->template->display($templateName);
	}

}


class TurnView {
 // display a turn

}



$game = new Game();
$render = new Renderer();

$render->createPlaceholder('dump',' world!');
$render->displayWithTemplate('index.tpl');

?>