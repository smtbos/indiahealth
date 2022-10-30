<?php
include("config.php");
if (!isset($login_user)) {
    header("location:signin.php");
}
?>
<?php include("header.php"); ?>
<main class="container">
    <div class="row">
        <div class="col-12">
            <h3 class="mt-5 mb-3 text-center font-weight-bold">My Health</h3>
        </div>
        <?php
        $user_id = $login_user["user_id"];
        $q = "SELECT * From `reports`, `laboratorys` WHERE `report_patient` = '$user_id' AND `report_laboratory` = `laboratory_id` AND `report_status` = '3' ORDER BY `report_id` DESC LIMIT 50";
        $details = array();
        $res = mysqli_query($con, $q);
        $units = array();
        if (mysqli_num_rows($res) > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
                $detail = json_decode($row["report_details"], true);
                foreach ($detail as $d) {
                    if ($d["type"] == "template") {
                        foreach ($d["element"] as $ele) {
                            if ($ele["type"] == "unit") {
                                if (is_float($ele["value"]) || is_numeric($ele["value"])) {
                                    $units[$ele["id"]]["date"][] = $row["report_timestamp"];
                                    $units[$ele["id"]]["val"][] = $ele["value"];
                                }
                            } else if ($ele["type"] == "group") {
                                foreach ($ele["unit"] as $u) {
                                    if (is_float($u["value"]) || is_numeric($u["value"])) {
                                        $units[$u["id"]]["date"][] = $row["report_timestamp"];
                                        $units[$u["id"]]["val"][] = $u["value"];
                                    }
                                }
                            }
                        }
                    } else if ($d["type"] == "group") {
                        foreach ($d["unit"] as $u) {
                            if (is_float($u["value"]) || is_numeric($u["value"])) {
                                $units[$u["id"]]["date"][] = $row["report_timestamp"];
                                $units[$u["id"]]["val"][] = $u["value"];
                            }
                        }
                    }
                }
            }
        }
        if (sizeof($units) > 0) {
            foreach ($units as $key => $details) {
                $dates = $details["date"];
                $vals = $details["val"];
        ?>
                <div class="col-3 p-2 mb-2">
                    <div class="card border-secondary h-100">
                        <div class="card-body">
                            <h5><?php echo $globle_units[$key]; ?></h5>
                        </div>
                        <div class="card-footer pb-1">
                            <div class="row">
                                <div class="col-6">
                                    <span>Min - <?php echo min($vals); ?></span><br>
                                    Max - <?php echo max($vals); ?>
                                </div>
                                <div class="col-6 text-center">
                                    Avg.<br>
                                    <?php echo round(array_sum($vals) / count($vals), 2); ?>
                                </div>
                                <div class="col-12 text-center text-secondary mt-1">
                                    Last Update - <?php
                                                    echo date("d-m-Y", strtotime(max($dates)));
                                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
        } else {
            ?>
            <div class="col-12 text-center pt-3">
                <h3>No Data Found</h3>
            </div>
        <?php
        }
        ?>
    </div>
</main>
<?php include("footer.php"); ?>