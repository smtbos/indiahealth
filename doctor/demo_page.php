<?php
include("config.php");

?>
<?php include("header.php"); ?>
<main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 pt-4">
    <div class="row">
        <div class="col-12">
            <?php
            for ($i = 1; $i < 50; $i += 3) {
                echo "<h2>hi $i</h2>";
            }
            ?>
        </div>
    </div>
</main>
<?php include("footer.php"); ?>