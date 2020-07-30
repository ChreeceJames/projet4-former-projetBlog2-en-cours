<?php

class NavBar
{
	public $html;
	function __construct($actualPage, $listPages) {
		$this->html = "";
		$subFolder  = "/";
		global $config;
		if (isset($config["workingFolder"])){
			if ($config["workingFolder"] != ""){
				$subFolder = $config["workingFolder"]."/";
			}
		}
		$nPages     = count($listPages);
		foreach ($listPages as $key => $value) {
			if ($key == $actualPage) $this->html .= "<li  class='active'>$key</li>";
			else $this->html .= '<li><a href="http://test.com'.$subFolder.$value.'">'.$key.'</a></li>';
		}
	}
}