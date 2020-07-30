<?php


class AdminList extends controllerUtils
{
    /**
     * AdminList constructor.
     */
    public function __construct()
    {
        $this->isNotLogged();
    }
}