<?php
include("config.php");
if (isset($_GET["a"])) {
    $id = get_num("a");
    mysqli_query($con, "UPDATE `templates` SET `template_status` = '1' WHERE `template_id` = '$id'");
    $_SESSION["smsg"][] = "Template Activated";
    header("location:templates.php");
    exit();
}
if (isset($_GET["d"])) {
    $id = get_num("d");
    mysqli_query($con, "UPDATE `templates` SET `template_status` = '0' WHERE `template_id` = '$id'");
    $_SESSION["smsg"][] = "Template Deactivated";
    header("location:templates.php");
    exit();
}
if (isset($_POST["add"])) {
    $flag = true;

    $text = post_str("text");
    if (empty($text)) {
        $flag = false;
        $_SESSION["amsg"][] = "Template Name Can Not Be Empty";
    }

    $status = intval(isset($_POST["status"]));

    $elements = post_arr("element");
    foreach ($elements as $k => $v) {
        $elements[$k] = json_decode($v, true);
    }


    if (count($elements) == 0) {
        $flag = false;
        $_SESSION["amsg"][] = "Please Add Some Units or Groups to This Template";
    }

    if ($flag) {
        $text = mysqli_real_escape_string($con, $text);
        // $units = mysqli_real_escape_string($con, json_encode($units));
        // $groups = mysqli_real_escape_string($con, json_encode($groups));
        $elements = mysqli_real_escape_string($con, json_encode($elements));
        $user_id = $_SESSION["user_id"];
        $template_insert = "INSERT INTO `templates`(`template_id`, `template_user`, `template_text`, `template_element`, `template_status`) VALUES (NULL, '$user_id', '$text', '$elements', '$status')";
        if (mysqli_query($con, $template_insert)) {
            $_SESSION["smsg"][] = "Templates Added";
            header("location:templates.php");
        } else {
            $_SESSION["amsg"][] = "Error";
            header("location:templates.php");
        }
    } else {
        header("location:templates.php");
    }
}
if (isset($_GET["edit"])) {
    $id = get_num("edit");
    $template_result = mysqli_query($con, "SELECT * FROM `templates` WHERE `template_id` = '$id'");
    if (mysqli_num_rows($template_result) == 1) {
        $template = mysqli_fetch_assoc($template_result);
    } else {
        $_SESSION["amsg"][] = "Invalid Action";
        $cascad = true;
        header("location:templates.php");
    }
}
if (isset($_POST["update"])) {
    $flag = true;

    $id = post_num("id");

    $text = post_str("text");
    if (empty($text)) {
        $flag = false;
        $_SESSION["amsg"][] = "Template Name Can Not Be Empty";
    }

    $status = intval(isset($_POST["status"]));

    $elements = post_arr("element");
    foreach ($elements as $k => $v) {
        $elements[$k] = json_decode($v, true);
    }


    if (count($elements) == 0) {
        $flag = false;
        $_SESSION["amsg"][] = "Please Add Some Units or Groups to This Template";
    }

    if ($flag) {
        $text = mysqli_real_escape_string($con, $text);
        // $units = mysqli_real_escape_string($con, json_encode($units));
        $elements = mysqli_real_escape_string($con, json_encode($elements));
        $template_update = "UPDATE `templates` SET `template_text` = '$text', `template_element` = '$elements', `template_status` = '$status' WHERE `template_id` = '$id'";
        if (mysqli_query($con, $template_update)) {
            $_SESSION["smsg"][] = "Templates Updated";
            header("location:templates.php");
        } else {
            $_SESSION["amsg"][] = "Error";
            header("location:templates.php");
        }
    } else {
        header("location:templates.php");
    }

    exit();
}
$unit_disabled = array();
?>
<?php include("header.php"); ?>
<main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 pt-4">
    <div class="row">
        <div class="col-12">
            <?php
            if (isset($template)) {
            ?>
                <form action="" method="POST">
                    <div class="card border-primary">
                        <div class="card-header bg-primary text-white">
                            <i class="fa fa-edit"></i> Edit Template
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="mb-1" for="text"><i class="fa fa-th"></i> &nbsp;Template Name :</label>
                                        <div>
                                            <input type="text" name="text" id="text" class="form-control" value="<?php echo $template["template_text"]; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group row mb-3">
                                        <div class="col-12 mb-1">
                                            <label for="status"><i class="fa fa-question-circle"></i> &nbsp;Template Staus :</label>
                                        </div>
                                        <div class="col-12 mt-1">
                                            <div class="custom-control custom-switch ">
                                                <input type="checkbox" class="custom-control-input" id="status" name="status" <?php if ($template["template_status"]) {
                                                                                                                                    echo "checked";
                                                                                                                                } ?>>
                                                <label class="custom-control-label" for="status"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 pb-1">
                                    <div class='card d-md-block d-none mb-2' style="background-color: #bdbdbd;">
                                        <div class='card-body pt-2 pb-2'>
                                            <div class='row'>
                                                <div class='col-md-6'>Group / Unit Text</div>
                                                <div class='col-md-2'>Symbol</div>
                                                <div class='col-md-2'>Range</div>
                                                <div class='col-md-2 text-center'>Action</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12" id="element_list">
                                    <?php
                                    $elements = json_decode($template["template_element"], true);
                                    $elements_grouped_ids = array(
                                        "units" => array(),
                                        "groups" => array()
                                    );
                                    foreach ($elements as $v) {
                                        if ($v["type"] == "unit") {
                                            $elements_grouped_ids["units"][] = $v["id"];
                                        } else if ($v["type"] == "group") {
                                            $elements_grouped_ids["groups"][] = $v["id"];
                                        }
                                    }

                                    $template_units = array();
                                    $template_groups = array();

                                    if (count($elements_grouped_ids["units"]) > 0) {
                                        $unit_ids = implode(", ", $elements_grouped_ids["units"]);
                                        $unit_bulk_select = "SELECT * FROM `units` WHERE `unit_id` IN ($unit_ids)";
                                        $unit_bulk_res = mysqli_query($con, $unit_bulk_select);
                                        while ($unit_bulk_row = mysqli_fetch_assoc($unit_bulk_res)) {
                                            $template_units[$unit_bulk_row["unit_id"]] = $unit_bulk_row;
                                        }
                                    }
                                    if (count($elements_grouped_ids["groups"]) > 0) {
                                        $group_ids = implode(", ", $elements_grouped_ids["groups"]);
                                        $group_bulk_select = "SELECT * FROM `groups` WHERE `group_id` IN ($group_ids)";
                                        $group_bulk_res = mysqli_query($con, $group_bulk_select);
                                        while ($group_bulk_row = mysqli_fetch_assoc($group_bulk_res)) {
                                            $template_groups[$group_bulk_row["group_id"]] = $group_bulk_row;
                                        }
                                    }
                                    foreach ($elements as $ele) {
                                        if ($ele["type"] == "unit") {
                                            $row = $template_units[$ele["id"]];
                                    ?>
                                            <div class="card mb-2">
                                                <div class="card-body pt-2 pb-2">
                                                    <div class="row">
                                                        <div class="col-6 d-md-none my-auto">Unit Text</div>
                                                        <div class="col-6 my-auto">
                                                            U -
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
                                                        <div class="col-md-2 col-6 text-md-center pt-2 pb-2"><button class="u_btn_remove btn btn-outline-danger btn-sm" type="button" id="list-unit-<?php echo $row["unit_id"]; ?>" data-unit-id="<?php echo $row["unit_id"]; ?>" style="width: 31px;"><i class='fa fa-trash'></i></button></div>
                                                    </div>
                                                    <input type="hidden" name="element[]" value='{"type":"unit","id":"<?php echo $row["unit_id"]; ?>"}'>
                                                </div>
                                            </div>
                                        <?php
                                        } else if ($ele["type"] == "group") {
                                            $row = $template_groups[$ele["id"]];
                                            $unit_disabled = array_merge($unit_disabled, json_decode($row["group_unit"], true));
                                        ?>
                                            <div class="card mb-2">
                                                <div class="card-body pt-2 pb-2">
                                                    <div class="row">
                                                        <div class="col-6 d-md-none my-auto">Group Text</div>
                                                        <div class="col-10 my-auto">
                                                            G -
                                                            <?php
                                                            if ($row["group_text"] == $row["group_dtext"]) {
                                                                echo $row["group_text"];
                                                            } else {
                                                                echo $row["group_dtext"] . "<br><span class='text-secondary'>" . $row["group_text"] . "</span>";
                                                            }
                                                            ?>
                                                        </div>
                                                        <div class="col-6 d-md-none my-auto">Action</div>
                                                        <div class="col-md-2 col-6 text-md-center pt-2 pb-2"><button class="g_btn_remove btn btn-outline-danger btn-sm" type="button" id="list-group-<?php echo $row["group_id"]; ?>" data-group-id="<?php echo $row["group_id"]; ?>" data-group-unit="<?php echo implode(",", json_decode($row["group_unit"], true)); ?>" style="width: 31px;"><i class='fa fa-trash'></i></button></div>
                                                    </div><input type="hidden" name="element[]" value='{"type":"group","id":"<?php echo $row["group_id"]; ?>"}'>
                                                </div>
                                            </div>
                                    <?php
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="col-12 text-center pt-3">
                                    <button type="button" class="btn btn-primary mr-2" data-toggle="modal" data-target="#GroupListModel">
                                        <i class="fa fa-list-ul"></i> Groups List
                                    </button>
                                    <button type="button" class="btn btn-primary ml-2" data-toggle="modal" data-target="#UnitListModel">
                                        <i class="fa fa-list-ul"></i> Units List
                                    </button>
                                </div>

                            </div>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $template["template_id"]; ?>">
                        <div class="card-footer">
                            <button type="submit" name="update" class="btn btn-info mr-3"><i class="fa fa-check"></i> Update Template</button>
                            <button type="reset" class="btn btn-warning mr-3"><i class="fa fa-undo"></i> Reset</button>
                            <button class="btn btn-success" type="button" onclick="location.href='templates.php'"><i class="fa fa-backward"></i> Back</button>
                        </div>
                    </div>
                </form>
            <?php
            } else {
            ?>
                <form action="" method="POST">
                    <div class="card border-primary">
                        <div class="card-header bg-primary text-white">
                            <i class="fa fa-plus"></i> Add Template
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="mb-1" for="text"><i class="fa fa-th"></i> &nbsp;Template Name :</label>
                                        <div>
                                            <input type="text" name="text" id="text" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group row mb-3">
                                        <div class="col-12 mb-1">
                                            <label for="status"><i class="fa fa-question-circle"></i> &nbsp;Template Staus :</label>
                                        </div>
                                        <div class="col-12 mt-1">
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
                                                <div class='col-md-6'>Group / Unit Text</div>
                                                <div class='col-md-2'>Symbol</div>
                                                <div class='col-md-2'>Range</div>
                                                <div class='col-md-2 text-center'>Action</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12" id="element_list">
                                </div>
                                <div class="col-12 text-center pt-3">
                                    <button type="button" class="btn btn-primary mr-2" data-toggle="modal" data-target="#GroupListModel">
                                        <i class="fa fa-list-ul"></i> Groups List
                                    </button>
                                    <button type="button" class="btn btn-primary ml-2" data-toggle="modal" data-target="#UnitListModel">
                                        <i class="fa fa-list-ul"></i> Units List
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" name="add" class="btn btn-info mr-3"><i class="fa fa-plus"></i> Add Template</button>
                            <button type="reset" class="btn btn-warning mr-3"><i class="fa fa-undo"></i> Reset</button>
                        </div>
                    </div>
                </form>
        </div>
        <div class="col-12 pt-5">
            <table class="table table-sm " id="template_table">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Text</th>
                        <th>Groups / Units</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <script>
                $(document).ready(function() {
                    var table = $('#template_table').DataTable({
                        "lengthMenu": [10, 25, 50, 75, 100],
                        "processing": true,
                        "serverSide": true,
                        'serverMethod': 'post',
                        "ajax": {
                            url: "api.php",
                            "data": function(d) {
                                return $.extend({}, d, {
                                    "templates": 1,
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
                                data: "elements"
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
<!-- Unit Modal -->
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
                                    <button class="u_btn_add_remove btn <?php
                                                                        if (isset($template) && in_array($row["unit_id"], $elements_grouped_ids["units"])) {
                                                                            echo "btn-danger";
                                                                        } else {
                                                                            echo "btn-success";
                                                                        }
                                                                        ?> btn-sm" id="u-btn-add-remove-<?php echo $row["unit_id"]; ?>" type="button" data-unit-id="<?php echo $row["unit_id"]; ?>" data-unit-text="<?php echo $row["unit_text"]; ?>" data-unit-dtext="<?php echo $row["unit_dtext"]; ?>" data-unit-symbol="<?php if (!empty($row["unit_symbol"])) {
                                                                                                                                                                                                                                                                                                                                echo $row["unit_symbol"];
                                                                                                                                                                                                                                                                                                                            } else {
                                                                                                                                                                                                                                                                                                                                echo "-";
                                                                                                                                                                                                                                                                                                                            } ?>" <?php
                                                                                                                                                                                                                                                                                                                                    $s = unit_html_view(json_decode($row["unit_range"], true), $row["unit_symbol"], true);
                                                                                                                                                                                                                                                                                                                                    echo "data-unit-range='" . $s . "' ";
                                                                                                                                                                                                                                                                                                                                    ?> style="width: 31px;" <?php if (in_array($row["unit_id"], $unit_disabled)) {
                                                                                                                                                                                                                                                                                                                                                                echo "disabled";
                                                                                                                                                                                                                                                                                                                                                            } ?>> <?php
                                                                                                                                                                                                                                                                                                                                                                    if (isset($template) && in_array($row["unit_id"], $elements_grouped_ids["units"])) {
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
<!-- Group Modal -->
<div class="modal fade" id="GroupListModel" tabindex="-1" role="dialog" aria-labelledby="GroupListModelTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered  modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold" id="GroupListModelTitle">Units</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php
                $q = "SELECT * FROM `groups`";
                $res = mysqli_query($con, $q);
                while ($row = mysqli_fetch_assoc($res)) {
                ?>
                    <div class="card mb-3">
                        <div class="card-body pt-2 pb-2">
                            <div class="row">
                                <div class="col-10 my-auto unit_text">
                                    <?php
                                    if ($row["group_text"] == $row["group_dtext"]) {
                                        echo $row["group_text"];
                                    } else {
                                        echo $row["group_dtext"] . "<br><span class='text-secondary'>" . $row["group_text"] . "</span>";
                                    }
                                    ?>
                                </div>
                                <div class="col-2 text-center pl-0 pr-0">
                                    <button class="g_btn_add_remove btn <?php
                                                                        if (isset($template) && in_array($row["group_id"], $elements_grouped_ids["groups"])) {
                                                                            echo "btn-danger";
                                                                        } else {
                                                                            echo "btn-success";
                                                                        }
                                                                        ?> btn-sm" id="g-btn-add-remove-<?php echo $row["group_id"]; ?>" type="button" data-group-id="<?php echo $row["group_id"]; ?>" data-group-text="<?php echo $row["group_text"]; ?>" data-group-unit="<?php echo implode(",", json_decode($row["group_unit"], true)); ?>" data-group-dtext="<?php echo $row["group_dtext"]; ?>" style="width: 31px;"> <?php
                                                                                                                                                                                                                                                                                                                                                                                                                            if (isset($template) && in_array($row["group_id"], $elements_grouped_ids["groups"])) {
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

        $("#element_list").sortable();
        $(".u_btn_add_remove").click(function() {
            if ($(this).hasClass("btn-success")) {
                var unit_id = $(this).attr("data-unit-id");
                var unit_text = $(this).attr("data-unit-text");
                var unit_dtext = $(this).attr("data-unit-dtext");
                var unit_symbol = $(this).attr("data-unit-symbol");
                var unit_range = $(this).attr("data-unit-range");
                if (unit_text == unit_dtext) {
                    var s = "<div class='card mb-2'><div class='card-body pt-2 pb-2'><div class='row'><div class='col-6 d-md-none my-auto'>Unit Text</div><div class='col-6 my-auto'>U - " + unit_text + "</div><div class='col-6 d-md-none my-auto'>Symbol</div><div class='col-md-2 col-6 my-auto'>" + unit_symbol + "</div><div class='col-6 d-md-none my-auto'>Range</div><div class='col-md-2 col-6 my-auto'>" + unit_range + "</div><div class='col-6 d-md-none my-auto'>Action</div><div class='col-md-2 col-6 text-md-center pt-2 pb-2'><button class='u_btn_remove btn btn-outline-danger btn-sm' type='button' id='list-unit-" + unit_id + "' data-unit-id='" + unit_id + "' style='width: 31px;'><i class='fa fa-trash'></i></button></div></div><input type='hidden' name='element[]' value='{\"type\":\"unit\",\"id\":\"" + unit_id + "\"}'></div></div>"; //
                } else {
                    var s = "<div class='card mb-2'><div class='card-body pt-2 pb-2'><div class='row'><div class='col-6 d-md-none my-auto'>Unit Text</div><div class='col-6 my-auto'>U - " + unit_dtext + "<br><span class='text-secondary'>" + unit_text + "</span></div><div class='col-6 d-md-none my-auto'>Symbol</div><div class='col-md-2 col-6 my-auto'>" + unit_symbol + "</div><div class='col-6 d-md-none my-auto'>Range</div><div class='col-md-2 col-6 my-auto'>" + unit_range + "</div><div class='col-6 d-md-none my-auto'>Action</div><div class='col-md-2 col-6 text-md-center pt-2 pb-2'><button class='u_btn_remove btn btn-outline-danger btn-sm' type='button' id='list-unit-" + unit_id + "' data-unit-id='" + unit_id + "' style='width: 31px;'><i class='fa fa-trash'></i></button></div></div><input type='hidden' name='element[]' value='{\"type\":\"unit\",\"id\":\"" + unit_id + "\"}'></div></div>";
                }
                $("#element_list").append(s);
                $("#list-unit-" + unit_id).click(function() {
                    var unit_id = $(this).attr("data-unit-id");
                    console.log(unit_id);
                    $(this).parent().parent().parent().parent().remove();
                    $("#u-btn-add-remove-" + unit_id).removeClass("btn-danger");
                    $("#u-btn-add-remove-" + unit_id).addClass("btn-success");
                    $("#u-btn-add-remove-" + unit_id).html("<i class='fa fa-plus'></i>");
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
        $(".u_btn_remove").click(function() {
            var unit_id = $(this).attr("data-unit-id");
            console.log(unit_id);
            $(this).parent().parent().parent().parent().remove();
            $("#u-btn-add-remove-" + unit_id).removeClass("btn-danger");
            $("#u-btn-add-remove-" + unit_id).addClass("btn-success");
            $("#u-btn-add-remove-" + unit_id).html("<i class='fa fa-plus'></i>");
        });

        $(".g_btn_add_remove").click(function() {
            var unit = $(this).attr("data-group-unit");
            unit = unit.split(",");
            if ($(this).hasClass("btn-success")) {

                unit.forEach(function(unit_id) {
                    if ($("#u-btn-add-remove-" + unit_id).hasClass("btn-success")) {
                        $("#u-btn-add-remove-" + unit_id).prop('disabled', true);
                    } else {
                        $("#u-btn-add-remove-" + unit_id).click();
                        $("#u-btn-add-remove-" + unit_id).prop('disabled', true);
                    }
                });

                var group_id = $(this).attr("data-group-id");
                var group_text = $(this).attr("data-group-text");
                var group_dtext = $(this).attr("data-group-dtext");
                if (group_text == group_dtext) {
                    var s = "<div class='card mb-2'><div class='card-body pt-2 pb-2'><div class='row'><div class='col-6 d-md-none my-auto'>Group Text</div><div class='col-10 my-auto'>G - " + group_text + "</div><div class='col-6 d-md-none my-auto'>Action</div><div class='col-md-2 col-6 text-md-center pt-2 pb-2'><button class='g_btn_remove btn btn-outline-danger btn-sm' type='button' id='list-group-" + group_id + "' data-group-id='" + group_id + "' data-group-unit='" + $(this).attr("data-group-unit") + "' style='width: 31px;'><i class='fa fa-trash'></i></button></div></div><input type='hidden' name='element[]' value='{\"type\":\"group\",\"id\":\"" + group_id + "\"}'></div></div>";
                } else {
                    var s = "<div class='card mb-2'><div class='card-body pt-2 pb-2'><div class='row'><div class='col-6 d-md-none my-auto'>Group Text</div><div class='col-10 my-auto'>G - " + group_dtext + "<br><span class='text-secondary'>" + group_text + "</span></div><div class='col-6 d-md-none my-auto'>Action</div><div class='col-md-2 col-6 text-md-center pt-2 pb-2'><button class='g_btn_remove btn btn-outline-danger btn-sm' type='button' id='list-group-" + group_id + "' data-group-id='" + group_id + "' data-group-unit='" + $(this).attr("data-group-unit") + "' style='width: 31px;'><i class='fa fa-trash'></i></button></div></div><input type='hidden' name='element[]' value='{\"type\":\"group\",\"id\":\"" + group_id + "\"}'></div></div>";
                }
                $("#element_list").append(s);
                $("#list-group-" + group_id).click(function() {
                    var unit = $(this).attr("data-group-unit");
                    unit = unit.split(",");
                    unit.forEach(function(unit_id) {
                        $("#u-btn-add-remove-" + unit_id).prop('disabled', false);
                    });
                    var group_id = $(this).attr("data-group-id");
                    $(this).parent().parent().parent().parent().remove();
                    $("#g-btn-add-remove-" + group_id).removeClass("btn-danger");
                    $("#g-btn-add-remove-" + group_id).addClass("btn-success");
                    $("#g-btn-add-remove-" + group_id).html("<i class='fa fa-plus'></i>");
                });
                $(this).removeClass("btn-success");
                $(this).addClass("btn-danger");
                $(this).html("<i class='fa fa-minus'></i>");
            } else {

                unit.forEach(function(unit_id) {
                    $("#u-btn-add-remove-" + unit_id).prop('disabled', false);
                });

                var group_id = $(this).attr("data-group-id");
                $("#list-group-" + group_id).parent().parent().parent().parent().remove();
                $(this).removeClass("btn-danger");
                $(this).addClass("btn-success");
                $(this).html("<i class='fa fa-plus'></i>");
            }
        });
        $(".g_btn_remove").click(function() {
            var unit = $(this).attr("data-group-unit");
            unit = unit.split(",");
            unit.forEach(function(unit_id) {
                $("#u-btn-add-remove-" + unit_id).prop('disabled', false);
            });
            var group_id = $(this).attr("data-group-id");
            $(this).parent().parent().parent().parent().remove();
            $("#g-btn-add-remove-" + group_id).removeClass("btn-danger");
            $("#g-btn-add-remove-" + group_id).addClass("btn-success");
            $("#g-btn-add-remove-" + group_id).html("<i class='fa fa-plus'></i>");
        });
    });
</script>
<?php include("footer.php"); ?>