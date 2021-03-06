<?php
require_once "../inc.php";
$users_table_data  = new users_table();
$curr_user_data    = $users_table_data->retrieve_user_data_by_user_id(get_login_user_id());
if($curr_user_data['user_type'] != 2)
{
    header('Location: ../index.php');
}

////////////////// hospital data //////////////////
$hospital_table   = new hospitals_table();
$hospital_cur_data= $hospital_table->retrieve_hospital_by_id(get_login_user_id());
////////////////// patient data ////////////////////
$patient_table     = new patients_table();
$patients_data     = $patient_table->retrieve_all_patients_by_hospital_id_pending($hospital_cur_data['id']);
////////////////// hospital data ////////////////////
$users_table      = new users_table();
////////////////// tables /////////////////
$data = array();


                                foreach ($patients_data as $single_patient) {
                                    $get_hospital     = $hospital_table->retrieve_hospital_id_by_id($single_patient['hospital_id']);
                                    $hospital_data    = $users_table->retrieve_user_data_by_user_id($get_hospital['hospital_id']);
                                    $doctor_data      = $users_table->retrieve_user_data_by_user_id($single_patient['created_by']);
                                    $status = ($single_patient['status'] == 0) ? 'pending' : (($single_patient['status'] == 1) ? 'Approved' : 'Rejected');
                                    $doctor_name      = $doctor_data['full_name'];
                                    $full_name = $single_patient['full_name'];
                                    $age       = $single_patient['age'];
                                    $report    = "<a href='uploads/".$single_patient['report']."' download> Download Report</a>";
                                    $comment   = $single_patient['comments'];
                                    $hospital  = $hospital_data['full_name'];
                                    $action_buttons = "
                                    <a href='select_icu.php?patient_approve_uid=".$single_patient['id']."' target='_self'>
                                    <button class='btn btn-success btn-sm'>Approve</button>
                                    </a>
                                    <a href='process.php?patient_reject_uid=".$single_patient['id']."' target='_self'>
                                    <button class='btn btn-danger btn-sm'>Reject</button></a>";

                                    $data['data'][] = [$doctor_name,$full_name,$age,$comment,$hospital,$report,$status, $action_buttons];
                                }
echo json_encode($data);
