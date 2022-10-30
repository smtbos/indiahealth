<?php
include("config.php");
$user_id = $login_user["user_id"];
if (isset($_GET["report"])) {
    $report_id = get_num("report");
    $res = mysqli_query($con, "SELECT * FROM `reports`, `users` WHERE `report_id` = '$report_id' AND `report_patient` = '$user_id' AND `report_patient` = `user_id`");
    if (mysqli_num_rows($res) == 1) {
        $row = mysqli_fetch_assoc($res);
        report_render($report_id, true, array(array("my_report.php", "Back to Reports")));
        exit();
    } else {
        $_SESSION["amsg"][] = "Invalid Report ID";
        header("location:my_report.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report id</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css">
    <style>
        * {
            font-weight: bold !important;
        }

        * {
            -webkit-print-color-adjust: exact !important;
            color-adjust: exact !important;
        }

        pre {
            border: none !important;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            .mydivs,
            .mydivs * {
                visibility: visible;
            }

            .mydivs {
                page-break-inside: avoid;
            }
        }

        .mydivs {
            page-break-inside: avoid;

        }
    </style>
</head>

<body class="p-3 font-weight-bold pt-5">
    <main class="container">
        <div class="row mydivs pt-4 pb-4 mb-3 pl-2 pr-2" style="border: 1px solid black; border-radius: 5px;">
            <div class="col-12">
                <div class="row">
                    <?php
                    $patient_id = $row["report_patient"];
                    $q = "SELECT * FROM `users` WHERE `user_id` = '$patient_id'";
                    $res = mysqli_query($con, $q);
                    $user = mysqli_fetch_assoc($res);
                    ?>
                    <div class="col-4">
                        Patient id : <?php echo $user["user_id"]; ?>
                    </div>
                    <div class="col-4 text-center">
                        Age/Gender : <?php $diff = date_diff(date_create(date("d-m-Y", time())), date_create(date("d-m-Y", strtotime($user["user_dob"]))));
                                        echo $diff->format("%y") . " / " . strtoupper($user["user_gender"][0]); ?>
                    </div>
                    <div class="col-4">
                        Report Date : <?php echo date("d-m-Y", strtotime($row["report_timestamp"])) . " &nbsp; " . date("h:i A", strtotime($row["report_timestamp"])); ?>
                    </div>
                    <div class="col-8 mt-3">
                        Patient Name : <?php echo $user["user_name"]; ?>
                    </div>
                    <div class="col-4 mt-3">
                        Report id : <?php echo $row["report_id"]; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $report_details = json_decode($row["report_details"], true);
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
                <div class="row mydivs">
                    <div class="col-12 pt-3 pb-3">
                        <div class="row">
                            <div class="col-12 text-center">
                                <h5 class="font-weight-bold"><u><?php echo $report_templates_bulk[$query["id"]]["template_text"]; ?></u></h5>
                            </div>
                            <div class="col-12 mt-3">
                                <div class="row">
                                    <div class="col-5">Perameter</div>
                                    <div class="col-7 text-center">
                                        <div class="row">
                                            <div class="col-4">Result</div>
                                            <div class="col-2">Unit</div>
                                            <div class="col-6">Normal Range</div>
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
                                            <div class="col-5"><?php echo $this_unit["unit_dtext"];  ?></div>
                                            <div class="col-7 text-center">
                                                <div class="row">
                                                    <div class="col-4">
                                                        <?php
                                                        if (!empty($ele["value"])) {
                                                            echo $ele["value"];
                                                        } else {
                                                            echo "-";
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="col-2">
                                                        <?php
                                                        if (!empty($this_unit["unit_symbol"])) {
                                                            echo $this_unit["unit_symbol"];
                                                        } else {
                                                            echo "-";
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="col-6">
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
                                                <div class="col-5 pl-5"><?php echo $this_unit["unit_dtext"];  ?></div>
                                                <div class="col-7 text-center">
                                                    <div class="row">
                                                        <div class="col-4">
                                                            <?php
                                                            if (!empty($uni["value"])) {
                                                                echo $uni["value"];
                                                            } else {
                                                                echo "-";
                                                            } ?>
                                                        </div>
                                                        <div class="col-2">
                                                            <?php echo $this_unit["unit_symbol"]; ?>
                                                        </div>
                                                        <div class="col-6">
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
                </div>
            <?php
            }
            if ($query["type"] == "group") {
                $group_id = $query["id"];
            ?>
                <div class="row mydivs">
                    <div class="col-12 pt-3 pb-3">
                        <div class="row">
                            <div class="col-12 text-center">
                                <h5 class="font-weight-bold"><u><?php echo $report_groups_bulk[$query["id"]]["group_dtext"]; ?></u></h5>
                            </div>
                            <div class="col-12 mt-3">
                                <div class="row">
                                    <div class="col-5">Perameter</div>
                                    <div class="col-7 text-center">
                                        <div class="row">
                                            <div class="col-4">Result</div>
                                            <div class="col-2">Unit</div>
                                            <div class="col-6">Normal Range</div>
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
                                        <div class="col-5"><?php echo $this_unit["unit_dtext"];  ?></div>
                                        <div class="col-7 text-center">
                                            <div class="row">
                                                <div class="col-4">
                                                    <?php if (!empty($uni["value"])) {
                                                        echo $uni["value"];
                                                    } else {
                                                        echo "-";
                                                    } ?>
                                                </div>
                                                <div class="col-2">
                                                    <?php echo $this_unit["unit_symbol"]; ?>
                                                </div>
                                                <div class="col-6">
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
                </div>
        <?php
            }
        }
        ?>
        <div class="row">
            <div class="col-12 text-center p-3 mt-3 bg-white">
                <button type="button" class="btn btn-lg btn-success" onclick="window.print()">Print Report</button><br>
                <?php
                $fl = false;
                if (isset($_SERVER["HTTP_REFERER"])) {
                    if (strpos($_SERVER["HTTP_REFERER"], "index")) {
                        $fl = true;
                    }
                }
                if ($fl) {
                ?>
                    <button type="button" class="btn btn-secondary mt-3" onclick="location.href='index.php'">Back to Patient</button>
                <?php
                } else {
                ?>
                    <button type="button" class="btn btn-secondary mt-3" onclick="location.href='my_report.php'">Back to My Reports</button>
                <?php
                }
                ?>
                <!-- <button type="button" class="btn btn-secondary mt-3" onclick="location.href='index.php'">Back to Patient</button> -->
            </div>
        </div>
    </main>
</body>

</html>