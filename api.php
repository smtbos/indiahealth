<?php
include("config.php");
if (isset($_POST["get_name"])) {
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
                $qq = "SELECT * FROM `users` WHERE `user_username` = '$pid' AND `user_dob` = '$dob'";
                $res = mysqli_query($con, $qq);
                if (mysqli_num_rows($res) == 1) {
                    $ro = mysqli_fetch_assoc($res);
                    $response["status"] = "success";
                    $response["name"] = $ro["user_name"];
                } else {
                    $response["status"] = "failed";
                    $response["reason"] = "Not Found";
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



if (isset($_POST["my_reports"])) {
    $draw = $_POST['draw'];
    $row = $_POST['start'];
    $rowperpage = $_POST['length'];
    $columnIndex = $_POST['order'][0]['column'];
    $columnName = $_POST['columns'][$columnIndex]['data'];
    $columnSortOrder = $_POST['order'][0]['dir'];
    $searchValue = $_POST['search']['value'];
    $patient = $login_user["user_id"];
    $searchQuery = "";

    # Search
    if ($searchValue != '') {
        $searchQuery = " AND ( `report_id` LIKE '$searchValue%' OR `laboratory_name` LIKE '$searchValue%' )";
    }

    # Total Records
    $sel = mysqli_query($con, "SELECT COUNT(*) AS `allcount` FROM `reports`, `users` WHERE `report_patient` = '$patient' AND `user_id` = '$patient'  AND  `report_status` = '3'");
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records["allcount"];

    # Filter is Remain
    $sel = mysqli_query($con, "SELECT COUNT(*) AS `allcount` FROM `reports`, `users` WHERE `report_patient` = '$patient' AND `user_id` = '$patient'  AND  `report_status` = '3' ");
    $records = mysqli_fetch_assoc($sel);
    $totalRecordsWithFilter = $records["allcount"];

    # Data
    $q = "SELECT `report_id` AS `id`,`report_sid` AS `sid`, `laboratory_name` AS `lab_name`,  `user_id` AS `pid`, `user_name` AS `patient`, `report_timestamp` AS `date` FROM `reports`, `users`, `laboratorys` WHERE `report_patient` = '$patient'  AND `report_laboratory` = `laboratory_id` AND `user_id` = '$patient'  AND `report_status` = '3'  $searchQuery ORDER BY `$columnName` $columnSortOrder LIMIT $row, $rowperpage";
    $res = mysqli_query($con, $q);
    $rows = array();
    while ($row = mysqli_fetch_assoc($res)) {
        $rows[] = array(
            "lab_name" => $row["lab_name"],
            "id" => $row["sid"],
            "pid" => $row["pid"],
            "date" => date("d-m-Y", strtotime($row["date"])),
            "patient" => $row["patient"],
            "view" => "<a href='print.php?report=" . $row["id"] . "'><button class='btn btn-sm btn-success mb-1 btn-usize'> <i class='fa fa-eye'></i> View</button></a><br><a href='my_report.php?del=" . $row["id"] . "'><button class='btn btn-sm btn-danger btn-usize'> <i class='fa fa-trash'></i> Delete</button></a>",
        );
    }
    $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordsWithFilter,
        "aaData" => $rows
    );
    echo json_encode($response);
    exit();
}
