<?php
include("config.php");
if (isset($_GET["report"])) {
    $report_id = get_num("report");
    report_render($report_id, true, array(array("reports.php", "Back to Reports"), array("index.php", "Back to Dahsboard")));
}
