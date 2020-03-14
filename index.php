<?php
/*
 * On indique que les chemins des fichiers qu'on inclut
 * seront relatifs au répertoire src.
 */
set_include_path("./src");

/* Inclusion des classes utilisées dans ce fichier */
require_once("Router.php");

set_include_path("./src/view");
require_once("View.php");

set_include_path("./src/control");
require_once("Controller.php");

set_include_path("./src/model");
require_once("Account.php");
require_once("AccountStorage.php");
require_once("AccountStorageMySQL.php");
require_once("Book.php");
require_once("BookStorage.php");
require_once("BookBuilder.php");
require_once("BookStorageMySQL.php");

set_include_path("./src/lib");
require_once("FileStore.php");
require_once("AuthenticationManager.php");

set_include_path("./src/config");
require_once("config.php");
require_once("InitDB.php");

session_start();

/*
 * Cette page est simplement le point d'arrivée de l'internaute
 * sur notre site. On se contente de créer un routeur
 * et de lancer son main.
 */
$router = new Router();
try{
    $db = new PDO('mysql:host='.HOSTNAME.';port='.DATABASEPORT.';dbname='.DATABASE.';charset=utf8', USERDATABASE, PSWDDATABASE);
}
catch (Exception $e){
    die('Erreur : ' . $e->getMessage());
}
$bookStorage= new BookStorageMySQL($db);
$accountStorage= new AccountStorageMySQL($db);


/*
// * decommenter ces deux ligne permettent de remettre la base de donnees a son etat initial
$initDB= new InitDB();
$db->query($initDB->getSql())->execute();*/

$router->main($bookStorage, $accountStorage);
?>
