<?php
include("config.php");
if (isset($_GET["discard"])) {
    $report = get_str("discard");
    mysqli_query($con, "UPDATE `reports` SET `report_status` = '0' WHERE `report_sid` = '$report'");
    $_SESSION["smsg"][] = "Report is Descarded";
    header("location:fill_report.php");
    exit();
}
if (isset($_GET["report"])) {
    $report_id = get_str("report");
    $lab_id = $login_lab["laboratory_id"];
    $report_select = "SELECT * FROM `reports` WHERE `report_sid` = '$report_id' AND ( `report_laboratory` = '$lab_id' OR `report_laboratory` IS NULL )";
    $res = mysqli_query($con, $report_select);
    if (mysqli_num_rows($res) == 1) {
        $report_row = mysqli_fetch_assoc($res);
        if ($report_row["report_laboratory"] == NULL) {
            mysqli_query($con, "UPDATE `reports` SET `report_laboratory` = '$lab_id' WHERE `report_sid` = '$report_id'");
        }
    } else {
        $_SESSION["smsg"][] = "Invalid Action";
        header("location:fill_report.php");
        exit();
    }
}
if (isset($_POST["save"]) || isset($_POST["post"]) || isset($_POST["print"])) {

    $report = post_arr("report");

    $report_id = post_num("report_id");

    $report_select = "SELECT * FROM `reports` WHERE `report_id` = '$report_id'";
    $report_res = mysqli_query($con, $report_select);
    if (mysqli_num_rows($report_res) == 1) {
        $report_row = mysqli_fetch_assoc($report_res);

        $patient_id = $report_row["report_patient"];
        $tr = mysqli_query($con, "SELECT * FROM `users` WHERE `user_id` = '$patient_id'");
        $patient = mysqli_fetch_assoc($tr);
        $report_details = json_decode($report_row["report_details"], true);
        foreach ($report_details as $k => $v) {
            if ($v["type"] == 'template') {
                $template_id = $v["id"];
                foreach ($v["element"] as $kk => $vv) {
                    if ($vv["type"] == 'unit') {
                        $unit_id = $vv["id"];
                        if (isset($report["template"][$template_id]["unit"][$unit_id])) {
                            if (is_string($report["template"][$template_id]["unit"][$unit_id])) {
                                $report_details[$k]["element"][$kk]["value"] = $report["template"][$template_id]["unit"][$unit_id];
                            }
                        }
                    }
                    if ($vv["type"] == 'group') {
                        $group_id = $vv["id"];
                        foreach ($vv["unit"] as $kkk => $vvv) {
                            $unit_id = $vvv["id"];
                            if (isset($report["template"][$template_id]["group"][$group_id]["unit"][$unit_id])) {
                                if (is_string($report["template"][$template_id]["group"][$group_id]["unit"][$unit_id])) {
                                    $report_details[$k]["element"][$kk]["unit"][$kkk]["value"] = $report["template"][$template_id]["group"][$group_id]["unit"][$unit_id];
                                }
                            }
                        }
                    }
                }
            }
            if ($v["type"] == 'group') {
                $group_id = $v["id"];
                foreach ($v["unit"] as $kkk => $vvv) {
                    $unit_id = $vvv["id"];
                    if (isset($report["group"][$group_id]["unit"][$unit_id])) {
                        if (is_string($report["group"][$group_id]["unit"][$unit_id])) {
                            $report_details[$k]["unit"][$kkk]["value"] = $report["group"][$group_id]["unit"][$unit_id];
                        }
                    }
                }
            }
        }
        $report_details = mysqli_real_escape_string($con, json_encode($report_details));
        $status = 2;
        if (isset($_POST["post"]) || isset($_POST["print"])) {
            $status = 3;
        }
        $q = "UPDATE `reports` SET  `report_details`= '$report_details', `report_status` = '$status' WHERE `report_id` = '$report_id'";
        if (mysqli_query($con, $q)) {
            if (isset($_POST["post"])) {
                $_SESSION["smsg"][] = "Report Posted";
            } else if (isset($_POST["save"])) {
                $_SESSION["smsg"][] = "Report Saved as Draft";
            }
            if (isset($_POST["post"]) || isset($_POST["print"])) {
                $to = $patient["user_email"];
                $name = $patient["user_name"];
                $auth = $report_row["report_auth"];
                $report_sid = $report_row["report_sid"];
                $report_date = date("d-m-Y", strtotime($report_row["report_timestamp"]));
                $la = $login_lab["laboratory_name"];
                report_added_notification($to, $name, $report_sid, $auth, $report_date, $la);
            }
            if (isset($_POST["print"])) {
                header("location:print.php?report=" . $report_id);
            } else {
                header("location:fill_report.php");
            }
        } else {
            $_SESSION["amsg"][] = "Invalid Action";
            header("location:fill_report.php");
        }
    } else {
        $_SESSION["amsg"][] = "Invalid Action";
        header("location:fill_report.php");
    }
    exit();
}
if (isset($_GET["pid"]) && isset($_GET["dob"])) {

    $pid =  mysqli_real_escape_string($con, get_str("pid"));
    $dob = get_str("dob");
    $patient_qry = "SELECT * FROM `users` WHERE `user_username` = '$pid' AND `user_dob` = '$dob'";
    $patient_res = mysqli_query($con, $patient_qry);
    if (mysqli_num_rows($patient_res) == 1) {
        $patient = mysqli_fetch_assoc($patient_res);
        $patient_id = $patient["user_id"];
        $report_select = "SELECT * FROM `reports`, `users` WHERE `report_patient` = '$patient_id' AND `report_laboratory` IS NULL AND `user_id` = `report_patient`";
        $report_res = mysqli_query($con, $report_select);
        if (mysqli_num_rows($report_res) == 0) {
            $_SESSION["amsg"][] = "Invalid Action";
            header("location:create_report.php");
            exit();
        }
    } else {
        $_SESSION["amsg"][] = "Invalid Action";
        header("location:create_report.php");
        exit();
    }
}
?>
<?php include("header.php"); ?>
<main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 pt-4">
    <div class="row">
        <div class="col-12">
            <?php
            if (isset($_GET["pid"]) && isset($_GET["dob"]) && isset($report_res)) {
            ?>
                <div class="row">
                    <?php
                    while ($row = mysqli_fetch_assoc($report_res)) {
                    ?>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-12 mb-4">
                            <div class="card">
                                <div class="card-header text-center">
                                    <div class="row">
                                        <div class="col-4 text-left my-auto">Report id : <?php echo $row["report_sid"]; ?></div>
                                        <div class="col-4 my-auto">PID : <?php echo $row["user_username"]; ?></div>
                                        <div class="col-4"><?php echo date("d-m-Y", strtotime($row["report_timestamp"])) ?></div>
                                        <div class="col-8 text-left">Name : <?php echo $row["user_name"]; ?></div>
                                        <div class="col-4"><?php echo  date("h:i A", strtotime($row["report_timestamp"]));  ?></div>
                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <button type="button" class="btn btn-success" onclick="location.href='fill_report.php?report=<?php echo $row['report_sid']; ?>'"><i class="fa fa-pencil-square-o"></i> Fill Results</button>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            <?php
            } else {
            ?>

                <form method="POST">
                    <div class="row">
                        <?php
                        if (isset($_GET["report"])) {
                        ?>

                            <?php
                            // $report_id = get_num("report");
                            // $lab_id = $login_lab["laboratory_id"];
                            // $report_select = "SELECT * FROM `reports` WHERE `report_id` = '$report_id' AND `report_laboratory` = '$lab_id'";
                            // $res = mysqli_query($con, $report_select);
                            // if (mysqli_num?_rows($res) == 1) {

                            //     $report_row = mysqli_fetch_assoc($res);
                            ?>


                            <input type="hidden" name="report_id" value="<?php echo $report_row["report_id"]; ?>">

                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <?php
                                            $patient_id = $report_row["report_patient"];
                                            $q = "SELECT * FROM `users` WHERE `user_id` = '$patient_id'";
                                            $res = mysqli_query($con, $q);
                                            $user = mysqli_fetch_assoc($res);
                                            ?>
                                            <div class="col-4">
                                                Patient id : <?php echo $user["user_username"]; ?>
                                            </div>
                                            <div class="col-4 text-center">
                                                Age/Gender : <?php $diff = date_diff(date_create(date("d-m-Y", time())), date_create(date("d-m-Y", strtotime($user["user_dob"]))));
                                                                echo $diff->format("%y") . " / " . strtoupper($user["user_gender"][0]); ?>
                                            </div>
                                            <div class="col-4">
                                                Report Date : <?php echo date("d-m-Y", strtotime($report_row["report_timestamp"])) . " &nbsp; " . date("h:i A", strtotime($report_row["report_timestamp"])); ?>
                                            </div>
                                            <div class="col-8 mt-3">
                                                Patient Name : <?php echo $user["user_name"]; ?>
                                            </div>
                                            <div class="col-4 mt-3">
                                                Report id : <?php echo $report_row["report_sid"]; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $report_details = json_decode($report_row["report_details"], true);
                            $report_templates = array();
                            $report_groups = array();
                            $report_units = array();
                            foreach ($report_details as $k => $v) {
                                if ($v["type"] == "template") {
                                    $report_templates[] = $v["id"];
                                    foreach ($v["element"] as $kk => $vv) {
                                        if ($vv["type"] == "unit") {
                                            $report_units[] = $vv["id"];
                                        }
                                        if ($vv["type"] == "group") {
                                            $report_groups[] = $vv["id"];
                                            foreach ($vv["unit"] as $uni) {
                                                $report_units[] = $uni["id"];
                                            }
                                            // $report_units = array_merge($report_units, $vv["unit"]);
                                        }
                                    }
                                }
                                if ($v["type"] == "group") {
                                    $report_groups[] = $v["id"];
                                    foreach ($v["unit"] as $uni) {
                                        $report_units[] = $uni["id"];
                                    }
                                }
                            }

                            // Gethering Templates From Database.

                            $report_templates_bulk = array();
                            if (count($report_templates) > 0) {
                                $report_templates_bulk_ids = implode(", ", $report_templates);
                                $template_select = "SELECT `template_id`, `template_text` FROM `templates` WHERE `template_id` IN ($report_templates_bulk_ids)";
                                $template_res = mysqli_query($con, $template_select);
                                while ($t = mysqli_fetch_assoc($template_res)) {
                                    $report_templates_bulk[$t["template_id"]] = $t;
                                }
                            }

                            $report_groups_bulk = array();
                            if (count($report_groups) > 0) {
                                $report_groups_bulk_ids = implode(", ", $report_groups);
                                $group_select = "SELECT `group_id`, `group_dtext` FROM `groups` WHERE `group_id` IN ($report_groups_bulk_ids)";
                                $group_res = mysqli_query($con, $group_select);
                                while ($t = mysqli_fetch_assoc($group_res)) {
                                    $report_groups_bulk[$t["group_id"]] = $t;
                                }
                            }

                            $report_units_bulk = array();
                            if (count($report_units) > 0) {
                                $report_units_bulk_ids = implode(", ", $report_units);
                                $unit_select = "SELECT * FROM `units` WHERE `unit_id` IN ($report_units_bulk_ids)";
                                $unit_res = mysqli_query($con, $unit_select);
                                while ($t = mysqli_fetch_assoc($unit_res)) {
                                    $report_units_bulk[$t["unit_id"]] = $t;
                                }
                            }
                            ?>
                            <?php
                            foreach ($report_details as $query) {
                                if ($query["type"] == "template") {
                                    $template_id = $query["id"];
                            ?>
                                    <div class="col-12 pt-3 pb-3">
                                        <div class="row">
                                            <div class="col-12 text-center">
                                                <h5 class="font-weight-bold"><u><?php echo $report_templates_bulk[$query["id"]]["template_text"]; ?></u></h5>
                                            </div>
                                            <div class="col-12 mt-3">
                                                <div class="row">
                                                    <div class="col-4">Perameter</div>
                                                    <div class="col-8 text-center">
                                                        <div class="row">
                                                            <div class="col-3">Option</div>
                                                            <div class="col-3">Result</div>
                                                            <div class="col-2">Unit</div>
                                                            <div class="col-4">Normal Range</div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <?php
                                            foreach ($query["element"] as $ele) {
                                                if ($ele["type"] == "unit") {
                                                    $this_unit = $report_units_bulk[$ele["id"]];
                                            ?>
                                                    <div class="col-12 mt-3">
                                                        <div class="row">
                                                            <div class="col-4"><?php echo $this_unit["unit_dtext"];  ?></div>
                                                            <div class="col-8 text-center">
                                                                <div class="row">
                                                                    <div class="col-3">
                                                                        <?php
                                                                        $options = json_decode($this_unit["unit_option"], true);
                                                                        $desabled = "";
                                                                        if (count($options) == 0) {
                                                                            $desabled = "disabled";
                                                                        }
                                                                        ?>
                                                                        <select class="form-control form-control-sm unit-option " <?php echo $desabled; ?>>
                                                                            <option value="">--select--</option>
                                                                            <?php
                                                                            foreach ($options as $op) {
                                                                            ?>

                                                                                <option><?php echo $op; ?></option>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-3">
                                                                        <input type="text" name="report[template][<?php echo $template_id; ?>][unit][<?php echo $this_unit["unit_id"]; ?>]" class="form-control form-control-sm unit-value" placeholder="Result" value="<?php echo $ele["value"]; ?>">
                                                                    </div>
                                                                    <div class="col-2">
                                                                        <?php echo $this_unit["unit_symbol"]; ?>
                                                                    </div>
                                                                    <div class="col-4">
                                                                        <?php
                                                                        $range = json_decode($this_unit["unit_range"], true);
                                                                        echo unit_html_view($range, $this_unit["unit_symbol"]);
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php
                                                }
                                                if ($ele["type"] == "group") {
                                                    $group_id = $ele["id"];
                                                ?>
                                                    <div class="col-12 mt-5">
                                                        <h6 class="font-weight-bold"><u><?php echo $report_groups_bulk[$ele["id"]]["group_dtext"]; ?></u></h6>
                                                    </div>
                                                    <?php
                                                    foreach ($ele["unit"] as $uni) {
                                                        $this_unit = $report_units_bulk[$uni["id"]];
                                                    ?>
                                                        <div class="col-12 mt-3">
                                                            <div class="row">
                                                                <div class="col-4 pl-5"><?php echo $this_unit["unit_dtext"];  ?></div>
                                                                <div class="col-8 text-center">
                                                                    <div class="row">
                                                                        <div class="col-3">
                                                                            <?php
                                                                            $options = json_decode($this_unit["unit_option"], true);
                                                                            $desabled = "";
                                                                            if (count($options) == 0) {
                                                                                $desabled = "disabled";
                                                                            }
                                                                            ?>
                                                                            <select class="form-control form-control-sm unit-option" <?php echo $desabled; ?>>
                                                                                <option value="">--select--</option>
                                                                                <?php
                                                                                foreach ($options as $op) {
                                                                                ?>

                                                                                    <option><?php echo $op; ?></option>
                                                                                <?php
                                                                                }
                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-3">
                                                                            <input type="text" name="report[template][<?php echo $template_id; ?>][group][<?php echo $group_id; ?>][unit][<?php echo $this_unit["unit_id"]; ?>]" class="form-control form-control-sm unit-value" placeholder="Result" value="<?php echo $uni["value"]; ?>">
                                                                        </div>
                                                                        <div class="col-2">
                                                                            <?php echo $this_unit["unit_symbol"]; ?>
                                                                        </div>
                                                                        <div class="col-4">
                                                                            <?php
                                                                            $range = json_decode($this_unit["unit_range"], true);
                                                                            echo unit_html_view($range, $this_unit["unit_symbol"]);
                                                                            ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php
                                                    }
                                                    ?>
                                            <?php
                                                }
                                            }
                                            ?>

                                        </div>
                                    </div>
                                <?php
                                }
                                if ($query["type"] == "group") {
                                    $group_id = $query["id"];
                                ?>
                                    <div class="col-12 pt-3 pb-3">
                                        <div class="row">
                                            <div class="col-12 text-center">
                                                <h5 class="font-weight-bold"><u><?php echo $report_groups_bulk[$query["id"]]["group_dtext"]; ?></u></h5>
                                            </div>
                                            <div class="col-12 mt-3">
                                                <div class="row">
                                                    <div class="col-4">Perameter</div>
                                                    <div class="col-8 text-center">
                                                        <div class="row">
                                                            <div class="col-3">Option</div>
                                                            <div class="col-3">Result</div>
                                                            <div class="col-2">Unit</div>
                                                            <div class="col-4">Normal Range</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                            foreach ($query["unit"] as $uni) {
                                                $this_unit = $report_units_bulk[$uni["id"]];
                                            ?>
                                                <div class="col-12 mt-3">
                                                    <div class="row">
                                                        <div class="col-4"><?php echo $this_unit["unit_dtext"];  ?></div>
                                                        <div class="col-8 text-center">
                                                            <div class="row">
                                                                <div class="col-3">
                                                                    <?php
                                                                    $options = json_decode($this_unit["unit_option"], true);
                                                                    $desabled = "";
                                                                    if (count($options) == 0) {
                                                                        $desabled = "disabled";
                                                                    }
                                                                    ?>
                                                                    <select class="form-control form-control-sm unit-option" <?php echo $desabled; ?>>
                                                                        <option value="">--select--</option>
                                                                        <?php
                                                                        foreach ($options as $op) {
                                                                        ?>

                                                                            <option><?php echo $op; ?></option>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                                <div class="col-3">
                                                                    <input type="text" name="report[group][<?php echo $group_id; ?>][unit][<?php echo $this_unit["unit_id"]; ?>]" class="form-control form-control-sm unit-value" placeholder="Result" value="<?php echo $uni["value"]; ?>">
                                                                </div>
                                                                <div class="col-2">
                                                                    <?php echo $this_unit["unit_symbol"]; ?>
                                                                </div>
                                                                <div class="col-4">
                                                                    <?php
                                                                    $range = json_decode($this_unit["unit_range"], true);
                                                                    echo unit_html_view($range, $this_unit["unit_symbol"]);
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                            <?php
                                }
                            }
                            ?>
                            <div class="col-12 text-center mt-4">
                                <button type="submit" name="save" class="btn btn-warning mr-3"> <i class="fa fa-asterisk"></i> Save as Draft</button>
                                <button type="submit" name="post" class="btn btn-success ml-3 mr-3"><i class="fa fa-paper-plane"></i> Post Report</button>
                                <button type="submit" name="print" class="btn btn-success mr-3"><i class="fa fa-print"></i> Post & Print</button>
                            </div>
                            <?php
                            // } else {
                            ?>
                            <!-- No -->
                            <?php
                            // }
                            ?>
                        <?php
                        } else {
                        ?>
                            <?php
                            $lab_id = $login_lab["laboratory_id"];
                            $report_select = "SELECT * FROM `reports`, `users` WHERE `report_laboratory` = '$lab_id' AND `report_patient` = `user_id` AND ( `report_status` = '1' OR `report_status` = '2' ) ORDER BY `report_id` DESC";
                            $res = mysqli_query($con, $report_select);
                            if (mysqli_num_rows($res) > 0) {
                                while ($row = mysqli_fetch_assoc($res)) {
                            ?>
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-12 mb-4">
                                        <div class="card">
                                            <div class="card-header text-center">
                                                <div class="row">
                                                    <div class="col-4 text-left my-auto">Report id : <?php echo $row["report_sid"]; ?></div>
                                                    <div class="col-4 my-auto">PID : <?php echo $row["user_username"]; ?></div>
                                                    <div class="col-4"><?php echo date("d-m-Y", strtotime($row["report_timestamp"])) ?></div>
                                                    <div class="col-8 text-left">Name : <?php echo $row["user_name"]; ?></div>
                                                    <div class="col-4"><?php echo  date("h:i A", strtotime($row["report_timestamp"]));  ?></div>
                                                </div>
                                            </div>
                                            <div class="card-footer text-right">
                                                <button type="button" class="btn btn-success" onclick="location.href='fill_report.php?report=<?php echo $row['report_sid']; ?>'"><i class="fa fa-pencil-square-o"></i> Fill Results</button>
                                                <button type="button" class="btn btn-danger ml-3" onclick="location.href='fill_report.php?discard=<?php echo $row['report_sid']; ?>'"><i class="fa fa-trash"></i> Discard</button>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                            } else {
                                ?>
                                <div class="col-12 pt-5  mt-4 text-center">
                                    <h3>No Record Found</h3>
                                </div>
                            <?php
                            }
                            ?>
                        <?php
                        }
                        ?>
                    </div>
                </form>
                <script>
                    $(document).ready(function() {
                        $(".unit-option").change(function() {
                            $(this).parent().parent().find(".unit-value").val($(this).val());
                            console.log($(this).val());
                        });
                    })
                </script>
            <?php
            }
            ?>
        </div>
    </div>
</main>
<?php include("footer.php"); ?>