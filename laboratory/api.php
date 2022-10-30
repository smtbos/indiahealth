<?php
include("config.php");
if (isset($_POST["history"])) {
    $draw = $_POST['draw'];
    $row = $_POST['start'];
    $rowperpage = $_POST['length'];
    $columnIndex = $_POST['order'][0]['column'];
    $columnName = $_POST['columns'][$columnIndex]['data'];
    $columnSortOrder = $_POST['order'][0]['dir'];
    $searchValue = $_POST['search']['value'];
    $date = post_str("date");

    $lab_id = $login_lab["laboratory_id"];

    $searchQuery = "";

    # Search
    if ($searchValue != '') {
        $arr = explode(":", $searchValue);
        if (count($arr) == 2) {
            $arr[1] = mysqli_real_escape_string($con, $arr[1]);
            if ($arr[0] == "id") {
                $searchQuery = " AND `report_id` = '" . $arr[1] . "' ";
            } else if ($arr[0] == "pid") {
                $searchQuery = " AND `user_id` = '" . $arr[1] . "' ";
            } else if ($arr[0] == "name") {
                $searchQuery = " AND `user_name` LIKE '" . $arr[1] . "%' ";
            }
        } else {
            if (is_numeric($searchValue)) {
                $searchQuery = " AND `report_id` = '$searchValue' ";
            } else {
                $searchQuery = " AND `user_name` LIKE '$searchValue%' ";
            }
        }
    }

    # Total Records
    $sel = mysqli_query($con, "SELECT COUNT(*) AS `allcount` FROM `reports`, `users` WHERE `report_laboratory` = '$lab_id' AND  `report_patient` = `user_id` AND `report_status` = '3' AND `report_timestamp` LIKE '$date%' ");
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records["allcount"];

    # Filter is Remain
    $sel = mysqli_query($con, "SELECT COUNT(*) AS `allcount` FROM `reports`, `users` WHERE `report_laboratory` = '$lab_id' AND  `report_patient` = `user_id` AND `report_status` = '3'  AND `report_timestamp` LIKE '$date%'  $searchQuery ");
    $records = mysqli_fetch_assoc($sel);
    $totalRecordsWithFilter = $records["allcount"];

    # Data
    $q = "SELECT `report_id` AS `id`, `report_sid` AS `rid`,  `user_id` AS `pid`, `user_username` AS `sid`, `user_name` AS `patient`, `report_timestamp` FROM `reports`, `users` WHERE `report_laboratory` = '$lab_id' AND  `report_patient` = `user_id` AND `report_status` = '3'  AND `report_timestamp` LIKE '$date%'  $searchQuery ORDER BY `$columnName` $columnSortOrder LIMIT $row, $rowperpage";
    $res = mysqli_query($con, $q);
    $rows = array();
    while ($row = mysqli_fetch_assoc($res)) {
        $rows[] = array(
            "id" => "<span class='type_id'>" . $row["rid"] . "</span>",
            "pid" => "<span class='type_id'>" . $row["sid"] . "</span>",
            "patient" => $row["patient"],
            "print" => "<a href='print.php?report=" . $row["id"] . "'><button class='btn btn-sm btn-success'><i class='fa fa-print'></i> Print</button></a>",
            "date" => date("d-m-Y", strtotime($row["report_timestamp"])),
        );
    }
    $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordsWithFilter,
        "aaData" => $rows
    );
    echo json_encode($response);
}


if (isset($_POST["get_info"])) {
    $response = array();
    $pid = post_str("pid");
    $dob = post_str("dob");
    $user_id = $_SESSION["user_id"];
    if (isset($login_user)) {
        $q = "SELECT * FROM `laboratorys` WHERE `laboratory_user` = '$user_id'";
        $res = mysqli_query($con, $q);
        if (mysqli_num_rows($res) == 1) {
            $row = mysqli_fetch_assoc($res);
            if ($row["laboratory_license"] == 1 && $row["laboratory_status"] == 1) {
                $pid = mysqli_real_escape_string($con, $pid);
                $dob = mysqli_real_escape_string($con, $dob);
                $qq = "SELECT * FROM `users` WHERE `user_username` = '$pid' AND `user_dob` = '$dob' AND `user_status` = '1'";
                $res = mysqli_query($con, $qq);
                if (mysqli_num_rows($res) == 1) {
                    $ro = mysqli_fetch_assoc($res);
                    $patient_id = $ro["user_id"];
                    $response["status"] = "success";
                    $response["name"] = $ro["user_name"];
                    $response["image"] = get_patient_profile_link($ro["user_profile"]);
                    $response["age_gender"] = get_age_and_gender($ro["user_dob"], $ro["user_gender"]);
                    $report_select = "SELECT * FROM `reports` WHERE `report_patient` = '$patient_id' AND `report_laboratory` IS NULL";
                    $report_res = mysqli_query($con, $report_select);
                    $response["report"] = mysqli_num_rows($report_res);
                } else {
                    $response["status"] = "failed";
                    $response["reason"] = "Patient Not Found";
                }
            } else {
                $response["status"] = "failed";
                $response["reason"] = "Invalid Authority";
            }
        } else {
            $response["status"] = "failed";
            $response["reason"] = "Invalid Authority";
        }
    } else {
        $response["status"] = "failed";
        $response["reason"] = "Invalid Authority";
    }
    echo json_encode($response);
    exit();
}

