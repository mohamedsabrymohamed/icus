<?php
class countries_table
{
    private $_dbh;
    private $_table_name = 'countries';
    public function __construct()
    {
        $this->_dbh = new db_connection($this->_table_name);
    }

    public function retrieve_all_countries_with_nationalities(){
        $query = "SELECT id, country_name,nationality_name from ".$this->_table_name." where nationality_name <> '' ";
        $result = $this->_dbh->query($query);
        $trans_data = array();
        while($row = mysqli_fetch_assoc($result))
        {
            $trans_data[] = $row;
        }
        return $trans_data;
    }


    public function retrieve_all_countries(){
        $query = "SELECT id, country_name,language from ".$this->_table_name;
        $result = $this->_dbh->query($query);
        $trans_data = array();
        while($row = mysqli_fetch_assoc($result))
        {
            $trans_data[] = $row;
        }
        return $trans_data;
    }

    public function retrieve_country_by_id($country_id)
    {
        $query = "SELECT id,country_name,language,nationality_name from ".$this->_table_name." where id ='".$country_id."'";
        $result = $this->_dbh->query($query);
        $result_data = mysqli_fetch_assoc($result);
        if($result_data['id'] and !empty($result_data['id']))
        {
            return $result_data;
        }
        return false;
    }
}