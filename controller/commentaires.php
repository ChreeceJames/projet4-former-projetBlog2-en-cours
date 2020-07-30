<?php
require_once "model/commentaireModel.php";
require_once "view/commentaireView.php";
require_once "view/messageView.php";

class Commentaires
{
    private $_id;
    private $_pseudo;
    private $_contenu;
    private $_date_time_publication;
    private $_chapterID;
    private $_state;
    private $_html;

    public function __construct($args)
    {
        global $safeData;
        var_dump($args);
        $dataCommentaires = new CommentaireModel($args);

        // Si on soumet le formulaire de commentaire
        if (isset($safeData->post['submit_commentaire'])) {
            $dataCommentaires->createComment();
            if ($dataCommentaires->_success === false) $vue = new MessageView(false);
            else $vue = new MessageView(true);
            $this->_html = $vue->html;
        }

        if (isset($args["update"])) {
        	//on récupère les données de la base
            if ($dataCommentaires->_success === false) $vue = new MessageView(false);
        	else $vue = new MessageView(true);
            $this->_html =  $vue->html;
        }

        //on génère les données de la classe et la vue en fonction des arguments
        if (isset($args["id"])) {
            var_dump($dataCommentaires);
           //  $this->_contenu = file_get_contents("template/resumeCommentaire.html");
             $vue = new CommentaireView($dataCommentaires->_data,'fullComments');
             $this->_contenu = $vue->html;
        } else $vue = $this->revealComment($dataCommentaires->_data);
        //on enregistre la vue générée
       // $this->_contenu = $vue->html;

    }

    /**
     * génère la vue en fonction des données du model
     * @param Array $data données issues de la base de donnée
     * @return View
     */
    private function revealComment($data)
    {
        return new CommentaireView(
            $data,
            "resumeCommentaire"
        );

    }

    public function id()
    {
        return $this->_id;
    }

    public function pseudo()
    {
        return $this->_pseudo;
    }

    public function content()
    {
        return $this->_contenu;
    }

    public function timePublication()
    {
        return $this->_date_time_publication;
    }

    public function getChapterID()
    {
        return $this->_chapterID;
    }

    public function state()
    {
        return $this->_state;
    }

    // Liste des setters

    public function setId($id)
    {

        $id = (int)$id;
        if ($id > 0) {
            $this->_id = $id;
        }
    }

    public function setPseudo($_pseudo)
    {
        // On vérifie qu'il s'agit bien d'une chaîne de caractères.
        if (is_string($_pseudo)) {
            $this->_pseudo = $_pseudo;
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

    public function setChapterID($_chapterID)
    {

        $chapterID = (int)$_chapterID;
        if ($chapterID > 0) {
            $this->_chapterID = $chapterID;
        }
    }

    public function setState($_state)
    {
        $state = (int)$_state;
        if ($_state > 0 AND $_state < 4 ) {
            $this->_state = $state;
        }
    }

}
