<?php
require_once 'inc.php';
$user_id    = get_login_user_id();
$user_table = new users_table();
$user_data  = $user_table->retrieve_user_data_by_user_id($user_id);
$log_table  = new log_table();
$update_log = $log_table->update_login_user($user_id,$user_data['session_id']);
if($update_log)
{
    $user_update = $user_table->update_user_session($user_id,'');
    unset_user_session();
    $redirect_path = 'index.php';
    ?><script type="text/javascript">window.location = '<?php echo $redirect_path; ?>'; </script><?php

}