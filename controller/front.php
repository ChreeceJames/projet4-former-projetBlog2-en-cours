
<?php
require_once 'commentaires.php';
require_once 'view/view.php';
require_once 'chapitre.php';
require_once 'view/navBar.php';

class Front
{

    public  $html;
    private $uri;
    // private $template = "main" ;
    private $pages = [
        "accueil"           => "",
        // "liste de chapitres"=> "",
        /*"biographie"        => "bio",*/
        "contact"           => "contact"
    ];


    public function __construct(){


       // $this->accueil();

    //}


    //private function accueil(){
        // $nav = new NavBar("accueil", $this->pages);

        $chapitres = new Chapitre(["list"=>true]);
        $data = $chapitres->getData();
        $test = '';
        foreach ($data as $key => $value){

            //en fonction de chaque {{id}} on génrèrera le commentaire associé
            //on incrémentaera le html du chpitres et des commentaires générés

            $comments = new Commentaires(["id"=>$value['{{ id }}']]);
            var_dump($comments);
           $test .= $value['{{ html }}']->html;
        }


        $vue = new View(
            [
                "{{ main }}"  => "<section><h2>Billet simple pour l'Alaska<h2></section>",
                '{{ test }}' => $test
            ],
            "main"
        );
        $this->html = $vue->html;

    }

    private function combineChaptersAndComments($chapters, $comments){

    }

    /*

        private function chapitre($uri){

            if (!isset($uri[1]) || !$uri[1]) {
                $this->redirect404();
                return;
            }

            $slug = $uri[1];

            $nav      = new NavBar(null, $this->pages);
            $chapitre = new Chapitre([
                "slug"=>$slug
            ]);

            if (!$chapitre->id()) {
                $this->redirect404();
                return;
            }

            $message = null;

            // /chapitre/slug/moderate/nouveauStateDuCommentaire/idDuCommentaire
            if (isset($uri[2]) && $uri[2]=="moderate"){
                $commentaires = new Commentaires([
                    'fullComments'=>true,
                    'id' => $chapitre->id(),
                    'update'=> [
                        "state"=>$uri[3],
                        "id"=>$uri[4]
                    ]
                ]);

                $message = $commentaires->_html;
            } else {
                $commentaires = new Commentaires([
                    'fullComments'=>true,
                    "id" => $chapitre->id()
                ]);

                if(isset($_POST['submit_commentaire'])) {
                    $message = $commentaires->_html;
                }
            }

            $vueChapitre = new View(
                [
                    "{{ title }}"        => $chapitre->title(),
                    //"{{ main }}"       => ''.$vueChapitre->html,
                    "{{ navigation }}"   => $nav->html,
                    //"{{ nom chapitre }}" => $chapitre->title(),
                    "{{ main }}"         => $chapitre->content().$commentaires->content(),
                    "{{ message }}"      => $message,
                ],
                $this->template
            );
            $this->html = $vueChapitre->html;
        }

        private function redirect404()
        {
            $vue = new View(null, '404');
            $this->html = $vue->html;
        }

        private function listeChapitre($start, $qty){
            $chapitres    = new Chapitre(["list"=>["start"=>$start, "qty"=>$qty]]);

        // die(var_dump($chapitres));

            $nav          = new NavBar("accueil", $this->pages);
            // $vueChapitres = new View(
            // 	[
            // 		"{{ part number }}" => $chapitres->title(),
            // 		"{{ articles }}"    => $chapitres->content()
            // 	],
            // 	"list"
            // );
            $vue       = new View(
                [
                    "{{ title }}"      => "liste des chapitres",
                    "{{ main }}"       => $chapitres->content(),
                    "{{ navigation }}" => $nav->html,
            "{{ message }}"    => null,

                ],
                $this->template
            );
            $this->html = $vue->html;
        }
        private function contact(){
            $nav = new NavBar("contact", $this->pages);
            $formulaire = new View(
                [
                    "{{ pseudo }}"  => "Entrez votre pseudo ici.",
                    "{{ email }}"   =>"Entrez une adresse mail valide.",
                    "{{ message }}" =>"Détaillez-nous votre problème.",
                    "{{ msg }}"     => "<strong>Veuillez reporter chaque problèmes rencontrés ci-dessous s'il vous plait.<strong>"
                ],
                "formulaire"
            );
            $vue = new View(
                [
                    "{{ title }}"      => "Contact",
                    "{{ main }}"       => $formulaire->html,
                    "{{ navigation }}" => $nav->html,
                    "{{ message }}"      => null,
                ],
                $this->template
            );
            $this->html = $vue->html;
        }
      */
}