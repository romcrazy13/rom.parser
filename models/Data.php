<?php

include_once ROOT . "/models/Categories.php";
include_once ROOT . "/models/SubCategories.php";

class Data
{
    public $idSubCategory;
    public $title;
    public $body;

    function __construct($idSubCategory, $title, $body)
    {
        $this->idSubCategory = $idSubCategory;
        $this->title = $title;
        $this->body = $body;
    }

    public static function checkDataTables()
    {
        $categories = Categories::getItemsFromDB();
        $subCategories = SubCategories::getItemsFromDB();
        foreach ($categories as $item) {
            if (!isExistsTable($item['alias'])) {
                $sql = "(id             INT( 11 )       AUTO_INCREMENT PRIMARY KEY,
                        idSubCategory   INT( 11 )       NOT NULL,
                        title           varchar( 255 )  NOT NULL,
                        body            text            NOT NULL,
                        dateCreate      datetime        NOT NULL DEFAULT NOW()
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
                try {
                    getPDO()->exec("CREATE TABLE IF NOT EXISTS `" . $item['alias'] . "` $sql");
                    addNotes("Добавлена таблица `" . $item['alias'] . "`");
                } catch (PDOException $e) {
                    addError("<b>Ошибка при создании таблицы `" . $item['alias'] . "`</b><br>" . $e->getMessage());
                }
            }
            if ($data = self::getItemsFromJSON($item['alias'])){
                foreach ($data as $str) {
                    self::addItem(
                        $item['alias'],
                        self::getIdSubCategory($item['alias'], $str['category'], $subCategories),
                        $str['title'],
                        $str['body']);
                }
                if ($_SESSION['error'] == ''){
                    moveFileToTMP($item['alias'] . "." . SOURCE_TYPE);
                }
            }

        }
    }

    public static function getItemsFromJSON($sourceFileName)
    {
        $jsonString = file_get_contents(SOURCE_PATH . "$sourceFileName." . SOURCE_TYPE);
        $dataItems = json_decode($jsonString, true);
        return $dataItems;
    }

    public static function getItemsFromDB($categoryAlias, $idSubCategory, $order)
    {
        $sql = "SELECT * FROM `$categoryAlias`";
        $sql .= " WHERE `idSubCategory` = '$idSubCategory'";
        $sql .= " ORDER BY `$order`";
        addNotes($sql);
        $query = getPDO()->query($sql);
        $items = array();
        $i = 0;
        foreach ($query as $row){
            $items[$i]['id'] = $row['id'];
            $items[$i]['idSubCategory'] = $row['idSubCategory'];
            $items[$i]['title'] = $row['title'];
            $items[$i]['body'] = $row['body'];
            $items[$i]['dateCreate'] = $row['dateCreate'];
            $i++;
        }
        return $items;
    }

    public static function addItem($table, $idSubCategory, $title, $body)
    {
        $dsn = "mysql:host=" . HOST . ";dbname=" . DB . ";charset=utf8";
        $opt = array(
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        );
        $sql = "INSERT INTO `$table` (`idSubCategory`, `title`, `body`)
                              VALUES (:idSubCategory,  :title,  :body)";
        try {
            $DBH = new PDO($dsn, USER, PASSWORD, $opt);
            $query = $DBH->prepare($sql);
            $data = new Data($idSubCategory, $title, $body);
            $query->execute((array)$data);
            $id = $DBH->lastInsertId();
            addNotes("<b>`$table` добавлено</b> 
                              `id` = $id,
                              `idSubCategory` =" . $idSubCategory . ", 
                              `title` = " . $title);
        }
        catch (PDOException $e) {
            addError("<b>Ошибка добавления записи в таблицу `$table`</b><br>" . $e->getMessage());
            die();
        }
    }

    public static function getIdSubCategory($categoryAlias, $subCategoryAlias, $subCatigories)
    {
        $idCategory = Categories::getItemByAlias($categoryAlias)['id'];
        foreach ($subCatigories as $subCatigory){
            if (($idCategory == $subCatigory['idCategory']) && ($subCategoryAlias == $subCatigory['alias'])){
                return $subCatigory['id'];
                break;
            }
        }
    }

    public static function getItemById($categoryAlias, $idSubCategory, $id)
    {
        foreach (self::getItemsFromDB($categoryAlias, $idSubCategory, 'id') as $item){
            if ($item['id'] == $id){
                return $item;
                break;
            }
        }
    }
}