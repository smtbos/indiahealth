<?php
include("config.php");
if (isset($_GET["a"])) {
	$id = get_num("a");
	mysqli_query($con, "UPDATE `units` SET `unit_status` = '1' WHERE `unit_id` = '$id'");
	$_SESSION["smsg"][] = "Unit Activated";
	header("location:units.php");
	exit();
}
if (isset($_GET["d"])) {
	$id = get_num("d");
	mysqli_query($con, "UPDATE `units` SET `unit_status` = '0' WHERE `unit_id` = '$id'");
	$_SESSION["smsg"][] = "Unit Deactivated";
	header("location:units.php");
	exit();
}
if (isset($_POST["add"])) {
	$flag = true;

	$text = post_str("text");
	if (empty($text)) {
		$flag = false;
		$_SESSION["amsg"][] = "Unit Name Can Not Be Empty";
	}

	$dtext = post_str("dtext");
	if (empty($dtext)) {
		$flag = false;
		$_SESSION["amsg"][] = "Unit Display Text Can Not Be Empty";
	}

	$symbol = post_str("symbol");


	$options = post_arr("option");
	$options = array_filter($options);
	$options = array_values($options);

	$range = array(
		"status" => isset($_POST["range"]),
		"fix" => isset($_POST["fixrange"]),
		"diff" => isset($_POST["diffrange"]),
	);
	if ($range["diff"]) {
		$range["range"] = array(
			"male" => array(
				"start" => intval($_POST["astart"]["male"]),
				"end" => intval($_POST["aend"]["male"])
			),
			"female" => array(
				"start" => intval($_POST["astart"]["female"]),
				"end" => intval($_POST["aend"]["female"])
			),
			// "child" => array(
			// 	"start" => intval($_POST["astart"]["child"]),
			// 	"end" => intval($_POST["aend"]["child"])
			// )
		);
	} else {
		$range["range"] = array(
			"all" => array(
				"start" => post_number("start"),
				"end" => post_number("end")
			)
		);
	}
	$general = intval(isset($_POST["general"]));
	$status = intval(isset($_POST["status"]));

	if ($flag) {
		$user_id = $_SESSION["user_id"];
		$text = mysqli_real_escape_string($con, $text);
		$dtext = mysqli_real_escape_string($con, $dtext);
		$options = mysqli_real_escape_string($con, json_encode($options));
		$symbol = mysqli_real_escape_string($con, $symbol);
		$range = mysqli_real_escape_string($con, json_encode($range));

		$unit_insert = "INSERT INTO `units`(`unit_id`, `unit_user`, `unit_text`, `unit_dtext`, `unit_option`, `unit_symbol`, `unit_range`, `unit_general`, `unit_status`) VALUES (NULL, '$user_id', '$text', '$dtext', '$options', '$symbol', '$range', '$general', '$status')";
		if (mysqli_query($con, $unit_insert)) {
			$_SESSION["smsg"][] = "Unit Added";
			header("location:units.php");
		} else {
			$_SESSION["amsg"][] = "Error";
			header("location:units.php");
		}
	} else {
		header("location:units.php");
	}
}
if (isset($_GET["edit"])) {
	$id = get_num("edit");
	$unit_result = mysqli_query($con, "SELECT * FROM `units` WHERE `unit_id` = '$id'");
	if (mysqli_num_rows($unit_result) == 1) {
		$unit = mysqli_fetch_assoc($unit_result);
	} else {
		$_SESSION["amsg"][] = "Invalid Action";
		$cascad = true;
		header("location:units.php");
	}
}
if (isset($_POST["update"])) {

	$flag = true;

	$unit_id = post_num("id");

	$text = post_str("text");
	if (empty($text)) {
		$flag = false;
		$_SESSION["amsg"][] = "Unit Name Can Not Be Empty";
	}

	$dtext = post_str("dtext");
	if (empty($dtext)) {
		$flag = false;
		$_SESSION["amsg"][] = "Unit Display Text Can Not Be Empty";
	}

	$symbol = post_str("symbol");

	$options = post_arr("option");
	$options = array_filter($options);
	$options = array_values($options);

	$range = array(
		"status" => isset($_POST["range"]),
		"fix" => isset($_POST["fixrange"]),
		"diff" => isset($_POST["diffrange"]),
	);
	if ($range["diff"]) {
		$range["range"] = array(
			"male" => array(
				"start" => intval($_POST["astart"]["male"]),
				"end" => intval($_POST["aend"]["male"])
			),
			"female" => array(
				"start" => intval($_POST["astart"]["female"]),
				"end" => intval($_POST["aend"]["female"])
			),
			// "child" => array(
			// 	"start" => intval($_POST["astart"]["child"]),
			// 	"end" => intval($_POST["aend"]["child"])
			// )
		);
	} else {
		$range["range"] = array(
			"all" => array(
				"start" => post_number("start"),
				"end" => post_number("end")
			)
		);
	}

	$general = intval(isset($_POST["general"]));
	$status = intval(isset($_POST["status"]));

	if ($flag) {
		$user_id = $_SESSION["user_id"];
		$text = mysqli_real_escape_string($con, $text);
		$dtext = mysqli_real_escape_string($con, $dtext);
		$options = mysqli_real_escape_string($con, json_encode($options));
		$symbol = mysqli_real_escape_string($con, $symbol);
		$range = mysqli_real_escape_string($con, json_encode($range));


		$unit_update = "UPDATE `units` SET `unit_text` = '$text', `unit_dtext` = '$dtext', `unit_option` = '$options', `unit_symbol` = '$symbol', `unit_range` = '$range', `unit_general` = '$general', `unit_status` = '$status' WHERE `unit_id` = '$unit_id'";
		if (mysqli_query($con, $unit_update)) {
			$_SESSION["smsg"][] = "Unit Updated";
			header("location:units.php");
		} else {
			$_SESSION["amsg"][] = "Error";
			header("location:units.php");
		}
	} else {
		header("location:units.php?edit=" . $unit_id);
	}
}
?>
<?php include("header.php"); ?>
<main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 pt-4">
	<div class="row">
		<div class="col-12">
			<?php
			if (isset($unit)) {
			?>
				<form action="" method="POST">
					<div class="card border-primary">
						<div class="card-header bg-primary text-white">
							<i class="fa fa-edit"></i> Edit Unit
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-lg-4">
									<div class="form-group mb-3">
										<label class="mb-1" for="text"><i class="fa fa-square"></i> Unit Name :</label>
										<div>
											<input type="text" name="text" id="text" class="form-control" value="<?php echo $unit["unit_text"]; ?>">
										</div>
									</div>
								</div>
								<div class="col-lg-4">
									<div class="form-group mb-3">
										<label class="mb-1" for="dtext"><i class="fa fa-square"></i> Unit Display Text:</label>
										<div>
											<input type="text" name="dtext" id="dtext" class="form-control" value="<?php echo $unit["unit_dtext"]; ?>">
										</div>
									</div>
								</div>
								<div class="col-lg-4">
									<div class="form-group mb-3">
										<label class="mb-1" for="symbol"><i class="fa fa-percent"></i> Unit Symbol:</label>
										<div>
											<input type="text" name="symbol" id="symbol" class="form-control" value="<?php echo $unit["unit_symbol"]; ?>">
										</div>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group mb-3">
										<label class="mb-1"><i class="fa fa-list"></i> Unit Options:</label>
										<div id="option_list">
											<?php
											$options = json_decode($unit["unit_option"], true);
											if (count($options) == 0) {
												$options[] = "";
											}
											foreach ($options as $op) {
											?>
												<div class="input-group mb-2">
													<input type="text" class="form-control" name="option[]" value="<?php echo $op; ?>">
													<div class="input-group-append">
														<button class="btn btn-danger me_remove" type="button">Delete</button>
													</div>
												</div>
											<?php
											}
											?>
										</div>
										<center>
											<button class="btn btn-success mt-2 btn-sm" id="btn_add_option" type="button">Add Option</button>
										</center>
									</div>
								</div>
								<div class="col-lg-6 pt-2">
									<div class="form-group mb-3 row pt-4">
										<div class="col-6">
											<label class="mb-1" for="range"><i class="fa fa-arrows-h"></i> Numaric Range:</label>
										</div>
										<div class="col-6">
											<?php
											$range = json_decode($unit["unit_range"], true);
											?>
											<div class="custom-control custom-switch ">
												<input type="checkbox" class="custom-control-input" id="range" name="range" <?php if ($range["status"]) {
																																echo "checked";
																															} ?>>
												<label class="custom-control-label" for="range"></label>
											</div>
										</div>
										<div class="col-12" <?php if (!$range["status"]) {
																echo "style='display: none;'";
															} ?> id="range_values">
											<div class="card mt-2 pb-0" id="range_value">
												<div class="card-body">
													<div class="form-group row mb-2">
														<div class="col-lg-3 col-6">
															<label for="diffrange">Diffrent for Gander :</label>
														</div>
														<div class="col-lg-3 col-6">
															<div class="custom-control custom-switch ">
																<input type="checkbox" class="custom-control-input" id="diffrange" name="diffrange" <?php if ($range["diff"]) {
																																						echo "checked";
																																					} ?>>
																<label class="custom-control-label" for="diffrange"></label>
															</div>
														</div>
														<div class="col-lg-3 col-6">
															<label for="fixrange">Fix Range :</label>
														</div>
														<div class="col-lg-3 col-6">
															<div class="custom-control custom-switch ">
																<input type="checkbox" class="custom-control-input" id="fixrange" name="fixrange" <?php if ($range["fix"]) {
																																						echo "checked";
																																					} ?>>
																<label class="custom-control-label" for="fixrange"></label>
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-12" <?php if ($range["diff"]) {
																				echo "style='display: none;'";
																			} ?> id="srange">
															<div class="form-group row mb-2 ">
																<div class="col-lg-3 col-6  my-auto">
																	<label for="start">Start From:</label>
																</div>
																<div class="col-lg-9 col-6">
																	<input type="number" class="form-control" name="start" id="start" step="any" <?php if (!$range["diff"]) {
																																						echo ' value="' . $range["range"]["all"]["start"] . '" ';
																																					} ?>>
																</div>
															</div>
															<div class="form-group row ">
																<div class="col-lg-3 col-6  my-auto">
																	<label for="end">End to:</label>
																</div>
																<div class="col-lg-9 col-6">
																	<input type="number" class="form-control" name="end" id="end" step="any" <?php if (!$range["diff"]) {
																																					echo ' value="' . $range["range"]["all"]["end"] . '" ';
																																				} ?>>
																</div>
															</div>
														</div>
														<div class="col-12" <?php if (!$range["diff"]) {
																				echo "style='display: none;'";
																			} ?> id="drange">
															<h4>Male</h4>
															<div class="form-group row mb-2 ">
																<div class="col-lg-3 col-6  my-auto">
																	<label for="astart-male">Start From:</label>
																</div>
																<div class="col-lg-9 col-6">
																	<input type="number" class="form-control" name="astart[male]" id="astart-male" step="any" <?php if ($range["diff"]) {
																																									echo ' value="' . $range["range"]["male"]["start"] . '" ';
																																								} ?>>
																</div>
															</div>
															<div class="form-group row ">
																<div class="col-lg-3 col-6  my-auto">
																	<label for="aend-male">End to:</label>
																</div>
																<div class="col-lg-9 col-6">
																	<input type="number" class="form-control" name="aend[male]" id="aend-male" step="any" <?php if ($range["diff"]) {
																																								echo ' value="' . $range["range"]["male"]["end"] . '" ';
																																							} ?>>
																</div>
															</div>
															<h4>Female</h4>
															<div class="form-group row mb-2 ">
																<div class="col-lg-3 col-6  my-auto">
																	<label for="astart-female">Start From:</label>
																</div>
																<div class="col-lg-9 col-6">
																	<input type="number" class="form-control" name="astart[female]" id="astart-female" step="any" <?php if ($range["diff"]) {
																																										echo ' value="' . $range["range"]["female"]["start"] . '" ';
																																									} ?>>
																</div>
															</div>
															<div class="form-group row ">
																<div class="col-lg-3 col-6  my-auto">
																	<label for="aend-female">End to:</label>
																</div>
																<div class="col-lg-9 col-6">
																	<input type="number" class="form-control" name="aend[female]" id="aend-female" step="any" <?php if ($range["diff"]) {
																																									echo ' value="' . $range["range"]["female"]["end"] . '" ';
																																								} ?>>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group row mb-3">
										<div class="col-sm-6 mb-1">
											<label for="general"><i class="fa fa-question-circle"></i> Unit is General :</label>
										</div>
										<div class="col-sm-6">
											<div class="custom-control custom-switch ">
												<input type="checkbox" class="custom-control-input" id="general" name="general" <?php if ($unit["unit_general"]) {
																																	echo "checked";
																																} ?>>
												<label class="custom-control-label" for="general"></label>
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group row mb-3">
										<div class="col-sm-6 mb-1">
											<label for="status"><i class="fa fa-question-circle"></i> Unit Staus :</label>
										</div>
										<div class="col-sm-6">
											<div class="custom-control custom-switch ">
												<input type="checkbox" class="custom-control-input" id="status" name="status" <?php if ($unit["unit_status"]) {
																																	echo "checked";
																																} ?>>
												<label class="custom-control-label" for="status"></label>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<input type="hidden" name="id" value="<?php echo $unit["unit_id"]; ?>">
						<div class="card-footer">
							<button type="submit" name="update" class="btn btn-info mr-3"><i class="fa fa-check"></i> Update Unit</button>
							<button type="reset" class="btn btn-warning mr-3"><i class="fa fa-undo"></i> Reset</button>
							<button class="btn btn-success" type="button" onclick="location.href='units.php'"><i class="fa fa-backward"></i> Back</button>
						</div>
					</div>
				</form>
			<?php
			} else {
			?>
				<form action="" method="POST">
					<div class="card border-primary">
						<div class="card-header bg-primary text-white">
							<i class="fa fa-plus"></i> Add Unit
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-lg-4">
									<div class="form-group mb-3">
										<label class="mb-1" for="text"><i class="fa fa-square"></i> Unit Name :</label>
										<div>
											<input type="text" name="text" id="text" class="form-control">
										</div>
									</div>
								</div>
								<div class="col-lg-4">
									<div class="form-group mb-3">
										<label class="mb-1" for="dtext"><i class="fa fa-square"></i> Unit Display Text :</label>
										<div>
											<input type="text" name="dtext" id="dtext" class="form-control">
										</div>
									</div>
								</div>
								<div class="col-lg-4">
									<div class="form-group mb-3">
										<label class="mb-1" for="symbol"><i class="fa fa-percent"></i> Unit Symbol :</label>
										<div>
											<input type="text" name="symbol" id="symbol" class="form-control">
										</div>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group mb-3">
										<label class="mb-1"><i class="fa fa-list"></i> Unit Options :</label>
										<div id="option_list">
											<div class="input-group mb-2">
												<input type="text" class="form-control" name="option[]">
												<div class="input-group-append">
													<button class="btn btn-danger me_remove" type="button">Delete</button>
												</div>
											</div>
										</div>
										<center>
											<button class="btn btn-success mt-2 btn-sm" id="btn_add_option" type="button">Add Option</button>
										</center>
									</div>
								</div>
								<div class="col-lg-6 pt-2">
									<div class="form-group mb-3 row pt-4">
										<div class="col-6">
											<label class="mb-1" for="range"><i class="fa fa-arrows-h"></i> Numaric Range :</label>
										</div>
										<div class="col-6">
											<div class="custom-control custom-switch ">
												<input type="checkbox" class="custom-control-input" id="range" name="range">
												<label class="custom-control-label" for="range"></label>
											</div>
										</div>
										<div class="col-12" style="display: none;" id="range_values">
											<div class="card mt-2 pb-0" id="range_value">
												<div class="card-body">
													<div class="form-group row mb-2">
														<div class="col-lg-3 col-6">
															<label for="diffrange">Diffrent for Gander :</label>
														</div>
														<div class="col-lg-3 col-6">
															<div class="custom-control custom-switch ">
																<input type="checkbox" class="custom-control-input" id="diffrange" name="diffrange">
																<label class="custom-control-label" for="diffrange"></label>
															</div>
														</div>
														<div class="col-lg-3 col-6">
															<label for="fixrange">Fix Range :</label>
														</div>
														<div class="col-lg-3 col-6">
															<div class="custom-control custom-switch ">
																<input type="checkbox" class="custom-control-input" id="fixrange" name="fixrange">
																<label class="custom-control-label" for="fixrange"></label>
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-12" id="srange">
															<div class="form-group row mb-2 ">
																<div class="col-lg-3 col-6  my-auto">
																	<label for="start">Start From:</label>
																</div>
																<div class="col-lg-9 col-6">
																	<input type="number" class="form-control" name="start" id="start" step="any">
																</div>
															</div>
															<div class="form-group row ">
																<div class="col-lg-3 col-6  my-auto">
																	<label for="end">End to:</label>
																</div>
																<div class="col-lg-9 col-6">
																	<input type="number" class="form-control" name="end" id="end" step="any">
																</div>
															</div>
														</div>
														<div class="col-12" id="drange" style="display: none;">
															<h4>Male</h4>
															<div class="form-group row mb-2 ">
																<div class="col-lg-3 col-6  my-auto">
																	<label for="astart-male">Start From:</label>
																</div>
																<div class="col-lg-9 col-6">
																	<input type="number" class="form-control" name="astart[male]" id="astart-male" step="any">
																</div>
															</div>
															<div class="form-group row ">
																<div class="col-lg-3 col-6  my-auto">
																	<label for="aend-male">End to:</label>
																</div>
																<div class="col-lg-9 col-6">
																	<input type="number" class="form-control" name="aend[male]" id="aend-male" step="any">
																</div>
															</div>
															<h4>Female</h4>
															<div class="form-group row mb-2 ">
																<div class="col-lg-3 col-6  my-auto">
																	<label for="astart-female">Start From:</label>
																</div>
																<div class="col-lg-9 col-6">
																	<input type="number" class="form-control" name="astart[female]" id="astart-female" step="any">
																</div>
															</div>
															<div class="form-group row ">
																<div class="col-lg-3 col-6  my-auto">
																	<label for="aend-female">End to:</label>
																</div>
																<div class="col-lg-9 col-6">
																	<input type="number" class="form-control" name="aend[female]" id="aend-female" step="any">
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group row mb-3">
										<div class="col-6 mb-1">
											<label for="general"><i class="fa fa-question-circle"></i> Unit is General :</label>
										</div>
										<div class="col-6">
											<div class="custom-control custom-switch ">
												<input type="checkbox" class="custom-control-input" id="general" name="general">
												<label class="custom-control-label" for="general"></label>
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group row mb-3">
										<div class="col-6 mb-1">
											<label for="status"><i class="fa fa-question-circle"></i> Unit Staus :</label>
										</div>
										<div class="col-6">
											<div class="custom-control custom-switch ">
												<input type="checkbox" class="custom-control-input" id="status" name="status" checked>
												<label class="custom-control-label" for="status"></label>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card-footer">
							<button type="submit" name="add" class="btn btn-info mr-3"><i class="fa fa-plus"></i> Add Unit</button>
							<button type="reset" class="btn btn-warning mr-3"><i class="fa fa-undo"></i> Reset</button>
						</div>
					</div>
				</form>
		</div>
		<div class="col-12 pt-5">
			<table class="table table-sm " id="unit_table">
				<thead>
					<tr>
						<th>Id</th>
						<th>Text</th>
						<th>Symbol</th>
						<th>Options</th>
						<th>Range</th>
						<th>General</th>
						<th>Status</th>
						<th>Actions</th>
					</tr>
				</thead>
			</table>
			<script>
				$(document).ready(function() {
					var table = $('#unit_table').DataTable({
						"lengthMenu": [10, 25, 50, 75, 100],
						"processing": true,
						"serverSide": true,
						'serverMethod': 'post',
						"ajax": {
							url: "api.php",
							"data": function(d) {
								return $.extend({}, d, {
									"units": 1,
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
								data: "symbol"
							},
							{
								data: "option"
							},
							{
								data: "range"
							},
							{
								data: "general"
							},
							{
								data: "status"
							},
							{
								data: "action"
							},
						],
						'columnDefs': [{
							'targets': [2, 3, 4, 5, 6, 7],
							'orderable': false,
						}]
					});
				});
			</script>
		<?php
			}
		?>
		<script>
			var base = "<div class='input-group mb-2'><input type='text' class='form-control' name='option[]'><div class='input-group-append'> <button class='btn btn-danger me_remove' type='button'>Delete</button></div></div>";
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
				$("#btn_add_option").click(function() {
					$("#option_list").append(base);
					$(".me_remove").click(function() {
						$(this).parent().parent().remove();
					});
				});
				$(".me_remove").click(function() {
					$(this).parent().parent().remove();
				});
				$("#range").change(function() {
					if ($(this).is(':checked')) {
						$("#range_values").show("slow");
					} else {
						$("#range_values").hide("slow");
					}
				});
				$("#diffrange").change(function() {
					if ($(this).is(':checked')) {
						$("#drange").show("slow");
						$("#srange").hide("slow");
					} else {
						$("#srange").show("slow");
						$("#drange").hide("slow");
					}
				});
			});
		</script>
		</div>
	</div>
</main>
<?php include("footer.php"); ?>