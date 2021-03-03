<?php
require_once 'inc.php';
$cities_table = new cities_table();
$cities_data  = $cities_table->retrieve_cities_by_county_id(42);
$users_table  = new users_table();
$user_data    = $users_table->retrieve_user_data_by_user_id(get_login_user_id());
require_once 'head.php';
?>
    <body id="page-top">
<?php require_once 'sidebar.php';?>
    <div class="d-flex flex-column" id="content-wrapper">
        <div id="content">
<?php require_once 'header.php';?>
            <div class="container-fluid">
                <h3 class="text-dark mb-4">Profile</h3>
                <div class="row mb-3">

                    <div class="col-lg-12">

                        <div class="row">
                            <div class="col">
                                <div class="card shadow mb-3">
                                    <div class="card-header py-3">
                                        <p class="text-primary m-0 font-weight-bold"><?php echo ($user_data['user_type'] == 0) ? "Doctor " : "Hospital "; ?> Data</p>
                                    </div>
                                    <div class="card-body">
                                        <?php if($user_data['user_type'] == 0) { ?>
                                        <form action="process.php" method="post">
                                            <input type="hidden" name="form_name" value="update_doctor_profile_form">
                                            <?php
                                            if (isset($_GET['error']) && $_GET['error'] == 'Y') {
                                                ?>
                                                <p style="text-align: center;color: red;"><?php echo $_SESSION['update_profile_error']; ?></p>
                                            <?php } ?>
                                            <p id="error"></p>
                                            <div class="form-row">
                                                <div class="col">
                                                    <div class="form-group"><label for="username"><strong>Username</strong></label>
                                                        <input class="form-control" type="text" placeholder="usename" name="username" value="<?php echo $user_data['username'];?>"></div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group"><label for="email"><strong>Email Address</strong></label>
                                                        <input class="form-control" type="email" placeholder="user@example.com" name="email" value="<?php echo $user_data['email'];?>"></div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col">
                                                    <div class="form-group"><label for="first_name"><strong>Full Name</strong></label>
                                                        <input class="form-control" type="text" placeholder="Full Name" name="full_name" value="<?php echo $user_data['full_name'];?>"></div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group"><label for="hospital_name"><strong>Hospital Name</strong></label>
                                                        <input class="form-control" type="text" placeholder="Hospital Name" name="hospital_name" value="<?php echo $user_data['hospital'];?>"></div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col">
                                                    <div class="form-group"><label for="speciality"><strong>Speciality</strong></label>
                                                        <input class="form-control" type="text" placeholder="Speciality" name="speciality" value="<?php echo $user_data['speciality'];?>"></div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group"><label for="cert_id"><strong>Certification ID</strong></label>
                                                        <input class="form-control" type="text" placeholder="Certification ID" name="cert_id" value="<?php echo $user_data['cert_id'];?>"></div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col">
                                                    <div class="form-group"><label for="city_id"><strong>City</strong></label>
                                                        <select class="form-control" name="city_id" id="city_id" required>
                                                            <option selected disabled>Please Select City</option>
                                                            <?php
                                                            foreach ($cities_data as $single_city){
                                                                $selected  = "";
                                                                $user_city = $user_data['city_id'];
                                                                $city_id   = $single_city['id'];
                                                                if($user_city == $city_id)
                                                                {
                                                                    $selected = "selected ='selected'";
                                                                }
                                                                ?>
                                                                <option value="<?php echo $single_city['id'];?>" <?php echo $selected; ?> ><?php echo $single_city['city_name'];?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group"><label for="phone"><strong>Phone</strong></label>
                                                        <input class="form-control" type="text" placeholder="phone" name="phone" value="<?php echo $user_data['phone'];?>"></div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col">
                                                    <div class="form-group"><label for="password"><strong>Password</strong></label>
                                                        <input class="form-control" type="text" placeholder="Password" name="password"></div>
                                                </div>

                                            </div>

                                            <div class="form-group"><button class="btn btn-primary btn-sm" type="submit">Update</button></div>
                                        </form>
                                        <?php }elseif ($user_data['user_type'] == 2){
                                            $hospital_table = new hospitals_table();
                                            $hospital_data  = $hospital_table->retrieve_hospital_by_id(get_login_user_id());
                                            ?>
                                            <form action="process.php" method="post">
                                                <input type="hidden" name="form_name" value="update_hospital_profile_form">
                                                <?php
                                                if (isset($_GET['error']) && $_GET['error'] == 'Y') {
                                                    ?>
                                                    <p style="text-align: center;color: red;"><?php echo $_SESSION['update_profile_error']; ?></p>
                                                <?php } ?>
                                                <p id="error"></p>
                                                <div class="form-row">
                                                    <div class="col">
                                                        <div class="form-group"><label for="username"><strong>Username</strong></label>
                                                            <input class="form-control" type="text" placeholder="usename" name="username" value="<?php echo $user_data['username'];?>"></div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group"><label for="email"><strong>Email Address</strong></label>
                                                            <input class="form-control" type="email" placeholder="user@example.com" name="email" value="<?php echo $user_data['email'];?>"></div>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="col">
                                                        <div class="form-group"><label for="first_name"><strong>Full Name</strong></label>
                                                            <input class="form-control" type="text" placeholder="Full Name" name="full_name" value="<?php echo $user_data['full_name'];?>"></div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group"><label for="phone"><strong>Phone</strong></label>
                                                            <input class="form-control" type="text" placeholder="phone" name="phone" value="<?php echo $user_data['phone'];?>"></div>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="col">
                                                        <div class="form-group"><label for="address"><strong>Address</strong></label>
                                                            <input class="form-control" type="text" placeholder="address" name="address" value="<?php echo $hospital_data['location'];?>"></div>
                                                    </div>

                                                </div>
                                                <div class="form-row">
                                                    <div class="col">
                                                        <div class="form-group"><label for="city_id"><strong>City</strong></label>
                                                            <select class="form-control" name="city_id" id="city_id" required>
                                                                <option selected disabled>Please Select City</option>
                                                                <?php
                                                                foreach ($cities_data as $single_city){
                                                                    $selected  = "";
                                                                    $user_city = $user_data['city_id'];
                                                                    $city_id   = $single_city['id'];
                                                                    if($user_city == $city_id)
                                                                    {
                                                                        $selected = "selected ='selected'";
                                                                    }
                                                                    ?>
                                                                    <option value="<?php echo $single_city['id'];?>" <?php echo $selected; ?> ><?php echo $single_city['city_name'];?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="col">
                                                        <div class="form-group"><label for="city_id"><strong>Hospital Type</strong></label>
                                                            <select class="form-control" name="type_id" id="type_id" required>
                                                                <option <?php if($hospital_data['type'] == 0 ) {echo "selected ='selected'";}?> value="0">Governmental</option>
                                                                <option <?php if($hospital_data['type'] == 1 ) {echo "selected ='selected'";}?> value="1">Private</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="col">
                                                        <div class="form-group"><label for="password"><strong>Password</strong></label>
                                                            <input class="form-control" type="text" placeholder="Password" name="password"></div>
                                                    </div>

                                                </div>

                                                <div class="form-group"><button class="btn btn-primary btn-sm" type="submit">Update</button></div>
                                            </form>
                                        <?php }?>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
<?php require_once 'footer.php'?>

    </body>

</html>
