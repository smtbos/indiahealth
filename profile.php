<?php
include("config.php");
if (!isset($login_user)) {
    header("location:signin.php");
}
if (isset($_POST["update"])) {

    $flag = true;

    $user_id = $_SESSION["user_id"];
    $cur_username = $login_user["user_username"];
    $cur_profile = $login_user["user_profile"];


    // $username = post_str("username");
    // if (empty($username)) {
    //     $flag = false;
    //     $_SESSION["amsg"][] = "Username Can not be Empty";
    // } else {
    //     $cur_username = mysqli_real_escape_string($con, $cur_username);
    //     $temp_username = mysqli_real_escape_string($con, $username);
    //     $res = mysqli_query($con, "SELECT * FROM `users` WHERE `user_username` = '$temp_username' AND `user_id` != '$user_id'");
    //     if (mysqli_num_rows($res) > 0) {
    //         $flag = false;
    //         $_SESSION["amsg"][] = "Username Not Available";
    //     }
    // }
    $profile = file_arr("profile");
    if ($profile["name"] != "" && $profile["size"] != 0) {
        $pro_details = pathinfo($profile["name"]);
        if ($pro_details["extension"] == "png" || $pro_details["extension"] == "jpg" || $pro_details["extension"] == "jpeg" || $pro_details["extension"] == "jfif") {
            $profile_name = $user_id . "_" . md5($pro_details["filename"] . rand(1000, 15000)) . "." . $pro_details["extension"];
            if (move_uploaded_file($profile["tmp_name"], $patient_profile_path . $profile_name)) {
                if ($pro_details["extension"] == "png") {
                    png_crop($patient_profile_path . $profile_name);
                } else if ($pro_details["extension"] == "jpg" || $pro_details["extension"] == "jprg" || $pro_details["extension"] == "jfif") {
                    jpeg_crop($patient_profile_path . $profile_name);
                }
                if ($cur_profile != "") {
                    unlink($patient_profile_path . $cur_profile);
                }
                $user_profile = $profile_name;
            }
        } else {
            $flag = false;
            $_SESSION["amsg"][] = "Invalid Image";
        }
    } else {
        $user_profile = $cur_profile;
        if (isset($_POST["remove"])) {
            unlink($patient_profile_path . $cur_profile);
            $user_profile = "";
        }
    }

    if ($flag) {
        $username = mysqli_real_escape_string($con, $username);
        $user_profile = mysqli_real_escape_string($con,  $user_profile);
        // if (mysqli_query($con, "UPDATE `users` SET `user_username` = '$username', `user_profile` = '$user_profile' WHERE `user_id` = '$user_id'")) {
        if (mysqli_query($con, "UPDATE `users` SET `user_profile` = '$user_profile' WHERE `user_id` = '$user_id'")) {
            $_SESSION["smsg"][] = "Profile Updated";
            header("location:profile.php");
        } else {
            $_SESSION["amsg"][] = "Error";
            header("location:profile.php?edit");
        }
    } else {
        header("location:profile.php?edit");
    }
    exit();
}
?>
<?php include("header.php"); ?>
<main class="container">
    <div class="row">
        <div class="col-12 mt-5 pt-4">
            <?php
            if (isset($_GET["edit"])) {
            ?>
                <form method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-4 offset-md-0 col-sm-8 offset-sm-2">
                            <div class="row">
                                <div class="col-8 offset-2">
                                    <img src="<?php echo get_patient_profile_link($login_user["user_profile"]); ?>" class="img-fluid w-100" id="pro" style="border-radius: 50%;">
                                </div>
                            </div>
                            <?php
                            if (!empty($login_user["user_profile"])) {
                            ?>
                                
                                <div class="row pt-5">
                                    <div class="col-12 text-center">
                                        <button class="btn btn-success" type="button" id="remove"><i class="fa fa-times"></i> Remove Current<br>Profile Photo</button>
                                    </div>
                                </div>
                                <input type="checkbox" name="remove" id="remove_check" style="display: none;">
                            <?php
                            }
                            ?>
                        </div>
                        <div class="col-md-8 pt-sm-0 pt-3 my-auto">
                            <!-- <div class="row mb-3">
                                <div class="col-md-4 col-sm-6">
                                    <h4>Username:</h4>
                                </div>
                                <div class="col-md-8 col-sm-6">
                                    <input type="text" name="username" value="" class="form-control" required><?php // echo $login_user["user_username"]; 
                                                                                                                ?>
                                </div>
                            </div> -->
                            <div class="row mb-3">
                                <div class="col-md-4 col-sm-6">
                                    <h4>Profile Photo</h4>
                                </div>
                                <div class="col-md-8 col-sm-6">
                                    <div class="custom-file">
                                        <input type="file" name="profile" class="custom-file-input" id="validatedCustomFile" accept="image/png, image/jpeg">
                                        <label class="custom-file-label" for="validatedCustomFile">Choose file...</label>
                                        <div class="invalid-feedback">Example invalid custom file feedback</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>
                            $(document).ready(function() {
                                function showImage(src, target) {
                                    var fr = new FileReader();
                                    fr.onload = function(e) {
                                        target.src = this.result;
                                    };
                                    src.addEventListener("change", function() {
                                        fr.readAsDataURL(src.files[0]);
                                    });
                                }
                                var src = document.getElementById("validatedCustomFile");
                                var target = document.getElementById("pro");
                                showImage(src, target);
                            });
                        </script>
                        <div class="col-12 text-center mt-5">
                            <button type="submit" name="update" class="btn btn-success btn-lg"><i class="fa fa-check"></i> Update Profile</button>
                        </div>
                    </div>
                </form>
                <?php
                if (!empty($login_user["user_profile"])) {
                ?>
                    <script>
                        $(document).ready(function() {
                            var im = "<?php echo $patient_profile_path; ?>all.png";
                            $("#remove").click(function() {
                                $("#remove_check").prop("checked", true);
                                $("#pro").attr("src", im);
                            });
                        });
                    </script>
                <?php
                }
                ?>
            <?php
            } else {
            ?>
                <div class="row">
                    <div class="col-md-4 offset-md-0 col-sm-8 offset-sm-2">
                        <div class="row">
                            <div class="col-8 offset-2">
                                <img src="<?php echo get_patient_profile_link($login_user["user_profile"]); ?>" class="img-fluid w-100" style="border-radius: 50%;">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 pt-sm-0 pt-3 my-auto">
                        <div class="row mb-3">
                            <div class="col-md-4 col-sm-6">
                                <h4>Username:</h4>
                            </div>
                            <div class="col-md-8 col-sm-6">
                                <h4><?php echo $login_user["user_username"]; ?></h4>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 col-sm-6">
                                <h4>Name:</h4>
                            </div>
                            <div class="col-md-8 col-sm-6">
                                <h4><?php echo $login_user["user_name"]; ?></h4>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 col-sm-6">
                                <h4>Mobile:</h4>
                            </div>
                            <div class="col-md-8 col-sm-6">
                                <h4><?php echo $login_user["user_mobile"]; ?></h4>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 col-sm-6">
                                <h4>e-Mail:</h4>
                            </div>
                            <div class="col-md-8 col-sm-6">
                                <h4><?php echo $login_user["user_email"]; ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 text-center mt-5">
                        <a href="profile.php?edit"><button class="btn btn-info"><i class="fa fa-pencil"></i> Edit Profile</button></a>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</main>
<?php include("footer.php"); ?>