<?php
include("config.php");
?>
<?php include("header.php"); ?>
<style>
    #my_curo::after {
        content: "";
        position: absolute;
        height: 0;
        width: 0;
        left: 0;
        bottom: 0;
        border: 100px solid transparent;
        border-left: 50vw solid #84f5f1;
        border-right: 50vw solid #84f5f1;
        border-bottom: 6px solid #84f5f1;
    }
</style>
<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner text-center bg-primary pt-5" id="my_curo">
        <div class="carousel-item active">
            <div class="row" style="min-height: 500px;">
                <div class="col-md-4 col-10 offset-1 my-auto">
                    <h2 class="font-weight-bold">Why <?php echo $website_name; ?></h2>
                    <h4><?php echo $website_name; ?> is a Wildly Used Online Platform for Creating Medical Reports.</h4>
                    <?php
                    if (isset($login_user)) {
                    ?>
                        <a href="my_report.php"><button class="btn btn-signup btn-lg font-weight-bold mt-2">View My Reports</button></a>
                    <?php
                    } else {
                    ?>
                        <a href="signin.php"><button class="btn btn-signup btn-lg font-weight-bold mt-2">Sign Up Now</button></a>
                    <?php
                    }
                    ?>
                </div>
                <div class="col-6 d-md-block d-none offset-md-1 my-auto">
                    <img src="public/home/banner-main2.png" height="500">
                </div>
            </div>
        </div>
        <div class="carousel-item">
            <div class="row" style="min-height: 500px;">
                <div class="col-md-4 col-10 offset-1 my-auto">
                    <h2 class="font-weight-bold">Why <?php echo $website_name; ?></h2>
                    <h4><?php echo $website_name; ?> is Recomended By May Doctors.</h4>
                    <?php
                    if (isset($login_user)) {
                    ?>
                        <a href="my_report.php"><button class="btn btn-signup btn-lg font-weight-bold mt-2">View My Reports</button></a>
                    <?php
                    } else {
                    ?>
                        <a href="signin.php"><button class="btn btn-signup btn-lg font-weight-bold mt-2">Sign Up Now</button></a>
                    <?php
                    }
                    ?>
                </div>
                <div class="col-6 d-md-block d-none offset-md-1 my-auto">
                    <img src="public/home/banner-main.png" height="500">
                </div>
            </div>
        </div>
        <div class="carousel-item">
            <div class="row" style="min-height: 500px;">
                <div class="col-md-4 col-10 offset-1 my-auto">
                    <h2 class="font-weight-bold">Why <?php echo $website_name; ?></h2>
                    <h4>No Need To Cary Papers, All is Digital.</h4>
                    <?php
                    if (isset($login_user)) {
                    ?>
                        <a href="my_report.php"><button class="btn btn-signup btn-lg font-weight-bold mt-2">View My Reports</button></a>
                    <?php
                    } else {
                    ?>
                        <a href="signin.php"><button class="btn btn-signup btn-lg font-weight-bold mt-2">Sign Up Now</button></a>
                    <?php
                    }
                    ?>
                </div>
                <div class="col-6 d-md-block d-none offset-md-1 my-auto">
                    <img src="public/home/banner-main3.png" height="500">
                </div>
            </div>
        </div>
    </div>
    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
<script>
    $(document).ready(function() {
        $('.carousel').carousel({
            interval: 6000
        });
    });
</script>
<main style="background-color: white;">
    <div class="container">
        <div class="row">
            <div class="col-12 pt-5">
                <h2>Welcome to, <?php echo $website_name; ?></h2>
            </div>
            <div class="col-12 mt-4">
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card  border border-primary">
                            <div class="card-body">
                                <div class="row" style="height: 120px;">
                                    <div class="col-10  offset-1 my-auto">
                                        <h4>Creating Reports Made Easy</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card  border border-primary">
                            <div class="card-body">
                                <div class="row" style="height: 120px;">
                                    <div class="col-10  offset-1 my-auto">
                                        <h4>Patient Can See Their Reports Online</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card  border border-primary">
                            <div class="card-body">
                                <div class="row" style="height: 120px;">
                                    <div class="col-10  offset-1 my-auto">
                                        <h4>No Longer Paper Waste</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 offset-lg-4 mb-4">
                        <div class="card  border border-primary">
                            <div class="card-body">
                                <div class="row" style="height: 120px;">
                                    <div class="col-10  offset-1 my-auto">
                                        <h4>Doctors Can See Patient Reports</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include("footer.php"); ?>