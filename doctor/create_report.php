<?php
include("config.php");


if (isset($_POST["post_query"])) {

    $flag = true;

    $lab_id = $login_doctor["doctor_id"];

    $pid = post_str("pid");
    $dob = post_str("dob");
    $name = post_str("name");
    $report = post_arr("report");
    if (count($report) == 0) {
        $_SESSION["amsg"][] = "Please Select Some Report Elements";
        header("location:create_report.php?pid=" . $pid . "&dob=" . $dob);
        exit();
    }
    foreach ($report as $k => $v) {
        $report[$k] = json_decode($v, true);
        if ($report[$k]["type"] == "template") {
            $id = $report[$k]["id"];
            $template_select = "SELECT `template_element` FROM `templates` WHERE `template_id` = '$id'";
            $res = mysqli_query($con, $template_select);
            $row = mysqli_fetch_assoc($res);
            $report[$k]["element"] = json_decode($row["template_element"], true);
            foreach ($report[$k]["element"] as $kk => $vv) {
                if ($vv["type"] == "unit") {
                    $report[$k]["element"][$kk]["value"] = "";
                    $report[$k]["element"][$kk]["msg"] = "";
                }
                if ($vv["type"] == "group") {
                    $id = $vv["id"];
                    $group_select = "SELECT `group_unit` FROM `groups` WHERE `group_id` = '$id'";
                    $res = mysqli_query($con, $group_select);
                    $row = mysqli_fetch_assoc($res);
                    $units = json_decode($row["group_unit"], true);
                    foreach ($units as $uid) {
                        $report[$k]["element"][$kk]["unit"][] = array(
                            "type" => "unit",
                            "id" => $uid,
                            "value" => "",
                            "msg" => "",
                        );
                    }
                }
            }
        } else if ($report[$k]["type"] == "group") {
            $id = $report[$k]["id"];
            $group_select = "SELECT `group_unit` FROM `groups` WHERE `group_id` = '$id'";
            $res = mysqli_query($con, $group_select);
            $row = mysqli_fetch_assoc($res);

            $units = json_decode($row["group_unit"], true);
            foreach ($units as $uid) {
                $report[$k]["unit"][] = array(
                    "type" => "unit",
                    "id" => $uid,
                    "value" => "",
                    "msg" => "",
                );
            }
        }
    }
    $qq = "SELECT * FROM `users` WHERE `user_username` = '$pid'";
    $res = mysqli_query($con, $qq);
    if (mysqli_num_rows($res) > 0) {
        $ro = mysqli_fetch_assoc($res);
        if ($ro["user_dob"] == $dob) {
            $pid = $ro["user_id"];
        } else {
            $flag = false;
            $_SESSION["amsg"][] = "Invalid User Details";
        }
    } else {
        $flag = false;
        $_SESSION["amsg"][] = "Invalid User Details";
    }
    if ($flag) {
        $report = mysqli_real_escape_string($con, json_encode($report));
        $auth = mysqli_real_escape_string($con, generate_password(rand(500, 700)));
        $report_insert = "INSERT INTO `reports`(`report_id`, `report_doctor`, `report_patient`, `report_details`, `report_auth`) VALUES (NULL, '$lab_id', '$pid', '$report', '$auth')";
        if (mysqli_query($con, $report_insert)) {
            $report_id = mysqli_insert_id($con);
            $report_sid = get_code_by_id($report_id, 7);
            mysqli_query($con, "UPDATE `reports` SET `report_sid` = '$report_sid' WHERE `report_id` = '$report_id'");
            $_SESSION["smsg"][] = "Report Added";
            $_SESSION["smsg"][] = "Report ID is " . $report_sid;
            header("location:index.php");
        } else {
			echo mysqli_error($con);
			exit();
            $_SESSION["amsg"][] = "Error";
            header("location:create_report.php");
        }
    } else {
        echo "invalid";
        exit();
        header("location:create_report.php");
    }
    /*
    $report = array(
        array(
            "type" => "template",
            "id" => 2,
            "element" => array(
                array(
                    "type" => "unit",
                    "id" => 5
                ),
                array(
                    "type" => "group",
                    "id" => 2,
                    "unit" => array(
                        6, 9, 10 // Unit
                    )
                ),
            )
        ),
        array(
            "type" => "group",
            "id" => 2,
            "unit" => array(
                6, 9, 10 // Unit
            )
        ),
        array(
            "id" => "unit",
            "value" => 5
        ),
    );*/
    exit();
}
?>
<?php include("header.php"); ?>
<main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 pt-4">
    <form method="POST" action="create_report.php">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-1 bg-primary text-white">
                        <h5>Patient</h5>
                    </div>
                    <?php
                    $pid_value = "";
                    $dob_value = "";
                    $name_value = "";
                    $patient_profile = get_patient_profile_link("");
                    $age_gender = "";
                    $disabled = "disabled";
                    $dis_none = "display: none;";
                    if (isset($_GET["pid"]) && isset($_GET["dob"])) {
                        $pid = mysqli_real_escape_string($con, get_str("pid"));
                        $dob = mysqli_real_escape_string($con, get_str("dob"));
                        $res = mysqli_query($con, "SELECT * FROM `users` WHERE `user_username` = '$pid' AND `user_dob` = '$dob' AND `user_status` = '1'");
                        if (mysqli_num_rows($res) == 1) {
                            $user_details = mysqli_fetch_assoc($res);
                            $pid_value = $user_details["user_username"];
                            $dob_value = $user_details["user_dob"];
                            $name_value = $user_details["user_name"];
                            $patient_profile = get_patient_profile_link($user_details["user_profile"]);
                            $age_gender = get_age_and_gender($user_details["user_dob"], $user_details["user_gender"]);
                            $disabled = "";
                            $dis_none = "";
                        }
                    }
                    ?>
                    <div class="card-body pt-4 pb-0">
                        <div class="form-group row">
                            <div class="col-sm-6 mt-2">
                                <label><i class="fa fa-key"></i> Patient ID Number:</label>
                                <input type="text" name="pid" id="pid" class="form-control" value="<?php echo $pid_value; ?>">
                            </div>
                            <div class="col-sm-6 mt-2">
                                <label><i class="fa fa-calendar"></i> Patient Date of Birth:</label>
                                <input type="date" name="dob" id="dob" class="form-control" value="<?php echo $dob_value ?>">
                            </div>
                            <div class=" offset-lg-4 col-lg-4 offset-sm-3 col-sm-6 col-12 pt-4">
                                <button type="button" id="fetch" class="btn btn-success btn-block"><i class="fa fa-search"></i> Fetch Patient Data</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-4" id="details_ele" style="<?php echo $dis_none; ?>">
                        <div class="form-group row">
                            <div class="col-sm-2 offset-sm-1">
                                <img src="<?php echo $patient_profile; ?>" class="img-fluid w-100" style="border-radius: 50%;" id="patient_profile">
                            </div>
                            <div class="col-sm-8 offset-sm-1">
                                <div class="row">
                                    <div class="col-12">
                                        <label><i class="fa fa-user"></i> Patient Name:</label>
                                        <input type="text" name="name" id="name" class="form-control" value="<?php echo $name_value;  ?>" readonly required>
                                    </div>
                                    <div class="col-12 mt-3">
                                        <label><i class="fa fa-user"></i> Patient Age & Gender:</label>
                                        <input type="text" id="age_gender" class="form-control" value="<?php echo $age_gender;  ?>" readonly required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-4 pt-0" id="report_ele" style="<?php echo $dis_none; ?>">
                <div class="card">
                    <div class="card-header pb-1 bg-primary text-white">
                        <h5><i class="fa fa-list"></i> Report Details</h5>
                    </div>
                    <div class="card-body pt-4">
                        <div class="accordion" id="accordionExample">
                            <div class="card">
                                <div class="card-header" id="headingOne">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Templates
                                        </button>
                                    </h2>
                                </div>

                                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <div class="row pl-4 pr-4">
                                            <?php
                                            $temp = "SELECT * FROM `templates` WHERE 1=1";
                                            $res = mysqli_query($con, $temp);
                                            while ($temp = mysqli_fetch_assoc($res)) {
                                            ?>
                                                <div class="col-md-6">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" name="report[]" id="template-<?php echo $temp["template_id"]; ?>" value='{"type":"template","id":"<?php echo $temp["template_id"]; ?>"}'>
                                                        <label class="custom-control-label" for="template-<?php echo $temp["template_id"]; ?>"><?php echo $temp["template_text"]; ?></label>
                                                    </div>
                                                </div>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="headingTwo">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            Groups
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <div class="row pl-4 pr-4">
                                            <?php
                                            $group = "SELECT * FROM `groups` WHERE 1=1";
                                            $res = mysqli_query($con, $group);
                                            while ($group = mysqli_fetch_assoc($res)) {
                                            ?>
                                                <div class="col-md-6">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" name="report[]" id="group-<?php echo $group["group_id"]; ?>" value='{"type":"group","id":"<?php echo $group["group_id"]; ?>"}'>
                                                        <label class="custom-control-label" for="group-<?php echo $group["group_id"]; ?>"><?php echo name_html_view($group["group_text"], $group["group_dtext"]);  ?></label>
                                                    </div>
                                                </div>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 pt-4 text-center">
                <button type="submit" name="post_query" id="post_query" class="btn btn-success mr-3" <?php echo $disabled; ?>><i class="fa fa-plus-circle"></i> Post Report Query</button>
            </div>
        </div>
    </form>
</main>
<script>
    $(document).ready(function() {
        $("#fetch").click(function() {
            var me = $(this);
            var my_pid = $("#pid").val();
            var my_dob = $("#dob").val();
            $(me).prop('disabled', true);
            $.ajax({
                method: 'POST',
                url: '<?php echo $lab_url."doctor/"; ?>api.php',
                data: {
                    get_info: 1,
                    pid: my_pid,
                    dob: my_dob
                },
                success: function(res) {
                    res = JSON.parse(res);
                    console.log(res);
                    if (res.status == 'success') {
                        $("#name").val(res.name);
                        $("#age_gender").val(res.age_gender);
                        $('#patient_profile').attr('src', res.image);
                        $("#details_ele").show();
                        $("#report_ele").show();
                        $("#post_query").prop('disabled', false);

                    } else {
                        $.confirm({
                            title: 'Failed',
                            content: res.reason,
                            buttons: {
                                ok: {
                                    text: 'OK',
                                    btnClass: 'btn-blue',
                                    keys: ['enter', 'shift'],
                                }
                            }
                        });
                        $("#details_ele").hide();
                        $("#report_ele").hide();
                        $("#post_query").prop('disabled', true);
                    }
                },
                complete: function(res) {
                    $(me).prop('disabled', false);
                }
            });
        });
    });
</script>
<?php include("footer.php"); ?>