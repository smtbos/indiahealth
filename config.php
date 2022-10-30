<?php
session_start();
date_default_timezone_set("Asia/Kolkata");
$con = mysqli_connect("localhost", "root", "", "indiahealth") or die("Can't Connect");

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    $lab_url = "https://" . $_SERVER['HTTP_HOST'] . "/";
} else {
    $lab_url = "http://" . $_SERVER['HTTP_HOST'] . "/";
}

if ($_SERVER["HTTP_HOST"] == "localhost" || $_SERVER["HTTP_HOST"] == "localhost:8080") {
    $folder = "indiahealth/";
    $lab_url = "http://" . $_SERVER["HTTP_HOST"] . "/" . $folder;
}

/*
Settings
*/
define("MAX_USER_ID_LENGTH", 5);
define("MAX_REPORT_ID_LENGTH", 7);


/*
Making Error Free
*/
if (!function_exists('boolval')) {
    function boolval($var)
    {
        return !!$var;
    }
}

/*
Defining Project Details
*/
$website_url = $lab_url;
$lab_name = "India Health ";
$website_name = $lab_name;

if (isset($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];
    $res = mysqli_query($con, "SELECT * FROM `users` WHERE `user_id` = '$user_id'");
    $login_user = mysqli_fetch_assoc($res);
    if ($login_user["user_status"] != 1) {
        header("location:signout.php");
    }
}

$patient_profile_path = "public/patient/";


/* Basic Function Setup */
function post_str($key)
{
    if (isset($_POST[$key])) {
        return $_POST[$key];
    } else {
        return "";
    }
}

function post_num($key)
{
    if (isset($_POST[$key])) {
        return intval($_POST[$key]);
    } else {
        return 0;
    }
}

function post_float($key)
{
    if (isset($_POST[$key])) {
        return floatval($_POST[$key]);
    } else {
        return 0;
    }
}

function post_number($key)
{
    if (isset($_POST[$key])) {
        if (floor(floatval($_POST[$key])) == floatval($_POST[$key])) {
            return intval($_POST[$key]);
        } else {
            return floatval($_POST[$key]);
        }
    } else {
        return 0;
    }
}

function post_arr($key)
{
    if (isset($_POST[$key])) {
        if (is_array($_POST[$key])) {
            return $_POST[$key];
        } else {
            return array();
        }
    } else {
        return array();
    }
}

function get_str($key)
{
    if (isset($_GET[$key])) {
        return $_GET[$key];
    } else {
        return "";
    }
}

function get_num($key)
{
    if (isset($_GET[$key])) {
        return intval($_GET[$key]);
    } else {
        return 0;
    }
}
function get_float($key)
{
    if (isset($_GET[$key])) {
        return floatval($_GET[$key]);
    } else {
        return 0;
    }
}

function get_number($key)
{
    if (isset($_GET[$key])) {
        if (floor(floatval($_GET[$key])) == floatval($_GET[$key])) {
            return intval($_GET[$key]);
        } else {
            return floatval($_GET[$key]);
        }
    } else {
        return 0;
    }
}

function get_arr($key)
{
    if (isset($_GET[$key])) {
        if (is_array($_GET[$key])) {
            return $_GET[$key];
        } else {
            return array();
        }
    } else {
        return array();
    }
}

function file_arr($key)
{
    if (isset($_FILES[$key])) {
        return $_FILES[$key];
    } else {
        return false;
    }
}


// Loading Some Common Moduals
$globle_units = array();
$res = mysqli_query($con, "SELECT `unit_id`, `unit_dtext` FROM `units`");
while ($u = mysqli_fetch_array($res)) {
    $globle_units[$u["unit_id"]] = $u["unit_dtext"];
}

$globle_groups = array();
$res = mysqli_query($con, "SELECT `group_id`, `group_dtext` FROM `groups`");
while ($u = mysqli_fetch_array($res)) {
    $globle_groups[$u["group_id"]] = $u["group_dtext"];
}


// Get Login User id
function get_uid()
{
    $uid = 0;
    if (isset($_SESSION["user_id"])) {
        $uid = $_SESSION["user_id"];
    }
    return $uid;
}

function is_selected($val, $test)
{
    if ($val == $test) {
        echo " selected";
    }
}

// Get Person Name with Alis
function get_good_name($name, $gender)
{
    if ($gender == "male") {
        return "Mr. " . $name;
    } else if ($gender == "female") {
        return "Ms. " . $name;
    } else {
        return $name;
    }
}

// Register Mail Activity to Database
function register_mail_actvity($html, $created_by)
{
    if (false) {
        global $con;
        $html = mysqli_real_escape_string($con, $html);
        $uid = get_uid();
        $q = "INSERT INTO `mail`(`mail_id`, `mail_user`, `mail_from`, `mail_text`) VALUES (NULL, '$uid', '$created_by','$html')";
        mysqli_query($con, $q);
    }
}


function me_check($s)
{
    if ($s) {
        echo " checked ";
    }
}

function get_patient_profile_link($photo)
{
    global $website_url;
    global $patient_profile_path;
    if (empty($photo)) {
        return $website_url . $patient_profile_path . "all.png";
    } else {
        return $website_url . $patient_profile_path .  $photo;
    }
}




// temp function
function ps()
{
    echo "<pre>";
}
function pe()
{
    echo "</pre>";
}

// Random Password
function generate_password($length = 8)
{
    $base = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@#$%";
    $base = str_split($base);
    $base_len = count($base);
    $pass = "";
    for ($i = 1; $i <= $length; $i++) {
        shuffle($base);
        $pass .= $base[rand(0, ($base_len - 1))];
    }
    return $pass;
}


function unit_html_view($range, $symbol = "", $fix = false)
{
    $view = "";
    if ($range["status"]) {
        if ($range["fix"] && $fix) {
            $view .= "Fix,<br>";
        }
        if ($range["diff"]) {
            $view .= "Male: " . $range["range"]["male"]["start"] . " - " . $range["range"]["male"]["end"] . " " . $symbol . "<br>";
            $view .= "Female: " . $range["range"]["female"]["start"] . " - " . $range["range"]["female"]["end"] . " " . $symbol . "";
            // $view .= "Child: " . $range["range"]["child"]["start"] . " - " . $range["range"]["child"]["end"] . " " . $symbol;
        } else {
            $view .= $range["range"]["all"]["start"] . " - " . $range["range"]["all"]["end"] . " " . $symbol;
        }
    } else {
        $view = "-";
    }
    return $view;
}

function name_html_view($text, $dtext)
{
    $view = "";
    $view .= $dtext;
    if ($text != $dtext) {
        $view .= "<br><span class='text-secondary'>" . $text . "</span>";
    }
    return $view;
}


function if_or_dash($s)
{
    if (!empty($s)) {
        return $s;
    } else {
        return "-";
    }
}


function if_yes($n)
{
    if (boolval($n)) {
        return "Yes";
    } else {
        return "";
    }
}

function active_deactive($n, $type = 0)
{
    if (boolval($n)) {
        if ($type == 0) {
            return "Active";
        } else {
            return "<span class='text-success'>Active</span>";
        }
    } else {
        if ($type == 0) {
            return "Deactive";
        } else {
            return "<span class='text-danger'>Deactive</span>";
        }
    }
}

include("mail.php");

function mail_new_registration_welcome($to, $user_id,  $name, $uname, $dob, $pass, $register_by)
{
    global $website_name;
    global $website_url;
    global $con;

    $html = mail_template_new_register_with_password($website_name, $website_url, $user_id, $uname, $name, $dob, $pass, $register_by);

    mail_html($to, "Welcome to " . $website_name, $html, $website_name . " <admin@smtcodes.in>");
    register_mail_actvity($html, $register_by);
}

function mail_report_view_alert($to, $name, $report_id, $viewd_by)
{
    global $website_name;
    global $website_url;
    global $con;

    $html = mail_template_report_view($website_name, $website_url, $name, $report_id, $viewd_by);

    mail_html($to, "Your Report id Viewed", $html, $website_name . " <admin@smtcodes.in>");
    register_mail_actvity($html, $viewd_by);
}



function send_password_reset_details($to, $name, $username, $new_password, $reseted_by)
{
    global $website_name;
    global $website_url;
    global $con;

    $html = mail_template_password_reseted($website_name, $website_url, $name, $username, $new_password, $reseted_by);

    mail_html($to, "Your Password is Receted", $html, $website_name . " <admin@smtcodes.in>");
    register_mail_actvity($html, $reseted_by);
}

function report_added_notification($to, $name, $report_id, $report_auth_string, $report_date, $added_by)
{
    global $website_name;
    global $website_url;
    global $con;

    $html = mail_template_report_added($website_name, $website_url, $report_id, $report_date, $website_url . 'report/index.php?id=' . $report_id . '&auth=' . urlencode($report_auth_string), $name, $added_by);
    mail_html($to, "Report Added", $html, $website_name . " <admin@smtcodes.in>");
    register_mail_actvity($html, $added_by);
}


function send_mail_password_reseted_by_authority($to, $name, $username, $password, $reseted_by)
{
    global $website_name;
    global $website_url;
    global $con;

    $html = mail_template_password_reseted($website_name, $website_url, $name, $username, $password, $reseted_by);
    mail_html($to, "Your Password is Reseted", $html, $website_name . " <admin@smtcodes.in>");
    register_mail_actvity($html, $reseted_by);
}

function username_is_available($uname, $skip = "")
{
    global $con;
    $uname = mysqli_real_escape_string($con, $uname);
    $skip = mysqli_real_escape_string($con, $skip);
    $q = "SELECT * FROM `users` WHERE `user_username` = '$uname' AND `user_username` != '$skip'";
    $res = mysqli_query($con, $q);
    if (mysqli_num_rows($res) == 0) {
        return true;
    } else {
        return false;
    }
}


$a = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
$base = count($a);
function get_code_by_id($num, $len = MAX_USER_ID_LENGTH)
{
    global $a;
    global $base;
    $x = $num;
    $out = array();
    while ($x != 0) {
        $rem = $x % $base;
        $out[] = $rem;
        $x -= $rem;
        $x /= $base;
    }
    $res = "";
    for ($i = 0; $i < count($out); $i++) {
        $res = $a[$out[$i]] . $res;
    }

    for ($i = count($out) + 1; $i <= $len; $i++) {
        $res = "a" . $res;
    }

    return $res;
}







function get_profile_name($file_name)
{
    if (empty($file_name)) {
        return "";
    } else {
        return $file_name;
    }
}


function get_age_and_gender($date, $gender)
{
    $diff = date_diff(date_create(date("d-m-Y", time())), date_create(date("d-m-Y", strtotime($date))));
    return $diff->format("%y") . " / " . strtoupper($gender[0]);
}






function get_prev_link($default = "#")
{
    $final = "";
    if (isset($_SERVER["HTTP_REFERER"])) {
        if (!empty($_SERVER["HTTP_REFERER"])) {
            $final = $_SERVER["HTTP_REFERER"];
        } else {
            $final = $default;
        }
    } else {
        $final = $default;
    }
    echo $final;
}

































function png_crop($img)
{
    $im = imagecreatefrompng($img);
    $width = imagesx($im);
    $height = imagesy($im);

    if ($height < $width) {
        $finalh = $height;
        $y = 0;
        $finalw = $height;
        $x = intval(($width - $height) / 2);
    } else if ($width < $height) {
        $finalw = $width;
        $x = 0;
        $finalh = $width;
        $y = intval(($height - $width) / 2);
    } else {
        $x = 0;
        $y = 0;
        $finalh = $height;
        $finalw = $width;
    }
    $im2 = imagecrop($im, ['x' => $x, 'y' => $y, 'width' => $finalh, 'height' => $finalw]);
    if ($im2 !== FALSE) {
        imagepng($im2, $img);
        imagedestroy($im2);
        imagedestroy($im);
        return true;
    } else {
        imagedestroy($im);
        return false;
    }
}

function jpeg_crop($img)
{
    $im = imagecreatefromjpeg($img);
    $width = imagesx($im);
    $height = imagesy($im);

    if ($height < $width) {
        $finalh = $height;
        $y = 0;
        $finalw = $height;
        $x = intval(($width - $height) / 2);
    } else if ($width < $height) {
        $finalw = $width;
        $x = 0;
        $finalh = $width;
        $y = intval(($height - $width) / 2);
    } else {
        $x = 0;
        $y = 0;
        $finalh = $height;
        $finalw = $width;
    }
    $im2 = imagecrop($im, ['x' => $x, 'y' => $y, 'width' => $finalh, 'height' => $finalw]);
    if ($im2 !== FALSE) {
        imagejpeg($im2, $img);
        imagedestroy($im2);
        imagedestroy($im);
        return true;
    } else {
        imagedestroy($im);
        return false;
    }
}


/*
    array("my_report.php", "Back to Home")
*/

function report_render($report_id, $print = false, $buttons = array())
{
    global $con;
    global $website_name;
    global $website_url;

    $q = "SELECT * FROM `reports`, `laboratorys`, `users` WHERE `reports`.`report_id` = '$report_id' AND `reports`.`report_laboratory` = `laboratorys`.`laboratory_id` AND `reports`.`report_patient` = `users`.`user_id`";
    $res = mysqli_query($con, $q);
    if (mysqli_num_rows($res) == 1) {
        $row = mysqli_fetch_assoc($res);
?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo $row["report_sid"]; ?> - <?php echo $row["user_name"]; ?></title>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css">
            <style>
                * {
                    font-weight: bold !important;
                    font-size: 1.05rem;
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

                    header,
                    header * {
                        visibility: visible;
                    }

                    footer,
                    footer * {
                        visibility: visible;
                    }

                    footer {
                        position: fixed;
                        bottom: 0;
                    }


                    html,
                    body {
                        width: 210mm;
                        height: 297mm;
                        padding-bottom: 40px;
                    }
                }

                .mydivs {
                    page-break-inside: avoid;

                }
            </style>
        </head>

        <body class="p-3 font-weight-bold pt-5">
            <header class="container">
                <div class="row  pt-2 mb-2 pl-2 pr-2" style="height: 120px; overflow: hidden;">
                    <div class="col-6">
                        <h5><?php echo $row["laboratory_name"] ?></h5>
                        <h6><?php echo $row["laboratory_address"] ?></h6>
                        <h6><?php echo $row["laboratory_mobile"] ?></h6>
                    </div>
                    <div class="col-6 text-right">
                        <h5><?php echo $website_name; ?></h5>
                        <h6>Online Report Management<br>System Powered By Gov.</h6>
                    </div>
                </div>
                <div class="row  pt-4 pb-4 mb-3 pl-2 pr-2 border-top border-bottom">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-4">
                                Patient id : <?php echo $row["user_username"]; ?>
                            </div>
                            <div class="col-4 text-center">
                                Age/Gender : <?php $diff = date_diff(date_create(date("d-m-Y", time())), date_create(date("d-m-Y", strtotime($row["user_dob"]))));
                                                echo $diff->format("%y") . " / " . strtoupper($row["user_gender"][0]); ?>
                            </div>
                            <div class="col-4">
                                Report Date : <?php echo date("d-m-Y", strtotime($row["report_timestamp"])) . " &nbsp; " . date("h:i A", strtotime($row["report_timestamp"])); ?>
                            </div>
                            <div class="col-8 mt-3">
                                Patient Name : <?php echo $row["user_name"]; ?>
                            </div>
                            <div class="col-4 mt-3">
                                Report id : <?php echo $row["report_sid"]; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <main class="container">
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
                                        <h5 class="font-weight-bold h4"><u><?php echo $report_templates_bulk[$query["id"]]["template_text"]; ?></u></h5>
                                    </div>
                                    <div class="col-12 mt-3">
                                        <div class="row">
                                            <div class="col-5"><u>Perameter</u></div>
                                            <div class="col-7 text-center">
                                                <div class="row">
                                                    <div class="col-4"><u>Result</u></div>
                                                    <div class="col-2"><u>Unit</u></div>
                                                    <div class="col-6"><u>Normal Range</u></div>
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
                                            <div class="col-5"><u>Perameter</u></div>
                                            <div class="col-7 text-center">
                                                <div class="row">
                                                    <div class="col-4"><u>Result</u></div>
                                                    <div class="col-2"><u>Unit</u></div>
                                                    <div class="col-6"><u>Normal Range</u></div>
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
            </main>
            <footer class="container">
                <div class="row border-top pt-2">
                    <div class="col-12 text-secondary">
                        <span>Report id : <?php echo $row["report_sid"]; ?></span>
                        <span class="float-right"><a class="text-secondary">This Report is Genrated By India Health Website.</a></span>
                    </div>
                </div>
            </footer>
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center p-3 mt-3 bg-white">
                        <?php
                        if ($print) {
                        ?>
                            <button type="button" class="btn btn-lg btn-success" onclick="window.print()">Print Report</button><br>
                        <?php
                        }
                        ?>
                        <?php
                        foreach ($buttons as $btn) {
                        ?>
                            <button type="button" class="btn btn-secondary mt-3" onclick="location.href='<?php echo $btn[0]; ?>'"><?php echo $btn[1]; ?></button><br>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </body>

        </html>


<?php
    } else {
        echo "<h1>Invalid Link</h1>";
    }
}
