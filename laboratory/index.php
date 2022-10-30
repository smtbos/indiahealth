<?php
include("config.php");
include("login_config.php");
?>
<?php include("header.php"); ?>
<main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 pt-4">
    <div class="row">
        <div class="col-12 ">
            <div class="row">
                <?php
                $lab_id = $login_lab["laboratory_id"];
                $today = date("Y-m-d");
                $res = mysqli_query($con, "SELECT COUNT(*) as `total_reports` FROM `reports` WHERE `report_laboratory` = '$lab_id' AND `report_timestamp` LIKE '$today%'");
                $row = mysqli_fetch_assoc($res);
                $total_report = $row["total_reports"];


                $res = mysqli_query($con, "SELECT COUNT(*) as `total_pending` FROM `reports` WHERE `report_laboratory` = '$lab_id' AND `report_timestamp` LIKE '$today%' AND `report_status` IN (1,2)");
                $row = mysqli_fetch_assoc($res);
                $total_pending = $row["total_pending"];

                $total_filled = intval($total_report) - intval($total_pending);

                $res = mysqli_query($con, "SELECT COUNT(*) AS `total_patient` FROM (SELECT * FROM `reports` WHERE `report_laboratory` = '$lab_id' GROUP BY `report_patient`) `table`");
                $row = mysqli_fetch_assoc($res);
                $total_patient = $row["total_patient"];

                ?>
                <div class="col-xl-3 col-sm-6 mt-4 pl-4 pr-4">
                    <div class="card">
                        <div class="card-header" style="background-color: #f5b402;">
                            Todays Reports
                        </div>
                        <div class="card-body" style="background-color: #fad97f;">
                            <h1><?php echo $total_report; ?></h1>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mt-4 pl-4 pr-4">
                    <div class="card">
                        <div class="card-header" style="background-color: #56fa0a;">
                            Today Filled Reports
                        </div>
                        <div class="card-body" style="background-color: #a8fa82;">
                            <h1><?php echo $total_filled; ?></h1>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mt-4 pl-4 pr-4">
                    <div class="card">
                        <div class="card-header" style="background-color: #3f59fc;">
                            Today Panding Reports
                        </div>
                        <div class="card-body" style="background-color: #8494fa;">
                            <h1><?php echo $total_pending; ?></h1>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mt-4 pl-4 pr-4">
                    <div class="card">
                        <div class="card-header" style="background-color: #e92dfa;">
                            Total Patient
                        </div>
                        <div class="card-body" style="background-color: #e67ff0;">
                            <h1><?php echo $total_patient; ?></h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-12 text-center">
                    <h3 class="font-weight-bold">Recent Pending Reports</h3>

                </div>
                <?php
                $q = "SELECT * FROM `reports`, `users` WHERE `report_laboratory` = '$lab_id' AND `report_patient` = `user_id` AND `report_status` IN (1,2) ORDER BY `report_id` LIMIT 5";
                $res = mysqli_query($con, $q);
                if (mysqli_num_rows($res) > 0) {
                ?>
                    <div class="col-12 overflow-auto">
                        <table class="table">
                            <tr>
                                <th>id</th>
                                <th>Pid</th>
                                <th>Patient</th>
                                <th>Fill</th>
                                <th>Date</th>
                            </tr>
                            <?php
                            while ($row = mysqli_fetch_assoc($res)) {
                            ?>
                                <tr>
                                    <td><?php echo $row["report_id"]; ?></td>
                                    <td><?php echo $row["user_username"]; ?></td>
                                    <td><?php echo $row["user_name"]; ?></td>
                                    <td><a href="fill_report.php?report=<?php echo $row["report_id"]; ?>"><button class="btn btn-success">Fill Results</button></a></td>
                                    <td><?php echo date("d-m-Y", strtotime($row["report_timestamp"])); ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </table>
                    </div>
                <?php
                } else {
                ?>
                    <div class="col-12 text-center">
                        <h4>There not not any Pending Report</h4>
                    </div>
                <?php
                }
                ?>
                <div class="col-12 text-center mt-4">
                    <h3 class="font-weight-bold">Recent Reports</h3>

                </div>
                <?php
                $q = "SELECT * FROM `reports`, `users` WHERE `report_laboratory` = '$lab_id' AND `report_patient` = `user_id` AND `report_status` = '3' ORDER BY `report_id` LIMIT 5";
                $res = mysqli_query($con, $q);
                if (mysqli_num_rows($res) > 0) {
                ?>
                    <div class="col-12 overflow-auto">
                        <table class="table">
                            <tr>
                                <th>id</th>
                                <th>Pid</th>
                                <th>Patient</th>
                                <th>Fill</th>
                                <th>Date</th>
                            </tr>
                            <?php
                            while ($row = mysqli_fetch_assoc($res)) {
                            ?>
                                <tr>
                                    <td><?php echo $row["report_id"]; ?></td>
                                    <td><?php echo $row["user_username"]; ?></td>
                                    <td><?php echo $row["user_name"]; ?></td>
                                    <td><a href="print.php?report=<?php echo $row["report_id"]; ?>"><button class="btn btn-success">Print</button></a></td>
                                    <td><?php echo date("d-m-Y", strtotime($row["report_timestamp"])); ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </table>
                    </div>
                <?php
                } else {
                ?>
                    <div class="col-12 text-center">
                        <h4>There not not any Recent Report</h4>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</main>
<?php include("footer.php"); ?>