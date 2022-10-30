<?php
include("config.php");
if (isset($_POST["get_reports"])) {
    $draw = $_POST['draw'];
    $row = $_POST['start'];
    $rowperpage = $_POST['length'];
    $columnIndex = $_POST['order'][0]['column'];
    $columnName = $_POST['columns'][$columnIndex]['data'];
    $columnSortOrder = $_POST['order'][0]['dir'];
    $searchValue = $_POST['search']['value'];
    $patient = post_str("patient");
    $searchQuery = "";

    # Search
    if ($searchValue != '') {
        $searchQuery = " AND ( `report_id` LIKE '$searchValue%' )";
    }

    # Total Records
    $sel = mysqli_query($con, "SELECT COUNT(*) AS `allcount` FROM `reports`, `users` WHERE `report_patient` = `user_id` AND `user_username` = '$patient'  AND  `report_status` = '3'");
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records["allcount"];

    # Filter is Remain
    $sel = mysqli_query($con, "SELECT COUNT(*) AS `allcount` FROM `reports`, `users` WHERE `report_patient` = `user_id` AND `user_username` = '$patient'  AND  `report_status` = '3' ");
    $records = mysqli_fetch_assoc($sel);
    $totalRecordsWithFilter = $records["allcount"];

    # Data

    $q = "SELECT `report_id` AS `id`,  `report_sid` AS `sid`,  `user_username` AS `pid`, `user_name` AS `patient`, `report_timestamp` FROM `reports`, `users` WHERE `report_patient` = `user_id`  AND `user_username` = '$patient'  AND `report_status` = '3'  $searchQuery ORDER BY `$columnName` $columnSortOrder LIMIT $row, $rowperpage";
    $res = mysqli_query($con, $q);
    $rows = array();
    while ($row = mysqli_fetch_assoc($res)) {
        $rows[] = array(
            "id" => $row["sid"],
            // "pid" => $row["pid"],
            "date" => date("d-m-Y", strtotime($row["report_timestamp"])),
            "patient" => $row["pid"] . " - " . $row["patient"],
            "view" => "<a href='view.php?report=" . $row["id"] . "'><button class='btn btn-sm btn-success'><i class='fa fa-eye'></i> View</button></a>",
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
        $q = "SELECT * FROM `doctors` WHERE `doctor_user` = '$user_id'";
        $res = mysqli_query($con, $q);
        if (mysqli_num_rows($res) == 1) {
            $row = mysqli_fetch_assoc($res);
            if ($row["doctor_license"] == 1 && $row["doctor_status"] == 1) {
                $pid = mysqli_real_escape_string($con, $pid);
                $dob = mysqli_real_escape_string($con, $dob);
                $qq = "SELECT * FROM `users` WHERE `user_username` = '$pid' AND `user_dob` = '$dob' AND `user_status` = '1'";
                $res = mysqli_query($con, $qq);
                if (mysqli_num_rows($res) == 1) {
                    $ro = mysqli_fetch_assoc($res);
                    $response["status"] = "success";
                    $response["name"] = $ro["user_name"];
                    $response["image"] = get_patient_profile_link($ro["user_profile"]);
                    $response["age_gender"] = get_age_and_gender($ro["user_dob"], $ro["user_gender"]);
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

if (isset($_POST["my_patient"])) {
    $draw = $_POST['draw'];
    $row = $_POST['start'];
    $rowperpage = $_POST['length'];
    $columnIndex = $_POST['order'][0]['column'];
    $columnName = $_POST['columns'][$columnIndex]['data'];
    $columnSortOrder = $_POST['order'][0]['dir'];
    $searchValue = mysqli_real_escape_string($con, $_POST['search']['value']);
    $searchQuery = "";

    $structure = array(
        "tables" => array(
            "reports" => array(
                "report_id" => "id",
                "report_sid" => "rsid",
                "report_doctor" => "doctor",
                "report_patient" => "patient",
                "report_status" => "status",
                "report_timestamp" => "report_timestamp"
            ),
            "users" => array(
                "user_id" => "uid",
                "user_username" => "usid",
                "user_name" => "uname",
            ),
        ),
        "search" => array(
            "rsid",
            "usid",
            "uname",
        ),
        "order" => array(),
        "where" => array(
            "val" => array(
                "doctor" => $login_doctor["doctor_id"]
            ),
            "col" => array(
                "patient" => "uid"
            ),
        ),
        "order_by" => array(
            "col" => $columnName,
            "order" => $columnSortOrder
        ),
        "limit" => array(
            $row,
            $rowperpage
        ),
        "search_value" => $searchValue,
    );


    $q1 = "SELECT COUNT(*) AS `allcount` FROM ";
    $q2 = "SELECT COUNT(*) AS `allcount` FROM ";
    $q3 = "SELECT * FROM ";
    $sv = "";
    $q = "( SELECT ";
    $sl_ar = array();
    $tb_ar = array();
    foreach ($structure["tables"] as $t => $cs) {
        foreach ($cs as $cn => $c) {
            $sl_ar[] =  " `$t`.`$cn` AS `$c`";
        }
        $tb_ar[] = " `$t`";
    }
    $q .= implode(",", $sl_ar) . " FROM " . implode(",", $tb_ar) . ") `table` WHERE 1 = 1";
    foreach ($structure["where"]["col"] as $k => $v) {
        $q .= " AND `$k`  =  `$v` ";
    }
    foreach ($structure["where"]["val"] as $k => $v) {
        $q .= " AND `$k`  =  '$v' ";
    }
    $q1 .= $q;
    if ($structure["search_value"] != "") {
        $sq_ele = array();
        foreach ($structure["search"] as $sc) {
            $sq_ele[] = " `$sc` LIKE '" . $structure["search_value"] . "%' ";
        }
        $q .= " AND ( " . implode(" OR ", $sq_ele) . " )";
    }
    $q2 .= $q;
    if (in_array($structure["order_by"]["col"], $structure["order"])) {
        $q .= " ORDER BY `" . $structure["order_by"]["col"] . "` " . $structure["order_by"]["order"] . " ";
    }
    $q .= " LIMIT " . $structure["limit"][0] . ", " . $structure["limit"][1];
    $q3 .= $q;

    # Total Records
    $sel = mysqli_query($con, $q1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records["allcount"];

    # Filter is Remain
    $sel = mysqli_query($con, $q2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordsWithFilter = $records["allcount"];

    # Data
    $res = mysqli_query($con, $q3);
    $rows = array();
    while ($row = mysqli_fetch_assoc($res)) {
        $rpt = array(
            "id" => "<span class='type_id'>" . $row["rsid"] . "</span>",
            "pid" => "<span class='type_id'>" . $row["usid"] . "</span>",
            "patient" => $row["uname"],
            "date" => date("d-m-Y", strtotime($row["report_timestamp"])),
        );
        if ($row["status"] == 1) {
            $rpt["status"] = "Pending";
        } else if ($row["status"] == 2) {
            $rpt["status"] = "Proccessing";
        } else if ($row["status"] == 3) {
            $rpt["status"] = "Posted";
        } else {
            $rpt["status"] = "Discarded";
        }
        if ($row["status"] == 3) {
            $rpt["print"] = "<a href='print.php?report=" . $row["id"] . "'><button class='btn btn-sm btn-success'><i class='fa fa-print'></i> Print</button></a>";
        } else {
            $rpt["print"] = "<a href='#'><button class='btn btn-sm btn-success' disabled><i class='fa fa-print'></i> Print</button></a>";
        }
        $rows[] = $rpt;
    }
    $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordsWithFilter,
        "aaData" => $rows
    );
    echo json_encode($response);
}
