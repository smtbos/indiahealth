<?php
include("config.php");
$doctor_id = $login_doctor["doctor_id"];
$doctor_name = $login_doctor["doctor_name"];
if (isset($_GET["report"])) {
    $report_id = get_num("report");
    $res = mysqli_query($con, "SELECT * FROM `reports`, `users` WHERE `report_id` = '$report_id' AND `report_patient` = `user_id`");
    if (mysqli_num_rows($res) == 1) {
        $row = mysqli_fetch_assoc($res);
        $recent = json_decode($login_doctor["doctor_recent"], true);
        if ((($key = array_search($report_id, $recent)) !== false)) {
            unset($recent[$key]);
            $recent = array_values($recent);
        }
        $recent = array_reverse($recent);
        array_push($recent, $report_id);
        $recent = array_reverse($recent);
        $max = 10;
        if (count($recent) > $max) {
            while (count($recent) != $max) {
                $x = array_pop($recent);
            }
        }
        $recent = mysqli_real_escape_string($con, json_encode($recent));
        mysqli_query($con, "UPDATE `doctors` SET `doctor_recent` = '$recent' WHERE `doctor_id` = '$doctor_id'");
        mail_report_view_alert($row["user_email"], get_good_name($row["user_name"], $row["user_gender"]), $report_id, $doctor_name);
        report_render($report_id, false, array(array("get_report.php","Back To Get Reports")));
        exit();
    } else {
        $_SESSION["amsg"][] = "Invalid Report ID";
        header("location:history.php");
        exit();
    }
    exit();
}
?>
