<?php 
session_start();
require_once("vendor/autoload.php");

use \Slim\Slim;

$app = new Slim();

$app->config('debug', true);

require_once('admin.php');
require_once('admin-users.php');
require_once('admin-categories.php');
require_once('admin-products.php');
require_once('admin-account.php');
require_once('admin-clients.php');
require_once('admin-boleto.php');
require_once('functions.php');


$app->run();
 

