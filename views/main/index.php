<?php include_once ROOT . '/views/header.php'; ?>

    <div class="container block">
        <div class="block navigator unvisible">
            <a href=""></a>
        </div>
        <h2 class="center">Категории:</h2>
        <div>
            <ul>
                <?php
                foreach (Categories::getItemsFromDB() as $item){
                    echo "<li><a href='/" . $item['alias'] . "'>" . $item['name'] . "</a></li><br>";
                }
                ?>
            </ul>
        </div>
    </div>

<?php include_once ROOT . '/views/footer.php'; ?>