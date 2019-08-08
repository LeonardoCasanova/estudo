<?php

use \Estoque\PageAdmin;
use \Estoque\Model\Account;
use \Estoque\Model\User;


$app->get('/admin/account/:iduser', function ($iduser) {

User::verifyLogin();

$user = new User();

$user->get((int)$iduser);

$account = new Account();

$page = new PageAdmin();

$page->setTpl("account", array(
     "account" => $account->listAccount($iduser),
     "user" => $iduser   
    ));
});

$app->post('/admin/account', function () {

    User::verifyLogin();
  
    $account = new Account();

    $account->setData($_POST);

    $account->update();
  
    header('Location: /admin/users');

    exit;
   
});