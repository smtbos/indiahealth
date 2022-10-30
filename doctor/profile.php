<?php
include("config.php");

?>
<?php include("header.php"); ?>
<main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 pt-4">
    <div class="row">
        <div class="col-md-6 offset-md-3 pt-5">
            <?php
            $user_id = $_SESSION["user_id"];
            $qry = "SELECT * FROM `doctors` WHERE `doctor_user` = '$user_id'";
            $res = mysqli_query($con, $qry);
            $doctor = mysqli_fetch_assoc($res);
            ?>
            <div class="row mb-2">
                <div class="col-12">
                    Doctor Name:
                </div>
                <div class="col-12">
                    <h5><?php echo $doctor["doctor_name"]; ?></h5>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-12">
                    Doctor Mobile:
                </div>
                <div class="col-12">
                    <h5><?php echo $doctor["doctor_mobile"]; ?></h5>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-12">
                    Doctor e-Mail:
                </div>
                <div class="col-12">
                    <h5><?php echo $doctor["doctor_email"]; ?></h5>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-12">
                    Doctor Address:
                </div>
                <div class="col-12">
                    <h5><?php echo $doctor["doctor_address"]; ?></h5>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-12">
                    Doctor Pincode:
                </div>
                <div class="col-12">
                    <h5><?php echo $doctor["doctor_pincode"]; ?></h5>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include("footer.php"); ?>