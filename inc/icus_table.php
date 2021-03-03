<?php
class icus_table
{
    private $_dbh;
    private $_table_name = 'icus';
    public function __construct()
    {
        $this->_dbh = new db_connection($this->_table_name);
    }


    public function retrieve_all_icus(){
        $query = "SELECT * from ".$this->_table_name." order by id ASC";
        $result = $this->_dbh->query($query);
        $trans_data = array();
        while($row = mysqli_fetch_assoc($result))
        {
            $trans_data[] = $row;
        }
        return $trans_data;
    }

    public function retrieve_all_free_icus(){
        $query = "SELECT * from ".$this->_table_name." where status=0 order by id ASC";
        $result = $this->_dbh->query($query);
        $trans_data = array();
        while($row = mysqli_fetch_assoc($result))
        {
            $trans_data[] = $row;
        }
        return $trans_data;
    }


    public function retrieve_all_free_icus_by_hospital_id($hospital_id){
        $query = "SELECT * from ".$this->_table_name." where status=0 and hospital_id = ".$hospital_id." order by id ASC";
        $result = $this->_dbh->query($query);
        $trans_data = array();
        while($row = mysqli_fetch_assoc($result))
        {
            $trans_data[] = $row;
        }
        return $trans_data;
    }

    public function retrieve_all_icus_by_hospital_id($hospital_id){
        $query = "SELECT * from ".$this->_table_name." where hospital_id = ".$hospital_id." order by id ASC";
        $result = $this->_dbh->query($query);
        $trans_data = array();
        while($row = mysqli_fetch_assoc($result))
        {
            $trans_data[] = $row;
        }
        return $trans_data;
    }

    public function retrieve_icu_by_id($icu_id)
    {
        $query = "SELECT * from ".$this->_table_name." where id ='".$icu_id."'";
        $result = $this->_dbh->query($query);
        $result_data = mysqli_fetch_assoc($result);
        if($result_data['id'] and !empty($result_data['id']))
        {
            return $result_data;
        }
        return false;
    }


    public function add_new_icu(array $data)
    {
        if($data)
        {
            return $this->_dbh->insert($data);

        }
        return false;
    }

    public function update_icu_data(array $user_data,$condition)
    {
        return $this->_dbh->update($user_data, $condition);
    }
}
