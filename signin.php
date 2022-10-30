<?php
include("config.php");
if (isset($login_user)) {
    header("location:patient/");
}
if (isset($_POST["forgot"])) {
    $user = mysqli_real_escape_string($con, post_str("username"));
    $qry = "SELECT * FROM `users` WHERE `user_username` = '$user'";
    $res = mysqli_query($con, $qry);
    if (mysqli_num_rows($res) == 1) {
        $user_row = mysqli_fetch_assoc($res);
        $pre_hash = generate_password(rand(80, 120));
        $hash = mysqli_real_escape_string($con, $pre_hash);
        $user_id = $user_row["user_id"];
        mysqli_query($con, "DELETE FROM `forgots` WHERE `forgot_user` = '$user_id'");
        $qry = "INSERT INTO `forgots`(`forgot_id`, `forgot_user`, `forgot_hash`) VALUES (NULL, '$user_id', '$hash')";
        if (mysqli_query($con, $qry)) {
            $ri = mysqli_insert_id($con);
            $url = $lab_url . "signin.php?ri=" . $ri . "&ui=" . $user_id . "&hash=" . urlencode($pre_hash);
            mail_html($user_row["user_email"], "Password Reset Link", mail_template_password_reset($lab_name, $lab_url, $user_row["user_name"], $user_row["user_username"], $url),  $lab_name." <admin@smtcodes.in>");
            $_SESSION["smsg"][] = "Password Reset Link Sent to Yor Email id";
            header("location:signin.php");
        }
    } else {
        $_SESSION["amsg"][] = "Username Not Found";
        header("location:signin.php?forgot=1");
    }
}
if (isset($_GET["ri"]) && isset($_GET["ui"]) && isset($_GET["hash"])) {
    $ri = mysqli_real_escape_string($con, get_num("ri"));
    $ui = mysqli_real_escape_string($con, get_num("ui"));
    $hash = mysqli_real_escape_string($con, get_str("hash"));
    $qry = "SELECT * FROM `forgots` WHERE `forgot_id` = '$ri' AND `forgot_user` = '$ui' AND `forgot_hash` = '$hash'";
    $res = mysqli_query($con, $qry);
    if (mysqli_num_rows($res) == 1) {
        $reset = mysqli_fetch_assoc($res);
        if (isset($_POST["password"])) {
            $password = mysqli_real_escape_string($con, post_str("password"));
            if (mysqli_query($con, "UPDATE `users` SET `user_password` = '$password' WHERE `user_id` = '$ui'")) {
                mysqli_query($con, "DELETE FROM `forgots` WHERE `forgot_user` = '$ui'");
                $_SESSION["smsg"][] = "Password Reseted Successfully";
                header("location:signin.php");
            } else {
                $_SESSION["amsg"][] = "Error Cotact Admin For More Details";
                header("location:signin.php");
            }
        }
    } else {
        $_SESSION["amsg"][] = "Invalid or Expired URL";
        header("location:signin.php");
    }
}
if (isset($_POST["signin"])) {
    $username = post_str("username");
    $password = post_str("password");
    $username = mysqli_real_escape_string($con, $username);
    $user_select = "SELECT * FROM `users` WHERE `user_username` = '$username' AND `user_status` = '1'";
    $user_result = mysqli_query($con, $user_select);
    if (mysqli_num_rows($user_result) == 1) {
        $user = mysqli_fetch_assoc($user_result);
        if ($user["user_password"] == $password) {
            $_SESSION["user_id"] = $user["user_id"];
            $_SESSION["user_username"] = $user["user_username"];
            $_SESSION["smsg"][] = "Login Successfully";
            if ($user["user_last"] == "") {
                header("location:my_report.php");
            } else {
                header("location:" . $user["user_last"] . "/");
            }
        } else {
            $_SESSION["amsg"][] = "Invalid Password";
            header("location:signin.php");
        }
    } else {
        $_SESSION["amsg"][] = "Username Not Found";
        header("location:signin.php");
    }
}
if (isset($_POST["signup"])) {

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

    $password = post_str("password");
    if ($flag) {
        $username = mysqli_real_escape_string($con, $username);
        $name = mysqli_real_escape_string($con, $name);
        $dob = mysqli_real_escape_string($con, $dob);
        $gender = mysqli_real_escape_string($con, $gender);
        $mobile = mysqli_real_escape_string($con, $mobile);
        $email = mysqli_real_escape_string($con, $email);
        $password = mysqli_real_escape_string($con, $password);
        // $user_insert = "INSERT INTO `users`(`user_id`, `user_username`, `user_name`, `user_dob`, `user_gender`, `user_mobile`, `user_email`, `user_password`, `user_status`) VALUES (NULL, '$username', '$name', '$dob', '$gender', '$mobile', '$email', '$password', '1')";
        $user_insert = "INSERT INTO `users`(`user_id`, `user_name`, `user_dob`, `user_gender`, `user_mobile`, `user_email`, `user_password`, `user_status`) VALUES (NULL, '$name', '$dob', '$gender', '$mobile', '$email', '$password', '1')";
        if (mysqli_query($con, $user_insert)) {
            $uid = mysqli_insert_id($con);
            $_SESSION["user_id"] = $uid;
            $username = get_code_by_id($uid);
            $_SESSION["user_username"] = $username;
            mysqli_query($con, "UPDATE `users` SET `user_username` = '$username' WHERE `user_id` = '$uid'");
            $html = mail_template_new_register($website_name, $website_url, $uid, $username, get_good_name(post_str("name"), post_str("gender")), post_str("dob"));
            mail_html(post_str("email"), "Welcome to " . $website_name, $html, $website_name . " <admin@smtcodes.in>");
            register_mail_actvity($html, $uid);
            $_SESSION["smsg"][] = "Sign up Successfully";
            $_SESSION["smsg"][] = "Your Username is : " . $username;
            header("location:my_report.php");
        } else {
            $_SESSION["amsg"][] = "Error";
            header("location:signin.php");
        }
    } else {
        header("location:signin.php");
    }
}
?>
<?php include("header.php"); ?>
<main class="container-fluid">
    <div class="row">
        <?php
        if (isset($reset)) {
        ?>
            <div class="col-lg-4 col-md-6 col-sm-8 col-12 offset-lg-4 offset-md-3 offset-sm-2 pt-5 pb-sm-5 pr-md-4">
                <form method="POST">
                    <div class="text-center mb-3">
                        <h3 class="font-weight-bold text-success">Reset Password</h3>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="re_password"><i class="fa fa-lock"></i> Enter New Password :</label>
                        <input type="password" name="password" id="re_password" class="form-control" required>
                    </div>
                    <div>
                        <button type="submit" name="submit" class="btn btn-success"><i class="fa fa-sign-in"></i> Reset Now</button>
                    </div>
                </form>
            </div>
        <?php
        } else if (isset($_GET["forgot"])) {
        ?>
            <div class="col-lg-4 col-md-6 col-sm-8 col-12 offset-lg-4 offset-md-3 offset-sm-2 pt-5 pb-sm-5 pr-md-4">
                <form method="POST">
                    <div class="text-center mb-3">
                        <h3 class="font-weight-bold text-success">Forgot Password</h3>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="fo_username"><i class="fa fa-user"></i> Enter Username :</label>
                        <input type="text" name="username" id="fo_username" class="form-control" required>
                    </div>
                    <div>
                        <button type="submit" name="forgot" class="btn btn-success"><i class="fa fa-sign-in"></i> Forgot Now</button>
                    </div>
                </form>
            </div>
        <?php
        } else {
        ?>


            <div class="col-lg-4 col-md-5 col-sm-8 col-12 offset-lg-2 offset-md-1 offset-sm-2 pt-5 pb-sm-5 pr-md-4">
                <form method="POST">
                    <div class="text-center mb-3">
                        <h3 class="font-weight-bold text-success">Sign in Form</h3>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="in_username"><i class="fa fa-user"></i> Username:</label>
                        <input type="text" name="username" id="in_username" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="in_password"><i class="fa fa-lock"></i> Password :</label>
                        <input type="password" name="password" id="in_password" class="form-control" required>
                    </div>
                    <div>
                        <button type="submit" name="signin" class="btn btn-success"><i class="fa fa-sign-in"></i> Sign in</button>
                        <span class="float-right mt-2 pr-3"><a href="?forgot" class="text-decoration-none">Forgot Password?</a></span>
                    </div>
                </form>
            </div>
            <div class="col-lg-4 col-md-5 col-sm-8 col-12 offset-sm-2 offset-md-0 pt-md-5 pb-5 pl-md-4 pt-5">
                <form method="POST">
                    <div class="text-center mb-3">
                        <h3 class="font-weight-bold text-success">Sign up Form</h3>
                    </div>
                    <!-- <div class="form-group">
                    <label class="font-weight-bold"><i class="fa fa-user"></i> Username :</label>
                    <input type="text" name="username" class="form-control" required placeholder="jaykumar12">
                </div> -->
                    <div class="form-group">
                        <label class="font-weight-bold" for="up_name"><i class="fa fa-user"></i> Full Name :</label>
                        <input type="text" name="name" id="up_name" class="form-control" required placeholder="Jay Kumar">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="up_dob"><i class="fa fa-calendar"></i> Date of Birth :</label>
                        <input type="date" name="dob" id="up_dob" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="up_gender"><i class="fa fa-question-circle"></i> Gender :</label>
                        <select name="gender" id="up_gender" class="form-control" required>
                            <option value="">--select--</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="up_mobile"><i class="fa fa-phone"></i> Mobile :</label>
                        <input type="text" name="mobile" id="up_mobile" class="form-control" required minlength="10" maxlength="10" pattern="[0-9]{10}" placeholder="9876543210">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="up_email"><i class="fa fa-envelope"></i> e-Mail :</label>
                        <input type="email" name="email" id="up_email" class="form-control" required placeholder="name@example.com">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="up_password"><i class="fa fa-lock"></i> Password :</label>
                        <input type="password" name="password" id="up_password" class="form-control" required>
                    </div>
                    <div>
                        <button type="submit" name="signup" class="btn btn-success"><i class="fa fa-plus"></i> Sign up</button>
                    </div>
                </form>
            </div>
        <?php
        }
        ?>
    </div>
</main>
<?php include("footer.php"); ?>