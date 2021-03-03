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
                    <div class="d-sm-flex justify-content-between align-items-center mb-4">
                        <h3 class="text-dark mb-0">Dashboard</h3>

                    </div>
                    <?php
                    $users_table = new users_table();
                    $users_data  = $users_table->retrieve_user_data_by_user_id(get_login_user_id());
                    if($users_data['user_type'] == 0)
                    {
                        //get total patients
                        $patients_table = new patients_table();
                        $patients_data  = $patients_table->retrieve_all_patients_by_user_id(get_login_user_id());
                        $total_patients = (count($patients_data) ==0) ? 0 : count($patients_data);
                        //get available icus
                        $icus_table     = new icus_table();
                        $icus_data      = $icus_table->retrieve_all_free_icus();
                        $total_free_icus= (count($icus_data) ==0) ? 0 : count($icus_data);
                    ?>
                    <div class="row">
                        <div class="col-md-8 col-xl-6 mb-4">
                            <div class="card shadow border-left-primary py-2">
                                <div class="card-body">
                                    <div class="row align-items-center no-gutters">
                                        <div class="col mr-2">
                                            <div class="text-uppercase text-primary font-weight-bold text-xs mb-1"><span>My Patients</span></div>
                                            <div class="text-dark font-weight-bold h5 mb-0"><span><?php echo $total_patients;?></span></div>
                                        </div>
                                        <div class="col-auto"><i class="fas fa-user fa-2x text-gray-300"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 col-xl-6 mb-4">
                            <div class="card shadow border-left-success py-2">
                                <div class="card-body">
                                    <div class="row align-items-center no-gutters">
                                        <div class="col mr-2">
                                            <div class="text-uppercase text-success font-weight-bold text-xs mb-1"><span>Available ICUS</span></div>
                                            <div class="text-dark font-weight-bold h5 mb-0"><span><?php echo $total_free_icus;?></span></div>
                                        </div>
                                        <div class="col-auto"><i class="fas fa-hospital fa-2x text-gray-300"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <?php }elseif ($users_data['user_type'] == 2){
                        //get hospital id
                        $hospitals_table = new hospitals_table();
                        $hospital_data   = $hospitals_table->retrieve_hospital_by_id(get_login_user_id());
                        //get pending patients
                        $patients_table = new patients_table();
                        $patients_data  = $patients_table->retrieve_all_patients_by_hospital_id_pending($hospital_data['id']);
                        $total_patients = (count($patients_data) ==0) ? 0 : count($patients_data);
                        //get total icus free
                        $icus_table     = new icus_table();
                        $icus_data      = $icus_table->retrieve_all_free_icus_by_hospital_id($hospital_data['id']);
                        $total_free_icus= (count($icus_data) ==0) ? 0 : count($icus_data);
                        ?>
                    <div class="row">
                        <div class="col-md-8 col-xl-6 mb-4">
                            <div class="card shadow border-left-primary py-2">
                                <div class="card-body">
                                    <div class="row align-items-center no-gutters">
                                        <div class="col mr-2">
                                            <div class="text-uppercase text-primary font-weight-bold text-xs mb-1"><span>Pending Patients</span></div>
                                            <div class="text-dark font-weight-bold h5 mb-0"><span><?php echo $total_patients;?></span></div>
                                        </div>
                                        <div class="col-auto"><i class="fas fa-user fa-2x text-gray-300"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 col-xl-6 mb-4">
                            <div class="card shadow border-left-success py-2">
                                <div class="card-body">
                                    <div class="row align-items-center no-gutters">
                                        <div class="col mr-2">
                                            <div class="text-uppercase text-success font-weight-bold text-xs mb-1"><span>Available ICUS</span></div>
                                            <div class="text-dark font-weight-bold h5 mb-0"><span><?php echo $total_free_icus;?></span></div>
                                        </div>
                                        <div class="col-auto"><i class="fas fa-hospital fa-2x text-gray-300"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <?php }?>
                </div>


<?php require_once 'footer.php'?>
