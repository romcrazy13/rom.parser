<?php

include_once ROOT . "/models/Categories.php";
include_once ROOT . "/models/SubCategories.php";
include_once ROOT . "/models/Data.php";

function update()
{
    if (!($_SESSION['error'] = tryLinkDB())){
        if (!($_SESSION['error'] = checkTableCategories())){
            if (!($_SESSION['error'] = checkTableSubCategories())) {
                if (!($_SESSION['error'] = Categories::addItems())) {
                    if (!($_SESSION['error'] = Data::checkDataTables())) {

                    }
                }
            }
        }
    }
}

// Подключение к DB
function getPDO()
{
    try {
        $dsn = "mysql:host=" . HOST . ";dbname=" . DB . ";charset=utf8";
        $opt = array(
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        );
        $DBH = new PDO($dsn, USER, PASSWORD, $opt);
    } catch (PDOException $e) {
        addError("<b>Ошибка подключения к базе данных</b><br>" . $e->getMessage());
    }
    return $DBH;
}

// Проверка подключения к DB
function tryLinkDB()
{
    // Подключение к хосту
    try {
        $dbh = new PDO("mysql:host=" . HOST, USER, PASSWORD);
    } catch (PDOException $e) {
        addError("<b>Ошибка подключения к хосту</b><br>" . $e->getMessage());
    }
    sleep(SLEEP);
    if (!isExistsDB()){
        // При отсутствии DB создается новая
        try {
            $dbh->exec("CREATE DATABASE IF NOT EXISTS `" . DB . "` DEFAULT CHARSET=utf8 COLLATE utf8_general_ci");
            addNotes("База данных `" . DB . "` создана");
        } catch (PDOException $e) {
            addError("<b>Ошибка при создании базы данных</b><br>" . $e->getMessage());
        }
        sleep(SLEEP);
    }
}

// Проверка наявности DB
function isExistsDB()
{
    try {
        $dbh = new PDO("mysql:host=" . HOST, USER, PASSWORD);
        $dbs = $dbh->query('SHOW DATABASES');
    } catch (PDOException $e) {
        addError("<b>Ошибка запроса списка таблиц</b><br>" . $e->getMessage());
    }
    sleep(SLEEP);
    while (($x = $dbs->fetchColumn()) !== false) {
        if ($x == DB) {
            return true;
            break;
        }
    }
    return false;
}

// Проверка наявности таблицы в DB
function isExistsTable($table)
{
    $dbs = getPDO()->query('SHOW TABLES');
    while (($x = $dbs->fetchColumn()) !== false) {
        if ($x == $table) {
            return true;
            break;
        }
    }
    return false;
}

// Проверка наявности таблицы "tasks"
function checkTableCategories()
{
    if (!isExistsTable('categories')) {
        $sql = "(id         INT( 11 )       AUTO_INCREMENT PRIMARY KEY,
                name        varchar( 50 )   NOT NULL UNIQUE,
                alias       varchar( 50 )   NOT NULL UNIQUE,
                childCount  INT( 11 )       NOT NULL DEFAULT 0,
                dateCreate  datetime        NOT NULL DEFAULT NOW()
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        try {
            getPDO()->exec("CREATE TABLE IF NOT EXISTS `categories` $sql");
            addNotes("Добавлена таблица `categories`");
        } catch (PDOException $e) {
            addError("<b>Ошибка при создании таблицы 'categories'</b><br>" . $e->getMessage());
        }
    }
}

// Проверка наявности таблицы "tasks"
function checkTableSubCategories()
{
    if (!isExistsTable('subcategories')){
        $columns = "(id             INT( 11 )       AUTO_INCREMENT PRIMARY KEY,
                    idCategory      INT( 11 )       NOT NULL,
                    name            varchar( 50 )   NOT NULL,
                    alias           varchar( 50 )   NOT NULL,
                    childCount      INT( 11 )       NOT NULL DEFAULT 0,
                    dateCreate      datetime        NOT NULL DEFAULT NOW()
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        try {
            getPDO()->exec("CREATE TABLE IF NOT EXISTS `subcategories` $columns");
        } catch (PDOException $e) {
            addError("<b>Ошибка при создании таблицы 'subcategories'</b><br>" . $e->getMessage());
        }
    }
}

function moveFileToTMP($fileName)
{
/*    $subDir = date("Ymd/");
    $source = SOURCE_PATH . $fileName;
    addNotes("<br><br><br>source = $source<br><br><br>");
    $dest = SOURCE_TMP_PATH . $subDir . date("YmdHis_") . $fileName;
    addNotes("<br><br><br>dest = $dest<br><br><br>");
    if (!file_exists($subDir)) {
        mkdir($subDir);
    }
    if (copy($source, $dest)){
        unlink($source);
    }
*/
}

