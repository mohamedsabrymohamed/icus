<?php
class cities_table
{
    private $_dbh;
    private $_table_name = 'cities';
    public function __construct()
    {
        $this->_dbh = new db_connection($this->_table_name);
    }

    public function retrieve_cities_by_county_id($country_id){
        $query = "SELECT id, city_name from ".$this->_table_name." where country_id = ".$country_id;
        $result = $this->_dbh->query($query);
        $trans_data = array();
        while($row = mysqli_fetch_assoc($result))
        {
            $trans_data[] = $row;
        }
        return $trans_data;
    }

    public function retrieve_city_by_id($city_id)
    {
        $query = "SELECT id,city_name from ".$this->_table_name." where id ='".$city_id."'";
        $result = $this->_dbh->query($query);
        $result_data = mysqli_fetch_assoc($result);
        if($result_data['id'] and !empty($result_data['id']))
        {
            return $result_data;
        }
        return false;
    }

    public function retrieve_city_by_user_access($user_id)
    {
        $query = "select  DISTINCT(cities.id),cities.city_name
                    from ".$this->_table_name." as cities, 
                    user_access as user_access 
                    WHERE
                    cities.id = user_access.city_id
                    AND
                    user_access.user_id = ".$user_id;
        $result = $this->_dbh->query($query);
        $trans_data = array();
        while($row = mysqli_fetch_assoc($result))
        {
            $trans_data[] = $row;
        }
        return $trans_data;
    }



}