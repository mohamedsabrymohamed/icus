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
                <h3 class="text-dark mb-4">Add New Patient</h3>
                <div class="row mb-3">

                    <div class="col-lg-12">

                        <div class="row">
                            <div class="col">
                                <div class="card shadow mb-3">
                                    <div class="card-header py-3">
                                        <p class="text-primary m-0 font-weight-bold">Patient Data</p>
                                    </div>
                                    <div class="card-body">
                                            <form action="process.php" method="post" enctype="multipart/form-data">
                                                <input type="hidden" name="form_name" value="add_new_patient_form">
                                                <?php
                                                if (isset($_GET['error']) && $_GET['error'] == 'Y') {
                                                    ?>
                                                    <p style="text-align: center;color: red;"><?php echo $_SESSION['add_new_patient_error']; ?></p>
                                                <?php } ?>
                                                <p id="error"></p>
                                                <div class="form-row">
                                                    <div class="col">
                                                        <div class="form-group"><label for="full_name"><strong>Full Name</strong></label>
                                                            <input class="form-control" type="text" placeholder="Full Name" name="full_name" ></div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group"><label for="age"><strong>Age</strong></label>
                                                            <input class="form-control" type="number" placeholder="Age" name="age" ></div>
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="col">
                                                        <div class="form-group"><label for="file"><strong>Patient Report</strong></label>
                                                            <input class="form-control" type="file" name="fileToUpload" id="fileToUpload">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="col">
                                                        <div class="form-group"><label for="status"><strong>Hospital</strong></label>
                                                            <select class="form-control" name="hospital_id" id="hospital_id" required>
                                                                <?php
                                                                $users_table = new users_table();
                                                                $user_data   = $users_table->retrieve_all_hospitals();
                                                                foreach ($user_data as $single_hospital){
                                                                ?>
                                                                <option value="<?php echo $single_hospital['id'];?>"><?php echo $single_hospital['full_name'];?></option>
                                                                <?php }?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="col">
                                                        <div class="form-group"><label for="comment"><strong>Doctor Comments</strong></label>
                                                            <textarea class="form-control" id="comment" rows="3" name="comment"></textarea>
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
