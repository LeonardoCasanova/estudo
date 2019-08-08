<?php

use \Estoque\Model\Product;
use \Estoque\Model\User;
use \Estoque\PageAdmin;

$app->get("/admin/products", function () {

  User::Verifylogin();

  $search = (isset($_GET['search']) ? $_GET['search'] : "");
  $page = (isset($_GET['page'])) ? (int) $_GET['page'] : 1;

  if ($search != '') {

    $pagination = Product::getPageSearch($search, $page);
  } else {

    $pagination = Product::getPage($page);
  }
  $pages = [];

  for ($i = 0; $i < $pagination['pages']; $i++) {

    array_push($pages, [
      'href' => '/admin/products?' . http_build_query([
        'page' => $i + 1,
        'search' => $search
      ]),
      'text' => $i + 1
    ]);
  }

  $page = new PageAdmin();

  $page->setTpl("products", [
    "products" => $pagination['data'],
    'search' => $search,
    'pages' => $pages

  ]);
});


$app->get("/admin/products/create", function () {

  User::Verifylogin();

  $page = new PageAdmin();

  $page->setTpl("products-create");
});


$app->post("/admin/products/create", function () {

  User::Verifylogin();

  $product = new Product();

  $product->setData($_POST);

  $product->save();

  header('Location: /admin/products');

  exit();
});


$app->get("/admin/products/:idproduct", function ($idproduct) {
  User::verifyLogin();
  $product = new Product();

  $product->get((int) $idproduct);

  $page = new PageAdmin();

  $valores = $product->getValues();  

  $page->setTpl("products-update", [

    "product" => $valores,
  ]);
});


$app->get("/admin/products/ver/:idproduct", function ($idproduct) {
  User::verifyLogin();
  $product = new Product();

  $product->get((int) $idproduct);

  $page = new PageAdmin();

  $valores = $product->getValues();

  $page->setTpl("products-see", [

    "product" => $valores,
  ]);
});


$app->post("/admin/products/:idproduct", function ($idproduct) {

  User::verifyLogin();

  $product = new Product();

  $product->get((int) $idproduct);

  $product->setData($_POST);

  $product->save();

  header('Location: /admin/products');

  exit;
});

$app->get("/admin/products/:idproduct/delete", function ($idproduct) {
  User::verifyLogin();
  $product = new Product();

  $product->get((int) $idproduct);

  $product->delete();

  header('Location: /admin/products');

  exit;
});
