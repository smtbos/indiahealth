<?php
include("config.php");

?>
<?php include("header.php"); ?>
<main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 pt-4">
    <div class="row">
        <div class="col-md-6 offset-md-3 pt-5">
            <?php
            $user_id = $_SESSION["user_id"];
            $qry = "SELECT * FROM `laboratorys` WHERE `laboratory_user` = '$user_id'";
            $res = mysqli_query($con, $qry);
            $lab = mysqli_fetch_assoc($res);
            ?>
            <div class="row mb-2">
                <div class="col-12">
                    Laboratory Name:
                </div>
                <div class="col-12">
                    <h5><?php echo $lab["laboratory_name"]; ?></h5>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-12">
                    Laboratory Mobile:
                </div>
                <div class="col-12">
                    <h5><?php echo $lab["laboratory_mobile"]; ?></h5>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-12">
                    Laboratory e-Mail:
                </div>
                <div class="col-12">
                    <h5><?php echo $lab["laboratory_email"]; ?></h5>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-12">
                    Laboratory Address:
                </div>
                <div class="col-12">
                    <h5><?php echo $lab["laboratory_address"]; ?></h5>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-12">
                    Laboratory Pincode:
                </div>
                <div class="col-12">
                    <h5><?php echo $lab["laboratory_pincode"]; ?></h5>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include("footer.php"); ?>