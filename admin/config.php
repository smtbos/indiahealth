<?php
include("../config.php");

if(!$login_user["user_admin"])
{
    header("location:../");
    exit();
}

if($login_user["user_last"] != "admin")
{
    $user_id = $_SESSION["user_id"];
    mysqli_query($con, "UPDATE `users` SET `user_last` = 'admin' WHERE `user_id` = '$user_id'");
}