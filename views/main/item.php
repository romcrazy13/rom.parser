<?php include_once ROOT . '/views/header.php'; ?>

    <div class="container block">
        <div class="block navigator">
            <a class="links" href="/<?php echo $_SESSION['categoryAlias']; ?>">Категории</a>
            <a class="links" href="/<?php echo $_SESSION['categoryAlias']; ?>">
                <?php echo $_SESSION['categoryName']; ?>
            </a>
            <a class="links" href="/<?php echo $_SESSION['categoryAlias'] . "/" . $_SESSION['subCategoryAlias']; ?>">
                <?php echo $_SESSION['subCategoryName']; ?>
            </a>
        </div>
        <h3 class="center"><?php echo $_SESSION['title'] ?>:</h3>
        <div>
            <?php echo $_SESSION['body']; ?>
        </div>
    </div>

<?php include_once ROOT . '/views/footer.php'; ?>