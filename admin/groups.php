<?php

include("config.php");

if (isset($_GET["a"])) {
    $id = get_num("a");
    mysqli_query($con, "UPDATE `groups` SET `group_status` = '1' WHERE `group_id` = '$id'");
    $_SESSION["smsg"][] = "Group Activated";
    header("location:groups.php");
    exit();
}

if (isset($_GET["d"])) {
    $id = get_num("d");
    mysqli_query($con, "UPDATE `groups` SET `group_status` = '0' WHERE `group_id` = '$id'");
    $_SESSION["smsg"][] = "Group Deactivated";
    header("location:groups.php");
    exit();
}

if (isset($_POST["add"])) {
    $flag = true;

    $text = post_str("text");
    if (empty($text)) {
        $flag = false;
        $_SESSION["amsg"][] = "Group Name Can Not Be Empty";
    }

    $dtext = post_str("dtext");
    if (empty($dtext)) {
        $flag = false;
        $_SESSION["amsg"][] = "Group Dispay Text Can Not Be Empty";
    }

    $status = intval(isset($_POST["status"]));

    $units = post_arr("units");
    if (count($units) == 0) {
        $flag = false;
        $_SESSION["amsg"][] = "Please Add Units to This Group";
    }

    if ($flag) {
        $text = mysqli_real_escape_string($con, $text);
        $units = mysqli_real_escape_string($con, json_encode($units));
        $user_id = $_SESSION["user_id"];
        $group_insert = "INSERT INTO `groups`(`group_id`, `group_user`, `group_text`, `group_dtext`, `group_unit`, `group_status`) VALUES (NULL, '$user_id', '$text', '$dtext', '$units', '$status')";
        if (mysqli_query($con, $group_insert)) {
            $_SESSION["smsg"][] = "Group Added";
            header("location:groups.php");
        } else {
            $_SESSION["amsg"][] = "Error";
            header("location:groups.php");
        }
    } else {
        header("location:groups.php");
    }
}

if (isset($_GET["edit"])) {
    $id = get_num("edit");
    $group_result = mysqli_query($con, "SELECT * FROM `groups` WHERE `group_id` = '$id'");
    if (mysqli_num_rows($group_result) == 1) {
        $group = mysqli_fetch_assoc($group_result);
    } else {
        $_SESSION["amsg"][] = "Invalid Action";
        $cascad = true;
        header("location:group.php");
    }
}

if (isset($_POST["update"])) {
    $flag = true;

    $id = post_num("id");

    $text = post_str("text");
    if (empty($text)) {
        $flag = false;
        $_SESSION["amsg"][] = "Group Name Can Not Be Empty";
    }

    $dtext = post_str("dtext");
    if (empty($dtext)) {
        $flag = false;
        $_SESSION["amsg"][] = "Group Dispay Text Can Not Be Empty";
    }

    $status = intval(isset($_POST["status"]));

    $units = post_arr("units");
    if (count($units) == 0) {
        $flag = false;
        $_SESSION["amsg"][] = "Please Add Units to This Group";
    }

    if ($flag) {
        $text = mysqli_real_escape_string($con, $text);
        $units = mysqli_real_escape_string($con, json_encode($units));
        $user_id = $_SESSION["user_id"];
        $group_update = "UPDATE `groups` SET `group_text` = '$text', `group_dtext` = '$dtext', `group_unit` = '$units', `group_status` = '$status' WHERE `group_id` = '$id'";
        if (mysqli_query($con, $group_update)) {
            $_SESSION["smsg"][] = "Group Updated";
            header("location:groups.php");
        } else {
            $_SESSION["amsg"][] = "Error";
            header("location:groups.php");
        }
    } else {
        header("location:groups.php");
    }

    exit();
}
?>
<?php include("header.php"); ?>
<main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 pt-4">
    <div class="row">
        <div class="col-12">
            <?php
            if (isset($group)) {
            ?>
                <form action="" method="POST">
                    <div class="card border-primary">
                        <div class="card-header bg-primary text-white">
                            <i class="fa fa-edit"></i> Edit Group
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="mb-1" for="text"><i class="fa fa-th-large"></i> Group Name:</label>
                                        <div>
                                            <input type="text" name="text" id="text" class="form-control" value="<?php echo $group["group_text"]; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="mb-1" for="dtext"><i class="fa fa-th-large"></i> Group Display Text:</label>
                                        <div>
                                            <input type="text" name="dtext" id="dtext" class="form-control" value="<?php echo $group["group_dtext"]; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group row mb-3">
                                        <div class="col-6 mb-1">
                                            <label for="status"><i class="fa fa-question-circle"></i> Group Staus:</label> 
                                        </div>
                                        <div class="col-6">
                                            <div class="custom-control custom-switch ">
                                                <input type="checkbox" class="custom-control-input" id="status" name="status" <?php if ($group["group_status"]) {
                                                                                                                                    echo "checked";
                                                                                                                                }
                                                                                                                                ?>>
                                                <label class="custom-control-label" for="status"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 pb-1">
                                    <div class='card d-md-block d-none mb-2' style="background-color: #bdbdbd;">
                                        <div class='card-body pt-2 pb-2'>
                                            <div class='row'>
                                                <div class='col-md-6'>Unit Text</div>
                                                <div class='col-md-2'>Symbol</div>
                                                <div class='col-md-2'>Range</div>
                                                <div class='col-md-2 text-center'>Action</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12" id="unit_list">
                                    <?php
                                    $unit_ids = json_decode($group["group_unit"], true);
                                    $str_ids = implode(", ", $unit_ids);
                                    $q = "SELECT * FROM `units` WHERE `unit_id` IN ($str_ids)";
                                    $res = mysqli_query($con, $q);
                                    $units = array();
                                    while ($row = mysqli_fetch_assoc($res)) {
                                        $units[$row["unit_id"]] = $row;
                                    }
                                    foreach ($unit_ids as $s) {
                                        $row = $units[$s];
                                    ?>
                                        <div class="card mb-2">
                                            <div class="card-body pt-2 pb-2">
                                                <div class="row">
                                                    <div class="col-6 d-md-none my-auto">Unit Text</div>
                                                    <div class="col-6 my-auto">
                                                        <?php echo $row["unit_dtext"]; ?>
                                                        <?php
                                                        if ($row["unit_dtext"] != $row["unit_text"]) {
                                                        ?>
                                                            <br><span class="text-secondary"><?php echo $row["unit_text"]; ?></span>
                                                        <?php
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="col-6 d-md-none my-auto">Symbol</div>
                                                    <div class="col-md-2 col-6 my-auto">
                                                        <?php
                                                        if ($row["unit_symbol"] != "") {
                                                            echo $row["unit_symbol"];
                                                        } else {
                                                            echo "-";
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="col-6 d-md-none my-auto">Range</div>
                                                    <div class="col-md-2 col-6 my-auto">
                                                        <?php
                                                        echo unit_html_view(json_decode($row["unit_range"], true), $row["unit_symbol"], true)
                                                        ?>
                                                    </div>
                                                    <div class="col-6 d-md-none my-auto">Action</div>
                                                    <div class="col-md-2 col-6 text-md-center pt-2 pb-2"><button class="btn_remove btn btn-outline-danger btn-sm" type="button" id="list-unit-<?php echo $row["unit_id"]; ?>" data-unit-id="<?php echo $row["unit_id"]; ?>" style="width: 31px;"><i class="fa fa-trash"></i></button></div>
                                                </div>
                                                <input type="hidden" name="units[]" value="<?php echo $row["unit_id"]; ?>">
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <div class="col-12 text-center pt-3">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#UnitListModel">
                                        <i class="fa fa-list-ul"></i> Units List
                                    </button>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $group["group_id"]; ?>">
                        <div class="card-footer">
                            <button type="submit" name="update" class="btn btn-info mr-3"><i class="fa fa-check"></i> Update Group</button>
                            <button type="reset" class="btn btn-warning mr-3"><i class="fa fa-undo"></i> Reset</button>
                            <button class="btn btn-success" type="button" onclick="location.href='groups.php'"><i class="fa fa-backward"></i> Back</button>
                        </div>
                    </div>
                </form>
            <?php
            } else {
            ?>
                <form action="" method="POST">
                    <div class="card border-primary">
                        <div class="card-header bg-primary text-white">
                            Add Group
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="mb-1" for="text"><i class="fa fa-th-large"></i> Group Name:</label>
                                        <div>
                                            <input type="text" name="text" id="text" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="mb-1" for="dtext"><i class="fa fa-th-large"></i> Group Display Text:</label>
                                        <div>
                                            <input type="text" name="dtext" id="dtext" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group row mb-3">
                                        <div class="col-6 mb-1">
                                            <label for="status"><i class="fa fa-question-circle"></i> Group Staus:</label>
                                        </div>
                                        <div class="col-6">
                                            <div class="custom-control custom-switch ">
                                                <input type="checkbox" class="custom-control-input" id="status" name="status" checked>
                                                <label class="custom-control-label" for="status"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 pb-1">
                                    <div class='card d-md-block d-none mb-2' style="background-color: #bdbdbd;">
                                        <div class='card-body pt-2 pb-2'>
                                            <div class='row'>
                                                <div class='col-md-6'>Unit Text</div>
                                                <div class='col-md-2'>Symbol</div>
                                                <div class='col-md-2'>Range</div>
                                                <div class='col-md-2 text-center'>Action</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12" id="unit_list">
                                </div>
                                <div class="col-12 text-center pt-3">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#UnitListModel">
                                        <i class="fa fa-list-ul"></i> Units List
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" name="add" class="btn btn-info mr-3"><i class="fa fa-plus"></i> Add Group</button>
                            <button type="reset" class="btn btn-warning mr-3"><i class="fa fa-undo"></i> Reset</button>
                        </div>
                    </div>
                </form>
        </div>
        <div class="col-12 pt-5">
            <table class="table table-sm mt-4" id="group_table">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Text</th>
                        <th>Units</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <script>
                $(document).ready(function() {
                    var table = $('#group_table').DataTable({
                        "lengthMenu": [10, 25, 50, 75, 100],
                        "processing": true,
                        "serverSide": true,
                        'serverMethod': 'post',
                        "ajax": {
                            url: "api.php",
                            "data": function(d) {
                                return $.extend({}, d, {
                                    "groups": 1,
                                });
                            }
                        },
                        columns: [{
                                data: "id"
                            },
                            {
                                data: "text"
                            },
                            {
                                data: "units"
                            },
                            {
                                data: "status"
                            },
                            {
                                data: "action"
                            },
                        ],
                        'columnDefs': [{
                            'targets': [2, 3, 4],
                            'orderable': false,
                        }]
                    });
                });
            </script>
        <?php
            }
        ?>
        </div>
    </div>
</main>
<!-- Modal -->
<div class="modal fade" id="UnitListModel" tabindex="-1" role="dialog" aria-labelledby="UnitListModelTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered  modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold" id="UnitListModelTitle">Units</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php
                $q = "SELECT * FROM `units`";
                $res = mysqli_query($con, $q);
                while ($row = mysqli_fetch_assoc($res)) {
                ?>
                    <div class="card mb-3">
                        <div class="card-body pt-2 pb-2">
                            <div class="row">
                                <div class="col-10 my-auto unit_text">
                                    <?php
                                    echo name_html_view($row["unit_text"], $row["unit_dtext"]);
                                    ?>
                                </div>
                                <div class="col-2 text-center pl-0 pr-0">
                                    <button class="btn_add_remove btn <?php
                                                                        if (isset($group) && in_array($row["unit_id"], $unit_ids)) {
                                                                            echo "btn-danger";
                                                                        } else {
                                                                            echo "btn-success";
                                                                        }
                                                                        ?> btn-sm" id="btn-add-remove-<?php echo $row["unit_id"]; ?>" type="button" data-unit-id="<?php echo $row["unit_id"]; ?>" data-unit-text="<?php echo $row["unit_text"]; ?>" data-unit-dtext="<?php echo $row["unit_dtext"]; ?>" data-unit-symbol="<?php if (!empty($row["unit_symbol"])) {
                                                                                                                                                                                                                                                                                                                                echo $row["unit_symbol"];
                                                                                                                                                                                                                                                                                                                            } else {
                                                                                                                                                                                                                                                                                                                                echo "-";
                                                                                                                                                                                                                                                                                                                            } ?>" <?php
                                                                                                                                                                                                                                                                                                                                    $s = unit_html_view(json_decode($row["unit_range"], true), $row["unit_symbol"], true);
                                                                                                                                                                                                                                                                                                                                    echo "data-unit-range='" . $s . "' ";
                                                                                                                                                                                                                                                                                                                                    ?> style="width: 31px;"> <?php
                                                                                                                                                                                                                                                                                                                                                                if (isset($group) && in_array($row["unit_id"], $unit_ids)) {
                                                                                                                                                                                                                                                                                                                                                                    echo "<i class='fa fa-minus'></i>";
                                                                                                                                                                                                                                                                                                                                                                } else {
                                                                                                                                                                                                                                                                                                                                                                    echo "<i class='fa fa-plus'></i>";
                                                                                                                                                                                                                                                                                                                                                                }
                                                                                                                                                                                                                                                                                                                                                                ?> </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Done</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#text").focusin(function() {
            $(this).data('val', $(this).val());
        });
        $("#text").change(function() {
            console.log($(this).data('val'));
            if ($("#dtext").val() == "" || $("#dtext").val() == $(this).data('val')) {
                $("#dtext").val($(this).val());
            }
        });
        $("#unit_list").sortable();
        $(".btn_add_remove").click(function() {
            if ($(this).hasClass("btn-success")) {
                var unit_id = $(this).attr("data-unit-id");
                var unit_text = $(this).attr("data-unit-text");
                var unit_dtext = $(this).attr("data-unit-dtext");
                var unit_symbol = $(this).attr("data-unit-symbol");
                var unit_range = $(this).attr("data-unit-range");
                if (unit_text == unit_dtext) {
                    var s = "<div class='card mb-2'><div class='card-body pt-2 pb-2'><div class='row'><div class='col-6 d-md-none my-auto'>Unit Text</div><div class='col-6 my-auto'>" + unit_text + "</div><div class='col-6 d-md-none my-auto'>Symbol</div><div class='col-md-2 col-6 my-auto'>" + unit_symbol + "</div><div class='col-6 d-md-none my-auto'>Range</div><div class='col-md-2 col-6 my-auto'>" + unit_range + "</div><div class='col-6 d-md-none my-auto'>Action</div><div class='col-md-2 col-6 text-md-center pt-2 pb-2'><button class='btn_remove btn btn-outline-danger btn-sm' type='button' id='list-unit-" + unit_id + "' data-unit-id='" + unit_id + "' style='width: 31px;'><i class='fa fa-trash'></i></button></div></div><input type='hidden' name='units[]' value='" + unit_id + "'></div></div>";
                } else {
                    var s = "<div class='card mb-2'><div class='card-body pt-2 pb-2'><div class='row'><div class='col-6 d-md-none my-auto'>Unit Text</div><div class='col-6 my-auto'>" + unit_dtext + "<br><span class='text-secondary'>" + unit_text + "</span></div><div class='col-6 d-md-none my-auto'>Symbol</div><div class='col-md-2 col-6 my-auto'>" + unit_symbol + "</div><div class='col-6 d-md-none my-auto'>Range</div><div class='col-md-2 col-6 my-auto'>" + unit_range + "</div><div class='col-6 d-md-none my-auto'>Action</div><div class='col-md-2 col-6 text-md-center pt-2 pb-2'><button class='btn_remove btn btn-outline-danger btn-sm' type='button' id='list-unit-" + unit_id + "' data-unit-id='" + unit_id + "' style='width: 31px;'><i class='fa fa-trash'></i></button></div></div><input type='hidden' name='units[]' value='" + unit_id + "'></div></div>";
                }
                $("#unit_list").append(s);
                $("#list-unit-" + unit_id).click(function() {
                    var unit_id = $(this).attr("data-unit-id");
                    console.log(unit_id);
                    $(this).parent().parent().parent().parent().remove();
                    $("#btn-add-remove-" + unit_id).removeClass("btn-danger");
                    $("#btn-add-remove-" + unit_id).addClass("btn-success");
                    $("#btn-add-remove-" + unit_id).html("<i class='fa fa-plus'></i>");
                });
                $(this).removeClass("btn-success");
                $(this).addClass("btn-danger");
                $(this).html("<i class='fa fa-minus'></i>");
            } else {
                var unit_id = $(this).attr("data-unit-id");
                $("#list-unit-" + unit_id).parent().parent().parent().parent().remove();
                $(this).removeClass("btn-danger");
                $(this).addClass("btn-success");
                $(this).html("<i class='fa fa-plus'></i>");
            }
        });
        $(".btn_remove").click(function() {
            var unit_id = $(this).attr("data-unit-id");
            console.log(unit_id);
            $(this).parent().parent().parent().parent().remove();
            $("#btn-add-remove-" + unit_id).removeClass("btn-danger");
            $("#btn-add-remove-" + unit_id).addClass("btn-success");
            $("#btn-add-remove-" + unit_id).html("<i class='fa fa-plus'></i>");
        });
    });
</script>
<?php include("footer.php"); ?>