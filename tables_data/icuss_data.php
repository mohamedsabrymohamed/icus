<?php
require_once "../inc.php";
$users_table_data  = new users_table();
$curr_user_data    = $users_table_data->retrieve_user_data_by_user_id(get_login_user_id());
if($curr_user_data['user_type'] != 2)
{
    header('Location: ../index.php');
}

////////////////// hospital data ////////////////////
$hospitals_table  = new hospitals_table();
$hospitals_data   = $hospitals_table->retrieve_hospital_by_id(get_login_user_id());
////////////////// icus data ////////////////////
$icus_table        = new icus_table();
$icus_data         = $icus_table->retrieve_all_icus_by_hospital_id($hospitals_data['id']);
////////////////// tables /////////////////
$data = array();


                                foreach ($icus_data as $single_icu) {
                                    $status = $single_icu['status'] == 0 ? 'Occupied' : 'Free';
                                    $action_buttons = "
                                    <a href='edit_icu.php?icu_uid=".$single_icu['id']."' target='_self'>
                                    <button class='btn btn-info btn-sm'>Edit</button>
                                    </a>
                                    <a href='process.php?icu_status_uid=".$single_icu['id']."' target='_self'>
                                    <button class='btn btn-warning btn-sm'>Change Status</button></a>";
                                    $data['data'][] = [$single_icu['name'], $status, $action_buttons];
                                }
echo json_encode($data);

