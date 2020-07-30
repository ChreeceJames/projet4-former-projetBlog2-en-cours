<?php
require_once 'controllerUtils.php';

class login extends controllerUtils
{
    public function __construct()
    {
        global $config;
        if (isset($_POST['email']) && isset($_POST['password'])) {

            $email = $_POST['email'];
            $password = $_POST['password'];

            if ($email == $this->login['email'] && $password == $this->login['password']) {
                $_SESSION['admin'] = $email;

                header('Location: ' . $config['workingFolder'].'/admin/list');
                exit;
            }
        }
    }
}