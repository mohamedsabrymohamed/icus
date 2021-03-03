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
                <h3 class="text-dark mb-4">Select Icu</h3>
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
                                                <input type="hidden" name="form_name" value="select_icu_form">
                                                <input type="hidden" name="patient_id" value="<?php echo $_GET['patient_approve_uid'];?>">

                                                <?php
                                                if (isset($_GET['error']) && $_GET['error'] == 'Y') {
                                                    ?>
                                                    <p style="text-align: center;color: red;"><?php echo $_SESSION['select_icu_error']; ?></p>
                                                <?php } ?>
                                                <p id="error"></p>
                                                <div class="form-row">
                                                    <div class="col">
                                                        <div class="form-group"><label for="icu_name"><strong>ICU Name</strong></label>
                                                            <select class="form-control" name="icu_id" id="icu_id" required>
                                                                <option selected disabled>Please Select ICU</option>
                                                                <?php
                                                                //get hospital data
                                                                $hospital_table = new hospitals_table();
                                                                $hospital_data  = $hospital_table->retrieve_hospital_by_id(get_login_user_id());
                                                                //get icu data
                                                                $icus_table = new icus_table();
                                                                $icus_data  = $icus_table->retrieve_all_free_icus_by_hospital_id($hospital_data['id']);
                                                                foreach($icus_data as $single_icu)
                                                                {
                                                                ?>
                                                                <option value="<?php echo $single_icu['id']?>"><?php echo $single_icu['name']?></option>
                                                                <?php }?>
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
