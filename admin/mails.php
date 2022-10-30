<?php
include("../config.php");
?>
<?php
if (isset($_GET["id"])) {
    $id = get_num("id");
    $q = "SELECT * FROM `mail` WHERE `mail_id` = '$id'";
    $Res = mysqli_query($con, $q);
    if (mysqli_num_rows($Res) == 1) {
        $row = mysqli_fetch_assoc($Res);
        echo $row["mail_text"];
?>
        <center>
            <button onclick="location.href='mails.php'" style="margin-top: 50px;">Go Back</button>
        </center>
    <?php
    } else {
        header("location:mails.php");
    }
    ?>

<?php
} else {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css">

    </head>

    <body style="background-color: #ffffcf; font-weight: bold;">
        <main class="container">
            <div class="row">
                <div class="col-12 pt-5 pb-4 text-center">
                    <h2>All Users</h2>
                </div>
                <div class="col-12">
                    <table class="table table-striped">
                        <tr>
                            <th>id</th>
                            <th>Username id</th>
                            <th>From </th>
                            <th>View</th>
                            <th>Date</th>
                        </tr>
                        <?php
                        $q = "SELECT * FROM `mail` ORDER BY `mail_id` DESC";
                        $Res = mysqli_query($con, $q);
                        while ($row = mysqli_fetch_assoc($Res)) {
                        ?>
                            <tr>
                                <td><?php echo $row["mail_id"]; ?></td>
                                <td><?php echo $row["mail_user"]; ?></td>
                                <td><?php echo $row["mail_from"]; ?></td>
                                <td><button onclick="location.href='mails.php?id=<?php echo $row['mail_id']; ?>'" class="btn btn-success">View</button></td>
                                <td><?php echo date("d-m-Y", strtotime($row["mail_timestamp"])) . "<br>" . date("h:i A", strtotime($row["mail_timestamp"])); ?></td>

                            </tr>
                        <?php
                        }
                        ?>
                    </table>
                </div>
            </div>
        </main>
    </body>

    </html>
<?php
}
?>