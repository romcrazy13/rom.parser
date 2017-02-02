




<footer>
    <div class="container">
        <div class="error">
            <?php
            if ((isset($_SESSION['error'])) && ($_SESSION['error'] != '')) {
                echo "!!!_ERROR_!!!<br>";
                echo $_SESSION['error'] . "<br>";
                $_SESSION['error'] = NULL;
            }
            ?>
        </div>
        <div class="notes unvisible">
            <?php
            if ((isset($_SESSION['notes'])) && ($_SESSION['notes'] != '')) {
                echo "!!!_NOTES_!!!<br>";
                echo $_SESSION['notes'] . "<br>";
                $_SESSION['notes'] = NULL;
            }
            ?>
        </div>
    </div>
</footer>
</body>


</html>



