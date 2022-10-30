<?php
include("config.php");
?>
<?php include("header.php"); ?>
<main class="container">
    <div class="row">
        <?php

        $units = array();
        $unit_select = "SELECT * FROM `units` WHERE `unit_status` = '1'";
        $unit_res = mysqli_query($con, $unit_select);
        while ($unit = mysqli_fetch_assoc($unit_res)) {
            $unit["unit_range"] = json_decode($unit["unit_range"], true);
            $units[$unit["unit_id"]] = $unit;
        }

        $groups = array();
        $group_select = "SELECT * FROM `groups` WHERE `group_status` = '1'";
        $group_res = mysqli_query($con, $group_select);
        while ($group = mysqli_fetch_assoc($group_res)) {
            $group["group_unit"] = json_decode($group["group_unit"], true);
            $new_units = array();
            foreach ($group["group_unit"] as $uni) {
                $new_units[$uni] = $units[intval($uni)];
            }
            $group["group_unit"] = $new_units;
            $groups[$group["group_id"]] = $group;
        }

        $templates = array();
        $template_select = "SELECT * FROM `templates` WHERE `template_status` = '1'";
        $template_res = mysqli_query($con, $template_select);
        while ($template = mysqli_fetch_assoc($template_res)) {
            $template["template_element"] = json_decode($template["template_element"], true);
            $templates[$template["template_id"]] = $template;
        }
        ?>

        <div class="col-12 mt-4">
            <h3 class="text-center mt-3 pb-3">Templates</h3>
            <div class="row">
                <div class="col-12">
                    <div class="accordion" id="templates">
                        <?php
                        foreach ($templates as $template) {
                        ?>
                            <div class="card">
                                <div class="card-header pb-2 pt-1" id="template-heading-<?php echo $template["template_id"]; ?>">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#template-lable-<?php echo $template["template_id"]; ?>" aria-expanded="true" aria-controls="template-lable-<?php echo $template["template_id"]; ?>">
                                            <?php echo $template["template_text"]; ?>
                                        </button>
                                    </h2>
                                </div>
                                <div id="template-lable-<?php echo $template["template_id"]; ?>" class="collapse" aria-labelledby="template-heading-<?php echo $template["template_id"]; ?>" data-parent="#templates">
                                    <div class="card-body pl-5">
                                        <div class="col-12">
                                            <div class="accordion" id="templates-<?php echo $template["template_id"]; ?>">
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</main> <?php include("footer.php"); ?>