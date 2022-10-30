<?php
include("../config.php");
if (!isset($login_user)) {
    $_SESSION["amsg"][] = "Please Sign in or Sign up Before To Register as Laboratory";
    header("location:../signin.php");
}
if (isset($_POST["apply"])) {

    $flag = true;

    $name = post_str("name");
    if (empty($name)) {
        $flag = false;
        $_SESSION["amsg"][] = "Name Can Not Be Empty";
    }

    $mobile = post_str("mobile");
    if (empty($mobile)) {
        $flag = false;
        $_SESSION["amsg"][] = "Mobile Can Not Be Empty";
    }

    $email = post_str("email");
    if (empty($email)) {
        $flag = false;
        $_SESSION["amsg"][] = "Email Can Not Be Empty";
    }

    $address = post_str("address");
    if (empty($address)) {
        $flag = false;
        $_SESSION["amsg"][] = "Address Can Not Be Empty";
    }

    $pincode = post_str("pincode");
    if (empty($pincode) || (!is_numeric($pincode)) || strlen($pincode) != 6) {
        $flag = false;
        $_SESSION["amsg"][] = "Invalid Pincode";
    }

    if ($flag) {

        $user_id = $_SESSION["user_id"];
        $name = mysqli_real_escape_string($con, $name);
        $mobile = mysqli_real_escape_string($con, $mobile);
        $email = mysqli_real_escape_string($con, $email);
        $address = mysqli_real_escape_string($con, $address);
        $pincode = mysqli_real_escape_string($con, $pincode); 

        $lab_insert = "INSERT INTO `laboratorys`(`laboratory_id`, `laboratory_user`, `laboratory_name`, `laboratory_mobile`, `laboratory_email`, `laboratory_address`, `laboratory_pincode`) VALUES (NULL, '$user_id', '$name', '$mobile', '$email', '$address', '$pincode')";
        if (mysqli_query($con, $lab_insert)) {
            $lab_id = mysqli_insert_id($con);
            $_SESSION["smsg"][] = "Your Application for Laboratory is Submited";
            $_SESSION["smsg"][] = "We Will Prosess Your Request";
            mysqli_query($con, "UPDATE `users` SET `user_laboratory` = '1' WHERE `user_id` = '$user_id'");
            
            $message =  mysqli_real_escape_string($con, "Laboratory id is : " . $lab_id);
            
            $q = "INSERT INTO `querys`(`query_id`, `query_user`, `query_handel`, `query_subject`, `query_details`, `query_solved`, `query_status`) VALUES (NULL, '$user_id', '0', 'Apply For Laboratory Registration', '$message', '0', '1')";
            mysqli_query($con, $q);
            header("location:register.php");
        } else {
            $_SESSION["amsg"][] = "Error";
            header("location:register.php");
        }
    } else {
        header("location:register.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $website_name; ?>Laboratory</title>
    <link rel="icon" href="<?php echo $lab_url . "public/ihm/logo_noback.png"; ?>">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <style>
        .feather {
            width: 16px;
            height: 16px;
            vertical-align: text-bottom;
        }

        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            /* Behind the navbar */
            padding: 48px 0 0;
            /* Height of navbar */
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
        }

        @media (max-width: 767.98px) {
            .sidebar {
                top: 2rem;
            }
        }

        .sidebar-sticky {
            position: relative;
            top: 0;
            height: calc(100vh - 48px);
            padding-top: .5rem;
            overflow-x: hidden;
            overflow-y: auto;
            /* Scrollable contents if viewport is shorter than content. */
        }

        .sidebar .nav-link {
            font-weight: 500;
            color: #333;
        }

        .sidebar .nav-link .feather {
            margin-right: 4px;
            color: #727272;
        }

        .sidebar .nav-link.active {
            color: #007bff;
        }

        .sidebar .nav-link:hover .feather,
        .sidebar .nav-link.active .feather {
            color: inherit;
        }

        footer {
            position: relative;
            bottom: 0px;
            right: 0px;
        }

        main {
            padding-bottom: 5rem;
        }

        .action_btn {
            font-size: 0.7rem;
        }
    </style>
</head>

<body>
    <main class="container-fluid">
        <div class="row font-weight-bold">
            <div class="col-lg-4 offset-lg-4 col-md-6 offset-md-3 mt-5">
                <h3 class="text-center font-weight-bold"><u><?php echo $website_name; ?></u></h3>
                <h4 class="text-center font-weight-bold mb-4">Registration For Laboratory</h4>
                <?php
                $user_id = $_SESSION["user_id"];
                $qry = "SELECT * FROM `laboratorys` WHERE `laboratory_user` = '$user_id'";
                $res = mysqli_query($con, $qry);
                if ($lab = mysqli_fetch_assoc($res)) {
                ?>
                    <div class="row mb-2">
                        <div class="col-12">
                            Laboratory Name:
                        </div>
                        <div class="col-12">
                            <h5><?php echo $lab["laboratory_name"]; ?></h5>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-12">
                            Laboratory Mobile:
                        </div>
                        <div class="col-12">
                            <h5><?php echo $lab["laboratory_mobile"]; ?></h5>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-12">
                            Laboratory e-Mail:
                        </div>
                        <div class="col-12">
                            <h5><?php echo $lab["laboratory_email"]; ?></h5>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-12">
                            Laboratory Pincode:
                        </div>
                        <div class="col-12">
                            <h5><?php echo $lab["laboratory_pincode"]; ?></h5>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-12">
                            Laboratory Address:
                        </div>
                        <div class="col-12">
                            <h5><?php echo $lab["laboratory_address"]; ?></h5>
                        </div>
                    </div>
                    <?php
                    if ($lab["laboratory_license"] == 0 && $lab["laboratory_status"] == 0) {
                    ?>
                        <h5 class="mb-4">Your Application for Laboratory is Under Proccess</h5>
                        <button type="button" onclick="location.href='../'" class="btn btn-success">Goto Home</button>
                    <?php
                    } else if ($lab["laboratory_license"] == 0 || $lab["laboratory_status"] == 0) {
                    ?>
                        <h5 class="mb-4">Your Laboratory Account is Deactivated by Authority, Contact as For More Details.</h5>
                        <button type="button" onclick="location.href='../contact.php'" class="btn btn-success">Contact as</button>
                        <button type="button" onclick="location.href='../'" class="btn btn-success">Goto Home</button>
                    <?php
                    } else {
                    ?>
                        <h5 class="mb-4">Your Laboratory Account is Active,</h5>
                        <button type="button" onclick="location.href='index.php'" class="btn btn-success">Goto Laboratory</button>
                        <button type="button" onclick="location.href='../'" class="btn btn-success">Goto Home</button>
                    <?php
                    }
                    ?>
                <?php
                } else {
                ?>
                    <form method="POST">

                        <div class="form-group">
                            <label>Laboratory Name:</label>
                            <div>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Laboratory Mobile:</label>
                            <div>
                                <input type="number" name="mobile" class="form-control" value="<?php echo $login_user["user_mobile"]; ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Laboratory e-Mail:</label>
                            <div>
                                <input type="email" name="email" class="form-control" value="<?php echo $login_user["user_email"]; ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Laboratory Pincode:</label>
                            <div>
                                <input type="number" name="pincode" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Laboratory Address:</label>
                            <div>
                                <textarea class="form-control" name="address" rows="3" required></textarea>
                            </div>
                        </div>
                        <input type="submit" class="btn btn-success" name="apply" value="Apply For Laboratory">
                        <button type="button" onclick="location.href='../'" class="btn btn-success">Goto Home</button>
                    </form>
                <?php
                }
                ?>
            </div>
        </div>
    </main>
</body>

</html>
<?php include("../message.php"); ?>