<?php
include("config.php");
if (!isset($login_user)) {
    header("location:signin.php");
}
if (isset($_GET["del"])) {
    $del_id = mysqli_real_escape_string($con, get_num("del"));
    $user_id = $login_user["user_id"];
    if (mysqli_query($con, "UPDATE `reports` SET `report_status` = 0 WHERE `report_id` = '$del_id' AND `report_patient` = '$user_id'")) {
        $_SESSION["smsg"][] = "Report Deleted";
        header("location:my_report.php");
    } else {
        $_SESSION["amsg"][] = "Error";
        header("location:my_report.php");
    }
}
?>
<?php include("header.php"); ?>
<main class="container">
    <div class="row">
        <div class="col-12">
            <h3 class="mt-5 mb-3 text-center font-weight-bold">Recent Reports</h3>
        </div>
        <div class="col-12">
            <?php
            $user_id = $login_user["user_id"];
            $q = "SELECT * From `reports`, `laboratorys` WHERE `report_patient` = '$user_id' AND `report_laboratory` = `laboratory_id` AND `report_status` = '3' ORDER BY `report_id` DESC LIMIT 8";
            $res = mysqli_query($con, $q);
            if (mysqli_num_rows($res) > 0) {
            ?>
                <div class="row mt-4">
                    <?php
                    while ($row = mysqli_fetch_assoc($res)) {
                    ?>
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-8">
                                            id : <?php echo $row["report_sid"]; ?>
                                            <br>
                                            Lab : <?php echo $row["laboratory_name"]; ?>
                                        </div>
                                        <div class="col-4 text-center" style="font-size: 14px;">
                                            <?php echo date("d-m-Y", strtotime($row["report_timestamp"])) . "<br>" . date("h:i A", strtotime($row["report_timestamp"])); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-center">
                                    <a href="print.php?report=<?php echo $row["report_id"]; ?>"><button class="btn btn-success"> <i class="fa fa-eye"></i> View</button></a>
                                    <a href="my_report.php?del=<?php echo $row["report_id"]; ?>"><button class="btn btn-danger ml-2"> <i class="fa fa-trash"></i> Delete</button></a>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <div class="col-12 mt-5" id="reports">
                    <table class="table w-100" id="report_table">
                        <thead>
                            <tr>
                                <th>Report id</th>
                                <th>Laboratory</th>
                                <th>Date</th>
                                <th>Name</th>
                                <th>View</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <script>
                    $(document).ready(function() {
                        var table = $('#report_table').DataTable({
                            "processing": true,
                            "scrollX": true,
                            "serverSide": true,
                            'serverMethod': 'post',
                            "ajax": {
                                url: "api.php",
                                "data": function(d) {
                                    return $.extend({}, d, {
                                        "my_reports": 1,
                                    });
                                }
                            },
                            columns: [{
                                    data: "id"
                                },
                                {
                                    data: "lab_name"
                                },
                                {
                                    data: "date"
                                },
                                {
                                    data: "patient"
                                },
                                {
                                    data: "view"
                                },
                            ],
                            'columnDefs': [{
                                'targets': [3, 4],
                                'orderable': false,
                            }]
                        });
                    });
                </script>
            <?php
            } else {
            ?>
                <h4 class="text-center mt-4 font-weight-bold">No Recent Reports To Display</h4>
            <?php
            }
            ?>
        </div>

    </div>
</main>
<?php include("footer.php"); ?>