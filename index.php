<?php
require_once "controller/security.php";


//votre configuration
$config = [
    "workingFolder" => "/projet4",
    "bdd_base"      => "chapitres",
    "bdd_user"      => "root",
    "bdd_password"  => "root",
    "debug"         => true,
];

//configuration Lionel

$config = [
    "workingFolder" => "",
    "bdd_base"      => "chapitres",
    "bdd_user"      => "root",
    "bdd_password"  => "root",
    "debug"         => true,
];

$safeData = new Security([
    "post" => [
        "contenu" => FILTER_SANITIZE_STRING,
        "id" => FILTER_SANITIZE_STRING,
        "titre" => FILTER_SANITIZE_STRING,
        "slug" => FILTER_SANITIZE_STRING,
        "pseudo" => FILTER_SANITIZE_STRING,
        "date_time_publication" => FILTER_VALIDATE_REGEXP,
        'options' => [
            'regexp' => '#^\d{2}/\d{2}/\d{4}$#'],
        "chapterID" => FILTER_SANITIZE_NUMBER_INT,
        "state" => FILTER_SANITIZE_NUMBER_INT,
        "date_time_edition" => FILTER_VALIDATE_REGEXP,
        'options' => [
            'regexp' => '#^\d{2}/\d{2}/\d{4}$#'
        ],
        "commentaires_pseudo" =>FILTER_SANITIZE_STRING,
        "commentaires_contenu" =>FILTER_SANITIZE_STRING,
    ],

    "get" => [

    ],
    "session" => [

    ]
]);


if ($config["debug"] === true) {
    error_reporting(E_ALL | E_STRICT);
    ini_set('display_errors',1);
}


switch ($safeData->uri[0]) {
    case 'admin':
        require 'controller/back.php';
        $page = new Back(array_slice($safeData->uri, 1));
        break;

    default:
        require 'controller/front.php';
        $page = new Front($safeData->uri);
        break;
}
echo $page->html;
