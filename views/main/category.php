<?php include_once ROOT . '/views/header.php'; ?>

    <div class="container block">
        <div class="block navigator">
            <a class="links" href="/">Категории</a>
        </div>
        <h2 class="center"><?php echo $_SESSION['categoryName']; ?>:</h2>
        <ul>
            <?php
            foreach (SubCategories::getItemsFromDB($_SESSION['categoryId'], 'dateCreate') as $item){
                echo "<li><a href='/" . $_SESSION['categoryAlias'] . "/" . $item['alias'] .
                    "'>" . $item['name'] . "</a></li><br>";
            }
            ?>
        </ul>
    </div>

<?php include_once ROOT . '/views/footer.php'; ?>

