<?php
require_once 'inc.php';
$cities_table = new cities_table();
$cities_data  = $cities_table->retrieve_cities_by_county_id(42);
require_once 'head.php';
?>
<body class="bg-gradient-primary">
<div class="container">
    <div class="card shadow-lg o-hidden border-0 my-5">
        <div class="card-body p-0">
            <div class="row">
                <div class="col-lg-5 d-none d-lg-flex">
                    <div class="flex-grow-1 bg-register-image" style="background-image: url(&quot;assets/img/dogs/image2.jpeg&quot;);"></div>
                </div>
                <div class="col-lg-7">
                    <div class="p-5">
                        <div class="text-center">
                            <h4 class="text-dark mb-4">Create an Doctor Account!</h4>
                        </div>
                        <form class="user" action="process.php" method="post">
                            <input type="hidden" name="form_name" value="add_user_form">
                            <?php
                            if (isset($_GET['error']) && $_GET['error'] == 'Y') {
                                ?>
                                <p style="text-align: center;color: red;"><?php echo $_SESSION['add_new_user_error']; ?></p>
                            <?php } ?>
                            <p id="error"></p>
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0"><input class="form-control form-control-user" type="text" id="exampleFirstName" placeholder="Full Name" name="full_name" required></div>
                                <div class="col-sm-6 mb-3 mb-sm-0"><input class="form-control form-control-user" type="text" id="username" aria-describedby="username" placeholder="Username" name="username" required></div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0"><input class="form-control form-control-user" type="email" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Email Address" name="email" required></div>
                                <div class="col-sm-6 mb-3 mb-sm-0"><input class="form-control form-control-user" type="password" id="examplePasswordInput" placeholder="Password" name="password" required></div>
                            </div>
                            <div class="form-group">
                                <select class="form-control" name="city_id" id="city_id" required>
                                    <option selected disabled>Please Select City</option>
                                    <?php
                                    foreach ($cities_data as $single_city){
                                    ?>
                                    <option value="<?php echo $single_city['id'];?>"><?php echo $single_city['city_name'];?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0"><input class="form-control form-control-user" type="text" id="hospital" aria-describedby="hospital" placeholder="Hospital" name="hospital" required></div>
                                <div class="col-sm-6 mb-3 mb-sm-0"><input class="form-control form-control-user" type="text" id="speciality" placeholder="speciality" name="speciality" required></div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0"><input class="form-control form-control-user" type="text" id="cert_id" placeholder="Certification ID" name="cert_id" required></div>
                                <div class="col-sm-6 mb-3 mb-sm-0"><input class="form-control form-control-user" type="text" id="phone" placeholder="phone" name="phone" required></div>
                            </div>



                            <button class="btn btn-primary btn-block text-white btn-user" type="submit">Register Account</button>
                            <hr>
                        </form>
<!--                        <div class="text-center"><a class="small" href="forgot-password.html">Forgot Password?</a></div>-->
                        <div class="text-center"><a class="small" href="index.php">Already have an account? Login!</a></div>
                        <div class="text-center"><a class="large" href="register_hospital.php">Create hospital Account!</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>

<?php require_once 'footer.php'?>

</body>

</html>
