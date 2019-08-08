<?php

use \Estoque\PageAdmin;
use \Estoque\Model\User;
use \Estoque\Model\Product;
use \Estoque\Model\Tempo;


$app->get('/', function () {

  User::verifyLogin();

  header("Location: /admin/products");
  exit;

});

$app->get('/admin/gerarprods', function () {

   $product = new Product();

   $product->geraMassa();

   header("Location: /admin/products");
  exit;

});

$app->get('/admin/time', function () {

  $tempo = '459661';  

  $time = new Tempo();

  $time->setTime($tempo);



  // header("Location: /admin/products");
//  exit;

});


$app->get('/admin/delete/prods', function () {

  $product = new Product();

  $product->deleteMassa();

  header("Location: /admin/products");
 exit;

});

$app->get('/admin/login', function () {

  $page = new PageAdmin(["header" => false, "footer" => false]);

  $page->setTpl("login");
});

$app->post('/admin/login', function () {

  User::login($_POST["deslogin"], $_POST["despassword"]);

  header("Location: /");
  exit;
});

$app->get('/admin/logout', function () {
  User::logout();

  header("Location: /admin/login");
  exit;
});

$app->get("/admin/forgot", function () {

  $page = new PageAdmin([
      "header" => false,
      "footer" => false
  ]);
  $page->setTpl("forgot");
});

$app->post("/admin/forgot", function () {


  $user = User::getForgot($_POST["email"]);

  header("Location: /admin/forgot/sent");

  exit;
});

$app->get("/admin/forgot/sent", function () {

  $page = new PageAdmin([
      "header" => false,
      "footer" => false
  ]);

  $page->setTpl("forgot-sent");
});

$app->get("/admin/forgot/reset", function () {

  $user = User::validForgotDecrypt($_GET["code"]);

  $page = new PageAdmin([
      "header" => false,
      "footer" => false
  ]);

  $page->setTpl("forgot-reset", array(
      "name" => $user["desperson"],
      "code" => $_GET["code"]
  ));
});

$app->post("/admin/forgot/reset", function () {

  $forgot = User::validForgotDecrypt($_POST["code"]);

  User::setForgotUsed($forgot["idrecovery"]);

  $user = new User();

  $user->get((int)$forgot["iduser"]);

  $passwordEncrypted = password_hash($_POST['password'], PASSWORD_DEFAULT, ["cost" => 8]);

  $user->setPassword($passwordEncrypted);

  $page = new PageAdmin([
      "header" => false,
      "footer" => false
  ]);

  $page->setTpl("forgot-reset-success");
});

