<?php

class MainController
{

    function __construct()
    {

    }

    function actionIndex()
    {
        include_once ROOT . "/views/main/index.php";
    }

    function actionUpdate()
    {
            update();
            header("Location: /");
    }

    function actionViewCategories($categoryAlias)
    {
        $category = Categories::getItemByAlias($categoryAlias);
        $_SESSION['categoryId'] = $category['id'];
        $_SESSION['categoryAlias'] = $categoryAlias;
        $_SESSION['categoryName'] = $category['name'];
        include_once ROOT . "/views/main/category.php";
    }

    function actionViewSubCategories($categoryAlias, $subCategoryAlias)
    {
        $category = Categories::getItemByAlias($categoryAlias);
        $subCategory = SubCategories::getItemByAlias($category['id'], $subCategoryAlias);
        $_SESSION['subCategoryId'] = $subCategory['id'];
        $_SESSION['subCategoryAlias'] = $subCategoryAlias;
        $_SESSION['subCategoryName'] = $subCategory['name'];
        include_once ROOT . "/views/main/subCategory.php";
    }

    function actionViewItem($categoryAlias, $subCategoryAlias, $itemId)
    {
        $category = Categories::getItemByAlias($categoryAlias);
        $_SESSION['categoryId'] = $category['id'];
        $_SESSION['categoryAlias'] = $categoryAlias;
        $_SESSION['categoryName'] = $category['name'];

        $subCategory = SubCategories::getItemByAlias($category['id'], $subCategoryAlias);
        $_SESSION['subCategoryId'] = $subCategory['id'];
        $_SESSION['subCategoryAlias'] = $subCategoryAlias;
        $_SESSION['subCategoryName'] = $subCategory['name'];

        addNotes("categoryAlias = " . $categoryAlias);
        addNotes("subCategoryAlias = " . $subCategoryAlias);
        addNotes("itemId = " . $itemId);

        $item = Data::getItemById($_SESSION['categoryAlias'], $_SESSION['subCategoryId'], $itemId);
        $_SESSION['itemId'] = $itemId;
        $_SESSION['title'] = $item['title'];
        $_SESSION['body'] = $item['body'];
        include_once ROOT . "/views/main/item.php";
    }


}