<?php
require_once 'inc.php';

////icu data
$icu_table = new icus_table();
$icu_data  = $icu_table->retrieve_icu_by_id($_GET['icu_uid']);
require_once 'head.php';
?>
    <body id="page-top">
<?php require_once 'sidebar.php';?>
    <div class="d-flex flex-column" id="content-wrapper">
        <div id="content">
<?php require_once 'header.php';?>
            <div class="container-fluid">
                <h3 class="text-dark mb-4">Edit Icu</h3>
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
                                                <input type="hidden" name="form_name" value="edit_icu_form">
                                                <input type="hidden" name="icu_id" value="<?php echo $_GET['icu_uid']?>">
                                                <?php
                                                if (isset($_GET['error']) && $_GET['error'] == 'Y') {
                                                    ?>
                                                    <p style="text-align: center;color: red;"><?php echo $_SESSION['edit_icu_error']; ?></p>
                                                <?php } ?>
                                                <p id="error"></p>
                                                <div class="form-row">
                                                    <div class="col">
                                                        <div class="form-group"><label for="icu_name"><strong>ICU Name</strong></label>
                                                            <input class="form-control" type="text" placeholder="ICU Name" name="icu_name" value="<?php echo $icu_data['name'];?>"></div>
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="col">
                                                        <div class="form-group"><label for="status"><strong>ICU Status</strong></label>
                                                            <select class="form-control" name="status" id="status" required>
                                                                <option <?php if($icu_data['status'] == 0){echo "selected='selected'";}?> value="0">Free</option>
                                                                <option  <?php if($icu_data['status'] == 1){echo "selected='selected'";}?> value="1">Occupied</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group"><button class="btn btn-primary btn-sm" type="submit">Edit</button></div>
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
