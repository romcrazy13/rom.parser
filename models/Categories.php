<?php

include_once ROOT . "/models/SubCategories.php";

class Categories
{
    public $name;
    public $alias;

    function __construct($name, $alias)
    {
        $this->name = $name;
        $this->alias = $alias;
    }

    public static function getItemsFromJSON()
    {
        $jsonString = file_get_contents(SOURCE_PATH . INDEX_FILE_NAME . "." . SOURCE_TYPE);
        $categoryItems = json_decode($jsonString, true);
        return $categoryItems;
    }

    public static function getItemsFromDB($order = "alias")
    {
        $query = getPDO()->query("SELECT * FROM `categories` ORDER BY `$order`");
        $items = array();
        $i = 0;
        foreach ($query as $row){
            $items[$i]['id'] = $row['id'];
            $items[$i]['name'] = $row['name'];
            $items[$i]['alias'] = $row['alias'];
            $items[$i]['dateCreate'] = $row['dateCreate'];
            $i++;
        }
        return $items;
    }

    public static function getItemByAlias($alias)
    {
        foreach (self::getItemsFromDB() as $item){
            if ($item['alias'] == $alias){
                return $item;
                break;
            }
        }
    }

    // Добавление записей в таблицу 'categories'
    public static function addItems()
    {
        $dsn = "mysql:host=" . HOST . ";dbname=" . DB . ";charset=utf8";
        $opt = array(
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        );
        $sql = "INSERT INTO `categories` (`name`, `alias`)
                                  VALUES (:name,  :alias)";
        try {
            $DBH = new PDO($dsn, USER, PASSWORD, $opt);
            $query = $DBH->prepare($sql);
            // Вызываем массив из файла categories.json
            $data = self::getItemsFromJSON();
            if (!is_null($data)) {
                foreach ($data as $item) {
                    if (!$id = self::getItemByAlias($item['alias'])['id']) {
                        $category = new Categories($item['name'], $item['alias']);
                        $query->execute((array)$category);
                        $id = $DBH->lastInsertId();
                        addNotes("<b>`categories` добавлено</b> 
                              `id` = $id,
                              `name = `" . $item['name'] . ", 
                              `alias` = " . $item['alias']);
                    }
                    foreach ($item['children'] as $sub) {
                        SubCategories::addItem($id, $sub['name'], $sub['alias']);
                    }
                }
            }
        }
        catch
        (PDOException $e) {
            addError("<b>Ошибка добавления записи в таблицу 'categories'</b><br>" . $e->getMessage());
            die();
        }
        if ($_SESSION['error'] == ''){
            moveFileToTMP(INDEX_FILE_NAME . "." . SOURCE_TYPE);
        }
    }

}