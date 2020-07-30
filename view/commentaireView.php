<?php
require_once "view.php";
require_once "index.php";

class CommentaireView extends View
{

    private $path;
    public $html = "";
   // public function __construct($data, $template) {
   //     if (isset($data[0])) $this->makeLoopHtml($data, $template);
     //   else                 $this->makeHtml($data, $template);
   // }

    public function __construct($data, $template)
    {
        if (empty($data)) return;
        parent::__construct($data, $template);

        global $safeData;
        global $config;

        if ($safeData->uri[0] === "admin") {
            $admin = true;
            $boutonText = "valider";
            $this->path = "./" . $config["workingFolder"] . "/" . implode("/", $safeData->uri) . "/";
        } else {
            $admin = false;
            $boutonText = "signaler";
            $this->path = $config['workingFolder'] . "/" . $safeData->uri[0] . "/" . $safeData->uri[1] . "/";

        }
        $path = "./" . $config["workingFolder"] . "/" . implode("/", $safeData->uri);
	$this->path = "./";
        foreach ($data as $key => $value) {
            var_dump($this);
            switch ($value["state"]) {
                case '0':
                    $value["{{ bouton }}"] = '<a href="' . $this->$path . 'moderate/1/' . $value["id"] . '" class="bouton">' . $boutonText . '</a>';
                    break;
                case '1':
                    $value["{{ bouton }}"] = '<a href="' . $this->$path . 'moderate/2/' . $value["id"] . '" class="bouton">' . $boutonText . '</a>';
                    break;
                case '2':
                    $value["{{ bouton }}"] = '<a href="' . $this->$path . 'moderate/3/' . $value["id"] . '" class="bouton">' . $boutonText . '</a>';
                    break;
            }
            if ($admin) $value["{{ bouton }}"] .= $this->addDelete($value['id']);
            $this->makeHtml($value, $template);
        }
    }

    private function addDelete($id)
    {
        return "<br><a href='/$this->path/moderate/4/$id' class='bouton'>supprimer</a>";
    }
}