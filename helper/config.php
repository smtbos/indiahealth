<?php
include("../config.php");

if (!$login_user["user_helper"]) {
    header("location:../");
    exit();
}

if ($login_user["user_last"] != "helper") {
    $user_id = $_SESSION["user_id"];
    mysqli_query($con, "UPDATE `users` SET `user_last` = 'helper' WHERE `user_id` = '$user_id'");
}