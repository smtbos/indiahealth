<?php
include("config.php");
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

    if ($flag) {
        $doc = $login_doctor["doctor_name"];
        // $username = mysqli_real_escape_string($con, $username);
        $name = mysqli_real_escape_string($con, $name);
        $dob = mysqli_real_escape_string($con, $dob);
        $gender = mysqli_real_escape_string($con, $gender);
        $mobile = mysqli_real_escape_string($con, $mobile);
        $email = mysqli_real_escape_string($con, $email);
        $dpassword = mysqli_real_escape_string($con, $password);
        // $user_insert = "INSERT INTO `users`(`user_id`, `user_username`, `user_name`, `user_dob`, `user_gender`, `user_mobile`, `user_email`, `user_password`, `user_status`) VALUES (NULL, '$username', '$name', '$dob', '$gender', '$mobile', '$email', '$dpassword', '1')";
        $user_insert = "INSERT INTO `users`(`user_id`, `user_name`, `user_dob`, `user_gender`, `user_mobile`, `user_email`, `user_password`, `user_status`) VALUES (NULL, '$name', '$dob', '$gender', '$mobile', '$email', '$dpassword', '1')";
        if (mysqli_query($con, $user_insert)) {
            $_SESSION["smsg"][] = "Patient Added Successfully";
            $pid = mysqli_insert_id($con);
            $username = get_code_by_id($pid);
            mysqli_query($con, "UPDATE `users` SET `user_username` = '$username' WHERE `user_id` = '$pid'");
            mail_new_registration_welcome(post_str("email"), $pid, get_good_name(post_str("name"), post_str("gender")), $username, post_str("dob"), $password, $lab, $doc);
            // header("location:create_report.php?pid=" . $pid . "&dob=" . post_str("dob"));
            header("location:add_patient.php");
        } else {
            $_SESSION["amsg"][] = "Error";
            header("location:add_patient.php");
        }
    } else {
        header("location:add_patient.php");
    }
}
?>
<?php include("header.php"); ?>
<main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 pt-4">
    <div class="row">
        <div class="col-lg-6 col-md-8 col-sm-8 col-12 offset-lg-3 offset-md-2 offset-sm-2 offset-md-0 pt-md-5 pb-5 pl-md-4">
            <form method="POST">
                <div class="text-center mb-3">
                    <h3 class="font-weight-bold text-success">Add Patient</h3>
                </div>
                <!-- <div class="form-group">
                    <label class="font-weight-bold"><i class="fa fa-user"></i> Username :</label>
                    <input type="text" name="username" class="form-control" required placeholder="jaykumar12">
                </div> -->
                <div class="form-group">
                    <label class="font-weight-bold"><i class="fa fa-user"></i> Full Name :</label>
                    <input type="text" name="name" class="form-control" required placeholder="Jay Kumar">
                </div>
                <div class="form-group">
                    <label class="font-weight-bold"><i class="fa fa-calendar"></i> Date of Birth :</label>
                    <input type="date" name="dob" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold"><i class="fa fa-question-circle"></i> Gender :</label>
                    <select name="gender" class="form-control" required>
                        <option value="">--select--</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold"><i class="fa fa-phone"></i> Mobile :</label>
                    <input type="text" name="mobile" class="form-control" required minlength="10" maxlength="10" pattern="[0-9]{10}" placeholder="9876543210">
                </div>
                <div class="form-group">
                    <label class="font-weight-bold"><i class="fa fa-envelope"></i> e-Mail :</label>
                    <input type="email" name="email" class="form-control" required placeholder="name@example.com">
                </div>
                <div>
                    <button type="submit" name="add" class="btn btn-success"><i class="fa fa-plus"></i> Add Patient</button>
                </div>
            </form>
        </div>
    </div>
</main>
<?php include("footer.php"); ?>