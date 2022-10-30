<?php
include("config.php");
?>
<?php include("header.php"); ?>
<main class="container">
    <div class="row pb-2">
        <?php
        if (isset($login_user)) {
        ?>
            <div class="col-12 pt-4">
                <a href="my_querys.php"><button class="btn btn-success btn-sm float-right">View Posted Query</button></a>
            </div>
            <div class="col-12 pt-4">
                <div class="card">
                    <div class="card-header pb-2 pt-3">
                        <h5>is There Any Unusal Thing Happen With Your Account?</h5>
                    </div>
                    <div class="card-body">
                        <h6>After Login With You Account Visit <a href="<?php echo $lab_url; ?>query.php"><?php echo $lab_url; ?>query.php</a> </h6>
                        <h6>Fill Up Your Details and Submit</h6>
                        <h6>Our Team Solve Your Query</h6>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
        <div class="col-12 pt-5">
            <h2 class="font-weight-bold">Patient</h2>
            <div class="accordion" id="accordionpatient">
                <div class="card">
                    <div class="card-header pb-2 pt-1" id="headingpatient1">
                        <h2 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapsepatient1" aria-expanded="true" aria-controls="collapsepatient1">
                                How to Register
                            </button>
                        </h2>
                    </div>
                    <!--show-->
                    <div id="collapsepatient1" class="collapse" aria-labelledby="headingpatient1" data-parent="#accordionpatient">
                        <div class="card-body pl-5">
                            <h6>Is You New on India Helth Master?</h6>
                            <h6>No Tesion Just Visit <a href="<?php echo $lab_url; ?>signin.php"><?php echo $lab_url; ?>signin.php</a> and Register With as</h6>

                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header pb-2 pt-1" id="headingpatient2">
                        <h2 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapsepatient2" aria-expanded="false" aria-controls="collapsepatient2">
                                How to See My Reports
                            </button>
                        </h2>
                    </div>
                    <div id="collapsepatient2" class="collapse" aria-labelledby="headingpatient2" data-parent="#accordionpatient">
                        <div class="card-body pl-5  ">
                            <h6>Visit <a href="<?php echo $lab_url; ?>signin.php"><?php echo $lab_url; ?>signin.php</a> and Sign in, Then Click on My Reports</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 pt-5">
            <h2 class="font-weight-bold">Doctor</h2>
            <div class="accordion" id="accordiondoctor">
                <div class="card">
                    <div class="card-header pb-2 pt-1" id="headingdoctor1">
                        <h2 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapsedoctor1" aria-expanded="true" aria-controls="collapsedoctor1">
                                How to Register as Doctor
                            </button>
                        </h2>
                    </div>
                    <div id="collapsedoctor1" class="collapse" aria-labelledby="headingdoctor1" data-parent="#accordiondoctor">
                        <div class="card-body pl-5">
                            <h6>Is You New on India Helth Master?</h6>
                            <h6>No Tesion Just Visit <a href="<?php echo $lab_url; ?>signin.php"><?php echo $lab_url; ?>signin.php</a> and Register With as</h6>
                            <h6>After That Visit <a href="<?php echo $lab_url; ?>doctor/register.php"><?php echo $lab_url; ?>doctor/register.php</a> </h6>
                            <h6>Fill Up Your Details and The Wait For Approvel</h6>
                            <h6>Our Team Review You Request</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 pt-5">
            <h2 class="font-weight-bold">Laboratory</h2>
            <div class="accordion" id="accordionlab">
                <div class="card">
                    <div class="card-header pb-2 pt-1" id="headinglab1">
                        <h2 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapselab1" aria-expanded="true" aria-controls="collapselab1">
                                How to Register as Laboratory
                            </button>
                        </h2>
                    </div>
                    <div id="collapselab1" class="collapse" aria-labelledby="headinglab1" data-parent="#accordionlab">
                        <div class="card-body pl-5">
                            <h6>Is You New on India Helth Master?</h6>
                            <h6>No Tesion Just Visit <a href="<?php echo $lab_url; ?>signin.php"><?php echo $lab_url; ?>signin.php</a> and Register With as</h6>
                            <h6>After That Visit <a href="<?php echo $lab_url; ?>laboratory/register.php"><?php echo $lab_url; ?>laboratory/register.php</a> </h6>
                            <h6>Fill Up Your Details and The Wait For Approvel</h6>
                            <h6>Our Team Review You Request</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include("footer.php"); ?>