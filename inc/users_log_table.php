<?php 
    class log_table
    {
        private $_dbh;
        private $_table_name = 'users_log';
        public function __construct()
        {
            $this->_dbh = new db_connection($this->_table_name);
        }
        
        public function create_login_log($location,$session_id)
        {
            $user_id 		= get_login_user_id();
            $user_os        =   getOS();
			$user_browser   =   getBrowser();

	    $log_data = array();
            if($user_id and !empty($user_id))
            {
                $log_data['user_id'] = $user_id;
                $log_data['session_id'] = $session_id;
                $log_data['ip'] = $_SERVER['REMOTE_ADDR'];
                $log_data['browser'] = $user_browser;
                $log_data['os'] = $user_os ;
                $log_data['location'] = $location ;
                $log_data['login_time'] = DATE('Y-m-d H:i:s');
				return $this->_dbh->insert($log_data);
            }
            return false;        
        }
		
		public function retrieve_login_by_user_id($user_id,$session_id)
		{
			
			$query = "SELECT * FROM ".$this->_table_name." 
						where 
						USER_ID = '".$user_id."'
						AND
						session_id='".$session_id."'";
            $result = $this->_dbh->query($query);
            $login_data = array();
			
            while($row = mysqli_fetch_assoc($result))
            {
                $login_data[] = $row;        
            }
            return $login_data;
		}

		public function update_login_user($user_id,$session_id)
		{
            $logout_time= date("Y-m-d H:i:s");
            $query = "update ".$this->_table_name." 
					SET 
					logout_time = '".$logout_time."'
					WHERE  
					session_id = '".$session_id."'
					and
					user_id ='".$user_id."'";
					 
            $result = $this->_dbh->query($query);
		   
		    if($result)
		    {
		        return true;
		    }
		    return false;		    
		}
    }
    
?>
