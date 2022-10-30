<?php
include('config.php');
function my_rights($arr)
{
    $rights = array();
    if ($arr["admin"]) {
        $rights[] = "Admin";
    }
    if ($arr["helper"]) {
        $rights[] = "Helper";
    }
    if ($arr["laboratory"]) {
        $rights[] = "Laboratory";
    }
    if ($arr["doctor"]) {
        $rights[] = "Doctor";
    }
    if ($arr["patient"]) {
        $rights[] = "Patient";
    }
    return implode("<br>", $rights);
}


function unit_act_deact_button($n, $page, $id, $sp = true, $ml = "ml-2 ", $usize = false)
{
    if ($usize) {
        $usize = "btn-usize ";
    } else {
        $usize = "";
    }
    if ($sp) {
        if (boolval($n)) {
            return "<a href='" . $page . ".php?d=" . $id . "'><button class='" . $ml . $usize . "btn btn-sm btn-danger action_btn' type='button' >Deactive</button></a>";
        } else {
            return "<a href='" . $page . ".php?a=" . $id . "'><button class='" . $ml . $usize . "alertbtn btn-sm btn-danger action_btn' type='button' >Active</button></a>";
        }
    } else {
        return "";
    }
}

if (isset($_POST["uname"])) {
    $response = array();
    $uid = post_str("uid");
    $res = mysqli_query($con, "SELECT `user_name`, `user_id` FROM `users` WHERE `user_username` = '$uid'");
    if (mysqli_num_rows($res) == 1) {
        $row = mysqli_fetch_assoc($res);
        $uid = $row["user_id"];
        if (isset($_POST["doc"]) || isset($_POST["lab"])) {
            if (isset($_POST["doc"])) {
                $doc = post_num("doc");
                $res = mysqli_query($con, "SELECT * FROM `doctors` WHERE `doctor_id` != '$doc' AND `doctor_user` = '$uid'");
            } else {
                $lab = post_num("lab");
                $res = mysqli_query($con, "SELECT * FROM `laboratorys` WHERE `laboratory_id` != '$lab' AND `laboratory_user` = '$uid'");
            }
            if (mysqli_num_rows($res) == 0) {
                $response["status"] = "success";
                $response["uname"] = $row["user_name"];
            } else {
                $response["status"] = "failed";
                $response["reason"] = "User Already Registred With another Laboratory";
            }
        } else {
        }
    } else {
        $response["status"] = "failed";
        $response["reason"] = "Invalid User id";
    }
    echo json_encode($response);
    exit();
}


if (isset($_POST["users"])) {
    $draw = $_POST['draw'];
    $row = $_POST['start'];
    $rowperpage = $_POST['length'];
    $columnIndex = $_POST['order'][0]['column'];
    $columnName = $_POST['columns'][$columnIndex]['data'];
    $columnSortOrder = $_POST['order'][0]['dir'];
    $searchValue = mysqli_real_escape_string($con, $_POST['search']['value']);
    $searchQuery = "";

    # Search
    if ($searchValue == "helper") {
        $searchQuery .= " AND `user_helper` = '1' ";
    } else {
        if ($searchValue != '') {
            $searchValue = mysqli_real_escape_string($con, $searchValue);
            $searchQuery = " AND ( `user_id` = '$searchValue' OR `user_username` LIKE '$searchValue%' OR `user_name` LIKE '$searchValue%' OR `user_mobile` LIKE '$searchValue%' OR `user_email` LIKE '$searchValue%' )";
        }
    }



    # Total Records
    $sel = mysqli_query($con, "SELECT COUNT(*) AS `allcount` FROM `users`");
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records["allcount"];

    # Filter is Remain
    $sel = mysqli_query($con, "SELECT COUNT(*) AS `allcount` FROM `users` WHERE 1 = 1 " . $searchQuery);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordsWithFilter = $records["allcount"];

    # Data

    $q = "SELECT `user_id` AS `id`, `user_username` AS `username`, `user_name` AS `name`, `user_dob`, `user_gender`, `user_mobile`, `user_email`, `user_password`, `user_admin`, `user_doctor`, `user_laboratory`, `user_helper`, `user_patient`, `user_last`, `user_status`, `user_timestamp` FROM `users`  WHERE 1 = 1 " . $searchQuery . " ORDER BY `$columnName` $columnSortOrder  LIMIT $row, $rowperpage";
    $res = mysqli_query($con, $q);
    $rows = array();
    while ($row = mysqli_fetch_assoc($res)) {
        $rows[] = array(
            "id" => $row["id"],
            "username" => $row["username"],
            "name" => $row["name"],
            "dob" => $row["user_dob"],
            "contact" => $row["user_mobile"] . "<br>" . $row["user_email"],
            "rights" => my_rights(array(
                "admin" => $row["user_admin"],
                "helper" => $row["user_helper"],
                "laboratory" => $row["user_laboratory"],
                "doctor" => $row["user_doctor"],
                "patient" => $row["user_patient"],
            )),
            "edit" => "<a href='users.php?view=" . $row["id"] . "'><button class='btn btn-primary btn-usize btn-sm action_btn mb-1'><i class='fa fa-eye'></i> View</button></a><br><a href='users.php?edit=" . $row["id"] . "'><button class='btn btn-success btn-usize btn-sm action_btn mb-1'><i class='fa fa-edit'></i> Edit</button></a><br>" . unit_act_deact_button($row["user_status"], "users", $row["id"], true, "", true),
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


if (isset($_POST["doctors"])) {
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
            "doctors" => array(
                "doctor_id" => "id",
                "doctor_user" => "user",
                "doctor_name" => "name",
                "doctor_mobile" => "mobile",
                "doctor_email" => "email",
                "doctor_status" => "status",
                "doctor_license" => "license",
            ),
            "users" => array(
                "user_id" => "uid",
                "user_username" => "sid",
                "user_name" => "uname",
            )
        ),
        "search" => array(
            "id",
            "sid",
            "name",
            "mobile",
            "email",
        ),
        "order" => array(
            "id",
            "name"
        ),
        "where" => array(
            "val" => array(
                // "id" => 2
            ),
            "col" => array(
                "user" => "uid"
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
        $rows[] = array(
            "id" => $row["id"],
            "name" => $row["name"] . "<br><span class='text-secondary'>" . $row["sid"] . " - " . $row["uname"] . "</span>",
            "contact" => $row["mobile"] . "<br>" . $row["email"],
            "action" => "<a href='doctors.php?view=" . $row["id"] . "'><button class='btn btn-primary btn-sm btn-usize action_btn mb-1'><i class='fa fa-eye'></i> View</button></a><br><a href='doctors.php?edit=" . $row["id"] . "'><button class='btn btn-success btn-sm btn-usize action_btn mb-1'><i class='fa fa-edit'></i> Edit</button></a><br>" . unit_act_deact_button($row["license"], "doctors", $row["id"], $row["status"], "", true)
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


if (isset($_POST["laboratorys"])) {
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
            "laboratorys" => array(
                "laboratory_id" => "id",
                "laboratory_user" => "user",
                "laboratory_name" => "name",
                "laboratory_mobile" => "mobile",
                "laboratory_email" => "email",
                "laboratory_status" => "status",
                "laboratory_license" => "license",

            ),
            "users" => array(
                "user_id" => "uid",
                "user_username" => "sid",
                "user_name" => "uname",
            )
        ),
        "search" => array(
            "id",
            "uid",
            "name",
            "mobile",
            "email",
        ),
        "order" => array(
            "id",
            "name"
        ),
        "where" => array(
            "val" => array(
                // "id" => 2
            ),
            "col" => array(
                "user" => "uid"
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
        $rows[] = array(
            "id" => $row["id"],
            "name" => $row["name"] . "<br><span class='text-secondary'>" . $row["sid"] . " - " . $row["uname"] . "</span>",
            "contact" => $row["mobile"] . "<br>" . $row["email"],
            "action" => "<a href='laboratorys.php?view=" . $row["id"] . "'><button class='btn btn-primary btn-sm action_btn btn-usize mb-1'><i class='fa fa-eye'></i> View</button></a><br><a href='laboratorys.php?edit=" . $row["id"] . "'><button class='btn btn-success btn-sm action_btn btn-usize mb-1'><i class='fa fa-edit'></i> Edit</button></a><br>" . unit_act_deact_button($row["license"], "laboratorys", $row["id"], $row["status"], "", true),
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

if (isset($_POST["units"])) {
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
            "units" => array(
                "unit_id" => "id",
                "unit_text" => "text",
                "unit_dtext" => "dtext",
                "unit_option" => "option",
                "unit_symbol" => "symbol",
                "unit_range" => "range",
                "unit_general" => "general",
                "unit_status" => "status",
            ),
        ),
        "search" => array(
            "id",
            "text",
            "dtext",
            "symbol",
        ),
        "order" => array(
            "id",
            "text",
        ),
        "where" => array(
            "val" => array(
                // "id" => 2
            ),
            "col" => array(
                // "user" => "uid"
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
        $rows[] = array(
            "id" => $row["id"],
            "text" => name_html_view($row["text"], $row["dtext"]),
            "symbol" => if_or_dash($row["symbol"]),
            "option" => if_or_dash(implode(", ", json_decode($row["option"], true))),
            "range" => unit_html_view(json_decode($row["range"], true), $row["symbol"], true),
            "general" => if_or_dash(if_yes($row["general"])),
            "status" => active_deactive($row["status"]),
            "action" => "<a href='units.php?edit=" . $row['id'] . "'><button class='btn btn-sm btn-success action_btn'><i class='fa fa-edit'></i> Edit</button></a>" . unit_act_deact_button($row["status"], "units", $row["id"]),
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


if (isset($_POST["groups"])) {
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
            "groups" => array(
                "group_id" => "id",
                "group_text" => "text",
                "group_dtext" => "dtext",
                "group_unit" => "unit",
                "group_status" => "status",
            ),
        ),
        "search" => array(
            "id",
            "text",
            "dtext",
        ),
        "order" => array(
            "id",
            "text",
        ),
        "where" => array(
            "val" => array(
                // "id" => 2
            ),
            "col" => array(
                // "user" => "uid"
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
    // echo $q1;
    // exit();
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


    function units_list_render($units)
    {
        global $globle_units;
        foreach ($units as $k => $v) {
            $units[$k] = $globle_units[$v];
        }
        return implode("<br>", $units);
    }

    while ($row = mysqli_fetch_assoc($res)) {
        $rows[] = array(
            "id" => $row["id"],
            "text" => name_html_view($row["text"], $row["dtext"]),
            "units" => units_list_render(json_decode($row["unit"], true)),
            // "option" => if_or_dash(implode(", ", json_decode($row["option"], true))),
            "status" => active_deactive($row["status"]),
            "action" => "<a href='groups.php?edit=" . $row['id'] . "'><button class='btn btn-sm btn-success action_btn'><i class='fa fa-edit'></i> Edit</button></a>" . unit_act_deact_button($row["status"], "groups", $row["id"]),
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

if (isset($_POST["templates"])) {
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
            "templates" => array(
                "template_id" => "id",
                "template_text" => "text",
                "template_element" => "element",
                "template_status" => "status",
            ),
        ),
        "search" => array(
            "id",
            "text",
        ),
        "order" => array(
            "id",
            "text",
        ),
        "where" => array(
            "val" => array(
                // "id" => 2
            ),
            "col" => array(
                // "user" => "uid"
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
    // echo $q1;
    // exit();
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


    function units_and_group_list_render($elements)
    {
        global $globle_units;
        global $globle_groups;
        $re = array();
        foreach ($elements as $k => $v) {
            if ($v["type"] == "unit") {
                $re[] = "U - " . $globle_units[$v["id"]];
            } else {
                $re[] = "G - " . $globle_groups[$v["id"]];
            }
        }
        return implode("<br>", $re);
    }

    while ($row = mysqli_fetch_assoc($res)) {
        $rows[] = array(
            "id" => $row["id"],
            "text" => $row["text"],
            //"units" => units_list_render(json_decode($row["unit"], true)),
            "elements" => units_and_group_list_render(json_decode($row["element"], true)),
            "status" => active_deactive($row["status"]),
            "action" => "<a href='templates.php?edit=" . $row['id'] . "'><button class='btn btn-sm btn-success action_btn'><i class='fa fa-edit'></i> Edit</button></a>" . unit_act_deact_button($row["status"], "templates", $row["id"]),
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



if (isset($_POST["querys"])) {
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
            "querys" => array(
                "query_id" => "id",
                "query_user" => "user",
                "query_handel" => "handel",
                "query_subject" => "subject",
                "query_details" => "details",
                "query_message" => "message",
                // "query_solved" => "solved",
                "query_status" => "status",
            ),
            "users" => array(
                "user_id" => "uid",
                "user_name" => "uname",
            )
        ),
        "search" => array(
            "id",
            "uid",
            "subject",
        ),
        "order" => array(
            "id",
            "subject",
            "solved"
        ),
        "where" => array(
            "val" => array(
                "handel" => 1
            ),
            "col" => array(
                "user" => "uid"
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
        "ss" => " CASE WHEN `querys`.`query_solved` = 1 THEN 'Solved' ELSE 'Unsolved' END AS `solved`, ",
        "search_value" => $searchValue,
    );


    $q1 = "SELECT COUNT(*) AS `allcount` FROM ";
    $q2 = "SELECT COUNT(*) AS `allcount` FROM ";
    $q3 = "SELECT * FROM ";
    $sv = "";
    $q = "( SELECT " . $structure["ss"];
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
        $rows[] = array(
            "id" => $row["id"],
            "user" => $row["uid"] . " - " . $row["uname"],
            "subject" => $row["subject"],
            "details" => $row["details"],
            "solved" =>  $row["solved"],
            "action" => "<a href='querys.php?edit=" . $row["id"] . "'><button class='btn btn-success btn-sm'><i class='fa fa-edit'></i> Edit</button></a><br>" . $row["message"],
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




if (isset($_POST["contacts"])) {
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
            "contacts" => array(
                "contact_id" => "id",
                "contact_name" => "name",
                "contact_email" => "email",
                "contact_subject" => "subject",
                "contact_message" => "message",
                "contact_status" => "status",
            )
        ),
        "search" => array(
            "name",
            "subject",
        ),
        "order" => array(
            "id",
        ),
        "where" => array(
            "val" => array(
                // "handel" => 1
            ),
            "col" => array(
                // "user" => "uid"
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
        "ss" => "",
        "search_value" => $searchValue,
    );


    $q1 = "SELECT COUNT(*) AS `allcount` FROM ";
    $q2 = "SELECT COUNT(*) AS `allcount` FROM ";
    $q3 = "SELECT * FROM ";
    $sv = "";
    $q = "( SELECT " . $structure["ss"];
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
        $ro = array(
            "id" => $row["id"],
            "person" => $row["name"] . "<br>" . $row["email"],
            "details" => $row["subject"] . "<br><br>" . $row["message"],
        );
        if ($row["status"] == 0) {
            $ro["status"] = "<span class='text-danger'>Unreaded</span>";
            $ro["mark"] = "<a href='contacts.php?check=" . $row["id"] . "'><button class='btn btn-success btn-sm btn-usize'><i class='fa fa-check'></i> Readed</button></a>";
        } else {
            $ro["status"] = "<span class='text-success'>Readed</span>";
            $ro["mark"] = "<a href='contacts.php?uncheck=" . $row["id"] . "'><button class='btn btn-success btn-sm btn-usize'><i class='fa fa-undo'></i> UnReaded</button></a>";
        }
        $rows[] = $ro;
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



if (isset($_POST["reports"])) {
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
                "report_patient" => "pid",
                "report_timestamp" => "report_timestamp",
                "report_status" => "status",
            ),
            "users" => array(
                "user_id" => "uid",
                "user_name" => "patient",
                "user_username" => "sid",
            ),
        ),
        "search" => array(
            "id",
            "sid",
        ),
        "order" => array(
            "id",
        ),
        "where" => array(
            "val" => array(),
            "col" => array(
                "pid" => "uid",
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
        "ss" => "",
        "search_value" => $searchValue,
    );


    $q1 = "SELECT COUNT(*) AS `allcount` FROM ";
    $q2 = "SELECT COUNT(*) AS `allcount` FROM ";
    $q3 = "SELECT * FROM ";
    $sv = "";
    $q = "( SELECT " . $structure["ss"];
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
            "id" => $row["id"],
            "date" => date("d-m-Y", strtotime($row["report_timestamp"])),
            "pid" => $row["sid"],
            "patient" => $row["patient"],
            "action" => "<a href='reports.php?view=" . $row["id"] . "'><button class='btn btn-sm btn-primary btn-usize mb-1'><i class='fa fa-eye'></i> View</button></a><br><a href='view.php?report=" . $row["id"] . "'><button class='btn btn-sm btn-success btn-usize mb-1'><i class='fa fa-print'></i> Print</button></a>",
            "alter" => "<a href='reports.php?edit=" . $row["id"] . "'><button class='btn btn-sm btn-warning btn-usize mb-1'><i class='fa fa-edit'></i> Edit</button></a>",
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
        if ($row["status"] != 0) {
            $rpt["alter"] .= "<br><a href='reports.php?dis=" . $row["id"] . "'><button class='btn btn-sm btn-danger btn-usize mb-1'><i class='fa fa-trash'></i> Discard</button></a>";
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
    exit();
}
