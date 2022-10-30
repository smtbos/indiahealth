<?php
include("config.php");
?>
<?php include("header.php"); ?>
<main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 pt-4">
    <div class="row">
        <?php
        $res = mysqli_query($con, "SELECT COUNT(*) as `total_pednng` FROM `querys` WHERE `querys`.`query_solved` = '0' AND `querys`.`query_handel` = 1");
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
        <?php
        $res = mysqli_query($con, "SELECT COUNT(*) as `total_pednng` FROM `reports`");
        $ro = mysqli_fetch_assoc($res);
        $total_query_pending = $ro["total_pednng"];
        ?>
        <div class="col-xl-3 col-sm-6 mt-4 pl-4 pr-4">
            <div class="card">
                <div class="card-header" style="background-color: #f5b402;">
                    Total Reports
                </div>
                <div class="card-body" style="background-color: #fad97f;">
                    <h1><?php echo $total_query_pending; ?></h1>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-xl-3 col-sm-6 mt-4 pl-4 pr-4">
            <div class="card">
                <?php
                $res = mysqli_query($con, "SELECT COUNT(*) as `total` FROM `users` WHERE `user_patient` = 1");
                $ro = mysqli_fetch_assoc($res);
                $total_query_pending = $ro["total"];
                ?>
                <div class="card-header" style="background-color: #f5b402;">
                    Patient
                </div>
                <div class="card-body" style="background-color: #fad97f;">
                    <h1><?php echo $total_query_pending; ?></h1>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mt-4 pl-4 pr-4">
            <div class="card">
                <?php
                $res = mysqli_query($con, "SELECT COUNT(*) as `total` FROM `users` WHERE `user_laboratory` = 1");
                $ro = mysqli_fetch_assoc($res);
                $total_query_pending = $ro["total"];
                ?>
                <div class="card-header" style="background-color: #f5b402;">
                    Laboratorys
                </div>
                <div class="card-body" style="background-color: #fad97f;">
                    <h1><?php echo $total_query_pending; ?></h1>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mt-4 pl-4 pr-4">
            <div class="card">
                <?php
                $res = mysqli_query($con, "SELECT COUNT(*) as `total` FROM `users` WHERE `user_doctor` = 1");
                $ro = mysqli_fetch_assoc($res);
                $total_query_pending = $ro["total"];
                ?>
                <div class="card-header" style="background-color: #f5b402;">
                    Doctors
                </div>
                <div class="card-body" style="background-color: #fad97f;">
                    <h1><?php echo $total_query_pending; ?></h1>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mt-4 pl-4 pr-4">
            <div class="card">
                <?php
                $res = mysqli_query($con, "SELECT COUNT(*) as `total` FROM `users` WHERE `user_helper` = 1");
                $ro = mysqli_fetch_assoc($res);
                $total_query_pending = $ro["total"];
                ?>
                <div class="card-header" style="background-color: #f5b402;">
                    Helpers
                </div>
                <div class="card-body" style="background-color: #fad97f;">
                    <h1><?php echo $total_query_pending; ?></h1>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include("footer.php"); ?>