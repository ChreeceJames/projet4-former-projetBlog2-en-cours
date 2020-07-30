<?php

/**
 * 
 */
class View
{
	public $html = "";
	public function __construct($data, $template) {
		if (isset($data[0])) $this->makeLoopHtml($data, $template);
		else                 $this->makeHtml($data, $template);
	}

	protected function makeHtml($data, $template){
	    global $config;
	    $data["{{ working folder }}"] = $config["workingFolder"];
		$file = file_get_contents("template/$template.html");
		$this->html .= str_replace(
	      array_keys($data),
	      $data,
	      $file
	    );
	}

	private function makeLoopHtml($data, $template){
		foreach ($data as $key => $value) {
			$this->makeHtml($value, $template);
		}
	}
}