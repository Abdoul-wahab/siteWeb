<?php


interface AccountStorage
{
    public function checkAuth($login, $password);
    public function read($id);
    public function readAll();
    public function create($data);
    public function checkLogin($login);
}