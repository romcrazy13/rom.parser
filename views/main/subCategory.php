<?php include_once ROOT . '/views/header.php'; ?>

    <div class="container block">
        <div class="block navigator">
            <a class="links" href="/<?php echo $_SESSION['categoryAlias']; ?>">Категории</a>
            <a class="links" href="/<?php echo $_SESSION['categoryAlias']; ?>"><?php echo $_SESSION['categoryName']; ?></a>
        </div>
        <h2 class="center"><?php echo $_SESSION['subCategoryName']; ?>:</h2>
        <table>
            <?php foreach (Data::getItemsFromDB($_SESSION['categoryAlias'],
                                                $_SESSION['subCategoryId'],
                                                'dateCreate') as $item){ ?>
                <tr>
                    <td>
                        <a href="<?php echo "/" . $_SESSION['categoryAlias'] .
                                            "/" . $_SESSION['subCategoryAlias'] .
                                            "/" . $item['id']; ?>">
                            Показать
                        </a>
                    </td>
                    <td><?php echo $item['title'] ?></td>
                    <td><?php echo $item['body'] ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>

<?php include_once ROOT . '/views/footer.php'; ?>

