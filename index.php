<?php
require_once 'inc.php';
require_once 'head.php';
?>

<body class="bg-gradient-primary">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-9 col-lg-12 col-xl-10">
            <div class="card shadow-lg o-hidden border-0 my-5">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-flex">
                            <div class="flex-grow-1 bg-login-image" style="background-image: url(&quot;assets/img/dogs/image3.jpeg&quot;);"></div>
                        </div>
                        <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center">
                                    <h4 class="text-dark mb-4">ICU System</h4>
                                </div>
                                <form class="user" action="process.php" method="post">
                                    <input type="hidden" name="form_name" value="login_form">
                                    <input type="hidden" name="geoloc" id="geoloc" value="">
                                    <?php
                                    if(isset($_GET['error']) && $_GET['error'] == 'Y'){
                                        ?>
                                        <p style="text-align: center;color: red;margin-bottom: 10px;display: block;"><?php echo $_SESSION['login_error'];?></p>
                                    <?php }?>
                                    <?php
                                    if(isset($_GET['success']) && $_GET['success'] == 'Y'){
                                        ?>
                                        <p style="text-align: center;color: green;margin-bottom: 10px;display: block;"><?php echo $_SESSION['add_new_user_success'];?></p>
                                    <?php }?>
                                    <p id="error"></p>
                                    <div class="form-group"><input class="form-control form-control-user" type="text" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter Your Username" name="username""></div>
                                    <div class="form-group"><input class="form-control form-control-user" type="password" id="exampleInputPassword" placeholder="Password" name="password"></div>
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox small">
                                            <div class="form-check"><input class="form-check-input custom-control-input" type="checkbox" id="formCheck-1"><label class="form-check-label custom-control-label" for="formCheck-1">Remember Me</label></div>
                                        </div>
                                    </div><button class="btn btn-primary btn-block text-white btn-user" type="submit">Login</button>
                                    <hr>
                                </form>
<!--                                <div class="text-center"><a class="small" href="forgot-password.html">Forgot Password?</a></div>-->
                                <div class="text-center"><a class="small" href="register.php">Create an Account!</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

<?php require_once 'footer.php'?>
<script>
    var x = document.getElementById("error");
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            x.innerHTML = "Geolocation is not supported by this browser.";
        }
    }

    function showPosition(position) {
        document.getElementById("geoloc").value = position.coords.latitude +","+ position.coords.longitude;
    }
    window.onload = getLocation;

    if (localStorage) {
        localStorage.clear();
    }
</script>

</body>

</html>
