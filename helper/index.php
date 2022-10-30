<?php
include("config.php");
?>
<?php include("header.php"); ?>
<main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 pt-4">
    <div class="row">
        <div class="col-12">
            <?php
            $res = mysqli_query($con, "SELECT COUNT(*) as `total_pednng` FROM `querys` WHERE `querys`.`query_solved` = '0' AND `querys`.`query_handel` = 0");
            $ro = mysqli_fetch_assoc($res);
            $total_query_pending = $ro["total_pednng"];

            ?>
            <div class="col-xl-3 col-sm-6 mt-4 pl-4 pr-4">
                <div class="card">
                    <div class="card-header" style="background-color: #f5b402;">
                        Unsolved Querys
                    </div>
                    <div class="card-body" style="background-color: #fad97f;">
                        <h1><?php echo $total_query_pending; ?></h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include("footer.php"); ?>