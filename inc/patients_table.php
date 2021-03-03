<?php
class patients_table
{
    private $_dbh;
    private $_table_name = 'patients';
    public function __construct()
    {
        $this->_dbh = new db_connection($this->_table_name);
    }


    public function retrieve_all_patients(){
        $query = "SELECT * from ".$this->_table_name." order by id ASC";
        $result = $this->_dbh->query($query);
        $trans_data = array();
        while($row = mysqli_fetch_assoc($result))
        {
            $trans_data[] = $row;
        }
        return $trans_data;
    }

    public function retrieve_all_patients_by_hospital_id($hospital_id){
        $query = "SELECT * from ".$this->_table_name." where hospital_id = ".$hospital_id." order by id ASC";
        $result = $this->_dbh->query($query);
        $trans_data = array();
        while($row = mysqli_fetch_assoc($result))
        {
            $trans_data[] = $row;
        }
        return $trans_data;
    }

    public function retrieve_all_patients_by_hospital_id_pending($hospital_id){
        $query = "SELECT * from ".$this->_table_name." where hospital_id = ".$hospital_id." and status = 0 order by id ASC";
        $result = $this->_dbh->query($query);
        $trans_data = array();
        while($row = mysqli_fetch_assoc($result))
        {
            $trans_data[] = $row;
        }
        return $trans_data;
    }

    public function retrieve_all_patients_by_user_id($user_id){
        $query = "SELECT * from ".$this->_table_name." where created_by = ".$user_id." order by id ASC";
        $result = $this->_dbh->query($query);
        $trans_data = array();
        while($row = mysqli_fetch_assoc($result))
        {
            $trans_data[] = $row;
        }
        return $trans_data;
    }

    public function retrieve_patient_by_id($icu_id)
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


    public function add_new_patient(array $data)
    {
        if($data)
        {
            return $this->_dbh->insert($data);

        }
        return false;
    }

    public function update_patient_data(array $user_data,$condition)
    {
        return $this->_dbh->update($user_data, $condition);
    }
}
