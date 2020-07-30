<?php

require "model/chapitreModel.php";

// require "redaction.php";

class Chapitre
{
    public $_id;
    private $_titre;
    private $_contenu;
    private $_date_time_publication;
    private $_date_time_edition;
    public $_slug;
    public $_html;
    private $_data;


// new Chapitre(["id"=>22]);
// new Chapitre(["slug"=>"un-chapitre-extraordinaire"]);
// new Chapitre(["list"=>["start"=>15, "qty"=>5]]);
    /**
     * make a new iteration of chapitre
     * @param Array $args un tableau contenant soit id, soit slug, soit list
     */
    public function __construct($args)
    {
        global $safeData;
        if ($safeData->post !== null) {
            if ($safeData->post['titre'] !== null) {
               // die(var_dump($safeData->uri));
                $message = $this->updateOrAdd($safeData);
                $vue = $this->add($message);
            }
            if ($safeData->post['commentaires_pseudo'] !== null) {
                $addComment = new Commentaires(["add"=>true]);
                var_dump($addComment->html);
            }
        } else {
            //on récupère les données de la base
            $data    = new ChapitreModel($args);
            $pointer = 0;

            //on génère les données de la classe et la vue en fonction des arguments
            if (isset($args["list"]))   {
                $vue = $this->listOfChapter($args, $data->_contenu);
                $pointer++;
            }
        }
        //on enregistre la vue générée
        // $this->_contenu = $vue->html;
    }


    /* ----fonctions ajoutées---- */
    private function updateOrAdd($data)
    {
        global $safeData;
        $data = [
            "slug"    => $safeData->post["slug"],
            "title"   => $safeData->post["titre"],
            "content" => $safeData->post["contenu"]
        ];
        if ($safeData->post["id"] !== null) {
            $data["update"] = $safeData->post;
            $data["id"]   = $safeData->post["id"];
        }
        else {
            $data["save"] = true;
        }

        new ChapitreModel($data);
        return $data;
    }

    /**
     * génère la vue en fonction des données du model
     * @param Array $data données issues de la base de donnée
     * @return View
     */
    public function singleChapter($data)
    {

        //on hydrate la classe chapitre
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }

        if ($this->_date_time_edition != null) $this->_date_time_edition = "<em>article modifié le $this->_date_time_edition</em>";
        else $this->_date_time_edition = "";

        // on retourne la vue claculée en fonction des données
        return new View(

            [
                "{{ title }}"                 => $this->_titre,
                "{{ contenu }}"               => $this->_contenu,
                "{{ date_time_publication }}" => $this->_date_time_publication,
                "{{ date_time_edition }}"     => $this->_date_time_edition,
                "{{ slug }}"                  => $this->_slug,
                "{{ id }}"                    => $this->_id,
            ],
            "fullChapitre"
        );
    }


    /**
     * génère la vue en fonction des données du model et des spécifications données lors de l'instantiation de la classe.
     * @param Array $data données issues de la base de donnée
     * @param Array $args spécifications données lors de l'instantiation de la classe. Contient un tableau list avec les valeurs start et qty
     * @return View
     */
    private function listOfChapter($args, $data)
    {
        //on "hydrate" la classe chapitre

        // on retourne la vue claculée en fonction des données
        global $safeData;
        if($safeData->uri[0]==="admin") {
           // $path = "/admin/edit/";
           $admin = true;
        }
        else {
           // $path ="/chapitre/";
           $admin = false;
        }
        // foreach ($data as $key => $value){
        //     $data[$key]["{{ link }}"]=$path.$data[$key]["{{ slug }}"];

        // }
        if ($admin) $template = "adminList";
        else {
            foreach ($data as $key => $value){
                if ($key === count($data)-1) {
                    $data[$key]["{{ masque }}"]= "nextHidden";
                    $data[$key]["{{ nextChapter }}"]= "";
                }
                else {
                    $data[$key]["{{ masque }}"]= "";
                    $data[$key]["{{ nextChapter }}"]= "chapter".$data[$key+1]["{{ id }}"];
                }

                $data[$key]["{{ html }}"]= new View(
                    $data[$key],
                    "fullChapitre"
                );
            }

            // $template = "listContainer";
            // $list = 
            // $data = [
            //     "{{ title }}"=> $this->_titre,
            //     "{{ list }}"=> $list->html,
            // ];
        }

        $this->_data = $data;
        // return new View(
        //     $data,
        //     $template
        // );
}

    /**
     * ajoute un nouvel article
     * @param string $message [description]
     */
    public function add($message)
    {

        if (isset($message["save"])){
            $model = new ChapitreModel(["lastEntry"=>true]);
            $message["id"] = $model->_id;
        }

        if ( !isset($message["message"]) ) $message["message"] = "";


        return new View(
            [
                "{{ id }}"      => $message["id"],
                "{{ title }}"   => $message["title"],
                "{{ content }}" => $message["content"],
                "{{ message }}" => $message["message"],
                "{{ slug }}"    => $message["slug"]
            ],
            "addChapter"
        );
    }

    public function edit($data, $message)
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }

        return new View(
            [
                "{{ id }}" => $this->_id,
                "{{ title }}" => $this->_titre,
                "{{ content }}" => $this->_contenu,
                "{{ message }}" => $message,
                "{{ slug }}" => $this->_slug
            ],
            "editChapter"
        );
    }

        public function delete($data)
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }

        return new View(
            [
                "{{ id }}" => $this->_id,
                "{{ title }}" => $this->_titre,
                "{{ content }}" => $this->_contenu,
                "{{ message }}" => $message,
                "{{ slug }}" => $this->_slug
            ],
            "adminList"
        );
    }

// Liste des getters

    public function id()
    {
        return $this->_id;
    }

    public function title()
    {
        return $this->_titre;
    }

    public function content()
    {
        return $this->_contenu;
    }

    public function timePublication()
    {
        return $this->_date_time_publication;
    }

    public function timeEdition()
    {
        return $this->_date_time_edition;
    }

    public function slug()
    {
        return $this->_slug;
    }

    // Liste des setters

    public function setId($id)
    {

        $id = (int)$id;
        if ($id > 0) {
            $this->_id = $id;
        }
    }

    public function setTitre($_titre)
    {
        // On vérifie qu'il s'agit bien d'une chaîne de caractères.
        if (is_string($_titre)) {
            $this->_titre = $_titre;
        }
    }

    public function setContenu($_contenu)
    {
        if (preg_match(['#a-zA-Z0-9#'], $_contenu)) {
            $this->_contenu = $_contenu;
        }
    }

    public function setDateTimePublication($_date_time_publication)
    {
        if (preg_match('#[0-2][0-3]:[0-5][0-9]:[0-5][0-9]#', $_date_time_publication)) {
            $this->_date_time_publication = $_date_time_publication;
        }
    }

    public function setDateTimeEdition($_date_time_edition)
    {
        if (preg_match('#[0-2][0-3]:[0-5][0-9]:[0-5][0-9]#', $_date_time_edition)) {
            $this->_date_time_edition = $_date_time_edition;
        }
    }

    public function setSlug($_slug)
    {
        if (is_string($_slug)) {
            $this->_slug = $_slug;
        }
    }

    public function getData(){
        return $this->_data;
    }



}