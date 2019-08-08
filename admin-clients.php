<?php

use \Estoque\Model\Client;
use \Estoque\PageAdmin;
use \Estoque\Model\User;
use \Estoque\Model\Address;

$app->get("/admin/clients", function () {

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

  $page = new PageAdmin();

  // die(var_dump($pagination['data']));

  $page->setTpl("clients", [
    "clients" => $pagination['data'],
    'search' => $search,
    'pages' => $pages

  ]);
});


$app->get("/admin/clients/create", function () {

  User::Verifylogin();

  $page = new PageAdmin();

  $address = new Address();


  if (isset($_GET['zipcode'])) {


    $address->loadFromCep($_GET['zipcode']);

}

  if (!$address->getdesaddress()) {
    $address->setdesaddress('');
  }

  if (!$address->getdesnumber()) {
    $address->setdesnumber('');
  }

  if (!$address->getdescomplement()) {
    $address->setdescomplement('');
  }

  if (!$address->getdesdistrict()) {
    $address->setdesdistrict('');
  }

  if (!$address->getdescity()) {
    $address->setdescity('');
  }

  if (!$address->getdesstate()) {
    $address->setdesstate('');
  }

  if (!$address->getdescountry()) {
    $address->setdescountry('');
  }

  if (!$address->getdeszipcode()) {
    $address->setdeszipcode('');
  }


  $page->setTpl("clients-create",[
    'address' => $address->getValues()
  ]);
});


$app->post("/admin/clients/create", function () {

  User::Verifylogin();

  $client = new Client();

  $client->setData($_POST);
  
  $client->save();

  header('Location: /admin/clients');

  exit();
});


$app->get("/admin/clients/:idclient", function ($idclient) {

  User::verifyLogin();

  $client = new Client();

  $client->get((int) $idclient);

  $page = new PageAdmin();

  $valores = $client->getValues();

  $page->setTpl("clients-update", [
    "clients" => $valores,
  ]);

});


$app->post("/admin/clients/:idclient", function ($idclient) {

  User::verifyLogin();

  $client = new Client();

  $client->get((int) $idclient);

  $client->setData($_POST);

  $client->save();

  header('Location: /admin/clients');

  exit;
});

$app->get("/admin/clients/:idclient/delete", function ($idclient) {

  User::verifyLogin();

  $client = new Client();

  $client->get((int) $idclient);

  $client->delete();

  header('Location: /admin/clients');

  exit;
});
