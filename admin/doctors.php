<?php 

include("config.php"); 

if (isset($_GET["a"])) {
    $id = get_num("a");
    mysqli_query($con, "UPDATE `doctors` SET `doctor_license` = '1' WHERE `doctor_id` = '$id'");
    $_SESSION["smsg"][] = "Doctor License Activated";
    header("location:doctors.php");
    exit();
}

if (isset($_GET["d"])) {
    $id = get_num("d");
    mysqli_query($con, "UPDATE `doctors` SET `doctor_license` = '0' WHERE `doctor_id` = '$id'");
    $_SESSION["smsg"][] = "Doctor License Deactivated";
    header("location:doctors.php");
    exit();
}

if (isset($_POST["update"])) {

    $flag = true;

    $id = post_num("id");
    $uid = post_str("uid");
    $name = post_str("name");
    $mobile = post_str("mobile");
    $email = post_str("email");
    $pincode = post_str("pincode");
    $address = post_str("address");

    $tr  = mysqli_query($con, "SELECT * FROM `users` WHERE `user_username` = '$uid'");
    if (mysqli_num_rows($tr) == 1) {
        $trow = mysqli_fetch_assoc($tr);
        $uid = $trow["user_id"];
        $trr = mysqli_query($con, "SELECT * FROM `doctors` WHERE `doctor_id` != '$id' AND `doctor_user` = '$uid'");
        if (mysqli_num_rows($trr) == 1) {
            $flag = false;
            $_SESSION["amsg"][] = "User Already Registerd With Another Doctor Account";
        }
    } else {
        $flag = false;
        $_SESSION["amsg"][] = "Invalid User id";
    }

    if (empty($name)) {
        $flag = false;
        $_SESSION["amsg"][] = "Name Can not Be Empty";
    }

    if (empty($mobile)) {
        $flag = false;
        $_SESSION["amsg"][] = "Invalid Mobile Number";
    }

    if (empty($email)) {
        $flag = false;
        $_SESSION["amsg"][] = "Invalid email id";
    }

    $license = intval(isset($_POST["license"]));
    $status = intval(isset($_POST["status"]));

    if ($flag) {
        $name = mysqli_real_escape_string($con, $name);
        $mobile = mysqli_real_escape_string($con, $mobile);
        $email = mysqli_real_escape_string($con, $email);
        $pincode = mysqli_real_escape_string($con, $pincode);
        $address = mysqli_real_escape_string($con, $address);

        $q = "SELECT * FROM `doctors` WHERE `doctor_id` = '$id'";
        $re = mysqli_query($con, $q);
        if (mysqli_num_rows($re) == 1) {
            $ro = mysqli_fetch_assoc($re);
            if ($ro["doctor_user"] != $uid) {
                echo "okj";
                $tuid = $ro["doctor_user"];
                mysqli_query($con, "UPDATE `users` SET `user_doctor` = '0' WHERE `user_id` = '$tuid'");
            }
        }
        $q = "UPDATE `doctors` SET `doctor_user` = '$uid', `doctor_name` = '$name', `doctor_mobile` = '$mobile', `doctor_email` = '$email', `doctor_address` = '$address', `doctor_pincode` = '$pincode', `doctor_license` = '$license', `doctor_status` = '$status' WHERE `doctor_id` = '$id'";
        if (mysqli_query($con, $q)) {
            mysqli_query($con, "UPDATE `users` SET `user_doctor` = '1' WHERE `user_id` = '$uid'");
            $_SESSION["smsg"][] = "Doctor Updated Successfully";
            header("location:doctors.php");
        } else {
            $_SESSION["amsg"][] = "Error";
            header("location:doctors.php?edit=" . $id);
        }
    } else {
        header("location:doctors.php?edit=" . $id);
    }
    exit();
}

if (isset($_GET["view"])) {
    $docid = get_num("view");
    $q = "SELECT * FROM `doctors`, `users` WHERE `doctor_id` = '$docid' AND `doctor_user` = `user_id`";
    $res = mysqli_query($con, $q);
    if (mysqli_num_rows($res) == 1) {
        $doctor  = mysqli_fetch_assoc($res);
    } else {
        $_SESSION["amsg"][] = "Invalid id";
    }
}

if (isset($_GET["edit"])) {
    $docid = get_num("edit");
    $q = "SELECT * FROM `doctors`, `users` WHERE `doctor_id` = '$docid' AND `doctor_user` = `user_id`";
    $res = mysqli_query($con, $q);
    if (mysqli_num_rows($res) == 1) {
        $doctor  = mysqli_fetch_assoc($res);
    } else {
        $_SESSION["amsg"][] = "Invalid id";
    }
}

?>
<?php include("header.php"); ?>
<main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 pt-4">
    <div class="row">
        <?php
        if (isset($_GET["view"]) && isset($doctor)) {
        ?>
            <div class="col-12">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <i class="fa fa-eye"></i> View Doctor
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <h5 class="font-weight-bold mb-3">Doctor Details</h5>
                                <div class="row pb-2">
                                    <div class="col-4">
                                        Name <span class="float-right">:</span>
                                    </div>
                                    <div class="col-8 font-weight-medium">
                                        <?php echo $doctor["doctor_name"]; ?>
                                    </div>
                                </div>
                                <div class="row pb-2">
                                    <div class="col-4">
                                        Mobile <span class="float-right">:</span>
                                    </div>
                                    <div class="col-8 font-weight-medium">
                                        <?php echo $doctor["doctor_mobile"]; ?>
                                    </div>
                                </div>
                                <div class="row pb-2">
                                    <div class="col-4">
                                        Email <span class="float-right">:</span>
                                    </div>
                                    <div class="col-8 font-weight-medium">
                                        <?php echo $doctor["doctor_email"]; ?>
                                    </div>
                                </div>
                                <div class="row pb-2">
                                    <div class="col-4">
                                        Pincode <span class="float-right">:</span>
                                    </div>
                                    <div class="col-8 font-weight-medium">
                                        <?php echo $doctor["doctor_pincode"]; ?>
                                    </div>
                                </div>
                                <div class="row pb-2">
                                    <div class="col-4">
                                        Address <span class="float-right">:</span>
                                    </div>
                                    <div class="col-8 font-weight-medium">
                                        <?php echo $doctor["doctor_address"]; ?>
                                    </div>
                                </div>
                                <div class="row pb-2">
                                    <div class="col-4">
                                        License <span class="float-right">:</span>
                                    </div>
                                    <div class="col-8 font-weight-medium">
                                        <?php echo active_deactive($doctor["doctor_license"], 1); ?>
                                    </div>
                                </div>
                                <div class="row pb-2">
                                    <div class="col-4">
                                        Status <span class="float-right">:</span>
                                    </div>
                                    <div class="col-8 font-weight-medium">
                                        <?php echo active_deactive($doctor["doctor_status"], 1); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <h5 class="font-weight-bold mb-3">Person (Authority) Details</h5>
                                <div class="row pb-2">
                                    <div class="col-4">
                                        Name <span class="float-right">:</span>
                                    </div>
                                    <div class="col-8 font-weight-medium">
                                        <?php echo $doctor["user_name"]; ?>
                                    </div>
                                </div>
                                <div class="row pb-2">
                                    <div class="col-4">
                                        Mobile <span class="float-right">:</span>
                                    </div>
                                    <div class="col-8 font-weight-medium">
                                        <?php echo $doctor["user_mobile"]; ?>
                                    </div>
                                </div>
                                <div class="row pb-2">
                                    <div class="col-4">
                                        Email <span class="float-right">:</span>
                                    </div>
                                    <div class="col-8 font-weight-medium">
                                        <?php echo $doctor["user_email"]; ?>
                                    </div>
                                </div>
                                <div class="row pb-2">
                                    <div class="col-4">
                                        Username <span class="float-right">:</span>
                                    </div>
                                    <div class="col-8 font-weight-medium">
                                        <?php echo $doctor["user_username"]; ?>
                                    </div>
                                </div>
                                <div class="row pb-2">
                                    <div class="col-4">
                                        Status <span class="float-right">:</span>
                                    </div>
                                    <div class="col-8 font-weight-medium">
                                        <?php echo active_deactive($doctor["user_status"], 1); ?>
                                    </div>
                                </div>
                                <div class="row pb-2">
                                    <div class="col-8 offset-4 font-weight-medium pt-3">
                                        <a href="users.php?view=<?php echo $doctor["user_id"]; ?>"><button class="btn btn-sm btn-success"><i class="fa fa-eye"></i> View More</button></a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-success" type="button" onclick="location.href='<?php get_prev_link('doctors.php'); ?>'"><i class="fa fa-backward"></i> Back</button>
                        <button class="btn btn-warning ml-2" type="button" onclick="location.href='doctors.php?edit=<?php echo $doctor['doctor_id']; ?>'"><i class="fa fa-edit"></i> Edit</button>
                    </div>
                </div>
            </div>
        <?php
        } else if (isset($doctor)) {
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
                                            <label for="uid"><i class="fa fa-key"></i> Doctor Person id</label>
                                            <input type="text" class="form-control" name="uid" id="uid" value="<?php echo $doctor["user_username"]; ?>" required>
                                        </div>
                                        <div class="col-4 mt-3">
                                            <label for="uname"><i class="fa fa-user"></i> Doctor Person Name</label>
                                            <input type="text" class="form-control" name="uname" id="uname" value="<?php echo $doctor["user_name"]; ?>" readonly required>
                                        </div>
                                        <div class="col-4 mt-3">
                                            <label for="name"><i class="fa fa-user-md"></i> Doctor Name</label>
                                            <input type="text" class="form-control" name="name" id="name" value="<?php echo $doctor["doctor_name"]; ?>" required>
                                        </div>
                                        <div class="col-4 mt-3">
                                            <label for="mobile"><i class="fa fa-phone"></i> Doctor Mobile</label>
                                            <input type="text" class="form-control" name="mobile" id="mobile" value="<?php echo $doctor["doctor_mobile"]; ?>" required>
                                        </div>
                                        <div class="col-8 mt-3">
                                            <label for="email"><i class="fa fa-envelope"></i> Doctor Email</label>
                                            <input type="text" class="form-control" name="email" id="email" value="<?php echo $doctor["doctor_email"]; ?>" required>
                                        </div>
                                        <div class="col-4 mt-3">
                                            <label for="pincode"><i class="fa fa-map-marker"></i> Doctor Pincode</label>
                                            <input type="text" class="form-control" name="pincode" id="pincode" value="<?php echo $doctor["doctor_pincode"]; ?>" required>
                                        </div>
                                        <div class="col-8 mt-3">
                                            <label for="address"><i class="fa fa-map-marker"></i> Doctor Adress</label>
                                            <textarea class="form-control" name="address" id="address" required><?php echo $doctor["doctor_address"]; ?></textarea>
                                        </div>
                                        <div class="col-6 mt-3 text-center">
                                            <div class="row">
                                                <div class="col-12">
                                                    <label for="license"><i class="fa fa-question-circle"></i> License</label>
                                                </div>
                                                <div class="col-12">
                                                    <div class="custom-control custom-switch ">
                                                        <input type="checkbox" class="custom-control-input" id="license" name="license" <?php me_check($doctor["doctor_license"]); ?>>
                                                        <label class="custom-control-label" for="license"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 mt-3 text-center">
                                            <div class="row">
                                                <div class="col-12">
                                                    <label for="status"><i class="fa fa-question-circle"></i> Status</label>
                                                </div>
                                                <div class="col-12">
                                                    <div class="custom-control custom-switch ">
                                                        <input type="checkbox" class="custom-control-input" id="status" name="status" <?php me_check($doctor["doctor_status"]); ?>>
                                                        <label class="custom-control-label" for="status"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $doctor["doctor_id"]; ?>">
                        <div class="card-footer">
                            <button type="submit" name="update" class="btn btn-info mr-3"><i class="fa fa-check"></i> Update Doctor</button>
                            <button type="reset" class="btn btn-warning mr-3"><i class="fa fa-undo"></i> Reset</button>
                            <button class="btn btn-success" type="button" onclick="location.href='<?php get_prev_link('doctors.php'); ?>'"><i class="fa fa-backward"></i> Back</button>
                        </div>
                    </div>
                </form>
            </div>
            <script>
                $(document).ready(function() {
                    $("#uid").focusin(function() {
                        $(this).data('val', $(this).val());
                    });
                    $("#uid").change(function() {
                        var ui = $(this).val();
                        $.ajax({
                            url: 'api.php',
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                uname: 1,
                                doc: <?php echo $doctor["doctor_id"]; ?>,
                                uid: ui,
                            },
                            success: function(res) {
                                if (res.status == "success") {
                                    $("#uname").val(res.uname);
                                } else {
                                    $("#uid").val($("#uid").data('val'));
                                    $("#uid").focus();
                                    $.alert({
                                        title: 'Invalid Action',
                                        content: res.reason,
                                        buttons: {
                                            cancel: {
                                                text: 'OK',
                                                btnClass: 'btn-blue',
                                                keys: ['enter', 'shift'],
                                            }
                                        }
                                    });
                                }
                            }
                        })
                    });
                });
            </script>
        <?php
        } else {
        ?>
            <div class="col-12  pt-5">
                <table class="table w-100" id="doctor_table">
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Action</th>
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
                                        "doctors": 1,
                                    });
                                }
                            },
                            columns: [{
                                    data: "id"
                                },
                                {
                                    data: "name"
                                },
                                {
                                    data: "contact"
                                },
                                {
                                    data: "action"
                                },
                            ],
                            'columnDefs': [{
                                'targets': [2, 3],
                                'orderable': false,
                            }]
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