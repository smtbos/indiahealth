<?php
include("config.php");
$lab_id = $login_lab["laboratory_id"];
if (isset($_GET["report"])) {
    $report_id = get_num("report");
    $res = mysqli_query($con, "SELECT * FROM `reports`, `users` WHERE `report_id` = '$report_id' AND `report_laboratory` = '$lab_id' AND `report_patient` = `user_id`");
    if (mysqli_num_rows($res) == 1) {
        report_render($report_id, true, array(array("history.php", "Back to History"), array("index.php", "Back to Dahsboard")));
        exit();
    } else {
        $_SESSION["amsg"][] = "Invalid Report ID";
        header("location:history.php");
    }
}
?>