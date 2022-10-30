<?php
include("config.php");
if (isset($_POST["submit"])) {
    $name = mysqli_real_escape_string($con, post_str("name"));
    $email = mysqli_real_escape_string($con, post_str("email"));
    $subject = mysqli_real_escape_string($con, post_str("subject"));
    $message = mysqli_real_escape_string($con, post_str("message"));
    $qry = "INSERT INTO `contacts`(`contact_id`, `contact_name`, `contact_email`, `contact_subject`, `contact_message`, `contact_status`) VALUES (NULL, '$name', '$email', '$subject', '$message', '0')";
    if (mysqli_query($con, $qry)) {
        $_SESSION["smsg"][] = "Your Contact Request Subbmited";
        $_SESSION["smsg"][] = "Our Team Will Contact Back You as Soon as Posible";
        header("location:index.php");
    } else {
        $_SESSION["amsg"][] = "There is Some Techinical Issue";
        $_SESSION["amsg"][] = "Request Not Submited";
        $_SESSION["amsg"][] = "Try again Later";
        header("location:contact.php");
    }
}
?>
<?php include("header.php"); ?>
<main class="container">
    <div class="row pt-5">
        <div class="col-md-6 pb-4">
            <u style="color:#f15a29">
                <h1>India Helth</h1>
            </u>
            <div class="row">
                <div class="col-12 mt-4">
                    <h4 class="font-weight-bold">Delhi Head Branch</h4>
                    <h5 class="font-weight-bold pl-4">Stret No. 45, Delhi-451226</h5>
                    <h5 class="font-weight-bold pl-4">+91 2654595644</h5>
                </div>
                <div class="col-12 mt-4">
                    <h4 class="font-weight-bold">Ahemdabad Branch</h4>
                    <h5 class="font-weight-bold pl-4">Stret No. 1, Ahmdabad-541226</h5>
                    <h5 class="font-weight-bold pl-4">+91 2654595555</h5>
                </div>
            </div>
        </div>
        <div class="col-md-6 p-3 pl-4 pr-4">
            <?php
            $name = "";
            $email = "";
            if (isset($login_user)) {
                $name = $login_user["user_name"];
                $email = $login_user["user_email"];
            }
            ?>
            <form method="post">
                <div class="card h-100 border-success">
                    <div class="card-body pb-0">
                        <div class="form-group row">
                            <div class="col-12 mb-3">
                                <label for="name">Your Name : </label>
                                <input type="text" name="name" id="name" class="form-control" value="<?php echo $name; ?>" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="email">Your Email : </label>
                                <input type="email" name="email" id="email" class="form-control" value="<?php echo $email; ?>" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="subject">Subject : </label>
                                <input type="text" name="subject" id="subject" class="form-control" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="message">Message : </label>
                                <textarea name="message" id="message" class="form-control" rows="4" required></textarea>
                            </div>
                            <div class="col-12">
                                <input type="submit" value="Submit" name="submit" class="btn btn-success btn-lg">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main> <?php include("footer.php"); ?>