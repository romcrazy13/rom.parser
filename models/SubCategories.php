<?php

class SubCategories
{
    public $idCategory;
    public $name;
    public $alias;

    function __construct($idCategory, $name, $alias){
        $this->idCategory = $idCategory;
        $this->name = $name;
        $this->alias = $alias;
    }

    public static function getItemsFromDB($idCategory, $order)
    {
        $sql = "SELECT * FROM `subcategories`";
        $sql .= " WHERE `idCategory` = '$idCategory'";
        $sql .= " ORDER BY `$order`";
        addNotes($sql);
        $query = getPDO()->query($sql);
        $items = array();
        $i = 0;
        foreach ($query as $row){
            $items[$i]['id'] = $row['id'];
            $items[$i]['idCategory'] = $row['idCategory'];
            $items[$i]['name'] = $row['name'];
            $items[$i]['alias'] = $row['alias'];
            $items[$i]['dateCreate'] = $row['dateCreate'];
            $i++;
        }
        return $items;
    }

    // Добавление записей в таблицу 'subcategories'
    public static function addItem($idCategory, $name, $alias)
    {
        $dsn = "mysql:host=" . HOST . ";dbname=" . DB . ";charset=utf8";
        $opt = array(
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        );
        $sql = "INSERT INTO `subcategories` (`idCategory`, `name`, `alias`)
                                     VALUES (:idCategory,  :name,  :alias)";
        try {
            $DBH = new PDO($dsn, USER, PASSWORD, $opt);
            $query = $DBH->prepare($sql);
            if (!(self::getItemByAlias($idCategory, $alias))['id']){
                $subCategory = new SubCategories($idCategory, $name, $alias);
                $query->execute((array)$subCategory);
                $id = $DBH->lastInsertId();
                addNotes("<b>`subcategories` добавлено</b> 
                              `id` = $id,
                              `idCategory` =" . $idCategory . ", 
                              `name` = " . $name . ", 
                              `alias` = " . $alias);
            }
        }
        catch
        (PDOException $e) {
            addError("<b>Ошибка добавления записи в таблицу 'subcategories'</b><br>" . $e->getMessage());
            die();
        }
    }

    public static function getItemByAlias($idCategory, $alias)
    {
        foreach (self::getItemsFromDB($idCategory, 'alias') as $item){
            if (($item['idCategory'] == $idCategory) && ($item['alias'] == $alias)) {
                return $item;
                break;
            }
        }
    }

}
