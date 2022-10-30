<?php
include("config.php");
?>
<?php include("header.php"); ?>
<main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 pt-4">
    <div class="row">
        <div class="col-12">
            <h2 class="mt-3 mb-2 text-center">My Patient's Reports</h2>
            <table class="table table-striped w-100" id="history_table">
                <thead>
                    <tr>
                        <th>Report id</th>
                        <th>Date</th>
                        <th>Patient id</th>
                        <th>Patient Name</th>
                        <th>Status</th>
                        <th>Print</th>
                    </tr>
                </thead>
            </table>
            <script>
                $(document).ready(function() {
                    var table = $('#history_table').DataTable({
                        language: {
                            searchPlaceholder: " n, abc, pid:n"
                        },
                        "processing": true,
                        "serverSide": true,
                        'serverMethod': 'post',
                        "ajax": {
                            url: "api.php",
                            "data": function(d) {
                                return $.extend({}, d, {
                                    "my_patient": 1,
                                    // "date": $('#hdate').val(),
                                });
                            }
                        },
                        columns: [{
                                data: "id"
                            },
                            {
                                data: "date"
                            },
                            {
                                data: "pid"
                            },
                            {
                                data: "patient"
                            },
                            {
                                data: "status"
                            },
                            {
                                data: "print"
                            },
                        ],
                    });

                    // $("#hdate").change(function() {
                    //     table.draw();
                    //     console.log($(this).val());
                    // });
                    // $("#date_staus").change(function() {
                    //     if ($(this).prop("checked") == true) {
                    //         $("#hdate").val("<?php //echo date("Y-m-d"); 
                                                ?>");
                    //     } else {
                    //         $("#hdate").val("");
                    //     }
                    //     table.draw();
                    // });
                });
            </script>
        </div>
        <div class="col-12">
            <h2 class="mt-3 mb-2 text-center">Recent Viewd Report</h2>
            <?php
            $recent = json_decode($login_doctor["doctor_recent"], true);
            if (count($recent) > 0) {
                $str = implode(", ", $recent);
                $reports = array();
                $qry = "SELECT * FROM `reports`, `users` WHERE `report_id` IN ($str) AND `report_patient` = `user_id`";
                $res = mysqli_query($con, $qry);
                if (mysqli_num_rows($res) > 0) {
                    while ($row = mysqli_fetch_assoc($res)) {
                        $reports[$row["report_id"]] = $row;
                    }
                }
            ?>
                <table class="table mt-5">
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>Patient id</th>
                            <th>Date</th>
                            <th>Name</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($recent as $id) {
                            if (isset($reports[$id])) {
                        ?>
                                <tr>
                                    <td><?php echo $reports[$id]["report_id"]; ?></td>
                                    <td><?php echo $reports[$id]["user_username"]; ?></td>
                                    <td><?php echo $reports[$id]["user_name"]; ?></td>
                                    <td><?php echo date("d-m-Y", strtotime($reports[$id]["report_timestamp"])); ?></td>
                                    <td><?php echo "<a href='view.php?report=" . $reports[$id]["report_id"] . "'><button class='btn btn-sm btn-success'><i class='fa fa-eye'></i> View</button></a>" ?></td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            <?php
            } else {
            ?>
                <h3 class="mt-4 text-center">There is No Recent Reports</h3>
            <?php
            }

            ?>
        </div>
    </div>
</main>
<?php include("footer.php"); ?>