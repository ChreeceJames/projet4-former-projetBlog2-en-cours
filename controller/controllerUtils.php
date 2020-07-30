<?php


class controllerUtils
{
    protected $login = [
        'email' => 'jean@email.com',
        'password' => 'password'
    ];

    public function isNotLogged()
    {
        global $config;

        if (!isset($_SESSION['admin']) || $_SESSION['admin'] != $this->login['email']) {
            header('Location: ' . $config['workingFolder'].'/admin');
            exit;
        }
    }

    public function isLogged()
    {
        if (isset($_SESSION['admin']) && $_SESSION['admin'] == $this->login['email']) {
            return true;
        }

        return false;
    }
}