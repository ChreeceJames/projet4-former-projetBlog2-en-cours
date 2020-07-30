<?php

class MessageView {
	public $html;
	public function __construct($succeed) {
		if($succeed) $this->makeHtml(
			"succeed",
		 	"les modifications ont bien été enregistrées. :)"
		);
		else  $this->makeHtml(
			"failed",
			"les modifications n'ont pas pu être prises en compte. Veuillez recommencer plus tard"
		);
	}

	private function makeHtml($class, $msg){
		$this->html = "<div class='$class'>$msg</div>";
	}
}