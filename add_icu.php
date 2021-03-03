<?php
require_once 'inc.php';
require_once 'head.php';
?>
    <body id="page-top">
<?php require_once 'sidebar.php';?>
    <div class="d-flex flex-column" id="content-wrapper">
        <div id="content">
<?php require_once 'header.php';?>
            <div class="container-fluid">
                <h3 class="text-dark mb-4">Add New Icu</h3>
                <div class="row mb-3">

                    <div class="col-lg-12">

                        <div class="row">
                            <div class="col">
                                <div class="card shadow mb-3">
                                    <div class="card-header py-3">
                                        <p class="text-primary m-0 font-weight-bold">ICU Data</p>
                                    </div>
                                    <div class="card-body">
                                            <form action="process.php" method="post">
                                                <input type="hidden" name="form_name" value="add_new_icu_form">
                                                <?php
                                                if (isset($_GET['error']) && $_GET['error'] == 'Y') {
                                                    ?>
                                                    <p style="text-align: center;color: red;"><?php echo $_SESSION['add_new_icu_error']; ?></p>
                                                <?php } ?>
                                                <p id="error"></p>
                                                <div class="form-row">
                                                    <div class="col">
                                                        <div class="form-group"><label for="icu_name"><strong>ICU Name</strong></label>
                                                            <input class="form-control" type="text" placeholder="ICU Name" name="icu_name" ></div>
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="col">
                                                        <div class="form-group"><label for="status"><strong>ICU Status</strong></label>
                                                            <select class="form-control" name="status" id="status" required>
                                                                <option value="0">Free</option>
                                                                <option value="1">Occupied</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group"><button class="btn btn-primary btn-sm" type="submit">Add New</button></div>
                                            </form>

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
