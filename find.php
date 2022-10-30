<?php
include("config.php");
?>
<?php include("header.php"); ?>
<main class="container">
    <div class="row">
        <div class="col-lg-4 col-md-6 offset-lg-4 offset-md-3 pt-4">
            <form action="find.php" method="POST">
                <div class="form-group row">
                    <div class="col-12">
                        <label for="pincode">Enter Pincode : </label>
                        <input type="number" class="form-control" name="pincode" id="pincode" min="100000" maxlength="999999" value="<?php echo post_str("pincode"); ?>" required>
                    </div>
                    <div class="col-6 mt-3 d-none">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="doctor" name="doctor" checked>
                            <label class="custom-control-label" for="doctor">Doctor</label>
                        </div>
                    </div>
                    <div class="col-6 mt-3 d-none">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="laboratory" name="laboratory" checked>
                            <label class="custom-control-label" for="laboratory">Laboratory</label>
                        </div>
                    </div>
                    <div class="col-6 mt-4 offset-3">
                        <button type="submit" class="btn btn-success btn-block"><i class="fa fa-search"></i> Find</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-8 offset-md-2">
            <?php
            if (isset($_POST["pincode"])) {
                $pincode = mysqli_real_escape_string($con, post_num("pincode"));
                $doctor = isset($_POST["doctor"]);
                $laboratory = isset($_POST["laboratory"]);
                if ($doctor || true) {
            ?>
                    <h3 class="text-center  mt-4">Doctors</h3>
                    <div class="row mb-3">
                        <?php
                        $q = "SELECT * FROM `doctors` WHERE `doctors`.`doctor_pincode` = '$pincode' AND `doctor_license` = '1'";
                        $res = mysqli_query($con, $q);
                        if (mysqli_num_rows($res) > 0) {
                            while ($row = mysqli_fetch_assoc($res)) {
                        ?>
                                <div class="col-6 mt-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <?php echo $row["doctor_name"]; ?>
                                        </div>
                                        <div class="card-body pt-2 pb-2">
                                            <pre class="m-0"><?php echo $row["doctor_address"]; ?></pre>
                                            <pre class="m-0"><?php echo $row["doctor_mobile"]; ?></pre>
                                        </div>
                                        <div class="card-footer text-center pt-2 pb-2">
                                            <div class="row">
                                                <div class="col-6">
                                                    <a href="tel:<?php echo $row["doctor_mobile"]; ?>"><i class="fa fa-phone"></i></a>
                                                </div>
                                                <div class="col-6">
                                                    <a href="mailto:<?php echo $row["doctor_email"]; ?>"><i class="fa fa-envelope"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                        } else {
                            ?>
                            <div class="col-12 text-center">
                                <h4 class="mt-2">No Doctors Found</h4>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                <?php
                }
                if ($laboratory || true) {
                ?>
                    <h3 class="text-center  mt-4">Laboratorys</h3>
                    <div class="row mb-3">

                        <?php
                        $q = "SELECT * FROM `laboratorys` WHERE `laboratorys`.`laboratory_pincode` = '$pincode' AND `laboratory_license` = '1'";
                        $res = mysqli_query($con, $q);
                        if (mysqli_num_rows($res) > 0) {
                            while ($row = mysqli_fetch_assoc($res)) {
                        ?>
                                <div class="col-6 mt-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <?php echo $row["laboratory_name"]; ?>
                                        </div>
                                        <div class="card-body pt-2 pb-2">
                                            <pre class="m-0"><?php echo $row["laboratory_address"]; ?></pre>
                                            <pre class="m-0"><?php echo $row["laboratory_mobile"]; ?></pre>
                                        </div>
                                        <div class="card-footer text-center pt-2 pb-2">
                                            <div class="row">
                                                <div class="col-6">
                                                    <a href="tel:<?php echo $row["laboratory_mobile"]; ?>"><i class="fa fa-phone"></i></a>
                                                </div>
                                                <div class="col-6">
                                                    <a href="mailto:<?php echo $row["laboratory_email"]; ?>"><i class="fa fa-envelope"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                        } else {
                            ?>
                            <div class="col-12 text-center">
                                <h4 class="mt-2">No Laboratorys Found</h4>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
            <?php
                }
            }
            ?>
        </div>
    </div>
</main> <?php include("footer.php"); ?>