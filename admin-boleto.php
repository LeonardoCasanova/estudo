<?php

use \Estoque\PageAdmin;
use \Estoque\Model\User;
use \Estoque\Model\Product;
use \Estoque\Model\Client;
use \Estoque\Model\GeraBoleto;


$app->get('/admin/boleto', function () {

    User::Verifylogin();

    $search = (isset($_GET['search']) ? $_GET['search'] : "");
    $page = (isset($_GET['page'])) ? (int) $_GET['page'] : 1;
  
    if ($search != '') {
  
      $pagination = Client::getPageSearch($search, $page);
    } else {
  
      $pagination = Client::getPage($page);
    }
    $pages = [];
  
    for ($i = 0; $i < $pagination['pages']; $i++) {
  
      array_push($pages, [
        'href' => '/admin/clients?' . http_build_query([
          'page' => $i + 1,
          'search' => $search
        ]),
        'text' => $i + 1
      ]);
    }

    $product = Product::listProducts();

    $page = new PageAdmin();
  
    $page->setTpl("boleto", [
      "clients" => $pagination['data'],
      "products" => $product,
      'search' => $search,
      'pages' => $pages
  
    ]);
});

$app->post('/admin/boleto', function () {

  User::Verifylogin();

  $idcli = $_POST['clientes'];

  $data_venc = $_POST['data_venc'];
  

  $client = new Client();
  
  $client->get((int) $idcli);
 

  $product = Product::listProducts();
   
  $boleto = new GeraBoleto();
  
  $boleto->gerarBoleto($client->getValues(),$product,$data_venc);


});


$app->get('/admin/boleto/gerado/:link', function ($link) {
  
  echo ($link);
 

});







