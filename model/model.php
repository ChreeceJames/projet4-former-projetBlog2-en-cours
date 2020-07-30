<?php

class Model
{
    protected $bdd;
    public $_success;

    public function __construct()
    {
        global $config;
        try {
            $this->bdd = new PDO('mysql:host=localhost;dbname=' . $config['bdd_base'] . ';charset=utf8', $config['bdd_user'], $config['bdd_password']);
            $this->bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            if ($config['debug']) $this->bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    protected function hydrate($data)
    {
        foreach ($data as $key => $value) {
            $key = "_" . $key;
            $this->$key = $value;
        }
    }

    protected function query($sql, $all = false)
    {
        // var_dump($sql);
        $data = $this->bdd->query($sql);
        if ($all) return $data->fetchAll();
        return $data->fetch();

    }
    protected function execute($sql)
    {
        $data = $this->bdd->query($sql);

    }
    protected function prepareAndExecute($sql, $data)
    {
        $req = $this->bdd->prepare($sql);
        try {
            $this->_success = $req->execute($data);
        }
        catch (Exception $e) {
            $this->_success = false;
        }
    }

}
