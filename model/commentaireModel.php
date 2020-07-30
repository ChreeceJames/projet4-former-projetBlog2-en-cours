<?php
require "model.php";

class CommentaireModel extends Model
{
    public $_id;
    public $_pseudo;
    public $_contenu;
    public $_date_time_publication;
    public $_chapterID;
    public $_data;


    /**
     * make a new iteration of commentaire's model
     * @param Array $args un tableau contenant soit id, soit pseudo,
     */
    public function __construct($args)
    {
        parent::__construct();
        extract($args);

        //mise à jour de l'état d'un commentaire
        if (isset($update)) {
            switch ($update["state"]) {
                case 1:
                    $this->validateCom($update["id"]);
                    break;
                case 2:
                    $this->signalComment($update["id"]);
                    break;
                case 3:
                    $this->finalComment($update["id"]);
                    break;
                case 4:
                    $this->deleteComment($update["id"]);
                    break;
            }
        }

        if (isset($id)) {
            $sql = "SELECT pseudo as '{{ pseudo }}', contenu as '{{ contenu }}', date_time_publication as '{{ date }}', state, id FROM commentaires WHERE chapterID = $id AND state != 2 AND state != 0";
            $this->_data = $this->query($sql, true);
            return;
        }

        if (isset($add)) $this->createComment();
        //    if (isset($pseudo)){
        //      $sql = "SELECT * FROM commentaires WHERE pseudo = $pseudo ";
        //      $data = $this->query($sql);
        //      $this->hydrate($data);
        //      return;
        // }
        // if (isset($chapterID)){
        //     $sql = "SELECT * FROM commentaires WHERE commentaires (chapterID) = chapitre (id)";
        //     $data = $this->query($sql);
        //     $this->hydrate($data);
        //     return;
        //     }


    }


    public function createComment()
    {
        $mode_edition = 0;
        global $safeData;
        if ($safeData->post['commentaires_pseudo'] !== null AND $safeData->post['commentaires_contenu'] !== null ) {
            $this->prepareAndExecute(
                'INSERT INTO commentaires (pseudo, contenu, chapterID, date_time_publication) VALUES (:pseudo, :contenu, :chapterID, NOW())',
                [
                    'pseudo'    => $safeData->post['commentaires_pseudo'],
                    'contenu'   => $safeData->post['commentaires_contenu'],
                    'chapterID' => $safeData->post['chapterID']
                ]
            );
        } else {
            $message = "Le message n'a pas pu s'envoyer veuillez vérifier qu'il ne contient pas d'erreurs !";

        }
    }

    public function deleteComment($id)
    {
        $this->prepareAndExecute(
            "DELETE FROM commentaires WHERE id = $id",
            [ "id" => $id
            ]
        );
    }

    public function signalComment($id)
    {
        $this->prepareAndExecute(
            "UPDATE `commentaires` SET `state` = '2' WHERE `commentaires`.`id` = :id",
            [
                "id" => $id
            ]
        );
    }

    public function validateCom($id)
    {
        $this->bdd->query("UPDATE `commentaires` SET `state` = '2' WHERE `commentaires`.`id` = $id");
    }

    public function finalComment()
    {
        $this->bdd->query("UPDATE `commentaires` SET `state` = '3' WHERE `commentaires`.`id` = $id");
    }
}