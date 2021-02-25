<?php
class notifications_table
{
    private $_dbh;
    private $_table_name = 'notifications';

    public function __construct()
    {
        $this->_dbh = new db_connection($this->_table_name);
    }

    public function add_new_notification(array $data)
    {
        if($data)
        {
            return $this->_dbh->insert($data);

        }
        return false;
    }

    public function retrieve_all_notifications_by_user_id($user_id){
        $query = "SELECT * from ".$this->_table_name." where status = 0 and user_id = ".$user_id." order by created_date DESC";
        $result = $this->_dbh->query($query);
        $trans_data = array();
        while($row = mysqli_fetch_assoc($result))
        {
            $trans_data[] = $row;
        }
        return $trans_data;
    }


    public function count_notifications_by_user_id($user_id){
        $query = "SELECT count(id) as count_notif from ".$this->_table_name." where status = 0 and user_id = ".$user_id." order by created_date DESC";
        $result = $this->_dbh->query($query);
        $trans_data = array();
        while($row = mysqli_fetch_assoc($result))
        {
            $trans_data[] = $row;
        }
        return $trans_data;
    }


    public function change_status($user_id,$status)
    {
        $query = "update ".$this->_table_name." 
					SET 
					status = '".$status."'
					WHERE  
					user_id ='".$user_id."'";

        $result = $this->_dbh->query($query);

        if($result)
        {
            return true;
        }
        return false;
    }


}
