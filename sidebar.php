<?php
//get user type
$user_table = new users_table();
$user_data  = $user_table->retrieve_user_data_by_user_id(get_login_user_id());
$user_type  = $user_data['user_type'];
?>
<div id="wrapper">
    <nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0">
        <div class="container-fluid d-flex flex-column p-0"><a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="#">
                <div class="sidebar-brand-icon rotate-n-15"><i class="fas fa-laugh-wink"></i></div>
                <div class="sidebar-brand-text mx-3"><span>Brand</span></div>
            </a>
            <hr class="sidebar-divider my-0">
            <ul class="nav navbar-nav text-light" id="accordionSidebar">
                <?php if($user_type == 0 ){?>
                <li class="nav-item"><a class="nav-link active" href="dashboard.php"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
                <li class="nav-item"><a class="nav-link" href="patients.php"><i class="fas fa-table"></i><span>Patients</span></a></li>
                <li class="nav-item"><a class="nav-link" href="add_new_patient.php"><i class="fas fa-user"></i><span>Add New Patient</span></a></li>
            <?php }elseif ($user_type == 2 ){?>
                    <li class="nav-item"><a class="nav-link active" href="dashboard.php"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="icus.php"><i class="fas fa-table"></i><span>ICUs</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="add_icu.php"><i class="fas fa-user"></i><span>Add ICU</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="pending_patients.php"><i class="fas fa-table-tennis"></i><span>Pending Patients</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="all_patients.php"><i class="fas fa-table"></i><span>All Patients</span></a></li>

                <?php }?>
            </ul>
            <div class="text-center d-none d-md-inline"><button class="btn rounded-circle border-0" id="sidebarToggle" type="button"></button></div>
        </div>
    </nav>
