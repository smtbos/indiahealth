<?php
include("../config.php");
if (isset($_GET["id"]) && isset($_GET["auth"])) {
    // print_r($_GET);
    $id = mysqli_real_escape_string($con, get_str("id"));
    $auth =  mysqli_real_escape_string($con, get_str("auth"));
    $q = "SELECT * FROM `reports` WHERE `report_sid` = '$id' AND `report_auth` = '$auth'";
    $res = mysqli_query($con, $q);
    if (mysqli_num_rows($res) == 1) {
		$rep = mysqli_fetch_assoc($res);
        report_render($rep["report_id"], true, array(array($website_url, "Back to Home")));
    } else {
        $_SESSION["amsg"][] = "Invalid URL";
        header("location:../");
    }
} else {
    header("location:../");
}
