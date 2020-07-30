<?php

require_once "model.php";

class ChapitreModel extends Model
{

    public $_id;
    public $_titre;
    public $_contenu;
    public $_date_time_publication;
    public $_date_time_edition;
    public $_slug;

    /**
     * make a new iteration of chapitre's model
     * @param Array $args un tableau contenant soit id, soit slug, soit save, soit update
     */
    public function __construct($args)
    {
        parent::__construct();
        extract($args);

        if (isset($save)) {
            $sql = 'INSERT INTO `chapitres` ( `titre`, `contenu`, `date_time_publication`, `slug`) VALUES ( :title, :content, NOW() , :slug)';
            $data = $this->prepareAndExecute($sql, compact("title", "content", "slug" ));

            // if (isset($save['_date_time_edition'])) {
                //la requete ne sera pas celle ci
                // $sql = "SELECT * FROM chapitres WHERE _date_time_edition = $save";
                //ça ne sera pas un simple query
                // $data = $this->query($sql);
                //je ne suis pas certain que pour le moment l'on aura des données à hidrater
                // $this->hydrate($data);
            // } else {
                //la requete ne sera pas celle ci
                // $sql = "SELECT * FROM chapitres WHERE _date_time_publication = $save";
                //ça ne sera pas un simple query
                // $data = $this->query($sql);
                //je ne suis pas certain que pour le moment l'on aura des données à hidrater
                // $this->hydrate($data);
            // }
            

            return;
        }

        if (isset($update)) {
            var_dump("update");
            $sql = "UPDATE `chapitres` SET  `id`=':id', `titre`=':titre', `contenu`=':contenu', `slug`=':slug', `date_time_edition` = NOW() WHERE `chapitres`.`id` = :id";
            $data = $this->prepareAndExecute($sql, $update);
            // $this->hydrate($data);
        }

        if (isset($edit)) {
            var_dump("edit ".$slug);
            $sql = "SELECT * FROM `chapitres` ORDER BY `slug`='$slug' LIMIT 1";
            $data = $this->query($sql,false);
            $this->hydrate($data);
            return;
        }

        if (isset($lastEntry)) {
             $sql = "SELECT `id` FROM `chapitres` ORDER BY `id` DESC";
             $data = $this->query($sql);
             $this->hydrate($data);
            return;
        }

        if (isset($id)) {
            var_dump("-----");
            $sql = "SELECT * FROM chapitres WHERE id = $id";
            $data = $this->query($sql,false);
            $this->hydrate($data);
            return;
        }

        // if (isset($slug) && $this->exist($slug)) {
        //     $sql = "SELECT * FROM chapitres WHERE slug = '$slug'";
        //     $data = $this->query($sql, false);
        //     $this->hydrate($data);
        //     return;
        // }

        if (isset($list)) {
            $sql = "SELECT contenu AS '{{ content }}', titre AS '{{ title }}', id AS '{{ id }}' FROM chapitres ORDER BY date_time_publication DESC";
           $this->_contenu = $this->query($sql, true);
            return;
        }
        if (isset($delete)) {
            $sql = "DELETE FROM `chapitres` WHERE `slug`= '$to_remove'";
            $data = $this->execute($sql);
        }
    }

    public function exist($slug)
    {
        $sql = "SELECT * FROM chapitres WHERE slug = '$slug'";
        return $this->query($sql, false);
    }

    public function CreateOrModifyChapter($data)
    {
        $mode_edition = 0;
        $message = '';

        if (isset($data['id']) AND !empty($data['id'] && $data['id'] !== null)) {
            $mode_edition = 1;

            $edit_id = (int) $data['id'];
            $edit_chapitre = $this->bdd->prepare('SELECT * FROM chapitres WHERE id = ?');
            $edit_chapitre->execute($edit_id);

            if ($edit_chapitre->rowCount() == 1) {

                $edit_chapitre->fetch();

            } else {
                die("Erreur : le chapitre concerné n'existe pas...");
            }
        }

        if (isset($data['titre'], $data['contenu'], $data['slug'])) {
            if (!empty($data['titre']) AND !empty($data['contenu']) AND !empty($data['slug'])) {

                $chapitre_titre = htmlspecialchars($data['titre']);
                $chapitre_slug = htmlspecialchars($data['slug']);

                if ($mode_edition == 0) {
                    $ins = $this->bdd->prepare('INSERT INTO chapitres (titre, contenu, slug, date_time_publication) VALUES (:titre, :contenu, :slug, NOW())');
                    $ins->bindParam(':titre', $chapitre_titre, PDO::PARAM_STR);
                    $ins->bindParam(':contenu', $chapitre_contenu, PDO::PARAM_STR);
                    $ins->bindParam(':slug', $chapitre_slug, PDO::PARAM_STR);
                    $ins->execute();
                    $message = 'Le chapitre a bien été publié!';
                } else {
                    $update = $this->bdd->prepare('UPDATE chapitres SET titre = ?, contenu = ?, slug = ? date_time_edition = NOW() WHERE id = ?');
                    $update->execute([$chapitre_titre, $chapitre_contenu, $chapitre_slug, $edit_id]);
                    $message = 'Le chapitre a bien été mis à jour!';
                }

            } else {
                $message = "Veuillez remplir tout les champs!";
            }
        }

        return $message;
    }

    public function DeleteChapter()
    {

        if (isset($_GET->id) AND !empty($_GET->id)) {
            $suppr_id = htmlspecialchars($_GET->id);
            $suppr = $bdd->prepare('DELETE FROM chapitres WHERE id = ?');
            $suppr->execute(string($suppr_id));
            //header('Location: http://127.0.0.1/Tutos_PHP/Articles/');
        }
    }
}