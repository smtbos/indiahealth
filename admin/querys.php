<?php
include("config.php");

if (isset($_GET["edit"])) {
    $id = get_num("edit");
    $q = "SELECT * FROM `querys` WHERE `query_id` = '$id' AND `query_handel` = '1'";
    $res = mysqli_query($con, $q);
    if (mysqli_num_rows($res) == 1) {
        $query = mysqli_fetch_assoc($res);
    } else {
        $_SESSION["amsg"][] = "Invalid ID";
        header("location:querys.php");
    }
}

if (isset($_POST["update"])) {
    $id = post_num("id");
    $message = post_str("message");
    $solved = intval(isset($_POST["solved"]));
    $q = "UPDATE `querys` SET `query_message` = '$message', `query_solved` = '$solved' WHERE `query_id` = '$id'";
    if (mysqli_query($con, $q)) {
        $_SESSION["smsg"][] = "Query Update";
        header("location:querys.php");
    } else {
        $_SESSION["amsg"][] = "Error";
        header("location:querys.php");
    }
}
?>
<?php include("header.php"); ?>
<main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 pt-4">
    <div class="row">
        <?php
        if (isset($query)) {
        ?>
            <div class="col-12">
                <form action="" method="POST">
                    <div class="card border-primary">
                        <div class="card-header bg-primary text-white">
                            Edit Query
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <h4>Subject : <?php echo $query["query_subject"]; ?></h4>
                                    <h4>Details : <?php echo $query["query_details"]; ?></h4>
                                </div>
                                <div class="col-12">
                                    <label>Message:</label>
                                    <input type="text" name="message" value="<?php echo $query["query_message"]; ?>" class="form-control">
                                </div>
                                <div class="col-3 mt-3 text-center">
                                    <div class="row">
                                        <div class="col-12">
                                            Solved
                                        </div>
                                        <div class="col-12">
                                            <div class="custom-control custom-switch ">
                                                <input type="checkbox" class="custom-control-input" id="solved" name="solved" <?php me_check($query["query_solved"]); ?>>
                                                <label class="custom-control-label" for="solved"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $query["query_id"]; ?>">
                        <div class="card-footer">
                            <input type="submit" name="update" value="Update Query" class="btn btn-info mr-3">
                            <input type="reset" value="Reset" class="btn btn-warning mr-3">
                            <button class="btn btn-success" type="button" onclick="location.href='querys.php'">Back</button>
                        </div>
                    </div>
                </form>
            </div>
        <?php
        } else {
        ?>
            <div class="col-12">
                <table class="table" id="query_table">
                    <thead>
                        <tr>
                            <th>Query id</th>
                            <th>User</th>
                            <th>Subject</th>
                            <th>Details</th>
                            <th>Solved</th>
                            <th>Action</th>
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
                                        "querys": 1,
                                    });
                                }
                            },
                            columns: [{
                                    data: "id"
                                },
                                {
                                    data: "user"
                                },
                                {
                                    data: "subject"
                                },
                                {
                                    data: "details"
                                },
                                {
                                    data: "solved"
                                },
                                {
                                    data: "action"
                                },
                            ],
                            'columnDefs': [{
                                'targets': [1, 3, 5],
                                'orderable': false,
                            }]
                        });
                    });
                </script>
            </div>
        <?php
        }
        ?>
    </div>
</main>
<?php include("footer.php"); ?>