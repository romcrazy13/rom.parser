<?php
// Front Controller

// Общие настройки
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Подключение файлов системы
define('ROOT', dirname(__FILE__));

session_start();
if (!isset($_SESSION['error'])){
    $_SESSION['error'] = NULL;
}
if (!isset($_SESSION['notes'])){
    $_SESSION['notes'] = NULL;
}

require_once ROOT . '/config/settings.php';
require_once ROOT . '/components/DB.php';
require_once ROOT . '/components/Router.php';

// Проверка соединения с БД

// Вызов Router
$router = new Router();
$router->run();

function addError($error)
{
    $_SESSION['error'] .= $error . "<br>";
}

// Проверка есть ли ошибки
// если есть то 'true'
function isSetError(){
    return ($_SESSION['error'] != NULL);
}

function addNotes($notes)
{
    $_SESSION['notes'] .= $notes . "<br>";
}

?>