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
                            <h4 class="text-dark mb-4">Create an Hospital Account!</h4>
                        </div>
                        <form class="user" action="process.php" method="post">
                            <input type="hidden" name="form_name" value="add_hospital_form">
                            <?php
                            if (isset($_GET['error']) && $_GET['error'] == 'Y') {
                                ?>
                                <p style="text-align: center;color: red;"><?php echo $_SESSION['add_new_hospital_error']; ?></p>
                            <?php } ?>
                            <p id="error"></p>
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0"><input class="form-control form-control-user" type="email" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Email Address" name="email" required></div>
                                <div class="col-sm-6 mb-3 mb-sm-0"><input class="form-control form-control-user" type="text" id="username" aria-describedby="username" placeholder="Username" name="username" required></div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0"><input class="form-control form-control-user" type="text" id="exampleFirstName" placeholder="Hospital Name" name="hospital_name" required></div>
                                <div class="col-sm-6 mb-3 mb-sm-0"><input class="form-control form-control-user" type="text" id="phone" aria-describedby="phone" placeholder="phone" name="phone" required></div>
                            </div>


                            <div class="form-group">
                                <input class="form-control" type="text" id="location" aria-describedby="location" placeholder="Address" name="location" required>
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

                            <div class="form-group">
                                <select class="form-control" name="type" id="type" required>
                                    <option selected disabled>Please Select Hospital Type</option>
                                    <option value="0">Governmental</option>
                                    <option value="1">Private</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <input class="form-control form-control-user" type="password" id="examplePasswordInput" placeholder="Password" name="password" required>
                            </div>

                            <button class="btn btn-primary btn-block text-white btn-user" type="submit">Register Account</button>
                            <hr>
                        </form>
<!--                        <div class="text-center"><a class="small" href="forgot-password.html">Forgot Password?</a></div>-->
                        <div class="text-center"><a class="small" href="index.php">Already have an account? Login!</a></div>
                        <div class="text-center"><a class="large" href="register.php">Create Doctor Account!</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>

<?php require_once 'footer.php'?>
