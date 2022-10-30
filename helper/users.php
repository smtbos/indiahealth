<?php
include("config.php");
if (isset($_GET["a"])) {
    $id = get_num("a");
    mysqli_query($con, "UPDATE `users` SET `user_status` = '1' WHERE `user_id` = '$id'");
    $_SESSION["smsg"][] = "User Activated";
    header("location:users.php");
    exit();
}
if (isset($_GET["d"])) {
    $id = get_num("d");
    mysqli_query($con, "UPDATE `users` SET `user_status` = '0' WHERE `user_id` = '$id'");
    $_SESSION["smsg"][] = "User Deactivated";
    header("location:users.php");
    exit();
}
if (isset($_POST["add"])) {
    $flag = true;

    // $username = strtolower(post_str("username"));
    // if (empty($username)) {
    //     $flag = false;
    //     $_SESSION["amsg"][] = "Username Can Not Be Empty";
    // }

    // if (!username_is_available($username)) {
    //     $flag = false;
    //     $_SESSION["amsg"][] = "Username Already Exist";
    // }

    $name = post_str("name");
    if (empty($name)) {
        $flag = false;
        $_SESSION["amsg"][] = "Name Can Not Be Empty";
    }

    $dob = post_str("dob");

    $gender = post_str("gender");
    if (!($gender == 'male' || $gender == 'female')) {
        $flag = false;
        $_SESSION["amsg"][] = "Invalid Value for Gender";
    }

    $mobile = post_str("mobile");
    if (empty($mobile)) {
        $flag = false;
        $_SESSION["amsg"][] = "Mobile Can Not Be Empty";
    }

    $email = post_str("email");
    if (empty($email)) {
        $flag = false;
        $_SESSION["amsg"][] = "Email Can Not Be Empty";
    }

    $password = generate_password();

    $doctor = intval(isset($_POST["doctor"]));
    $laboratory = intval(isset($_POST["laboratory"]));
    $patient = intval(isset($_POST["patient"]));
    $status = intval(isset($_POST["status"]));


    if ($flag) {
        // $username = mysqli_real_escape_string($con, $username);
        $name = mysqli_real_escape_string($con, $name);
        $dob = mysqli_real_escape_string($con, $dob);
        $gender = mysqli_real_escape_string($con, $gender);
        $mobile = mysqli_real_escape_string($con, $mobile);
        $email = mysqli_real_escape_string($con, $email);
        $dpassword = mysqli_real_escape_string($con, $password);
        // $user_insert = "INSERT INTO `users`(`user_id`, `user_username`, `user_name`, `user_dob`, `user_gender`, `user_mobile`, `user_email`, `user_password`, `user_admin`, `user_doctor`, `user_laboratory`, `user_helper`, `user_patient`, `user_status`) VALUES (NULL, '$username', '$name', '$dob', '$gender', '$mobile', '$email', '$dpassword', '0', '$doctor', '$laboratory', ' 0', '$patient', '$status')";
        $user_insert = "INSERT INTO `users`(`user_id`, `user_name`, `user_dob`, `user_gender`, `user_mobile`, `user_email`, `user_password`, `user_admin`, `user_doctor`, `user_laboratory`, `user_helper`, `user_patient`, `user_status`) VALUES (NULL, '$name', '$dob', '$gender', '$mobile', '$email', '$dpassword', '0', '$doctor', '$laboratory', ' 0', '$patient', '$status')";
        if (mysqli_query($con, $user_insert)) {
            $_SESSION["smsg"][] = "User / Patient Added Successfully";
            $pid = mysqli_insert_id($con);
            $username = get_code_by_id($pid);
            mysqli_query($con, "UPDATE `users` SET `user_username` = '$username' WHERE `user_id` = '$pid'");
            mail_new_registration_welcome(post_str("email"), $pid, get_good_name(post_str("name"), post_str("gender")), $username, post_str("dob"), $password, $website_name . " Admin");
            header("location:users.php");
        } else {
            $_SESSION["amsg"][] = "Error";
            header("location:users.php");
        }
    } else {
        header("location:users.php");
    }
}
if (isset($_GET["view"])) {
    $uid = get_num("view");
    $qq = "SELECT * FROM `users` WHERE `user_id` = '$uid'";
    $res = mysqli_query($con, $qq);
    if (mysqli_num_rows($res) == 1) {
        $user = mysqli_fetch_assoc($res);
    } else {
        $_SESSION["amsg"][] = "Invalid User id";
        header("location:users.php");
        exit();
    }
}
if (isset($_GET["edit"])) {
    $uid = get_num("edit");
    $qq = "SELECT * FROM `users` WHERE `user_id` = '$uid'";
    $res = mysqli_query($con, $qq);
    if (mysqli_num_rows($res) == 1) {
        $user = mysqli_fetch_assoc($res);
    } else {
        $_SESSION["amsg"][] = "Invalid User id";
        header("location:users.php");
        exit();
    }
}
if (isset($_POST["update"])) {
    
    $flag = true;

    $uid = post_num("id");

    $q = "SELECT * FROM `users` WHERE `user_id` = '$uid'";
    $r = mysqli_query($con, $q);
    $old_username = "";
    if (mysqli_num_rows($r)) {
        $ro = mysqli_fetch_assoc($r);
        // $old_username = $ro["user_username"];

        // $username = strtolower(post_str("username"));
        // if (empty($username)) {
        //     $flag = false;
        //     $_SESSION["amsg"][] = "Username Can Not Be Empty";
        // }

        // if (!username_is_available($username, $old_username)) {
        //     $flag = false;
        //     $_SESSION["amsg"][] = "Username Already Exist";
        // }

        $name = post_str("name");
        if (empty($name)) {
            $flag = false;
            $_SESSION["amsg"][] = "Name Can Not Be Empty";
        }

        $dob = post_str("dob");

        $gender = post_str("gender");
        if (!($gender == 'male' || $gender == 'female')) {
            $flag = false;
            $_SESSION["amsg"][] = "Invalid Value for Gender";
        }

        $mobile = post_str("mobile");
        if (empty($mobile)) {
            $flag = false;
            $_SESSION["amsg"][] = "Mobile Can Not Be Empty";
        }

        $password = $ro["user_password"];
        $pass_update = isset($_POST["chpass"]);
        if ($pass_update) {
            $new_pass = generate_password();
            $password = $new_pass;
        }

        $doctor = intval(isset($_POST["doctor"]));
        $laboratory = intval(isset($_POST["laboratory"]));
        $patient = intval(isset($_POST["patient"]));
        $status = intval(isset($_POST["status"]));


        if ($flag) {
            // $username = mysqli_real_escape_string($con, $username);
            $name = mysqli_real_escape_string($con, $name);
            $dob = mysqli_real_escape_string($con, $dob);
            $gender = mysqli_real_escape_string($con, $gender);
            $mobile = mysqli_real_escape_string($con, $mobile);
            $dpassword = mysqli_real_escape_string($con, $password);
            // $user_insert = "UPDATE `users` SET `user_username` = '$username', `user_name` = '$name', `user_dob` = '$dob', `user_gender` = '$gender', `user_mobile` = '$mobile', `user_password` = '$password', `user_doctor` = '$doctor', `user_laboratory` = '$laboratory', `user_patient` = '$patient', `user_status` = '$status' WHERE `user_id` = '$uid'";
            $user_insert = "UPDATE `users` SET `user_name` = '$name', `user_dob` = '$dob', `user_gender` = '$gender', `user_mobile` = '$mobile', `user_password` = '$password', `user_doctor` = '$doctor', `user_laboratory` = '$laboratory', `user_patient` = '$patient', `user_status` = '$status' WHERE `user_id` = '$uid'";
            if (mysqli_query($con, $user_insert)) {
                $_SESSION["smsg"][] = "User / Patient Update Successfully";
                if ($pass_update) {
					$qry = "SELECT * FROM `users` WHERE `user_id` = '$uid'";
					$res = mysqli_query($con, $qry);
					$uro = mysqli_fetch_assoc($res);
                    send_mail_password_reseted_by_authority($uro["user_email"], get_good_name($uro["user_name"], $uro["user_gender"]), $uro["user_username"], $new_pass, $website_name . " Admin");
                }
                header("location:users.php");
            } else {
                $_SESSION["amsg"][] = "Error";
                header("location:users.php");
            }
        } else {
            // header("location:users.php");
        }
    } else {
        $flag = false;
        $_SESSION["amsg"][] = "Invalid Action";
    }
    exit();
}
?>
<?php include("header.php"); ?>
<main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 pt-4">
    <div class="row">
    <?php
        if (isset($_GET["view"]) && isset($user)) {
        ?>
            <div class="col-12">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <i class="fa fa-eye"></i> View User
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <h5 class="font-weight-bold mb-3">User Details</h5>
                                <div class="row pb-2">
                                    <div class="col-4">
                                        Name <span class="float-right">:</span>
                                    </div>
                                    <div class="col-8 font-weight-medium">
                                        <?php echo $user["user_name"]; ?>
                                    </div>
                                </div>
                                <div class="row pb-2">
                                    <div class="col-4">
                                        Mobile <span class="float-right">:</span>
                                    </div>
                                    <div class="col-8 font-weight-medium">
                                        <?php echo $user["user_mobile"]; ?>
                                    </div>
                                </div>
                                <div class="row pb-2">
                                    <div class="col-4">
                                        Email <span class="float-right">:</span>
                                    </div>
                                    <div class="col-8 font-weight-medium">
                                        <?php echo $user["user_email"]; ?>
                                    </div>
                                </div>
                                <div class="row pb-2">
                                    <div class="col-4">
                                        DOB <span class="float-right">:</span>
                                    </div>
                                    <div class="col-8 font-weight-medium">
                                        <?php echo date("d-m-Y", strtotime($user["user_dob"])); ?>
                                    </div>
                                </div>
                                <div class="row pb-2">
                                    <div class="col-4">
                                        Username <span class="float-right">:</span>
                                    </div>
                                    <div class="col-8 font-weight-medium">
                                        <?php echo $user["user_username"]; ?>
                                    </div>
                                </div>
                                <div class="row pb-2">
                                    <div class="col-4">
                                        Status <span class="float-right">:</span>
                                    </div>
                                    <div class="col-8 font-weight-medium">
                                        <?php echo active_deactive($user["user_status"], 1); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <h5 class="font-weight-bold mb-3">Authority Details</h5>
                                <div class="row pb-2">
                                    <div class="col-4">
                                        Laboratory <span class="float-right">:</span>
                                    </div>
                                    <div class="col-8 font-weight-medium">
                                        <?php echo active_deactive($user["user_laboratory"], 1); ?>
                                    </div>
                                </div>
                                <div class="row pb-2">
                                    <div class="col-4">
                                        Doctor <span class="float-right">:</span>
                                    </div>
                                    <div class="col-8 font-weight-medium">
                                        <?php echo active_deactive($user["user_doctor"], 1); ?>
                                    </div>
                                </div>
                                <div class="row pb-2">
                                    <div class="col-4">
                                        Patient <span class="float-right">:</span>
                                    </div>
                                    <div class="col-8 font-weight-medium">
                                        <?php echo active_deactive($user["user_patient"], 1); ?>
                                    </div>
                                </div>
                            </div>

                            <?php
                            if ($user["user_laboratory"]) {
                                $uid = $user["user_id"];
                                $qry = "SELECT * FROM `laboratorys` WHERE `laboratory_user` = '$uid'";
                                $res = mysqli_query($con, $qry);
                                if (mysqli_num_rows($res) == 1) {
                                    $laboratory = mysqli_fetch_assoc($res);
                            ?>
                                    <div class="col-12 mt-4">
                                        <div class="card">
                                            <div class="card-header pt-2 pb-2">
                                                <i class="fa fa-flask"></i> Laboratory Details
                                            </div>
                                            <div class="card-body">
                                                <div class="row pb-2">
                                                    <div class="col-3">
                                                        Name <span class="float-right">:</span>
                                                    </div>
                                                    <div class="col-9 font-weight-medium">
                                                        <?php echo $laboratory["laboratory_name"]; ?>
                                                    </div>
                                                </div>
                                                <div class="row pb-2">
                                                    <div class="col-3">
                                                        Mobile <span class="float-right">:</span>
                                                    </div>
                                                    <div class="col-9 font-weight-medium">
                                                        <?php echo $laboratory["laboratory_mobile"]; ?>
                                                    </div>
                                                </div>
                                                <div class="row pb-2">
                                                    <div class="col-3">
                                                        Email <span class="float-right">:</span>
                                                    </div>
                                                    <div class="col-9 font-weight-medium">
                                                        <?php echo $laboratory["laboratory_email"]; ?>
                                                    </div>
                                                </div>
                                                <div class="row pb-2">
                                                    <div class="col-3">
                                                        Pincode <span class="float-right">:</span>
                                                    </div>
                                                    <div class="col-9 font-weight-medium">
                                                        <?php echo $laboratory["laboratory_pincode"]; ?>
                                                    </div>
                                                </div>
                                                <div class="row pb-2">
                                                    <div class="col-3">
                                                        Address <span class="float-right">:</span>
                                                    </div>
                                                    <div class="col-9 font-weight-medium">
                                                        <?php echo $laboratory["laboratory_address"]; ?>
                                                    </div>
                                                </div>
                                                <div class="row pb-2">
                                                    <div class="col-9 offset-3 font-weight-medium">
                                                        <a href="laboratorys.php?view=<?php echo $laboratory["laboratory_id"]; ?>"><button class="btn btn-sm btn-success"><i class="fa fa-eye"></i> View More</button></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            }
                            ?>
                            <?php
                            if ($user["user_doctor"]) {
                                $uid = $user["user_id"];
                                $qry = "SELECT * FROM `doctors` WHERE `doctor_user` = '$uid'";
                                $res = mysqli_query($con, $qry);
                                if (mysqli_num_rows($res) == 1) {
                                    $doctor = mysqli_fetch_assoc($res);
                            ?>
                                    <div class="col-12 mt-4">
                                        <div class="card">
                                            <div class="card-header pt-2 pb-2">
                                                <i class="fa fa-user-md"></i> Doctor Details
                                            </div>
                                            <div class="card-body">
                                                <div class="row pb-2">
                                                    <div class="col-3">
                                                        Name <span class="float-right">:</span>
                                                    </div>
                                                    <div class="col-9 font-weight-medium">
                                                        <?php echo $doctor["doctor_name"]; ?>
                                                    </div>
                                                </div>
                                                <div class="row pb-2">
                                                    <div class="col-3">
                                                        Mobile <span class="float-right">:</span>
                                                    </div>
                                                    <div class="col-9 font-weight-medium">
                                                        <?php echo $doctor["doctor_mobile"]; ?>
                                                    </div>
                                                </div>
                                                <div class="row pb-2">
                                                    <div class="col-3">
                                                        Email <span class="float-right">:</span>
                                                    </div>
                                                    <div class="col-9 font-weight-medium">
                                                        <?php echo $doctor["doctor_email"]; ?>
                                                    </div>
                                                </div>
                                                <div class="row pb-2">
                                                    <div class="col-3">
                                                        Pincode <span class="float-right">:</span>
                                                    </div>
                                                    <div class="col-9 font-weight-medium">
                                                        <?php echo $doctor["doctor_pincode"]; ?>
                                                    </div>
                                                </div>
                                                <div class="row pb-2">
                                                    <div class="col-3">
                                                        Address <span class="float-right">:</span>
                                                    </div>
                                                    <div class="col-9 font-weight-medium">
                                                        <?php echo $doctor["doctor_address"]; ?>
                                                    </div>
                                                </div>
                                                <div class="row pb-2">
                                                    <div class="col-9 offset-3 font-weight-medium">
                                                        <a href="doctors.php?view=<?php echo $laboratory["laboratory_id"]; ?>"><button class="btn btn-sm btn-success"><i class="fa fa-eye"></i> View More</button></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            }
                            ?>
                            <div class="col-12 mt-4">
                                <div class="card">
                                    <div class="card-header pt-2 pb-2">
                                        <i class="fa fa-list-ul"></i> Recent Reports
                                    </div>
                                    <div class="card-body">
                                        <?php
                                        $uid = $user["user_id"];
                                        $qry = "SELECT `reports`.* , (SELECT `doctor_name` FROM `doctors` WHERE `report_doctor` = `doctor_id`) AS `doctor_name`, `laboratory_name` FROM `reports`, `laboratorys` WHERE `report_patient` = '$uid' AND `report_laboratory` = `laboratory_id` ORDER BY `report_id` DESC LIMIT 5";
                                        $res = mysqli_query($con, $qry);
                                        if (mysqli_num_rows($res) > 0) {
                                        ?>
                                            <div class="row mb-2 pb-1 border-bottom">
                                                <div class="col-2">
                                                    Report id
                                                </div>
                                                <div class="col-2">
                                                    Date
                                                </div>
                                                <div class="col-2">
                                                    Refrence Doctor
                                                </div>
                                                <div class="col-2">
                                                    Laboratory
                                                </div>
                                                <div class="col-2">
                                                    Status
                                                </div>
                                                <div class="col-2">
                                                    Action
                                                </div>
                                            </div>
                                            <?php
                                            while ($report = mysqli_fetch_assoc($res)) {
                                            ?>
                                                <div class="row pb-2">
                                                    <div class="col-2">
                                                        <?php echo $report["report_sid"]; ?>
                                                    </div>
                                                    <div class="col-2">
                                                        <?php echo date("d-m-Y", strtotime($report["report_timestamp"])); ?>
                                                    </div>
                                                    <div class="col-2">
                                                        <?php
                                                        if (is_null($report["doctor_name"])) {
                                                            echo "-";
                                                        } else {
                                                            echo $report["doctor_name"];
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="col-2">
                                                        <?php echo $report["laboratory_name"]; ?>
                                                    </div>
                                                    <div class="col-2">
                                                        <?php
                                                        if ($report["report_status"] == 1) {
                                                            echo "Pending";
                                                        }
                                                        if ($report["report_status"] == 2) {
                                                            echo "Proccessig";
                                                        }
                                                        if ($report["report_status"] == 3) {
                                                            echo "Posted";
                                                        }
                                                        if ($report["report_status"] == 0) {
                                                            echo "Deleted";
                                                        }

                                                        ?>
                                                    </div>
                                                    <div class="col-2">
                                                    <a href="reports.php?view=<?php echo $report["report_id"]; ?>"><button class="btn btn-primary btn-usize btn-sm mb-1"><i class="fa fa-eye"></i> View</button></a><br>
                                                    <a href="view.php?report=<?php echo $report["report_id"]; ?>"><button class="btn btn-success btn-usize btn-sm mb-1"><i class="fa fa-print"></i> Print</button></a><br>
                                                    </div>
                                                </div>
                                            <?php
                                            }
                                            ?>
                                            <div class="row pb-2">
                                                <div class="col-12 font-weight-medium text-center pt-3">
                                                    <a href="reports.php?patient=<?php echo $user["user_username"]; ?>"><button class="btn btn-sm btn-success"><i class="fa fa-eye"></i> View More</button></a>
                                                </div>
                                            </div>
                                        <?php
                                        } else {
                                        ?>
                                            <h4 class="text-center">No Reports Found</h4>
                                        <?php
                                        }
                                        ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-success" type="button" onclick="location.href='<?php get_prev_link('users.php'); ?>'"><i class="fa fa-backward"></i> Back</button>
                    </div>
                </div>
            </div>
        <?php
        } else if (isset($user)) {
        ?>

            <div class="col-12">
                <form action="" method="POST">
                    <div class="card border-primary">
                        <div class="card-header bg-primary text-white">
                            <i class="fa fa-edit"></i> Edit User / Patient
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group row">
                                        <!-- <div class="col-4 mt-3">
                                            <label><i class="fa fa-user"></i> Username</label>
                                            <input type="text" class="form-control" name="username" value="" required><?php // echo $user["user_username"]; 
                                                                                                                        ?>
                                        </div> -->
                                        <div class="col-4 mt-3">
                                            <label><i class="fa fa-user"></i> Full Name</label>
                                            <input type="text" class="form-control" name="name" value="<?php echo $user["user_name"]; ?>" required>
                                        </div>
                                        <div class="col-4 mt-3">
                                            <label><i class="fa fa-calendar"></i> Date of Birth</label>
                                            <input type="date" class="form-control" name="dob" value="<?php echo $user["user_dob"]; ?>" required>
                                        </div>
                                        <div class="col-4 mt-3">
                                            <label><i class="fa fa-question-circle"></i> Gender</label>
                                            <select name="gender" class="form-control" required>
                                                <?php
                                                $male = "";
                                                $female = "";
                                                if ($user["user_gender"] == "male") {
                                                    $male = "selected";
                                                } else {
                                                    $female = "selected";
                                                }
                                                ?>
                                                <option value="">--select--</option>
                                                <option value="male" <?php echo $male; ?>>Male</option>
                                                <option value="female" <?php echo $female; ?>>Female</option>
                                            </select>
                                        </div>
                                        <div class="col-4 mt-3">
                                            <label><i class="fa fa-phone"></i> Mobile</label>
                                            <input type="text" class="form-control" name="mobile" minlength="10" maxlength="10" value="<?php echo $user["user_mobile"]; ?>" required>
                                        </div>
                                        <div class="col-8 mt-3">
                                            <label><i class="fa fa-envelope"></i> e-Mail</label>
                                            <input type="text" class="form-control" value="<?php echo $user["user_email"]; ?>" readonly>
                                        </div>
                                        <div class="col-3 mt-3 text-center">
                                            <div class="row">
                                                <div class="col-12">
                                                    <i class="fa fa-user-md"></i> Doctor
                                                </div>
                                                <div class="col-12">
                                                    <div class="custom-control custom-switch ">
                                                        <input type="checkbox" class="custom-control-input" id="doctor" name="doctor" <?php me_check($user["user_doctor"]); ?>>
                                                        <label class="custom-control-label" for="doctor"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-3 mt-3 text-center">
                                            <div class="row">
                                                <div class="col-12">
                                                    <i class="fa fa-flask"></i> Laboratory
                                                </div>
                                                <div class="col-12">
                                                    <div class="custom-control custom-switch ">
                                                        <input type="checkbox" class="custom-control-input" id="laboratory" name="laboratory" <?php me_check($user["user_laboratory"]); ?>>
                                                        <label class="custom-control-label" for="laboratory"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-3 mt-3 text-center">
                                            <div class="row">
                                                <div class="col-12">
                                                    <i class="fa fa-user"></i> Patient
                                                </div>
                                                <div class="col-12">
                                                    <div class="custom-control custom-switch ">
                                                        <input type="checkbox" class="custom-control-input" id="patient" name="patient" <?php me_check($user["user_patient"]); ?>>
                                                        <label class="custom-control-label" for="patient"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-3 mt-3 text-center">
                                            <div class="row">
                                                <div class="col-12">
                                                    <i class="fa fa-question-circle"></i> Status
                                                </div>
                                                <div class="col-12">
                                                    <div class="custom-control custom-switch ">
                                                        <input type="checkbox" class="custom-control-input" id="status" name="status" <?php me_check($user["user_status"]); ?>>
                                                        <label class="custom-control-label" for="status"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-4 text-center">
                                            <div class="row">
                                                <div class="col-12">
                                                    <i class="fa fa-lock"></i> Generate New Password For This User
                                                </div>
                                                <div class="col-12">
                                                    <div class="custom-control custom-switch ">
                                                        <input type="checkbox" class="custom-control-input" id="chpass" name="chpass">
                                                        <label class="custom-control-label" for="chpass"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $user["user_id"]; ?>">
                        <div class="card-footer">
                            <button type="submit" name="update" class="btn btn-info mr-3"><i class="fa fa-check"></i> Update User</button>
                            <button type="reset" class="btn btn-warning mr-3"><i class="fa fa-undo"></i> Reset</button>
                            <button class="btn btn-success" type="button" onclick="location.href='users.php'"><i class="fa fa-backward"></i> Back</button>
                        </div>
                    </div>
                </form>
            </div>
        <?php
        } else {
        ?>
            <div class="col-12">
                <form action="" method="POST">
                    <div class="card border-primary">
                        <div class="card-header bg-primary text-white">
                            <i class="fa fa-plus"></i> Add User / Patient
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group row">
                                        <!-- <div class="col-4 mt-3">
                                            <label><i class="fa fa-user"></i> Username</label>
                                            <input type="text" class="form-control" name="username" required>
                                        </div> -->
                                        <div class="col-4 mt-3">
                                            <label><i class="fa fa-user"></i> Full Name</label>
                                            <input type="text" class="form-control" name="name" required>
                                        </div>
                                        <div class="col-4 mt-3">
                                            <label><i class="fa fa-calendar"></i> Date of Birth</label>
                                            <input type="date" class="form-control" name="dob" required>
                                        </div>
                                        <div class="col-4 mt-3">
                                            <label><i class="fa fa-question-circle"></i> Gender</label>
                                            <select name="gender" class="form-control" required>
                                                <option value="">--select--</option>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                            </select>
                                        </div>
                                        <div class="col-4 mt-3">
                                            <label><i class="fa fa-phone"></i> Mobile</label>
                                            <input type="text" class="form-control" name="mobile" minlength="10" maxlength="10" required>
                                        </div>
                                        <div class="col-8 mt-3">
                                            <label><i class="fa fa-envelope"></i> e-Mail</label>
                                            <input type="text" class="form-control" name="email" required>
                                        </div>
                                        <div class="col-3 mt-3 text-center">
                                            <div class="row">
                                                <div class="col-12">
                                                    <i class="fa fa-user-md"></i> Doctor
                                                </div>
                                                <div class="col-12">
                                                    <div class="custom-control custom-switch ">
                                                        <input type="checkbox" class="custom-control-input" id="doctor" name="doctor">
                                                        <label class="custom-control-label" for="doctor"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-3 mt-3 text-center">
                                            <div class="row">
                                                <div class="col-12">
                                                    <i class="fa fa-flask"></i> Laboratory
                                                </div>
                                                <div class="col-12">
                                                    <div class="custom-control custom-switch ">
                                                        <input type="checkbox" class="custom-control-input" id="laboratory" name="laboratory">
                                                        <label class="custom-control-label" for="laboratory"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-3 mt-3 text-center">
                                            <div class="row">
                                                <div class="col-12">
                                                    <i class="fa fa-user"></i> Patient
                                                </div>
                                                <div class="col-12">
                                                    <div class="custom-control custom-switch ">
                                                        <input type="checkbox" class="custom-control-input" id="patient" name="patient" checked>
                                                        <label class="custom-control-label" for="patient"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-3 mt-3 text-center">
                                            <div class="row">
                                                <div class="col-12">
                                                    <i class="fa fa-question-circle"></i> Status
                                                </div>
                                                <div class="col-12">
                                                    <div class="custom-control custom-switch ">
                                                        <input type="checkbox" class="custom-control-input" id="status" name="status" checked>
                                                        <label class="custom-control-label" for="status"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" name="add" class="btn btn-info mr-3"><i class="fa fa-user-plus"></i> Add User</button>
                            <button type="reset" class="btn btn-warning mr-3"><i class="fa fa-undo"></i> Reset</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-12  pt-5">
                <table class="table w-100" id="user_table">
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>Username</th>
                            <th>Name</th>
                            <th>DOB</th>
                            <th>Contact</th>
                            <th>Rights</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                </table>
                <script>
                    $(document).ready(function() {
                        var table = $('#user_table').DataTable({
                            "lengthMenu": [10, 25, 50, 75, 100],
                            "processing": true,
                            "serverSide": true,
                            'serverMethod': 'post',
                            "ajax": {
                                url: "api.php",
                                "data": function(d) {
                                    return $.extend({}, d, {
                                        "users": 1,
                                    });
                                }
                            },
                            columns: [{
                                    data: "id"
                                },
                                {
                                    data: "username"
                                },
                                {
                                    data: "name"
                                },
                                {
                                    data: "dob"
                                },
                                {
                                    data: "contact"
                                },
                                {
                                    data: "rights"
                                },
                                {
                                    data: "edit"
                                },
                            ],
                            'columnDefs': [{
                                'targets': [3, 4, 5],
                                'orderable': false,
                            }]
                        });

                    });
                </script>
            </div>
        <?php
        }
        ?>
    </div>
</main>
<?php include("footer.php"); ?>