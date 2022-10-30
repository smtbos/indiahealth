<?php

include("config.php");

if (isset($_GET["a"])) {
    $id = get_num("a");
    mysqli_query($con, "UPDATE `laboratorys` SET `laboratory_license` = '1' WHERE `laboratory_id` = '$id'");
    $_SESSION["smsg"][] = "Laboratory License Activated";
    header("location:laboratorys.php");
    exit();
}

if (isset($_GET["d"])) {
    $id = get_num("d");
    mysqli_query($con, "UPDATE `laboratorys` SET `laboratory_license` = '0' WHERE `laboratory_id` = '$id'");
    $_SESSION["smsg"][] = "Laboratory License Deactivated";
    header("location:laboratorys.php");
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
        $trr = mysqli_query($con, "SELECT * FROM `laboratorys` WHERE `laboratory_id` != '$id' AND `laboratory_user` = '$uid'");
        if (mysqli_num_rows($trr) == 1) {
            $flag = false;
            $_SESSION["amsg"][] = "User Already Registerd With Another Laboratory";
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

        $q = "SELECT * FROM `laboratorys` WHERE `laboratory_id` = '$id'";
        $re = mysqli_query($con, $q);
        if (mysqli_num_rows($re) == 1) {
            $ro = mysqli_fetch_assoc($re);
            if ($ro["laboratory_user"] != $uid) {
                echo "okj";
                $tuid = $ro["laboratory_user"];
                mysqli_query($con, "UPDATE `users` SET `user_laboratory` = '0' WHERE `user_id` = '$tuid'");
            }
        }
        $q = "UPDATE `laboratorys` SET `laboratory_user` = '$uid', `laboratory_name` = '$name', `laboratory_mobile` = '$mobile', `laboratory_email` = '$email', `laboratory_address` = '$address', `laboratory_pincode` = '$pincode', `laboratory_license` = '$license', `laboratory_status` = '$status' WHERE `laboratory_id` = '$id'";
        if (mysqli_query($con, $q)) {
            mysqli_query($con, "UPDATE `users` SET `user_laboratory` = '1' WHERE `user_id` = '$uid'");
            $_SESSION["smsg"][] = "Laboratory Updated Successfully";
            header("location:laboratorys.php");
        } else {
            $_SESSION["amsg"][] = "Error";
            header("location:laboratorys.php?edit=" . $id);
        }
    } else {
        header("location:laboratorys.php?edit=" . $id);
    }
    print_r($_SESSION);

    exit();
}

if (isset($_GET["view"])) {
    $labid = get_num("view");
    $q = "SELECT * FROM `laboratorys`, `users` WHERE `laboratory_id` = '$labid' AND `laboratory_user` = `user_id`";
    $res = mysqli_query($con, $q);
    if (mysqli_num_rows($res) == 1) {
        $laboratory  = mysqli_fetch_assoc($res);
    } else {
        $_SESSION["amsg"][] = "Invalid id";
    }
}

if (isset($_GET["edit"])) {
    $labid = get_num("edit");
    $q = "SELECT * FROM `laboratorys`, `users` WHERE `laboratory_id` = '$labid' AND `laboratory_user` = `user_id`";
    $res = mysqli_query($con, $q);
    if (mysqli_num_rows($res) == 1) {
        $laboratory  = mysqli_fetch_assoc($res);
    } else {
        $_SESSION["amsg"][] = "Invalid id";
    }
}

?>
<?php include("header.php"); ?>
<main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 pt-4">
    <div class="row">
        <?php
        if (isset($_GET["view"]) && isset($laboratory)) {
        ?>
            <div class="col-12">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <i class="fa fa-eye"></i> View Laboratory
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
                                        <?php echo $laboratory["laboratory_name"]; ?>
                                    </div>
                                </div>
                                <div class="row pb-2">
                                    <div class="col-4">
                                        Mobile <span class="float-right">:</span>
                                    </div>
                                    <div class="col-8 font-weight-medium">
                                        <?php echo $laboratory["laboratory_mobile"]; ?>
                                    </div>
                                </div>
                                <div class="row pb-2">
                                    <div class="col-4">
                                        Email <span class="float-right">:</span>
                                    </div>
                                    <div class="col-8 font-weight-medium">
                                        <?php echo $laboratory["laboratory_email"]; ?>
                                    </div>
                                </div>
                                <div class="row pb-2">
                                    <div class="col-4">
                                        Pincode <span class="float-right">:</span>
                                    </div>
                                    <div class="col-8 font-weight-medium">
                                        <?php echo $laboratory["laboratory_pincode"]; ?>
                                    </div>
                                </div>
                                <div class="row pb-2">
                                    <div class="col-4">
                                        Address <span class="float-right">:</span>
                                    </div>
                                    <div class="col-8 font-weight-medium">
                                        <?php echo $laboratory["laboratory_address"]; ?>
                                    </div>
                                </div>
                                <div class="row pb-2">
                                    <div class="col-4">
                                        License <span class="float-right">:</span>
                                    </div>
                                    <div class="col-8 font-weight-medium">
                                        <?php echo active_deactive($laboratory["laboratory_license"], 1); ?>
                                    </div>
                                </div>
                                <div class="row pb-2">
                                    <div class="col-4">
                                        Status <span class="float-right">:</span>
                                    </div>
                                    <div class="col-8 font-weight-medium">
                                        <?php echo active_deactive($laboratory["laboratory_status"], 1); ?>
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
                                        <?php echo $laboratory["user_name"]; ?>
                                    </div>
                                </div>
                                <div class="row pb-2">
                                    <div class="col-4">
                                        Mobile <span class="float-right">:</span>
                                    </div>
                                    <div class="col-8 font-weight-medium">
                                        <?php echo $laboratory["user_mobile"]; ?>
                                    </div>
                                </div>
                                <div class="row pb-2">
                                    <div class="col-4">
                                        Email <span class="float-right">:</span>
                                    </div>
                                    <div class="col-8 font-weight-medium">
                                        <?php echo $laboratory["user_email"]; ?>
                                    </div>
                                </div>
                                <div class="row pb-2">
                                    <div class="col-4">
                                        Username <span class="float-right">:</span>
                                    </div>
                                    <div class="col-8 font-weight-medium">
                                        <?php echo $laboratory["user_username"]; ?>
                                    </div>
                                </div>
                                <div class="row pb-2">
                                    <div class="col-4">
                                        Status <span class="float-right">:</span>
                                    </div>
                                    <div class="col-8 font-weight-medium">
                                        <?php echo active_deactive($laboratory["user_status"], 1); ?>
                                    </div>
                                </div>
                                <div class="row pb-2">
                                    <div class="col-8 offset-4 font-weight-medium pt-3">
                                        <a href="users.php?view=<?php echo $laboratory["user_id"]; ?>"><button class="btn btn-sm btn-success"><i class="fa fa-eye"></i> View More</button></a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-success" type="button" onclick="location.href='<?php get_prev_link('laboratorys.php'); ?>'"><i class="fa fa-backward"></i> Back</button>
                        <button class="btn btn-warning ml-2" type="button" onclick="location.href='laboratorys.php?edit=<?php echo $laboratory['laboratory_id']; ?>'"><i class="fa fa-edit"></i> Edit</button>
                    </div>
                </div>
            </div>
        <?php
        } else if (isset($laboratory)) {
        ?>
            <div class="col-12">
                <form action="" method="POST">
                    <div class="card border-primary">
                        <div class="card-header bg-primary text-white">
                            <i class="fa fa-edit"></i> Edit Laboratory
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group row">
                                        <div class="col-4 mt-3">
                                            <label for="uid"><i class="fa fa-key"></i> Laboratory Person id</label>
                                            <input type="text" class="form-control" name="uid" id="uid" value="<?php echo $laboratory["user_username"]; ?>" required>
                                        </div>
                                        <div class="col-4 mt-3">
                                            <label for="uname"><i class="fa fa-user"></i> Laboratory Person Name</label>
                                            <input type="text" class="form-control" name="uname" id="uname" value="<?php echo $laboratory["user_name"]; ?>" readonly required>
                                        </div>
                                        <div class="col-4 mt-3">
                                            <label for="name"><i class="fa fa-user-md"></i> Laboratory Name</label>
                                            <input type="text" class="form-control" name="name" id="name" value="<?php echo $laboratory["laboratory_name"]; ?>" required>
                                        </div>
                                        <div class="col-4 mt-3">
                                            <label for="mobile"><i class="fa fa-phone"></i> Laboratory Mobile</label>
                                            <input type="text" class="form-control" name="mobile" id="mobile" value="<?php echo $laboratory["laboratory_mobile"]; ?>" required>
                                        </div>
                                        <div class="col-8 mt-3">
                                            <label for="email"><i class="fa fa-envelope"></i> Laboratory Email</label>
                                            <input type="text" class="form-control" name="email" id="email" value="<?php echo $laboratory["laboratory_email"]; ?>" required>
                                        </div>
                                        <div class="col-4 mt-3">
                                            <label for="pincode"><i class="fa fa-map-marker"></i> Laboratory Pincode</label>
                                            <input type="text" class="form-control" name="pincode" id="pincode" value="<?php echo $laboratory["laboratory_pincode"]; ?>" required>
                                        </div>
                                        <div class="col-8 mt-3">
                                            <label for="address"><i class="fa fa-map-marker"></i> Laboratory Adress</label>
                                            <textarea class="form-control" name="address" id="address" required><?php echo $laboratory["laboratory_address"]; ?></textarea>
                                        </div>
                                        <div class="col-6 mt-3 text-center">
                                            <div class="row">
                                                <div class="col-12">
                                                    <label for="license"><i class="fa fa-question-circle"></i> License</label>
                                                </div>
                                                <div class="col-12">
                                                    <div class="custom-control custom-switch ">
                                                        <input type="checkbox" class="custom-control-input" id="license" name="license" <?php me_check($laboratory["laboratory_license"]); ?>>
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
                                                        <input type="checkbox" class="custom-control-input" id="status" name="status" <?php me_check($laboratory["laboratory_status"]); ?>>
                                                        <label class="custom-control-label" for="status"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $laboratory["laboratory_id"]; ?>">
                        <div class="card-footer">
                            <button type="submit" name="update" class="btn btn-info mr-3" title="Update Laboratory (Alt + O)" accesskey="o"><i class="fa fa-check"></i> Update Laboratory</button>
                            <button type="reset" class="btn btn-warning mr-3" title="Reset (Alt + Z)" accesskey="z"><i class="fa fa-undo"></i> Reset</button>
                            <button class="btn btn-success" type="button" onclick="location.href='laboratorys.php'" title="Back to Laboratory Page (Alt + B)" accesskey="b"><i class="fa fa-backward"></i> Back</button>
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
                                lab: <?php echo $laboratory["laboratory_id"]; ?>,
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
                <table class="table w-100" id="laboratorys_table">
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
                        var table = $('#laboratorys_table').DataTable({
                            "lengthMenu": [10, 25, 50, 75, 100],
                            "processing": true,
                            "serverSide": true,
                            'serverMethod': 'post',
                            "ajax": {
                                url: "api.php",
                                "data": function(d) {
                                    return $.extend({}, d, {
                                        "laboratorys": 1,
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