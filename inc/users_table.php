<?php
class users_table
{
    private $_dbh;
    private $_table_name = 'users';
    public function __construct()
    {
        $this->_dbh = new db_connection($this->_table_name);
    }


    public function retrieve_username_by_user_id($user_id)
    {
        $query = "SELECT id,username from ".$this->_table_name." where id ='".$user_id."'";
        $result = $this->_dbh->query($query);
        $result_data = mysqli_fetch_assoc($result);
        if($result_data['id'] and !empty($result_data['id']))
        {
            return $result_data;
        }
        return false;
    }

    public function retrieve_user_data_by_user_id($user_id)
    {
        $query = "SELECT id,full_name,status,session_id,email,user_type,username from ".$this->_table_name." where id ='".$user_id."'";
        $result = $this->_dbh->query($query);
        $result_data = mysqli_fetch_assoc($result);
        if($result_data['id'] and !empty($result_data['id']))
        {
            return $result_data;
        }
        return false;
    }


    public function update_user_data(array $user_data,$condition)
    {
        return $this->_dbh->update($user_data, $condition);
    }

    public function update_user_session($user_id,$session_id)
    {
        $query = "update ".$this->_table_name." 
					SET 
					session_id = '".$session_id."'
					WHERE  
					id ='".$user_id."'";

        $result = $this->_dbh->query($query);

        if($result)
        {
            return true;
        }
        return false;
    }


    public function add_new_user(array $user_data)
    {
        if($user_data)
        {
            $password = $user_data['password'];
            if($password)
            {
                $rng = new CSPRNG();
                $user_data['salt'] = hash('SHA256',$rng->GenerateToken());
                $password_string = hash('SHA256',$user_data['password']);
                $user_data['password'] = hash('SHA256',$user_data['salt'].$password_string);
                return $this->_dbh->insert($user_data);
            }
        }
        return false;
    }

    public function retrieve_user_by_username($username)
    {
        $query = "select id,full_name,password,salt from ".$this->_table_name." where username ='".$username."'";
        $result = $this->_dbh->query($query);
        $result_data = mysqli_fetch_assoc($result);
        if($result_data['id'] and !empty($result_data['id']))
        {
            return $result_data;
        }
        return false;
    }

    public function verify_user($username,$user_password)
    {
        $user_data = $this->retrieve_user_by_username($username);
        if($user_data)
        {
            $password_string = hash('SHA256',$user_password);
            $user_password = hash('SHA256',$user_data['salt'].$password_string);
            if($user_password == $user_data['password'])
            {
                return $user_data['id'];
            }
        }
        return false;
    }

    public function count_users(){
        $query = "SELECT count(id) as count_users from ".$this->_table_name;
        $result = $this->_dbh->query($query);
        $result_data = mysqli_fetch_assoc($result);
        if($result_data['count_users'] and !empty($result_data['count_users']))
        {
            return $result_data;
        }
        return false;
    }

    public function retrieve_all_users(){
        $query = "SELECT id,full_name,email,username,status,user_type from ".$this->_table_name;
        $result = $this->_dbh->query($query);
        $trans_data = array();
        while($row = mysqli_fetch_assoc($result))
        {
            $trans_data[] = $row;
        }
        return $trans_data;
    }


    public function retrieve_all_users_except_current($user_id){
        $query = "SELECT id,full_name,email from ".$this->_table_name." where user_type = 1 and id != ".$user_id;
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
					id ='".$user_id."'";

        $result = $this->_dbh->query($query);

        if($result)
        {
            return true;
        }
        return false;
    }


    public function update_user_password(array $user_data,$uid)
    {
        $user_id = $uid;
        $password = $user_data['password'];
        $user_where = '`id`='.$user_id;

        if($password)
        {
            $rng = new CSPRNG();
            $user_data['salt'] = hash('SHA256',$rng->GenerateToken());
            $password_string = hash('SHA256',$user_data['password']);
            $user_data['password'] = hash('SHA256',$user_data['salt'].$password_string);
            return $this->update_user_data($user_data, $user_where);
        }

        return false;
    }



}
?>
