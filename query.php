<?php
include("config.php");
if (!isset($login_user)) {
    $_SESSION["amsg"][] = "You Need to Signin Before To Post any Query";
    header("location:signin.php");
    exit();
}
if (isset($_POST["post"])) {
    $flag = true;

    $subject = post_str("subject");
    $details = post_str("details");

    if (empty($subject)) {
        $flag = false;
        $_SESSION["amsg"][] = "Please Add Subject";
    }

    if (empty($details)) {
        $flag = false;
        $_SESSION["amsg"][] = "Please Fill Details";
    }

    if ($flag) {
        $user_id = $login_user["user_id"];
        $subject = mysqli_real_escape_string($con, $subject);
        $details = mysqli_real_escape_string($con, $details);
        $q = "INSERT INTO `querys`(`query_id`, `query_user`, `query_handel`, `query_subject`, `query_details`, `query_solved`, `query_status`) VALUES (NULL, '$user_id', '0', '$subject', '$details', '0', '1')";
        if (mysqli_query($con, $q)) {
            $_SESSION["smsg"][] = "Your Query is Posted";
            header("location:query.php");
        } else {
            $_SESSION["amsg"][] = "Error";
            header("location:query.php");
        }
    } else {
        header("location:query.php");
    }
}
?>
<?php include("header.php"); ?>
<main class="container">
    <div class="row">
        <div class="col-12 pt-5">
            <h3 class="font-weight-bold text-center">Post a isuue / Query</h3>
            <div class="card mt-4">
                <div class="card-body">
                    <form method="POST">
                        <div class="form-group row">
                            <div class="col-12">
                                <label>Subject</label>
                                <input name="subject" class="form-control">
                            </div>
                            <div class="col-12 mt-4">
                                <label>Details</label>
                                <textarea name="details" class="form-control"></textarea>
                            </div>
                            <div class="col-12 mt-4">
                                <input type="submit" name="post" value="Post Query" class="btn btn-success">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12 pt-4">
            <h4 class="text-center font-weight-bold">My Querys</h4>
        </div>
        <div class="col-12 pt-3">
            <table class="table">
                <tr>
                    <th class="text-center">Query<br>id</th>
                    <th>Subject</th>
                    <th>Details</th>
                    <th>Status</th>
                </tr>
                <?php
                $user_id = $login_user["user_id"];
                $q = "SELECT * FROM `querys` WHERE `query_user` = '$user_id' ORDER BY `query_id` DESC LIMIT 10";
                $res = mysqli_query($con, $q);
                while ($row = mysqli_fetch_assoc($res)) {
                ?>
                    <tr>
                        <td class="text-center"><?php echo $row["query_id"]; ?></td>
                        <td><?php echo $row["query_subject"]; ?></td>
                        <td><?php echo $row["query_details"]; ?></td>
                        <td>
                            <?php
                            if ($row["query_solved"]) {
                                echo "Solved<br>";
                            } else {
                                echo "Not Solved<br>";
                            }
                            if ($row["query_solved"]) {
                                echo $row["query_message"];
                            }
                            ?>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </table>
        </div>
    </div>
</main>
<?php include("footer.php"); ?>