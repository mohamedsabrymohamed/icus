<?php
class hospitals_table
{
    private $_dbh;
    private $_table_name = 'hospitals';
    public function __construct()
    {
        $this->_dbh = new db_connection($this->_table_name);
    }


    public function retrieve_all_hospitals(){
        $query = "SELECT * from ".$this->_table_name." order by hospital_name ASC";
        $result = $this->_dbh->query($query);
        $trans_data = array();
        while($row = mysqli_fetch_assoc($result))
        {
            $trans_data[] = $row;
        }
        return $trans_data;
    }

    public function retrieve_hospital_by_id($hospital_id)
    {
        $query = "SELECT * from ".$this->_table_name." where hospital_id ='".$hospital_id."'";
        $result = $this->_dbh->query($query);
        $result_data = mysqli_fetch_assoc($result);
        if($result_data['id'] and !empty($result_data['id']))
        {
            return $result_data;
        }
        return false;
    }


    public function add_new_hospital(array $data)
    {
        if($data)
        {
            return $this->_dbh->insert($data);

        }
        return false;
    }

    public function update_hospital_data(array $user_data,$condition)
    {
        return $this->_dbh->update($user_data, $condition);
    }
}
