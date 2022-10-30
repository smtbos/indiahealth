<?php
include("../config.php");

if (!$login_user["user_laboratory"]) {
    header("location:../");
    exit();
}

$user_id = $_SESSION["user_id"];
if ($login_user["user_last"] != "laboratory") {
    mysqli_query($con, "UPDATE `users` SET `user_last` = 'laboratory' WHERE `user_id` = '$user_id'");
}

$qry = "SELECT * FROM `laboratorys` WHERE `laboratory_user` = '$user_id'";
$res = mysqli_query($con, $qry);
if (mysqli_num_rows($res) == 1) {
    $login_lab = mysqli_fetch_assoc($res);
    if (!($login_lab["laboratory_status"] == 1 && $login_lab["laboratory_license"] == 1)) {
        header("location:register.php");
        exit();
    }
} else {
    $_SESSION["asmg"][] = "Please Register as Laboratory";
    header("location:register.php");
    exit();
}
