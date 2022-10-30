<?php
include("config.php");

?>
<?php include("header.php"); ?>
<main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 pt-4">
    <div class="row">
        <div class="col-6 offset-3">
            <div class="row">
                <div class="col-4">
                    <label>Enter Patient id :</label>
                </div>
                <?php
                $pid = "";
                $autpfetch = false;
                if (isset($_GET["patient"])) {
                    $pid = get_num("patient");
                    $autpfetch = true;
                }
                ?>
                <div class="col-8">
                    <input type="text" name="pid" id="pid" class="form-control" value="<?php echo $pid; ?>">
                </div>
                <div class="col-12 text-center mt-4">
                    <button type="button" id="view" class="btn btn-success"><i class="fa fa-eye"></i> View Reports</button>
                </div>
            </div>
        </div>
        <div class="col-12 mt-5" id="reports" style="display: none;">
            <table class="table w-100" id="report_table">
                <thead>
                    <tr>
                        <th>id</th>
                        <!-- <th>Patient id</th> -->
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
                                "get_reports" : 1,
                                "patient": $('#pid').val(),
                            });
                        }
                    },
                    columns: [{
                            data: "id"
                        }, 
                        // {
                        //     data: "pid"
                        // },
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
                });

                $("#view").click(function() {
                    if ($('#pid').val() != "") {
                        $("#reports").show();
                    } else {
                        $("#reports").hide();
                    }
                    table.draw();
                });

                <?php
                if ($autpfetch) {
                ?>
                $("#view").click();
                <?php
                }
                ?>
            });
        </script>
    </div>
</main>
<?php include("footer.php"); ?>