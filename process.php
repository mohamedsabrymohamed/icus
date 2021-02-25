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
                                $redirect_path = 'hospital.php';
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
                $hospital_data = array();
                $hospital_data['name']       = $_POST['hospital_name'];
                $hospital_data['location']   = $_POST['location'];
                $hospital_data['city_id']    = $_POST['city_id'];
                $hospital_data['type']       = $_POST['type'];
                $hospital_data['country_id']     = 42;

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

                break;
            }

            case 'edit_user_form':
            {
                $user_id  = $_POST['uid'];
                $user_data = array();
                $where                   = 'id = ' . $user_id;
                $user_data['full_name']  = $_POST['full_name'];
                $user_data['email']      = $_POST['email'];
                $user_data['username']   = $_POST['username'];
                $user_data['status']     = $_POST['status'];
                $user_data['user_type']  = $_POST['user_type'];
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
                    if($_POST['user_type'] == 0)
                    {
                        $user_access_table  = new UserAccess_table();
                        $user_access_remove = $user_access_table->delete_data($user_id);
                        if($_POST['hospital'][0] == 0)
                        {
                            $hospitals_table = new hospitals_table();
                            foreach($_POST['city'] as $single_city){
                                $hospitals_data  = $hospitals_table->retrieve_hospitals_by_city_id($single_city);
                                $user_access_table = new UserAccess_table();
                                foreach($hospitals_data as $single_hospital)
                                {
                                    $user_access_data                = array();
                                    $user_access_data['user_id']     = $user_id;
                                    $user_access_data['city_id']     = $single_city;
                                    $user_access_data['hospital_id'] = $single_hospital['id'];
                                    $user_add_data                   = $user_access_table->add_new_user_access($user_access_data);
                                }
                            }


                        }else{
                            $user_access_table = new UserAccess_table();
                            foreach($_POST['hospital'] as $single_hospital){
                                $hospitale_table  = new hospitals_table();
                                $hospital_data    = $hospitale_table->retrieve_hospital_by_id($single_hospital);
                                $user_access_data                = array();
                                $user_access_data['user_id']     = $user_id;
                                $user_access_data['city_id']     = $hospital_data['city_id'];
                                $user_access_data['hospital_id'] = $single_hospital;
                                $user_add_data                   = $user_access_table->add_new_user_access($user_access_data);
                            }

                        }
                        if($user_add_data)
                        {
                            $redirect_path = 'admin/users.php';
                            ?><script type="text/javascript">window.location = '<?php echo $redirect_path; ?>'; </script><?php
                        }else{
                            $_SESSION['add_new_user_error'] = "Error add new user access data. Please try again.";
                            $redirect_path = 'admin/add_new_user.php';
                            ?><script type="text/javascript">window.location = '<?php echo $redirect_path."?error=Y"; ?>'; </script><?php
                        }
                    }else{
                        $redirect_path = 'admin/users.php';
                        ?><script type="text/javascript">window.location = '<?php echo $redirect_path; ?>'; </script><?php
                    }


                }else{
                    $_SESSION['add_new_user_error'] = "Error add new user. Please try again.";
                    $redirect_path = 'admin/add_new_user.php';
                    ?><script type="text/javascript">window.location = '<?php echo $redirect_path."?error=Y"; ?>'; </script><?php
                }

                break;
            }
            case 'patient_data_form':
            {
                $patient_table                      = new patients_table();
                //check duplicate
                $check_duplicate_first              = $patient_table->check_duplicate($_POST['phone']);
                if(!$check_duplicate_first)
                {
                    $patient_data                       = array();
                    $patient_data['full_name']          = $_POST['full_name'];
                    $patient_data['phone']              = $_POST['phone'];
                    $patient_data['nationality_id']     = $_POST['nationality'];
                    $patient_data['city_id']            = $_POST['city'];
                    $patient_data['hospital_id']        = $_POST['hospital'];
                    $patient_data['bought_product']     = $_POST['brought_product'];
                    $patient_data['first_second']       = $_POST['first_second'];
                    $patient_data['place_of_birth_id']  = $_POST['place_of_birth'];
                    if(empty($_POST['place_of_birth_id']))
                    {
                        $patient_data['place_of_birth_id']  = NULL;
                    }else{

                        $patient_data['place_of_birth_id']  = $_POST['place_of_birth_id'];
                    }
                    $patient_data['educated']           = $_POST['educated'];
                    $patient_data['activation_code']           = $random_number = 'QV-'.rand(0,999999);
                    $patient_data['online_status']      = 1;
                    $patient_data['created_by']         = get_login_user_id();
                    $patient_data['created_date']       = date("Y-m-d H:i:s");
                    $patient_table                      = new patients_table();
                    /////check phone number
                    $check_phone_number                 = $patient_table->check_patient_phone_number($_POST['phone']);
                    if($check_phone_number['id'])
                    {
                        $patient_data['duplicate_status'] = 1;
                    }else{
                        $patient_data['duplicate_status'] = 0;
                    }
                    $add_new_patient                    = $patient_table->add_new_patient($patient_data);
                    if($add_new_patient)
                    {
                        $phone = '+'.$_POST['phone'];
                        send_sms($phone,$patient_data['activation_code']);

                        //$result = send_sms($phone,$patient_data['activation_code']);

                        // if ($result['http_status'] != 201) {
                        //     print "Error sending: " . ($result['error'] ? $result['error'] : "HTTP status ".$result['http_status']."; Response was " .$result['server_response']);
                        //   } else {
                        //     print "Response " . $result['server_response'];
                        //     // Use json_decode($result['server_response']) to work with the response further
                        //   }
                        echo json_encode(array('data_success'=>'1','patient_id'=>$add_new_patient));
                    }else{
                        echo json_encode(array('data_success'=>'0'));
                    }
                }else{
                    echo json_encode(array('data_success'=>'2'));
                }

                break;
            }
            case 'patient_data_form_local':
            {
                $patient_table                      = new patients_table();
                //check duplicate
                $check_duplicate_first              = $patient_table->check_duplicate($_POST['phone']);
                if(!$check_duplicate_first)
                {
                    $patient_data                       = array();
                    $patient_data['full_name']          = $_POST['full_name'];
                    $patient_data['phone']              = $_POST['phone'];
                    $patient_data['nationality_id']     = $_POST['nationality_id'];
                    $patient_data['city_id']            = $_POST['city_id'];
                    $patient_data['hospital_id']        = $_POST['hospital_id'];
                    $patient_data['first_second']       = $_POST['first_second'];
                    if(empty($_POST['place_of_birth_id']))
                    {
                        $patient_data['place_of_birth_id']  = NULL;
                    }else{

                        $patient_data['place_of_birth_id']  = $_POST['place_of_birth_id'];
                    }
                    $patient_data['bought_product']     = $_POST['bought_product'];
                    $patient_data['educated']           = $_POST['educated'];
                    $patient_data['online_status']      = 0;
                    $patient_data['created_by']         = get_login_user_id();
                    $patient_data['created_date']       = date("Y-m-d H:i:s");
                    /////check phone number
                    $check_phone_number                 = $patient_table->check_patient_phone_number($_POST['phone']);
                    if($check_phone_number['id'])
                    {
                        $patient_data['duplicate_status'] = 1;
                    }else{
                        $patient_data['duplicate_status'] = 0;
                    }
                    $add_new_patient                    = $patient_table->add_new_patient($patient_data);
                    if($add_new_patient)
                    {
                        echo json_encode(array('data_local_success'=>'1'));
                    }else{
                        echo json_encode(array('data_local_success'=>'0'));
                    }

                }else{
                    echo json_encode(array('data_local_success'=>'2'));
                }

                break;
            }
            case 'activation_code_form':
            {
                $patient_table                      = new patients_table();
                //check patient activation code
                $check_data                         = $patient_table->retrieve_patient_by_id($_POST['patient_id']);
                $current_activation_code            = $check_data['activation_code'];
                $sent_activation_code               = $_POST['acivation_code'];
                if($current_activation_code == $sent_activation_code )
                {
                    $patient_data                       = array();
                    $patient_data['activation_status']  = 1;
                    $where                              = 'id = ' . $_POST['patient_id'];
                    $patient_table                      = new patients_table();
                    //update activation status
                    $update_activation_status           = $patient_table->update_patient_data($patient_data,$where);

                    if($update_activation_status)
                    {
                        echo json_encode(array('data_success'=>'1'));
                    }else{
                        echo json_encode(array('data_success'=>'0'));
                    }
                }else{
                    echo json_encode(array('data_success'=>'0'));
                }

                break;
            }
            case 'country_id':
            {
                $country_id    = $_POST['country_id'];
                $country_table = new countries_table();
                $country_data  = $country_table->retrieve_country_by_id($country_id);
                $cities_table  = new cities_table();
                $cities_data   = $cities_table->retrieve_cities_by_county_id($country_id);
                $cities        = '';
                $cities       .= '<option disabled selected>Please Select City</option>';
                foreach ($cities_data as $single_city)
                {
                    $cities .="<option value=".$single_city['id'].">".$single_city['city_name']."</option>";
                }
                echo json_encode(array('language'=>$country_data['language'],'cities'=>$cities));
                break;
            }
            case 'city_id':
            {
                $city_id        = $_POST['city_id'];
                $hospital_table = new hospitals_table();
                $hospital_data  = $hospital_table->retrieve_hospitals_for_user($city_id,get_login_user_id());
                $hospitals      = '';
                $hospitals     .='<option disabled selected>Please Select Hospital</option>';
                foreach ($hospital_data as $single_hospital)
                {
                    $hospitals .="<option value=".$single_hospital['id'].">".$single_hospital['hospital_name']."</option>";
                }
                echo json_encode(array('hospitals'=>$hospitals));
                break;
            }
            case 'hospital_id':
            {
                $hospital_id        = $_POST['hospital_id'];
                $hospital_table     = new hospitals_table();
                $hospital_data      = $hospital_table->retrieve_hospital_by_id($hospital_id);
                echo json_encode(array('hospital_class'=>$hospital_data['hospital_class'],'hospital_type'=>$hospital_data['hospital_type']));
                break;
            }
            case 'city_id_admin':
            {
                $city_id        = $_POST['city_id'];
                $hospital_table = new hospitals_table();
                $hospital_data  = $hospital_table->retrieve_hospitals_by_city_id($city_id);
                $hospitals      = '';
                $hospitals     .='<option value="0">All</option>';
                foreach ($hospital_data as $single_hospital)
                {
                    $hospitals .="<option value=".$single_hospital['id'].">".$single_hospital['hospital_name']."</option>";
                }
                echo json_encode(array('hospitals'=>$hospitals));
                break;
            }
            case 'add_hospital_form':
            {
                $hospital_data = array();
                $hospital_data['hospital_name']   = $_POST['hospital_name'];
                $hospital_data['hospital_class']  = $_POST['hospital_class'];
                $hospital_data['hospital_type']   = $_POST['hospital_type'];
                $hospital_data['polyclinic']      = $_POST['polyclinic'];
                $hospital_data['city_id']         = $_POST['city'];
                $hospital_table                   = new hospitals_table();
                $hospital_add_new                 = $hospital_table->add_new_hospital($hospital_data);
                if($hospital_add_new)
                {
                    /////////// send email /////////////////////////
//                    $from    = 'no-reply@insights-marketing.co.uk';
//                    $subject = 'New Hospital Added';
//                    $users_table = new users_table();
//                    $users_data  = $users_table->retrieve_all_users_except_current(get_login_user_id());
//                    foreach($users_data as $single_user)
//                    {
//                        $to      = $single_user['email'];
//                        $body    = 'Dear '.$single_user['full_name']."/n Kindly note that new hospital added with name: ".$_POST['hospital_name']."/n Class: ".$_POST['hospital_class']." /n Type: ".$_POST['hospital_type'] ;
//                        send_email($from,$to,$subject,$body);
//                    }

                    $user_table     = new users_table();
                    $curr_user_data = $user_table->retrieve_user_data_by_user_id(get_login_user_id());
                    //create notification
                    $notifications_table   = new notifications_table();
                    $all_users             = $user_table->retrieve_all_users_except_current(get_login_user_id());
                    foreach ($all_users as $single_user)
                    {
                        $notifications_data    = array();
                        $notifications_data['notification_text'] = 'Hospital : '.$_POST['hospital_name'].' is added by :'.$curr_user_data['full_name'];
                        $notifications_data['created_by']        = get_login_user_id();
                        $notifications_data['user_id']           = $single_user['id'];
                        $add_notification                        = $notifications_table->add_new_notification($notifications_data);
                    }
                    ////////////////////////////// add new hospital to user access //////////////////

                    $user_access_table               = new UserAccess_table();
                    $retrive_subscibed_users         = $user_access_table->retrieve_useraccess_by_city_id($_POST['city']);
                    if(!empty($retrive_subscibed_users)){
                        foreach($retrive_subscibed_users as $single_subscibed_user)
                        {
                            $user_access_data                = array();
                            $user_access_data['user_id']     = $single_subscibed_user['user_id'];
                            $user_access_data['city_id']     = $_POST['city'];
                            $user_access_data['hospital_id'] = $hospital_add_new;
                            $user_add_data                   = $user_access_table->add_new_user_access($user_access_data);
                        }
                    }



                    $redirect_path = 'admin/hospitals.php';
                    ?><script type="text/javascript">window.location = '<?php echo $redirect_path; ?>'; </script><?php
                }else{
                    $_SESSION['add_new_hospital_error'] = "Error add new hospital data. Please try again.";
                    $redirect_path = 'admin/hospitals.php';
                    ?><script type="text/javascript">window.location = '<?php echo $redirect_path."?error=Y"; ?>'; </script><?php
                }
                break;
            }

            //////////////////// reports /////////////////////////
            case 'search_city_report_form':
            {
                $city_id        = $_POST['city_id'];
                $from_date      = $_POST['from_date'];
                $to_date        = $_POST['to_date'];
                $patient_table  = new patients_table();
                $patient_data   = $patient_table->patients_per_city($city_id,$from_date,$to_date);
                $users_table    = new users_table();
                $country_table  = new countries_table();
                $cities_table   = new cities_table();
                $hospital_table = new hospitals_table();
                $data           = array();
                $counter        = 0;
                foreach ($patient_data as $single_patient)
                {
                    $city_data           = $cities_table->retrieve_city_by_id($single_patient['city_id']);
                    $nationality_data    = $country_table->retrieve_country_by_id($single_patient['nationality_id']);
                    $place_of_birth_data = $country_table->retrieve_country_by_id($single_patient['place_of_birth_id']);
                    $hospital_data       = $hospital_table->retrieve_hospital_by_id($single_patient['hospital_id']);
                    $user_data           = $users_table->retrieve_user_data_by_user_id($single_patient['created_by']);
                    $product             = $single_patient['bought_product'] == 0 ? 'NO' : 'Yes';
                    $educated            =$single_patient['educated'] == 0 ? 'NO' : 'Yes';
                    $data [$counter]['full_name']= $single_patient['full_name'];
                    $data [$counter]['phone']= $single_patient['phone'];
                    $data [$counter]['nationality']= $nationality_data['country_name'];
                    $data [$counter]['city_name']= $city_data['city_name'];
                    $data [$counter]['hospital_name']= $hospital_data['hospital_name'];
                    $data [$counter]['first_second']= $single_patient['first_second'];
                    $data [$counter]['country_name']= $place_of_birth_data['country_name'];
                    $data [$counter]['product']= $product;
                    $data [$counter]['educated']= $educated;
                    $data [$counter]['sales_name']= $user_data['full_name'];
                    $counter ++;
                }


                echo json_encode(array('data_success'=>1,'data'=>$data));
                break;
            }

            case 'search_sales_report_form':
            {
                $user_id        = $_POST['user_id'];
                $from_date      = $_POST['from_date'];
                $to_date        = $_POST['to_date'];
                $patient_table  = new patients_table();
                $patient_data   = $patient_table->patients_per_sales_person($user_id,$from_date,$to_date);
                $country_table  = new countries_table();
                $cities_table   = new cities_table();
                $hospital_table = new hospitals_table();
                $data           = array();
                $counter        = 0;
                foreach ($patient_data as $single_patient)
                {
                    $city_data           = $cities_table->retrieve_city_by_id($single_patient['city_id']);
                    $nationality_data    = $country_table->retrieve_country_by_id($single_patient['nationality_id']);
                    $place_of_birth_data = $country_table->retrieve_country_by_id($single_patient['place_of_birth_id']);
                    $hospital_data       = $hospital_table->retrieve_hospital_by_id($single_patient['hospital_id']);
                    $product             = $single_patient['bought_product'] == 0 ? 'NO' : 'Yes';
                    $educated            =$single_patient['educated'] == 0 ? 'NO' : 'Yes';
                    $data [$counter]['full_name']=$single_patient['full_name'];
                    $data [$counter]['phone']=$single_patient['phone'];
                    $data [$counter]['nationality']=$nationality_data['country_name'];
                    $data [$counter]['city_name']=$city_data['city_name'];
                    $data [$counter]['hospital_name']=$hospital_data['hospital_name'];
                    $data [$counter]['first_second']=$single_patient['first_second'];
                    $data [$counter]['country_name']=$place_of_birth_data['country_name'];
                    $data [$counter]['product']=$product;
                    $data [$counter]['educated']=$educated;
                    $counter++;
                }


                echo json_encode(array('data_success'=>1,'data'=>$data));
                break;
            }

            case 'search_city_sales_report_form':
            {
                $user_id        = $_POST['user_id'];
                $city_id        = $_POST['city_id'];
                $from_date      = $_POST['from_date'];
                $to_date        = $_POST['to_date'];
                $patient_table  = new patients_table();
                $patient_data   = $patient_table->patients_per_city_and_sales($user_id,$city_id,$from_date,$to_date);
                $country_table  = new countries_table();
                $cities_table   = new cities_table();
                $hospital_table = new hospitals_table();
                $data           = array();
                $counter        = 0;
                foreach ($patient_data as $single_patient)
                {
                    $city_data           = $cities_table->retrieve_city_by_id($single_patient['city_id']);
                    $nationality_data    = $country_table->retrieve_country_by_id($single_patient['nationality_id']);
                    $place_of_birth_data = $country_table->retrieve_country_by_id($single_patient['place_of_birth_id']);
                    $hospital_data       = $hospital_table->retrieve_hospital_by_id($single_patient['hospital_id']);
                    $product             = $single_patient['bought_product'] == 0 ? 'NO' : 'Yes';
                    $educated            =$single_patient['educated'] == 0 ? 'NO' : 'Yes';
                    $data [$counter]['full_name']= $single_patient['full_name'];
                    $data [$counter]['phone']= $single_patient['phone'];
                    $data [$counter]['nationality']= $nationality_data['country_name'];
                    $data [$counter]['city_name']= $city_data['city_name'];
                    $data [$counter]['hospital_name']= $hospital_data['hospital_name'];
                    $data [$counter]['first_second']= $single_patient['first_second'];
                    $data [$counter]['country_name']= $place_of_birth_data['country_name'];
                    $data [$counter]['product']= $product;
                    $data [$counter]['educated']= $educated;
                    $counter++;
                }


                echo json_encode(array('data_success'=>1,'data'=>$data));
                break;
            }

            case 'all_patients_report_form':
            {
                $from_date      = $_POST['from_date'];
                $to_date        = $_POST['to_date'];
                $patient_table  = new patients_table();
                $patient_data   = $patient_table->all_patients_report($from_date,$to_date);
                $users_table    = new users_table();
                $country_table  = new countries_table();
                $cities_table   = new cities_table();
                $hospital_table = new hospitals_table();
                $data           = array();
                $counter        = 0;
                foreach ($patient_data as $single_patient)
                {
                    $city_data           = $cities_table->retrieve_city_by_id($single_patient['city_id']);
                    $nationality_data    = $country_table->retrieve_country_by_id($single_patient['nationality_id']);
                    $place_of_birth_data = $country_table->retrieve_country_by_id($single_patient['place_of_birth_id']);
                    $hospital_data       = $hospital_table->retrieve_hospital_by_id($single_patient['hospital_id']);
                    $user_data           = $users_table->retrieve_user_data_by_user_id($single_patient['created_by']);
                    $product             = $single_patient['bought_product'] == 0 ? 'NO' : 'Yes';
                    $educated            =$single_patient['educated'] == 0 ? 'NO' : 'Yes';
                    $data[$counter]['full_name']= "<td>".$single_patient['full_name']."</td>";
                    $data[$counter]['phone']= "<td>".$single_patient['phone']."</td>";
                    $data[$counter]['nationality']= "<td>".$nationality_data['country_name']."</td>";
                    $data[$counter]['city_name']= "<td>".$city_data['city_name']."</td>";
                    $data[$counter]['hospital_name']= "<td>".$hospital_data['hospital_name']."</td>";
                    $data[$counter]['first_second']= "<td>".$single_patient['first_second']."</td>";
                    $data[$counter]['country_name']= "<td>".$place_of_birth_data['country_name']."</td>";
                    $data[$counter]['product']= "<td>".$product."</td>";
                    $data[$counter]['educated']= "<td>".$educated."</td>";
                    $data[$counter]['sales_person']= "<td>".$user_data['full_name']."</td>";
                    $data[$counter]['created_date']= "<td>".$single_patient['created_date']."</td>";
                    $counter++;
                }


                echo json_encode(array('data_success'=>1,'data'=>$data));
                break;
            }

            case 'duplicate_patients_report_form':
            {
                $from_date      = $_POST['from_date'];
                $to_date        = $_POST['to_date'];
                $patient_table  = new patients_table();
                $patient_data   = $patient_table->duplicate_patients_report($from_date,$to_date);
                $users_table    = new users_table();
                $country_table  = new countries_table();
                $cities_table   = new cities_table();
                $hospital_table = new hospitals_table();
                $data           = array();
                $counter        = 0;
                foreach ($patient_data as $single_patient)
                {
                    $source_record       = $patient_table->retrieve_patient_by_phone_number($single_patient['phone']);
                    if($source_record)
                    {
                        $city_data           = $cities_table->retrieve_city_by_id($source_record['city_id']);
                        $nationality_data    = $country_table->retrieve_country_by_id($source_record['nationality_id']);
                        $place_of_birth_data = $country_table->retrieve_country_by_id($source_record['place_of_birth_id']);
                        $hospital_data       = $hospital_table->retrieve_hospital_by_id($source_record['hospital_id']);
                        $user_data           = $users_table->retrieve_user_data_by_user_id($source_record['created_by']);
                        $product             = $source_record['bought_product'] == 0 ? 'NO' : 'Yes';
                        $duplicate           = $source_record['duplicate_status'] == 0 ? 'Source' : 'Duplicate';
                        $educated            = $source_record['educated'] == 0 ? 'NO' : 'Yes';
                        $data[$counter]['full_name']= "<td>".$source_record['full_name']."</td>";
                        $data[$counter]['phone']= "<td>".$source_record['phone']."</td>";
                        $data[$counter]['nationality']= "<td>".$nationality_data['country_name']."</td>";
                        $data[$counter]['city_name']= "<td>".$city_data['city_name']."</td>";
                        $data[$counter]['hospital_name']= "<td>".$hospital_data['hospital_name']."</td>";
                        $data[$counter]['first_second']= "<td>".$source_record['first_second']."</td>";
                        $data[$counter]['country_name']= "<td>".$place_of_birth_data['country_name']."</td>";
                        $data[$counter]['product']= "<td>".$product."</td>";
                        $data[$counter]['duplicate']= "<td>".$duplicate."</td>";
                        $data[$counter]['educated']= "<td>".$educated."</td>";
                        $data[$counter]['sales_person']= "<td>".$user_data['full_name']."</td>";
                        $data[$counter]['created_date']= "<td>".$single_patient['created_date']."</td>";
                        $counter++;
                    }
                    $city_data           = $cities_table->retrieve_city_by_id($single_patient['city_id']);
                    $nationality_data    = $country_table->retrieve_country_by_id($single_patient['nationality_id']);
                    $place_of_birth_data = $country_table->retrieve_country_by_id($single_patient['place_of_birth_id']);
                    $hospital_data       = $hospital_table->retrieve_hospital_by_id($single_patient['hospital_id']);
                    $user_data           = $users_table->retrieve_user_data_by_user_id($single_patient['created_by']);
                    $product             = $single_patient['bought_product'] == 0 ? 'NO' : 'Yes';
                    $duplicate           = $single_patient['duplicate_status'] == 0 ? 'Source' : 'Duplicate';
                    $educated            =$single_patient['educated'] == 0 ? 'NO' : 'Yes';
                    $data[$counter]['full_name']= "<td>".$single_patient['full_name']."</td>";
                    $data[$counter]['phone']= "<td>".$single_patient['phone']."</td>";
                    $data[$counter]['nationality']= "<td>".$nationality_data['country_name']."</td>";
                    $data[$counter]['city_name']= "<td>".$city_data['city_name']."</td>";
                    $data[$counter]['hospital_name']= "<td>".$hospital_data['hospital_name']."</td>";
                    $data[$counter]['first_second']= "<td>".$single_patient['first_second']."</td>";
                    $data[$counter]['country_name']= "<td>".$place_of_birth_data['country_name']."</td>";
                    $data[$counter]['product']= "<td>".$product."</td>";
                    $data[$counter]['duplicate']= "<td>".$duplicate."</td>";
                    $data[$counter]['educated']= "<td>".$educated."</td>";
                    $data[$counter]['sales_person']= "<td>".$user_data['full_name']."</td>";
                    $data[$counter]['created_date']= "<td>".$single_patient['created_date']."</td>";
                    $counter++;
                }


                echo json_encode(array('data_success'=>1,'data'=>$data));
                break;
            }

            case 'duplicate_patients_sales_person_report_form':
            {
                $from_date      = $_POST['from_date'];
                $to_date        = $_POST['to_date'];
                $sales_person   = $_POST['user_id'];
                $patient_table  = new patients_table();
                $patient_data   = $patient_table->duplicate_patients_by_sales_person_report($from_date,$to_date,$sales_person);
                $users_table    = new users_table();
                $country_table  = new countries_table();
                $cities_table   = new cities_table();
                $hospital_table = new hospitals_table();
                $data           = array();
                $counter        = 0;
                foreach ($patient_data as $single_patient)
                {
                    $source_record       = $patient_table->retrieve_patient_by_phone_number($single_patient['phone']);
                    if($source_record)
                    {
                        $city_data           = $cities_table->retrieve_city_by_id($source_record['city_id']);
                        $nationality_data    = $country_table->retrieve_country_by_id($source_record['nationality_id']);
                        $place_of_birth_data = $country_table->retrieve_country_by_id($source_record['place_of_birth_id']);
                        $hospital_data       = $hospital_table->retrieve_hospital_by_id($source_record['hospital_id']);
                        $user_data           = $users_table->retrieve_user_data_by_user_id($source_record['created_by']);
                        $product             = $source_record['bought_product'] == 0 ? 'NO' : 'Yes';
                        $duplicate           = $source_record['duplicate_status'] == 0 ? 'Source' : 'Duplicate';
                        $educated            = $source_record['educated'] == 0 ? 'NO' : 'Yes';
                        $data[$counter]['full_name']= "<td>".$source_record['full_name']."</td>";
                        $data[$counter]['phone']= "<td>".$source_record['phone']."</td>";
                        $data[$counter]['nationality']= "<td>".$nationality_data['country_name']."</td>";
                        $data[$counter]['city_name']= "<td>".$city_data['city_name']."</td>";
                        $data[$counter]['hospital_name']= "<td>".$hospital_data['hospital_name']."</td>";
                        $data[$counter]['first_second']= "<td>".$source_record['first_second']."</td>";
                        $data[$counter]['country_name']= "<td>".$place_of_birth_data['country_name']."</td>";
                        $data[$counter]['product']= "<td>".$product."</td>";
                        $data[$counter]['duplicate']= "<td>".$duplicate."</td>";
                        $data[$counter]['educated']= "<td>".$educated."</td>";
                        $data[$counter]['sales_person']= "<td>".$user_data['full_name']."</td>";
                        $data[$counter]['created_date']= "<td>".$single_patient['created_date']."</td>";
                        $counter++;
                    }
                    $city_data           = $cities_table->retrieve_city_by_id($single_patient['city_id']);
                    $nationality_data    = $country_table->retrieve_country_by_id($single_patient['nationality_id']);
                    $place_of_birth_data = $country_table->retrieve_country_by_id($single_patient['place_of_birth_id']);
                    $hospital_data       = $hospital_table->retrieve_hospital_by_id($single_patient['hospital_id']);
                    $user_data           = $users_table->retrieve_user_data_by_user_id($single_patient['created_by']);
                    $product             = $single_patient['bought_product'] == 0 ? 'NO' : 'Yes';
                    $duplicate           = $single_patient['duplicate_status'] == 0 ? 'Source' : 'Duplicate';
                    $educated            =$single_patient['educated'] == 0 ? 'NO' : 'Yes';
                    $data[$counter]['full_name']= "<td>".$single_patient['full_name']."</td>";
                    $data[$counter]['phone']= "<td>".$single_patient['phone']."</td>";
                    $data[$counter]['nationality']= "<td>".$nationality_data['country_name']."</td>";
                    $data[$counter]['city_name']= "<td>".$city_data['city_name']."</td>";
                    $data[$counter]['hospital_name']= "<td>".$hospital_data['hospital_name']."</td>";
                    $data[$counter]['first_second']= "<td>".$single_patient['first_second']."</td>";
                    $data[$counter]['country_name']= "<td>".$place_of_birth_data['country_name']."</td>";
                    $data[$counter]['product']= "<td>".$product."</td>";
                    $data[$counter]['duplicate']= "<td>".$duplicate."</td>";
                    $data[$counter]['educated']= "<td>".$educated."</td>";
                    $data[$counter]['sales_person']= "<td>".$user_data['full_name']."</td>";
                    $data[$counter]['created_date']= "<td>".$single_patient['created_date']."</td>";
                    $counter++;
                }


                echo json_encode(array('data_success'=>1,'data'=>$data));
                break;
            }

            case 'comments_per_call_center_report_form':
            {
                $from_date      = $_POST['from_date'];
                $to_date        = $_POST['to_date'];
                $call_center    = $_POST['user_id'];
                $patient_table  = new patients_table();
                $patient_data   = $patient_table->retrieve_all_comments_report($from_date,$to_date,$call_center);
                $users_table    = new users_table();
                $country_table  = new countries_table();
                $cities_table   = new cities_table();
                $hospital_table = new hospitals_table();
                $data           = array();
                $counter        = 0;
                foreach ($patient_data as $single_patient)
                {
                    $city_data           = $cities_table->retrieve_city_by_id($single_patient['city_id']);
                    $nationality_data    = $country_table->retrieve_country_by_id($single_patient['nationality_id']);
                    $place_of_birth_data = $country_table->retrieve_country_by_id($single_patient['place_of_birth_id']);
                    $hospital_data       = $hospital_table->retrieve_hospital_by_id($single_patient['hospital_id']);
                    $user_data           = $users_table->retrieve_user_data_by_user_id($single_patient['created_by']);
                    $product             = $single_patient['bought_product'] == 0 ? 'NO' : 'Yes';
                    $duplicate           = $single_patient['duplicate_status'] == 0 ? 'Source' : 'Duplicate';
                    $educated            =$single_patient['educated'] == 0 ? 'NO' : 'Yes';
                    $data[$counter]['full_name']= "<td>".$single_patient['full_name']."</td>";
                    $data[$counter]['phone']= "<td>".$single_patient['phone']."</td>";
                    $data[$counter]['nationality']= "<td>".$nationality_data['country_name']."</td>";
                    $data[$counter]['city_name']= "<td>".$city_data['city_name']."</td>";
                    $data[$counter]['hospital_name']= "<td>".$hospital_data['hospital_name']."</td>";
                    $data[$counter]['first_second']= "<td>".$single_patient['first_second']."</td>";
                    $data[$counter]['country_name']= "<td>".$place_of_birth_data['country_name']."</td>";
                    $data[$counter]['product']= "<td>".$product."</td>";
                    $data[$counter]['duplicate']= "<td>".$duplicate."</td>";
                    $data[$counter]['educated']= "<td>".$educated."</td>";
                    $data[$counter]['sales_person']= "<td>".$user_data['full_name']."</td>";
                    $data[$counter]['created_date']= "<td>".$single_patient['created_date']."</td>";
                    $data[$counter]['comment']= "<td>".$single_patient['comment']."</td>";
                    $counter++;
                }


                echo json_encode(array('data_success'=>1,'data'=>$data));
                break;
            }
            ///////////////// change password  //////////////////////
            case 'change_password_form':
            {
                $user_id  = get_login_user_id();
                $user_data = array();
                $where                  = 'id = ' . $user_id;
                $user_table = new users_table();
                $user_data['password']  = $_POST['new_password'];
                $change_password = $user_table->update_user_password($user_data,$user_id);

                if($change_password)
                {
                    $redirect_path = 'admin/index.php';
                    ?><script type="text/javascript">window.location = '<?php echo $redirect_path; ?>'; </script><?php
                }else{
                    $_SESSION['change_password_error'] = "Error change password. Please try again.";
                    $redirect_path = 'admin/change_password.php';
                    ?><script type="text/javascript">window.location = '<?php echo $redirect_path."?error=Y"; ?>'; </script><?php
                }

                break;
            }
            case 'call_center_comment_form':
            {
                $comment_data                     = array();
                $patient_id                       = $_POST['pid'];
                $where                            = 'id = ' . $patient_id;
                $comment_data['comment']          = $_POST['comment'];
                $comment_data['comment_user_id']  = get_login_user_id();
                $comment_data['comment_date']     = DATE('Y-m-d H:i:s');
                $patient_table                    = new patients_table();
                $update_patient                   = $patient_table->update_patient_data($comment_data,$where);
                if($update_patient)
                {
                    //remove record from call_center_data table
                    $call_center_data_table  = new call_center_data_table();
                    $call_center_data        = $call_center_data_table->delete_data($patient_id);
                    //add count to daily call_center_count table
                    $call_center_count_table = new call_center_count_table();
                    $get_count               = $call_center_count_table->retrieve_day_count(get_login_user_id());
                    if($get_count['id'] and !empty($get_count['id']))
                    {
                        $new_count        = $get_count['day_count'] + 1;
                        $update_count     = $call_center_count_table->update_daily_count(get_login_user_id(),$new_count);
                    }else{
                        $new_count_data              = array();
                        $new_count_data['user_id']   = get_login_user_id();
                        $new_count_data['day_count'] = 1;
                        $new_count_data['day_date']  = DATE('Y-m-d H:i:s');
                        $add_new_count               = $call_center_count_table->add_new_data($new_count_data);
                    }
                    //create notification
                    $notifications_table   = new notifications_table();
                    $user_table            = new users_table();
                    $curr_user_data        = $user_table->retrieve_user_data_by_user_id(get_login_user_id());
                    $supervisours_data     = $user_table->retrieve_all_supervisor_users();
                    foreach($supervisours_data as $single_super)
                    {
                        $notifications_data    = array();
                        $notifications_data['notification_text'] = 'New Comment is added by :'.$curr_user_data['full_name'];
                        $notifications_data['created_by']        = get_login_user_id();
                        $notifications_data['user_id']           = $single_super['id'];
                        $add_notification                        = $notifications_table->add_new_notification($notifications_data);

                    }

                    $redirect_path = 'admin/call_center.php';
                    ?><script type="text/javascript">window.location = '<?php echo $redirect_path; ?>'; </script><?php
                }else{
                    $_SESSION['add_new_comment_error'] = "Error add new comment data. Please try again.";
                    $redirect_path = 'admin/comment.php';
                    ?><script type="text/javascript">window.location = '<?php echo $redirect_path."?error=Y"; ?>'; </script><?php
                }
                break;
            }
            case'patients_comment_approve':
            {
                $patient_ids = explode(",",$_POST['patient_array']);
                $patients_table = new patients_table();
                $approve_data = false;
                foreach ($patient_ids as $single_id)
                {
                    $patients_data                      = array();
                    $patients_data['supervisor_status'] = 1;
                    $patients_data['supervisor_id']     = get_login_user_id();
                    $patients_data['supervisor_date']   = date("Y-m-d H:i:s");
                    $where                              = 'id = ' . $single_id;
                    $approve_data                       = $patients_table->update_patient_data($patients_data,$where);
                }
                if($approve_data){
                    echo json_encode(array('data_success'=>1));
                }else{
                    echo json_encode(array('data_success'=>2));
                }

                break;
            }
            case'patients_comment_reject':
            {
                $patient_ids = explode(",",$_POST['patient_array']);
                $patients_table = new patients_table();
                $approve_data = false;
                foreach ($patient_ids as $single_id)
                {
                    $patients_data                      = array();
                    $patients_data['supervisor_status'] = 2;
                    $patients_data['supervisor_id']     = get_login_user_id();
                    $patients_data['supervisor_date']   = date("Y-m-d H:i:s");
                    $where                              = 'id = ' . $single_id;
                    $approve_data                       = $patients_table->update_patient_data($patients_data,$where);
                }
                if($approve_data){
                    echo json_encode(array('data_success'=>1));
                }else{
                    echo json_encode(array('data_success'=>2));
                }

                break;
            }
        }
    }
}




if($_GET) {

    // change user status

    $status_uid = @$_GET['status_uid'];
    if ($status_uid and !empty($status_uid)) {
        $users_table = new users_table();
        $user_data   = $users_table->retrieve_user_data_by_user_id($status_uid);
        if ($user_data) {
            $user_curr_status  = $user_data['status'];
            if($user_curr_status == 1){
                $user_status = 0;
            }else{
                $user_status = 1;
            }
            $users_table  = new users_table();
            $cart_delete  = $users_table->change_status($status_uid,$user_status);
        }
        $_SESSION['status_change'] = "User Status Changed Successfully.";
        $redirect_path = 'admin/users.php';
        ?>
        <script type="text/javascript">window.location = '<?php echo $redirect_path . '?user_status=Y'; ?>'; </script><?php
    }



    // change user status

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


    // Approve Patient data

    $approve = @$_GET['approve'];
    if ($approve and !empty($approve)) {
        $patient_table = new patients_table();
        $patient_data  = $patient_table->retrieve_patient_by_id($approve);
        if ($patient_data) {
            $patients_data                      = array();
            $patients_data['supervisor_status'] = 1;
            $patients_data['supervisor_id']     = get_login_user_id();
            $patients_data['supervisor_date']   = date("Y-m-d H:i:s");
            $where                              = 'id = ' . $approve;
            $patient_table                      = new patients_table();
            $approve_data                       = $patient_table->update_patient_data($patients_data,$where);
        }
        $_SESSION['patient_status_change'] = "Patient Status Changed Successfully.";
        $redirect_path = 'admin/patients_comments.php';
        ?>
        <script type="text/javascript">window.location = '<?php echo $redirect_path . '?patient_status_change=Y'; ?>'; </script><?php
    }

    // Reject Patient data

    $reject = @$_GET['reject'];
    if ($reject and !empty($reject)) {
        $patient_table = new patients_table();
        $patient_data  = $patient_table->retrieve_patient_by_id($reject);
        if ($patient_data) {
            $patients_data                      = array();
            $patients_data['supervisor_status'] = 2;
            $patients_data['supervisor_id']     = get_login_user_id();
            $patients_data['supervisor_date']   = date("Y-m-d H:i:s");
            $where                              = 'id = ' . $reject;
            $patient_table                      = new patients_table();
            $approve_data                       = $patient_table->update_patient_data($patients_data,$where);
        }
        $_SESSION['patient_status_change'] = "Patient Status Changed Successfully.";
        $redirect_path = 'admin/patients_comments.php';
        ?>
        <script type="text/javascript">window.location = '<?php echo $redirect_path . '?patient_status_change=Y'; ?>'; </script><?php
    }

}
