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
                    <div class="row">
                        <div class="col-md-8 col-xl-6 mb-4">
                            <div class="card shadow border-left-primary py-2">
                                <div class="card-body">
                                    <div class="row align-items-center no-gutters">
                                        <div class="col mr-2">
                                            <div class="text-uppercase text-primary font-weight-bold text-xs mb-1"><span>My Patients</span></div>
                                            <div class="text-dark font-weight-bold h5 mb-0"><span>5</span></div>
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
                                            <div class="text-dark font-weight-bold h5 mb-0"><span>5</span></div>
                                        </div>
                                        <div class="col-auto"><i class="fas fa-hospital fa-2x text-gray-300"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>


<?php require_once 'footer.php'?>
