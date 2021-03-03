<?php
$notifications_table = new notifications_table();
$notifications_data  = $notifications_table->retrieve_all_notifications_by_user_id(get_login_user_id());
$count_notifications = $notifications_table->count_notifications_by_user_id(get_login_user_id());
if($count_notifications[0]['count_notif'] > 0) {
    $ring_color = 'red';
    $number_color = 'blue';
}else{
    $ring_color   = 'green';
    $number_color = 'blue';
}
?>
<nav class="navbar navbar-light navbar-expand bg-white shadow mb-4 topbar static-top">
    <div class="container-fluid"><button class="btn btn-link d-md-none rounded-circle mr-3" id="sidebarToggleTop" type="button"><i class="fas fa-bars"></i></button>
        <form class="form-inline d-none d-sm-inline-block mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
            <div class="input-group"><input class="bg-light form-control border-0 small" type="text" placeholder="Search for ...">
                <div class="input-group-append"><button class="btn btn-primary py-0" type="button"><i class="fas fa-search"></i></button></div>
            </div>
        </form>
        <ul class="nav navbar-nav flex-nowrap ml-auto">
            <li class="nav-item dropdown d-sm-none no-arrow"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#"><i class="fas fa-search"></i></a>
                <div class="dropdown-menu dropdown-menu-right p-3 animated--grow-in" aria-labelledby="searchDropdown">
                    <form class="form-inline mr-auto navbar-search w-100">
                        <div class="input-group"><input class="bg-light form-control border-0 small" type="text" placeholder="Search for ...">
                            <div class="input-group-append"><button class="btn btn-primary py-0" type="button"><i class="fas fa-search"></i></button></div>
                        </div>
                    </form>
                </div>
            </li>
            <li class="nav-item dropdown no-arrow mx-1">
                <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#"><span class="badge badge-danger badge-counter" style="font-size: 20px;color:<?php echo $number_color;?>;background-color: <?php echo $ring_color;?>;"><?php echo $count_notifications[0]['count_notif'];?></span><i class="fas fa-bell fa-fw" style="font-size: 30px;"></i></a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-list dropdown-menu-right animated--grow-in">
                        <h6 class="dropdown-header">Notification center</h6>
                        <?php
                        foreach ($notifications_data as $single_notification)
                        {
                        ?>
                        <a class="d-flex align-items-center dropdown-item" href="#">
                            <div class="mr-3">
                                <div class="bg-primary icon-circle"><i class="fas fa-file-alt text-white"></i></div>
                            </div>
                            <div>
                                <p><?php echo $single_notification['notification_text'];?></p>
                            </div>
                        </a>
                        <?php }?>
                        <?php
                        if($count_notifications[0]['count_notif'] > 0){
                        ?>
                        <a class="text-center dropdown-item small text-gray-500" href="process.php?clear_notify=Y">Clear All Notifications</a>
                        <?php }?>
                    </div>
                </div>
            </li>

            <div class="d-none d-sm-block topbar-divider"></div>
            <li class="nav-item dropdown no-arrow">
                <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#">
                        <span class="d-none d-lg-inline mr-2 text-gray-600 small">
                            <?php
                            $users_table = new users_table();
                            $current_user_data = $users_table->retrieve_user_data_by_user_id(get_login_user_id());
                            echo $current_user_data['full_name'];
                            ?></span>
                        <img class="border rounded-circle img-profile" src="assets/img/avatars/avatar1.jpeg"></a>
                    <div class="dropdown-menu shadow dropdown-menu-right animated--grow-in"><a class="dropdown-item" href="profile.php"><i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>&nbsp;Profile</a><a class="dropdown-item" href="#">
                        <div class="dropdown-divider"></div><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>&nbsp;Logout</a>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</nav>
