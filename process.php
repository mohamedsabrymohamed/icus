<?php
require_once 'inc.php';

if($_POST)
{
    $form_name = $_POST['form_name'];
    if($form_name and !empty($form_name))
    {
        switch ($form_name)
        {
            case 'login_form':
            {
                $username = strtolower($_POST['username']);
                $password = $_POST['password'];
                $location = $_POST['geoloc'];
                $users_table = new users_table();
                $user_id = $users_table->verify_user($username,$password);
                if(empty($user_id))
                {
                    $_SESSION['login_error'] = "Wrong username or password";
                    $redirect_path = 'index.php';
                    ?><script type="text/javascript">window.location = '<?php echo $redirect_path."?error=Y"; ?>'; </script><?php
                }else{
                    $user_data = $users_table->retrieve_user_data_by_user_id($user_id);
                    if($user_data['status'] == 1)
                    {
                        $_SESSION['user_id'] = $user_id;
                        $_SESSION['timeout'] = time();
                        $_SESSION['web_session_timeout'] = 18000;
                        $session_id = session_id();
                        $users_table->update_user_session($user_id,$session_id);
                        $login_table = new log_table();
                        $add_new_log = $login_table->create_login_log($location,$session_id);
                        if($add_new_log)
                        {
                            if($user_data['user_type'] == 1)
                            {
                                //admin
                                $redirect_path = 'dashboard.php';
                                ?><script type="text/javascript">window.location = '<?php echo $redirect_path; ?>'; </script><?php
                            }elseif($user_data['user_type'] == 0){
                                //doctor
                                $redirect_path = 'dashboard.php';
                                ?><script type="text/javascript">window.location = '<?php echo $redirect_path; ?>'; </script><?php
                            }elseif($user_data['user_type'] == 2){
                                //hospital
                                $redirect_path = 'dashboard.php';
                                ?><script type="text/javascript">window.location = '<?php echo $redirect_path; ?>'; </script><?php
                            }

                        }
                    }else{
                        $_SESSION['login_error'] = "Please contact administrator.";
                        $redirect_path = 'index.php';
                        ?><script type="text/javascript">window.location = '<?php echo $redirect_path."?error=Y"; ?>'; </script><?php
                    }


                }

                break;
            }
            case 'add_user_form':
            {
                $user_data = array();
                $user_data['full_name']  = $_POST['full_name'];
                $user_data['email']      = $_POST['email'];
                $user_data['username']   = $_POST['username'];
                $user_data['password']   = $_POST['password'];
                $user_data['phone']      = $_POST['phone'];
                $user_data['status']     = 1;
                $user_data['user_type']  = 0;
                $user_data['city_id']  = $_POST['city_id'];
                $user_data['hospital']  = $_POST['hospital'];
                $user_data['speciality']  = $_POST['speciality'];
                $user_data['cert_id']  = $_POST['cert_id'];
                $user_table = new users_table();
                $add_new_user = $user_table->add_new_user($user_data);
                if($add_new_user)
                {
                    $_SESSION['add_new_user_success'] = "Account Created. You can now login.";
                    $redirect_path = 'index.php';
                    ?><script type="text/javascript">window.location = '<?php echo $redirect_path."?success=Y"; ?>'; </script><?php

                }else{
                    $_SESSION['add_new_user_error'] = "Error add new user. Please try again.";
                    $redirect_path = 'register.php';
                    ?><script type="text/javascript">window.location = '<?php echo $redirect_path."?error=Y"; ?>'; </script><?php
                }

                break;
            }

            case 'add_hospital_form':
            {

                $user_data = array();
                $user_data['full_name']  = $_POST['hospital_name'];
                $user_data['email']      = $_POST['email'];
                $user_data['username']   = $_POST['username'];
                $user_data['password']   = $_POST['password'];
                $user_data['phone']      = $_POST['phone'];
                $user_data['status']     = 1;
                $user_data['user_type']  = 2;
                $user_data['city_id']  = $_POST['city_id'];
                $user_table = new users_table();
                $add_new_user = $user_table->add_new_user($user_data);
                if($add_new_user)
                {
                    $hospital_data = array();
                    $hospital_data['hospital_id']= $add_new_user;
                    $hospital_data['location']   = $_POST['location'];
                    $hospital_data['type']       = $_POST['type'];

                    $hospital_table   = new hospitals_table();
                    $add_new_hospital = $hospital_table->add_new_hospital($hospital_data);
                    if($add_new_hospital)
                    {
                        $_SESSION['add_new_user_success'] = "Account Created. You can now login.";
                        $redirect_path = 'index.php';
                        ?><script type="text/javascript">window.location = '<?php echo $redirect_path."?success=Y"; ?>'; </script><?php

                    }else{
                        $_SESSION['add_new_hospital_error'] = "Error add new hospital. Please try again.";
                        $redirect_path = 'register_hospital.php';
                        ?><script type="text/javascript">window.location = '<?php echo $redirect_path."?error=Y"; ?>'; </script><?php
                    }
                }




                break;
            }

            case 'update_doctor_profile_form':
            {
                $user_id  = get_login_user_id();
                $user_data = array();
                $where                   = 'id = ' . $user_id;
                $user_data['full_name']  = $_POST['full_name'];
                $user_data['email']      = $_POST['email'];
                $user_data['username']   = $_POST['username'];
                $user_data['phone']      = $_POST['phone'];
                $user_data['status']     = 1;
                $user_data['user_type']  = 0;
                $user_data['city_id']  = $_POST['city_id'];
                $user_data['hospital']  = $_POST['hospital_name'];
                $user_data['speciality']  = $_POST['speciality'];
                $user_data['cert_id']  = $_POST['cert_id'];
                $user_table = new users_table();
                if(isset($_POST['password']) && !empty($_POST['password']))
                {
                    $user_data['password']  = $_POST['password'];
                    $edit_user = $user_table->update_user_password($user_data,$user_id);
                }else{
                    $edit_user = $user_table->update_user_data($user_data,$where);
                }
                if($edit_user)
                {
                    $redirect_path = 'dashboard.php';
                    ?><script type="text/javascript">window.location = '<?php echo $redirect_path; ?>'; </script><?php

                }else{
                    $_SESSION['update_profile_error'] = "Error add new user access data. Please try again.";
                    $redirect_path = 'profile.php';
                    ?><script type="text/javascript">window.location = '<?php echo $redirect_path."?error=Y"; ?>'; </script><?php
                }

                break;
            }

            case 'update_hospital_profile_form':
            {
                $user_id  = get_login_user_id();
                $user_data = array();
                $where                   = 'id = ' . $user_id;
                $user_data['full_name']  = $_POST['full_name'];
                $user_data['email']      = $_POST['email'];
                $user_data['username']   = $_POST['username'];
                $user_data['phone']      = $_POST['phone'];
                $user_data['status']     = 1;
                $user_data['user_type']  = 2;
                $user_data['city_id']  = $_POST['city_id'];
                $user_table = new users_table();
                if(isset($_POST['password']) && !empty($_POST['password']))
                {
                    $user_data['password']  = $_POST['password'];
                    $edit_user = $user_table->update_user_password($user_data,$user_id);
                }else{
                    $edit_user = $user_table->update_user_data($user_data,$where);
                }
                if($edit_user)
                {
                    $hospital_table       = new hospitals_table();
                    $update_hospital_data = array();
                    $where_hospital       = 'hospital_id = ' . $user_id;
                    $update_hospital_data['location'] = $_POST['address'];
                    $update_hospital_data['type']     = $_POST['type_id'];
                    $update_hospital                  = $hospital_table->update_hospital_data($update_hospital_data,$where_hospital);

                    $redirect_path = 'dashboard.php';
                    ?><script type="text/javascript">window.location = '<?php echo $redirect_path; ?>'; </script><?php

                }else{
                    $_SESSION['update_profile_error'] = "Error add new user access data. Please try again.";
                    $redirect_path = 'profile.php';
                    ?><script type="text/javascript">window.location = '<?php echo $redirect_path."?error=Y"; ?>'; </script><?php
                }

                break;
            }

            case 'add_new_icu_form':
            {
                //get hospital id
                $hospital_table           = new hospitals_table();
                $hospital_data            = $hospital_table->retrieve_hospital_by_id(get_login_user_id());
                $icu_data                 = array();
                $icu_data['name']         = $_POST['icu_name'];
                $icu_data['status']       = $_POST['status'];
                $icu_data['hospital_id']  = $hospital_data['id'];
                $icus_table               = new icus_table();
                $add_new_icu              = $icus_table->add_new_icu($icu_data);
                if($add_new_icu)
                {
                        $_SESSION['add_new_icu_success'] = "ICU Created.";
                        $redirect_path = 'icus.php';
                        ?><script type="text/javascript">window.location = '<?php echo $redirect_path."?success=Y"; ?>'; </script><?php

                    }else{
                        $_SESSION['add_new_icu_error'] = "Error add new icu. Please try again.";
                        $redirect_path = 'add_icu.php';
                        ?><script type="text/javascript">window.location = '<?php echo $redirect_path."?error=Y"; ?>'; </script><?php
                    }

                break;
            }

            case 'edit_icu_form':
            {
                //get hospital id
                $hospital_table           = new hospitals_table();
                $hospital_data            = $hospital_table->retrieve_hospital_by_id(get_login_user_id());
                $icu_data                 = array();
                $icu_data['name']         = $_POST['icu_name'];
                $icu_data['status']       = $_POST['status'];
                $icu_data['hospital_id']  = $hospital_data['id'];
                $where                    = 'id = ' . $_POST['icu_id'];
                $icus_table               = new icus_table();
                $update_icu               = $icus_table->update_icu_data($icu_data,$where);
                if($update_icu)
                {
                    $_SESSION['edit_icu_error'] = "ICU Updated.";
                    $redirect_path = 'icus.php';
                    ?><script type="text/javascript">window.location = '<?php echo $redirect_path."?success=Y"; ?>'; </script><?php

                }else{
                    $_SESSION['edit_icu_error'] = "Error update icu. Please try again.";
                    $redirect_path = 'edit.php';
                    ?><script type="text/javascript">window.location = '<?php echo $redirect_path."?error=Y"; ?>'; </script><?php
                }

                break;
            }

            case 'add_new_patient_form':
            {
                //get hospital id
                $hospital_table           = new hospitals_table();
                $hospital_data            = $hospital_table->retrieve_hospital_by_id($_POST['hospital_id']);
                $patient_data                 = array();
                $patient_data['full_name']    = $_POST['full_name'];
                $patient_data['age']          = $_POST['age'];
                $patient_data['hospital_id']  = $hospital_data['id'];
                $patient_data['comments']     = $_POST['comment'];
                $patient_data['created_by']   = get_login_user_id();
                $patient_data['created_date'] = date("Y-m-d H:i:s");
                if (!empty($_FILES['fileToUpload'])) {
                    $report = file_upload("fileToUpload");
                    if ($report) {
                        $patient_data['report'] = $report;
                    }
                }
                $patients_table           = new patients_table();
                $add_new_patient          = $patients_table->add_new_patient($patient_data);
                if($add_new_patient)
                {
                    ///get doctor data
                    $users_table = new users_table();
                    $users_data  = $users_table->retrieve_user_data_by_user_id(get_login_user_id());
                    //add notification to hospital
                    $notifications_data    = array();
                    $notifications_data['notification_text'] = 'Doctor : '.$users_data['full_name'].' is asking for free ICU.';
                    $notifications_data['created_by']        = get_login_user_id();
                    $notifications_data['user_id']           = $_POST['hospital_id'];
                    $notifications_table                     = new notifications_table();
                    $add_notification                        = $notifications_table->add_new_notification($notifications_data);

                    $_SESSION['add_new_patient_success'] = "Patient Created.";
                    $redirect_path = 'patients.php';
                    ?><script type="text/javascript">window.location = '<?php echo $redirect_path."?success=Y"; ?>'; </script><?php

                }else{
                    $_SESSION['add_new_patient_error'] = "Error add new icu. Please try again.";
                    $redirect_path = 'add_new_patient.php';
                    ?><script type="text/javascript">window.location = '<?php echo $redirect_path."?error=Y"; ?>'; </script><?php
                }

                break;
            }

            case 'select_icu_form':
            {
                //update icu status and patient
               $icu_data                = array();
               $where                   = 'id = ' . $_POST['icu_id'];
               $icus_data['patient_id'] = $_POST['patient_id'] ;
               $icus_data['status']     = 1;
               $icus_table              = new icus_table();
               $icus_update             = $icus_table->update_icu_data($icus_data,$where);
               //change patient status
               $patient_data            = array();
               $patient_where           = 'id = ' . $_POST['patient_id'];
               $patient_data['status']  = 1;
               $patients_table          = new patients_table();
               $update_patient          = $patients_table->update_patient_data($patient_data,$patient_where);

                if($update_patient)
                {
                    //get user data
                    $patients_table = new patients_table();
                    $patients_data  = $patients_table->retrieve_patient_by_id($_POST['patient_id']);
                    //add notification to hospital
                    $notifications_data    = array();
                    $notifications_data['notification_text'] = 'Hospital : Approved your request.';
                    $notifications_data['created_by']        = get_login_user_id();
                    $notifications_data['user_id']           = $patients_data['created_by'];
                    $notifications_table                     = new notifications_table();
                    $add_notification                        = $notifications_table->add_new_notification($notifications_data);


                    $_SESSION['change_patient_status_success'] = "ICU Approved.";
                    $redirect_path = 'pending_patients.php';
                    ?><script type="text/javascript">window.location = '<?php echo $redirect_path."?success=Y"; ?>'; </script><?php

                }else{
                    $_SESSION['select_icu_error'] = "Error add new icu. Please try again.";
                    $redirect_path = 'add_icu.php';
                    ?><script type="text/javascript">window.location = '<?php echo $redirect_path."?error=Y"; ?>'; </script><?php
                }

                break;
            }
        }
    }
}




if($_GET) {

    // change icu status

    $status_uid = @$_GET['icu_status_uid'];
    if ($status_uid and !empty($status_uid)) {
        $isues_table = new icus_table();
        $icus_data   = $isues_table->retrieve_icu_by_id($status_uid);
        if ($icus_data) {
            $icu_current_status  = $icus_data['status'];
            if($icu_current_status == 1){
                $icu_status = 0;
            }else{
                $icu_status = 1;
            }
            $icu_new_data = array();
            $icu_new_data['status'] = $icu_status;
            $where                  = 'id = ' . $status_uid;
            $isues_table    = new icus_table();
            $change_status  = $isues_table->update_icu_data($icu_new_data,$where);
        }
        $_SESSION['status_change'] = "ICU Status Changed Successfully.";
        $redirect_path = 'icus.php';
        ?>
        <script type="text/javascript">window.location = '<?php echo $redirect_path . '?user_status=Y'; ?>'; </script><?php
    }



    // clear notifications

    $clear_notify = @$_GET['clear_notify'];
    if ($clear_notify and !empty($clear_notify)) {
        $notifications_table = new notifications_table();
        $clear_notification  = $notifications_table->change_status(get_login_user_id(),1);
        if($clear_notification)
        {
            $_SESSION['notif_clear'] = "All notifications marked as read.";
            $redirect_path = $_SERVER['HTTP_REFERER'];
            ?>
            <script type="text/javascript">window.location = '<?php echo $redirect_path . '?notif_clear=Y'; ?>'; </script><?php
        }else{
            $_SESSION['notif_clear_error'] = "Error Please try again later.";
            $redirect_path = $_SERVER['HTTP_REFERER'];
            ?>
            <script type="text/javascript">window.location = '<?php echo $redirect_path . '?notif_clear_error=Y'; ?>'; </script><?php
        }

    }



    // Reject Patient data

    $reject = @$_GET['patient_reject_uid'];
    if ($reject and !empty($reject)) {
        $patient_table = new patients_table();
        $patient_data  = $patient_table->retrieve_patient_by_id($reject);
        if ($patient_data) {
            $patients_data                      = array();
            $patients_data['modified_by']       = get_login_user_id();
            $patients_data['modified_date']     = date("Y-m-d H:i:s");
            $patients_data['status']            = 2;
            $where                              = 'id = ' . $reject;
            $patient_table                      = new patients_table();
            $reject_data                        = $patient_table->update_patient_data($patients_data,$where);

            //add notification to hospital
            $notifications_data    = array();
            $notifications_data['notification_text'] = 'Hospital : Rejected your request.';
            $notifications_data['created_by']        = get_login_user_id();
            $notifications_data['user_id']           = $patient_data['created_by'];
            $notifications_table                     = new notifications_table();
            $add_notification                        = $notifications_table->add_new_notification($notifications_data);

        }
        $_SESSION['change_patient_status_success'] = "Patient Status Changed Successfully.";
        $redirect_path = 'pending_patients.php';
        ?>
        <script type="text/javascript">window.location = '<?php echo $redirect_path . '?success=Y'; ?>'; </script><?php
    }

}
