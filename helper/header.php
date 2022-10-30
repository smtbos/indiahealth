<?php
$page = basename($_SERVER["SCRIPT_NAME"], '.php');
function page_active($test)
{
    global $page;
    if ($test == $page) {
        echo "active";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $lab_name; ?>Helper</title>
    <link rel="icon" href="./public/ihm/logo_noback.png">

    <link rel="stylesheet" href="../public/css/bootstrap.min.css">
    <link rel="stylesheet" href="../public/css/jquery-confirm.min.css">
    <link rel="stylesheet" href="../public/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../public/fa/css/font-awesome.min.css" />
    <link rel="stylesheet" href="../public/css/style.css" />
    
    <script src="../public/js/jquery.min.js"></script>
    <script src="../public/js/bootstrap.bundle.min.js"></script>
    <script src="../public/js/jquery-confirm.min.js"></script>
    <script src="../public/js/jquery-ui.min.js"></script>
    <script src="../public/js/jquery.dataTables.min.js"></script>
</head>

<body class="font-weight-bold">
    <nav class="navbar navbar-expand-md bg-dark navbar-dark sticky-top ">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php"><?php echo $lab_name; ?>Helper</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidebarMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-collapse">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="text-white"><i class="fa fa-user-circle"></i>&nbsp; <?php echo $login_user["user_name"]; ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item font-weight-bold disabled" href="#"><i class="fa fa-hashtag"></i> <?php echo $_SESSION["user_username"]; ?></a>
                            <a class="dropdown-item" href="../profile.php"><i class="fa fa-user"></i> Profile</a>
                            <a class="dropdown-item" href="../"><i class="fa fa-home"></i> Home</a>
                            <?php
                            if ($login_user["user_admin"]) {
                            ?>
                                <a class="dropdown-item" href="../admin/"><i class="fa fa-user"></i> Goto Admin</a>
                            <?php
                            }
                            ?>
                            <?php
                            if ($login_user["user_doctor"]) {
                            ?>
                                <a class="dropdown-item" href="../doctor/"><i class="fa fa-user-md"></i> Goto Doctor</a>
                            <?php
                            }
                            ?>
                            <?php
                            if ($login_user["user_laboratory"]) {
                            ?>
                                <a class="dropdown-item" href="../laboratory/"><i class="fa fa-flask"></i> Goto Laboratory</a>
                            <?php
                            }
                            ?>
                            <?php
                            if ($login_user["user_helper"]) {
                            ?>
                                <a class="dropdown-item active" href="index.php"><i class="fa fa-user-o"></i> Goto Helper</a>
                            <?php
                            }
                            ?>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="../signout.php"><i class="fa fa-sign-out"></i> Sign Out</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid overflow-hidden pl-1 pr-1">
        <div class="row pl-2 pr-2">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item ">
                            <a class="nav-link <?php page_active("index"); ?>" href="index.php">
                                <i class="fa fa-tachometer"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link  <?php page_active("reports"); ?>" href="reports.php">
                                <i class="fa fa-list"></i> Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link  <?php page_active("users"); ?>" href="users.php">
                                <i class="fa fa-users"></i> Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link  <?php page_active("doctors"); ?>" href="doctors.php">
                                <i class="fa fa-user-md"></i> Doctors
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link  <?php page_active("laboratorys"); ?>" href="laboratorys.php">
                                <i class="fa fa-flask"></i> Laboratorys
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php page_active("querys"); ?>" href="querys.php">
                                <i class="fa fa-inbox"></i> Querys
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>