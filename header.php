<?php
$page = basename($_SERVER["SCRIPT_NAME"], '.php');
function page_active($test)
{
    global $page;
    if ($test == $page) {
        echo "active";
    }
}
function page_active_arr($tests)
{
    global $page;
    if (in_array($page, $tests)) {
        echo "active";
    }
}
if (isset($login_user)) {
    if ($login_user["user_last"] != "") {
        $user_id = $_SESSION["user_id"];
        mysqli_query($con, "UPDATE `users` SET `user_last` = '' WHERE `user_id` = '$user_id'");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $website_name; ?></title>
    <link rel="icon" href="./public/ihm/logo_noback.png">

    <link rel="stylesheet" href="./public/css/bootstrap.min.css">
    <link rel="stylesheet" href="./public/css/jquery-confirm.min.css">
    <link rel="stylesheet" href="./public/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="./public/fa/css/font-awesome.min.css" />
    <link rel="stylesheet" href="./public/css/style.css" />
    <link rel="stylesheet" href="./public/home.css" />

    <script src="./public/js/jquery.min.js"></script>
    <script src="./public/js/bootstrap.bundle.min.js"></script>
    <script src="./public/js/jquery-confirm.min.js"></script>
    <script src="./public/js/jquery-ui.min.js"></script>
    <script src="./public/js/jquery.dataTables.min.js"></script>
</head>

<body class="font-weight-bold">
    <nav class="navbar navbar-expand-lg bg-light navbar-light sticky-top " style="border-bottom: 1px solid #cacbcc !important;">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php"><?php // echo $website_name; 
                                                        ?> <img src="<?php echo $lab_url . "public/logo2.png" ?>" height="40"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidebarMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="sidebarMenu">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item <?php page_active("index"); ?>" style="font-size: 16px;">
                        <a class="nav-link" href="index.php"><i class="fa fa-home"></i> Home</a>
                    </li>
                    <?php
                    if (isset($login_user) && 0) {
                    ?>
                        <li class="nav-item <?php page_active("my_report"); ?>" style="font-size: 16px;">
                            <a class="nav-link" href="my_report.php"><i class="fa fa-list-ul"></i> My Reports</a>
                        </li>
                    <?php
                    }
                    ?>
                    <li class="nav-item <?php page_active("find"); ?>" style="font-size: 16px;">
                        <a class="nav-link" href="find.php"><i class="fa fa-search"></i> Find</a>
                    </li>
                    <li class="nav-item <?php page_active("about"); ?>" style="font-size: 16px;">
                        <a class="nav-link" href="about.php"><i class="fa fa-info-circle"></i> About as</a>
                    </li>
                    <li class="nav-item <?php page_active("contact"); ?>" style="font-size: 16px;">
                        <a class="nav-link" href="contact.php"><i class="fa fa-phone"></i> Contact as</a>
                    </li>
                    <li class="nav-item dropdown <?php page_active_arr(array("faq", "query")); ?>">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span><i class="fa fa-question-circle"></i>&nbsp; Help</span>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink1">
                            <a class="dropdown-item font-weight-bold d-none" href="report_details.php"><i class="fa fa-square"></i> Report Details</a>
                            <a class="dropdown-item font-weight-bold <?php page_active("faq"); ?>" href="faq.php"><i class="fa fa-question-circle"></i> FAQs</a>
                            <?php
                            if (isset($login_user)) {
                            ?>
                                <a class="dropdown-item font-weight-bold <?php page_active("query"); ?>" href="query.php"><i class="fa fa-question-circle"></i> Querys</a>
                            <?php
                            }
                            ?>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="navbar-collapse">
                <ul class="navbar-nav ml-auto">
                    <?php
                    if (isset($login_user)) {
                    ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="text-dark"><i class="fa fa-user-circle"></i>&nbsp; <?php echo $login_user["user_name"]; ?></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item font-weight-bold disabled" href="#"><i class="fa fa-hashtag"></i> <?php echo $_SESSION["user_username"]; ?></a>
                                <a class="dropdown-item <?php page_active("profile"); ?>" href="profile.php"><i class="fa fa-user"></i> Profile</a>
                                <a class="dropdown-item <?php page_active("my_report"); ?>" href="my_report.php"><i class="fa fa-list-ul"></i> My Reports</a></a>
                                <a class="dropdown-item <?php page_active("my_health"); ?>" href="my_health.php"><i class="fa fa-heartbeat"></i> My Health</a></a>
                                <a class="dropdown-item <?php page_active("index"); ?>" href="index.php"><i class="fa fa-home"></i> Home</a>
                                <?php
                                if ($login_user["user_admin"]) {
                                ?>
                                    <a class="dropdown-item" href="admin/"><i class="fa fa-user"></i> Goto Admin</a>
                                <?php
                                }
                                ?>
                                <?php
                                if ($login_user["user_doctor"]) {
                                ?>
                                    <a class="dropdown-item" href="doctor/"><i class="fa fa-user-md"></i> Goto Doctor</a>
                                <?php
                                }
                                ?>
                                <?php
                                if ($login_user["user_laboratory"]) {
                                ?>
                                    <a class="dropdown-item" href="laboratory/"><i class="fa fa-flask"></i> Goto Laboratory</a>
                                <?php
                                }
                                ?>
                                <?php
                                if ($login_user["user_helper"]) {
                                ?>
                                    <a class="dropdown-item" href="helper/"><i class="fa fa-user-o"></i> Goto Helper</a>
                                <?php
                                }
                                ?>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item <?php page_active("change_password"); ?>" href="change_password.php"><i class="fa fa-lock"></i> Chnage Password</a></a>
                                <a class="dropdown-item" href="signout.php"><i class="fa fa-sign-out"></i> Sign Out</a>
                            </div>
                        </li>
                    <?php
                    } else {
                    ?>
                        <li class="nav-item  <?php page_active("signin"); ?>" style="font-size: 16px;">
                            <a class="nav-link" href="signin.php"><i class="fa fa-sign-in"></i> Sign in</a>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>