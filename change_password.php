<?php
include("config.php");
if (!isset($login_user)) {
    header("location:signin.php");
}
if (isset($_POST["change"])) {
    $cu = post_str("cu_password");
    $new = post_str("new_password");
    $cnew = post_str("cnew_password");

    $flag = true;
    if ($cu != $login_user["user_password"]) {
        $flag = false;
        $_SESSION["amsg"][] = "Invalid Current Password";
    }

    if ($new != $cnew) {
        $flag = false;
        $_SESSION["amsg"][] = "New Password and Confirm Password Not Match";
    }

    if ($flag) {
        $user_id = $login_user["user_id"];
        $new = mysqli_real_escape_string($con, $new);
        $qry = "UPDATE `users` SET `user_password` = '$new' WHERE `user_id` = '$user_id'";
        if (mysqli_query($con, $qry)) {
            $_SESSION["smsg"][] = "Password Chnaged Successfully";
            header("location:index.php");
        } else {
            $_SESSION["amsg"][] = "Error";
            header("location:change_password.php");
        }
        exit();
    } else {
        header("location:change_password.php");
        exit();
    }
}
?>
<?php include("header.php"); ?>
<main class="container-fluid">
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-8 col-12 offset-lg-4 offset-md-3 offset-sm-2 pt-5 pb-sm-5 pr-md-4">
            <form method="POST">
                <div class="text-center mb-3">
                    <h3 class="font-weight-bold text-success">Change Password</h3>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold" for="cu_password"><i class="fa fa-lock"></i> Enter Current Password :</label>
                    <input type="password" name="cu_password" id="cu_password" class="form-control" required>
                </div>
                <div class="form-group pt-3">
                    <label class="font-weight-bold" for="new_password"><i class="fa fa-lock"></i> Enter New Password :</label>
                    <input type="password" name="new_password" id="new_password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold" for="cnew_password"><i class="fa fa-lock"></i> Enter Confirm New Password :</label>
                    <input type="password" name="cnew_password" id="cnew_password" class="form-control" required>
                </div>
                <div>
                    <button type="submit" name="change" class="btn btn-success"><i class="fa fa-sign-in"></i> Chnage Password</button>
                </div>
            </form>
        </div>
    </div>
</main>
<?php include("footer.php"); ?>