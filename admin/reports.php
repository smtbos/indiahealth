<?php
include("config.php");

if (isset($_GET["dis"])) {
    $dis = get_num("dis");
    if (mysqli_query($con, "UPDATE `reports` SET `report_status` = '0' WHERE `report_id` = '$dis'")) {
        $_SESSION["smsg"][] = "Report Discarded";
        header("location:reports.php");
    } else {
        $_SESSION["amsg"][] = "Error";
        header("location:reports.php");
    }
    exit();
}

if (isset($_GET["view"])) {
    $view = get_num("view");
    $qry = "SELECT `reports`.*, `users`.*, (SELECT `doctor_name` FROM `doctors` WHERE `doctor_id` = `report_doctor`) AS `doctor_name`, (SELECT `laboratory_name` FROM `laboratorys` WHERE `laboratory_id` = `report_laboratory`) AS `laboratory_name` FROM `reports`, `users` WHERE `report_id` = '$view' AND `report_patient` = `user_id`";
    $res = mysqli_query($con, $qry);
    if (mysqli_num_rows($res) == 1) {
        $report = mysqli_fetch_assoc($res);
    } else {
        $_SESSION["amsg"][] = "Report Details not FOUND";
        header("location:reports.php");
    }
}

if (isset($_GET["edit"]) && isset($_POST["update"])) {
    $rid = get_num("edit");
    $status = post_num("status");
    if (mysqli_query($con, "UPDATE `reports` SET `report_status` = '$status' WHERE `report_id` = '$rid'")) {
        $_SESSION["smsg"][] = "Report Update Successfull";
        header("location:reports.php");
    } else {
        $_SESSION["amsg"][] = "Error";
        header("location:reports.php");
    }
    exit();
}

if (isset($_GET["edit"])) {
    $view = get_num("edit");
    $qry = "SELECT `reports`.*, `users`.* FROM `reports`, `users` WHERE `report_id` = '$view' AND `report_patient` = `user_id`";
    $res = mysqli_query($con, $qry);
    if (mysqli_num_rows($res) == 1) {
        $report = mysqli_fetch_assoc($res);
    } else {
        $_SESSION["amsg"][] = "Report Details not FOUND";
        header("location:reports.php");
    }
}
?>
<?php include("header.php"); ?>
<main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 pt-4">
    <div class="row">
        <?php
        if (isset($_GET["view"]) && isset($view)) {
        ?>
            <div class="col-12">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <i class="fa fa-eye"></i> View Report
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <h5 class="font-weight-bold mb-3">Report Details</h5>
                                <div class="row pb-2">
                                    <div class="col-4">
                                        Report id <span class="float-right">:</span>
                                    </div>
                                    <div class="col-8 font-weight-medium">
                                        <?php echo $report["report_sid"]; ?>
                                    </div>
                                </div>
                                <div class="row pb-2">
                                    <div class="col-4">
                                        Status <span class="float-right">:</span>
                                    </div>
                                    <div class="col-8 font-weight-medium">
                                        <?php
                                        if ($report["report_status"] == 1) {
                                            echo "<span class='text-secondary'>Pending</span>";
                                        } else if ($report["report_status"] == 2) {
                                            echo "<span class='text-warning'>Proccesing</span>";
                                        } else if ($report["report_status"] == 3) {
                                            echo "<span class='text-success'>Posted</span>";
                                        } else {
                                            echo "<span class='text-danger'>Deleted</span>";
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="row pb-2">
                                    <div class="col-4">
                                        Report Details <span class="float-right">:</span>
                                    </div>
                                    <div class="col-8 font-weight-medium">
                                        <?php
                                        $rd = json_decode($report["report_details"], true);
                                        foreach ($rd as $r) {
                                            if ($r["type"] == "template") {
                                                $qr = "SELECT * FROM `templates` WHERE `template_id` = '" . $r["id"] . "'";
                                                $re = mysqli_query($con, $qr);
                                                $ro = mysqli_fetch_assoc($re);
                                                echo "T - " . $ro["template_text"] . "<br>";
                                            } else if ($r["type"] == "group") {
                                                $qr = "SELECT * FROM `groups` WHERE `group_id` = '" . $r["id"] . "'";
                                                $re = mysqli_query($con, $qr);
                                                $ro = mysqli_fetch_assoc($re);
                                                echo "G - " . $ro["group_dtext"] . "<br>";
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                                <?php
                                if ($report["doctor_name"] != NULL) {
                                ?>
                                    <div class="row pb-2">
                                        <div class="col-4">
                                            Doctor <span class="float-right">:</span>
                                        </div>
                                        <div class="col-8 font-weight-medium">
                                            <?php echo $report["doctor_name"]; ?>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                                <?php
                                if ($report["laboratory_name"] != NULL) {
                                ?>
                                    <div class="row pb-2">
                                        <div class="col-4">
                                            Laboratory <span class="float-right">:</span>
                                        </div>
                                        <div class="col-8 font-weight-medium">
                                            <?php echo $report["laboratory_name"]; ?>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                            <div class="col-6">
                                <h5 class="font-weight-bold mb-3">Patient Details</h5>
                                <div class="row pb-2">
                                    <div class="col-4">
                                        Name <span class="float-right">:</span>
                                    </div>
                                    <div class="col-8 font-weight-medium">
                                        <?php echo $report["user_name"]; ?>
                                    </div>
                                </div>
                                <div class="row pb-2">
                                    <div class="col-4">
                                        Mobile <span class="float-right">:</span>
                                    </div>
                                    <div class="col-8 font-weight-medium">
                                        <?php echo $report["user_mobile"]; ?>
                                    </div>
                                </div>
                                <div class="row pb-2">
                                    <div class="col-4">
                                        Email <span class="float-right">:</span>
                                    </div>
                                    <div class="col-8 font-weight-medium">
                                        <?php echo $report["user_email"]; ?>
                                    </div>
                                </div>
                                <div class="row pb-2">
                                    <div class="col-4">
                                        Username <span class="float-right">:</span>
                                    </div>
                                    <div class="col-8 font-weight-medium">
                                        <?php echo $report["user_username"]; ?>
                                    </div>
                                </div>
                                <div class="row pb-2">
                                    <div class="col-4">
                                        Status <span class="float-right">:</span>
                                    </div>
                                    <div class="col-8 font-weight-medium">
                                        <?php echo active_deactive($report["user_status"], 1); ?>
                                    </div>
                                </div>
                                <div class="row pb-2">
                                    <div class="col-8 offset-4 font-weight-medium pt-3">
                                        <a href="users.php?view=<?php echo $report["user_id"]; ?>"><button class="btn btn-sm btn-success"><i class="fa fa-eye"></i> View More</button></a>
                                    </div>
                                </div>
                            </div>
                            <?php
                            if ($report["report_doctor"] != NULL && $report["report_doctor"] != 0) {
                                $uid = $report["report_doctor"];
                                $qry = "SELECT * FROM `doctors` WHERE `doctor_id` = '$uid'";
                                $res = mysqli_query($con, $qry);
                                if (mysqli_num_rows($res) == 1) {
                                    $doctor = mysqli_fetch_assoc($res);
                            ?>
                                    <div class="col-12 mt-4">
                                        <div class="card">
                                            <div class="card-header pt-2 pb-2">
                                                <i class="fa fa-user-md"></i> Doctor Details
                                            </div>
                                            <div class="card-body">
                                                <div class="row pb-2">
                                                    <div class="col-3">
                                                        Name <span class="float-right">:</span>
                                                    </div>
                                                    <div class="col-9 font-weight-medium">
                                                        <?php echo $doctor["doctor_name"]; ?>
                                                    </div>
                                                </div>
                                                <div class="row pb-2">
                                                    <div class="col-3">
                                                        Mobile <span class="float-right">:</span>
                                                    </div>
                                                    <div class="col-9 font-weight-medium">
                                                        <?php echo $doctor["doctor_mobile"]; ?>
                                                    </div>
                                                </div>
                                                <div class="row pb-2">
                                                    <div class="col-3">
                                                        Email <span class="float-right">:</span>
                                                    </div>
                                                    <div class="col-9 font-weight-medium">
                                                        <?php echo $doctor["doctor_email"]; ?>
                                                    </div>
                                                </div>
                                                <div class="row pb-2">
                                                    <div class="col-3">
                                                        Pincode <span class="float-right">:</span>
                                                    </div>
                                                    <div class="col-9 font-weight-medium">
                                                        <?php echo $doctor["doctor_pincode"]; ?>
                                                    </div>
                                                </div>
                                                <div class="row pb-2">
                                                    <div class="col-3">
                                                        Address <span class="float-right">:</span>
                                                    </div>
                                                    <div class="col-9 font-weight-medium">
                                                        <?php echo $doctor["doctor_address"]; ?>
                                                    </div>
                                                </div>
                                                <div class="row pb-2">
                                                    <div class="col-9 offset-3 font-weight-medium">
                                                        <a href="doctors.php?view=<?php echo $laboratory["laboratory_id"]; ?>"><button class="btn btn-sm btn-success"><i class="fa fa-eye"></i> View More</button></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            }
                            ?>
                            <?php
                            if ($report["report_laboratory"] != NULL && $report["report_laboratory"] != 0) {
                                $uid = $report["report_laboratory"];
                                $qry = "SELECT * FROM `laboratorys` WHERE `laboratory_id` = '$uid'";
                                $res = mysqli_query($con, $qry);
                                if (mysqli_num_rows($res) == 1) {
                                    $laboratory = mysqli_fetch_assoc($res);
                            ?>
                                    <div class="col-12 mt-4">
                                        <div class="card">
                                            <div class="card-header pt-2 pb-2">
                                                <i class="fa fa-flask"></i> Laboratory Details
                                            </div>
                                            <div class="card-body">
                                                <div class="row pb-2">
                                                    <div class="col-3">
                                                        Name <span class="float-right">:</span>
                                                    </div>
                                                    <div class="col-9 font-weight-medium">
                                                        <?php echo $laboratory["laboratory_name"]; ?>
                                                    </div>
                                                </div>
                                                <div class="row pb-2">
                                                    <div class="col-3">
                                                        Mobile <span class="float-right">:</span>
                                                    </div>
                                                    <div class="col-9 font-weight-medium">
                                                        <?php echo $laboratory["laboratory_mobile"]; ?>
                                                    </div>
                                                </div>
                                                <div class="row pb-2">
                                                    <div class="col-3">
                                                        Email <span class="float-right">:</span>
                                                    </div>
                                                    <div class="col-9 font-weight-medium">
                                                        <?php echo $laboratory["laboratory_email"]; ?>
                                                    </div>
                                                </div>
                                                <div class="row pb-2">
                                                    <div class="col-3">
                                                        Pincode <span class="float-right">:</span>
                                                    </div>
                                                    <div class="col-9 font-weight-medium">
                                                        <?php echo $laboratory["laboratory_pincode"]; ?>
                                                    </div>
                                                </div>
                                                <div class="row pb-2">
                                                    <div class="col-3">
                                                        Address <span class="float-right">:</span>
                                                    </div>
                                                    <div class="col-9 font-weight-medium">
                                                        <?php echo $laboratory["laboratory_address"]; ?>
                                                    </div>
                                                </div>
                                                <div class="row pb-2">
                                                    <div class="col-9 offset-3 font-weight-medium">
                                                        <a href="laboratorys.php?view=<?php echo $laboratory["laboratory_id"]; ?>"><button class="btn btn-sm btn-success"><i class="fa fa-eye"></i> View More</button></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-success" type="button" onclick="location.href='<?php get_prev_link('reports.php'); ?>'"><i class="fa fa-backward"></i> Back</button>
                    </div>
                </div>
            </div>
        <?php
        } else if (isset($report)) {
        ?>
            <div class="col-12">
                <form action="" method="POST">
                    <div class="card border-primary">
                        <div class="card-header bg-primary text-white">
                            <i class="fa fa-edit"></i> Edit Doctor
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group row">
                                        <div class="col-4 mt-3">
                                            <label><i class="fa fa-key"></i> Report id</label>
                                            <input type="text" class="form-control" value="<?php echo $report["report_sid"]; ?>" readonly>
                                        </div>
                                        <div class="col-4 mt-3">
                                            <label><i class="fa fa-calendar"></i> Report Date</label>
                                            <input type="date" class="form-control" value="<?php echo date("Y-m-d", strtotime($report["report_timestamp"])) ?>" readonly>
                                        </div>
                                        <div class="col-4 mt-3">
                                            <label for="status"><i class="fa fa-question-circle"></i> Report Status</label>
                                            <select name="status" class="form-control" id="status">
                                                <option value="1" <?php is_selected("1", $report["report_status"]); ?>>Pending</option>
                                                <option value="2" <?php is_selected("2", $report["report_status"]); ?>>Proccesing</option>
                                                <option value="3" <?php is_selected("3", $report["report_status"]); ?>>Posted</option>
                                                <option value="0" <?php is_selected("0", $report["report_status"]); ?>>Discarded</option>
                                            </select>
                                        </div>
                                        <div class="col-4 mt-3">
                                            <label for="uname"><i class="fa fa-user"></i> Report Patient id</label>
                                            <input type="text" class="form-control" name="uname" id="uname" value="<?php echo $report["user_username"]; ?>" readonly>
                                        </div>
                                        <div class="col-8 mt-3">
                                            <label><i class="fa fa-user"></i> Report Patient Name</label>
                                            <input type="text" class="form-control" value="<?php echo $report["user_name"]; ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $doctor["doctor_id"]; ?>">
                        <div class="card-footer">
                            <button type="submit" name="update" class="btn btn-info mr-3"><i class="fa fa-check"></i> Update Report</button>
                            <button type="reset" class="btn btn-warning mr-3"><i class="fa fa-undo"></i> Reset</button>
                            <button class="btn btn-success" type="button" onclick="location.href='<?php get_prev_link('reports.php'); ?>'"><i class="fa fa-backward"></i> Back</button>
                        </div>
                    </div>
                </form>
            </div>
        <?php
        } else {
        ?>
            <div class="col-12  pt-5">
                <table class="table w-100" id="doctor_table">
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>Date</th>
                            <th>Pid</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Action</th>
                            <th>Alter</th>
                        </tr>
                    </thead>
                </table>
                <script>
                    $(document).ready(function() {
                        var table = $('#doctor_table').DataTable({
                            "lengthMenu": [10, 25, 50, 75, 100],
                            "processing": true,
                            "serverSide": true,
                            'serverMethod': 'post',
                            "ajax": {
                                url: "api.php",
                                "data": function(d) {
                                    return $.extend({}, d, {
                                        "reports": 1,
                                    });
                                }
                            },
                            columns: [{
                                    data: "id"
                                },
                                {
                                    data: "date"
                                },
                                {
                                    data: "pid"
                                },
                                {
                                    data: "patient"
                                },
                                {
                                    data: "status"
                                },
                                {
                                    data: "action"
                                },
                                {
                                    data: "alter"
                                },
                            ],
                            'columnDefs': [{
                                // 'targets': [2, 3],
                                'orderable': false,
                            }],
                            <?php
                            if (isset($_GET["patient"])) {
                            ?> "search": {
                                    "search": "<?php echo get_str("patient"); ?>"
                                },
                            <?php
                            }
                            ?>
                        });
                    });
                </script>
            </div>
        <?php
        }
        ?>
    </div>
</main>
<?php include("footer.php"); ?>