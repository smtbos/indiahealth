<?php
include("config.php");

if (isset($_GET["check"])) {
    $check = get_num("check");
    if (mysqli_query($con, "UPDATE `contacts` SET `contact_status` = 1 WHERE `contact_id` = $check")) {
        header("location:contacts.php?s=1");
    } else {
        header("location:contacts.php?s=0");
    }
}

if(isset($_GET["uncheck"]))
{
    $check = get_num("uncheck");
    if (mysqli_query($con, "UPDATE `contacts` SET `contact_status` = 0 WHERE `contact_id` = $check")) {
        header("location:contacts.php?s=1");
    } else {
        header("location:contacts.php?s=0");
    }
}
?>
<?php include("header.php"); ?>
<main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 pt-4">
    <div class="row">
        <div class="col-12">
            <table class="table" id="query_table">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Person Details</th>
                        <th>Details</th>
                        <th>Status</th>
                        <th>Mark</th>
                    </tr>
                </thead>
            </table>
            <script>
                $(document).ready(function() {
                    var table = $('#query_table').DataTable({
                        "lengthMenu": [10, 25, 50, 75, 100],
                        "processing": true,
                        "serverSide": true,
                        'serverMethod': 'post',
                        "ajax": {
                            url: "api.php",
                            "data": function(d) {
                                return $.extend({}, d, {
                                    "contacts": 1,
                                });
                            }
                        },
                        columns: [{
                                data: "id"
                            },
                            {
                                data: "person"
                            },
                            {
                                data: "details"
                            },
                            {
                                data: "status"
                            },
                            {
                                data: "mark"
                            },
                        ],
                        'columnDefs': [{
                            // 'targets': [1, 3, 5],
                            // 'orderable': false,
                        }]
                    });
                });
            </script>
        </div>
    </div>
</main>
<?php include("footer.php"); ?>