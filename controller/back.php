<?php

require_once 'view/view.php';
require_once 'view/navBar.php';
require_once 'login.php';
require_once 'AdminList.php';
require_once 'controllerUtils.php';
require_once 'chapitre.php';

class back extends controllerUtils
{
    public $html;
    private $uri;
    private $template = "admin";
    private $pages = [

        'Liste des articles' => 'admin/list',
        'Éditer un article'  => 'admin/edit',
        'Supprimer un article' => 'admin/delete',
        'Ajouter un article' => 'admin/add',
        'Déconnexion' => 'admin/logout',
    ];

    public function __construct($uri)
    {
        //SESSION
        if (!session_id()) @session_start();

        if (!isset($uri[0])) {
            $this->login();
            return;
        }

        $this->isNotLogged();

        $this->uri = $uri;
        switch ($uri[0]) {
            case 'list':
                $this->adminList();
                break;
            case 'logout':
                $this->logout();
                break;
            case 'edit':
                $this->edit($uri);
                break;
            case 'update':
                $this->update();
                break;
            case 'add':
                $this->add();
                break;
            case 'delete':
                $this->delete($uri);
                break;
            default:
                $this->login();
                break;
        }
    }

    public function login()
    {
        new login();
        $vueLogin = new View(
            [
            ],
            'login'
        );

        $this->html = $vueLogin->html;
    }

    public function adminList()
    {
        $chapitres = new Chapitre(["list" => ["start" => 0, "qty" => 200]]);
        $nav = new NavBar("liste des articles", $this->pages);

       /* $vueChapitres = new View(
            [
                "{{ part number }}" => $chapitres->title(),
                "{{ articles }}" => $chapitres->content(),
                "{{ supprimer }}" => "",
            ],
            "adminList"
        ); */

        $vue = new View(
            [
                "{{ title }}" => "liste des chapitres",
                "{{ main }}" => '' . $chapitres->content(),
                "{{ navigation }}" => $nav->html,
                "{{ message }}" => null,

            ],
            $this->template
        );


        $this->html = $vue->html;
    }

    public function logout()
    {
        if ($this->isLogged()) {
            session_unset();
            session_destroy();
        }
        header('Location: /admin/');
    }

    public function add()
    {
        $chapitre = new Chapitre(["add" => true]);

        $nav = new NavBar("Ajouter un article", $this->pages);

        $vue = new View(
            [
                "{{ title }}" => "Ajouter un chapitre",
                "{{ main }}" => $chapitre->content(),
                "{{ navigation }}" => $nav->html,
                "{{ message }}" => null,
            ],
            $this->template
        );

        $this->html = $vue->html;
    }

    public function edit($uri)
    {
        $slug = $uri[1];

        global $safeData;
        if ($safeData->post["id"] !== null) $chapitre = new Chapitre([
            "edit"   => true,
            "slug"   => $safeData->post["id"],
            "update" => $safeData->post
        ]);
        else $chapitre = new Chapitre([
            "edit" => true,
            "slug" => $slug
        ]);

        $nav = new NavBar("Éditer un article", $this->pages);

        $vue = new View(
            [
                "{{ title }}" => "Éditer un chapitre",
                "{{ main }}" => $chapitre->content(),
                "{{ navigation }}" => $nav->html,
                "{{ message }}" => null,
            ],
            $this->template
        );
        $this->html = $vue->html;
    }

    public function update()
    {
        $chapitre = new Chapitre([
            "update"=>true
        ]);
        header("location: edit/$chapitre->_slug");

    }
    public function delete($uri)
    {
        $id = $uri[1];
        $chapitre = new Chapitre([
            "delete" =>true,
            'to_remove' => $id
        ]);
        header("location:../list");
    }
}