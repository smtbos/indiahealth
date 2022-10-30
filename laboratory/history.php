<?php
include("config.php");
?>
<?php include("header.php"); ?>
<main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 pt-4">
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-md-4 offset-md-4 col-sm-6 offset-sm-2">
                    <div class="form-group row">
                        <div class="col-sm-5 my-auto pt-3 text-sm-left text-center">
                            <div class="custom-control custom-switch ">
                                <input type="checkbox" class="custom-control-input" id="date_staus" checked>
                                <label class="custom-control-label" for="date_staus">Filter by Date</label>
                            </div>
                        </div>
                        <div class="col-sm-7 pt-3">
                            <input type="date" id="hdate" value="<?php echo date("Y-m-d"); ?>" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <table class="table table-striped w-100" id="history_table">
                <thead>
                    <tr>
                        <th>Report id</th>
                        <th>Pid</th>
                        <th>Patient</th>
                        <th>Print</th>
                        <th>Date</th>
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
                                    "history" : 1,
                                    "date": $('#hdate').val(),
                                });
                            }
                        },
                        columns: [{
                                data: "id"
                            },
                            {
                                data: "pid"
                            },
                            {
                                data: "patient"
                            },
                            {
                                data: "print"
                            },
                            {
                                data: "date"
                            },
                        ],
                    });

                    $("#hdate").change(function() {
                        table.draw();
                        console.log($(this).val());
                    });
                    $("#date_staus").change(function() {
                        if ($(this).prop("checked") == true) {
                            $("#hdate").val("<?php echo date("Y-m-d"); ?>");
                        } else {
                            $("#hdate").val("");
                        }
                        table.draw();
                    });
                });
            </script>
        </div>
    </div>
</main>
<?php include("footer.php"); ?>