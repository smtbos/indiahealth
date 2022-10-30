<?php
include("../config.php");

if (!$login_user["user_doctor"]) {
    header("location:../");
    exit();
}

if ($login_user["user_last"] != "doctor") {
    $user_id = $_SESSION["user_id"];
    mysqli_query($con, "UPDATE `users` SET `user_last` = 'doctor' WHERE `user_id` = '$user_id'");
}

$qry = "SELECT * FROM `doctors` WHERE `doctor_user` = '$user_id'";
$res = mysqli_query($con, $qry);
if (mysqli_num_rows($res) == 1) {
    $login_doctor = mysqli_fetch_assoc($res);
    if (!($login_doctor["doctor_status"] == 1 && $login_doctor["doctor_license"] == 1)) {
        header("location:register.php");
        exit();
    }
} else {
    $_SESSION["asmg"][] = "Please Register as Laboratory";
    header("location:register.php");
    exit();
}
