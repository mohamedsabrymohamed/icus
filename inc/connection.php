<?php
	class db_connection
	{
		private $_dbh;
		private $_db_user = 'root';
		private $_db_pass = '123456';
		private $_db_host = 'localhost';
		private $_db_name = 'icu';
		private $_table_name;
		private $_query_string = null;

		public function __construct($table_name)
		{
		    $this->_table_name = $table_name;
		    $this->_dbh = mysqli_connect($this->_db_host, $this->_db_user, $this->_db_pass, $this->_db_name);
		    $db_error = mysqli_connect_error();
		    if($db_error)
		    {
		        die('DB - Access Error!');
		    }
			mysqli_set_charset( $this->_dbh,"utf8");
		}

		public function query($query)
		{
		    $result = mysqli_query($this->_dbh, $query);
		    return $result;
		}

		private function build_insert_query_string(array $data)
		{
		    if(count($data)>0)
		    {
		        $query_string = "";
		        $key_string = "";
		        $value_string = "";

		        $i = 0;
		        foreach($data as $key=>$value)
		        {
		            $i++;
		            if($i!=1)
		            {
		                $key_string .= ", ";
		                $value_string .= ", ";
		            }
		            $key_string .= "`".$key."`";
		            if(!isset($value))
		            {
		                $value_string .= "NULL";
		            }
		            else
		            {
		                $value_string .= "'".mysqli_real_escape_string($this->_dbh, $value)."'";
		            }
		        }
		        $query_string = "INSERT INTO `".$this->_table_name."` (".$key_string.") VALUES(".$value_string.")";
		        $this->_query_string = $query_string;
		    }
		}

		private function build_update_query_string($data,$where)
		{
			$i = 0;
			$query_string = "";
			foreach($data as $key=>$value)
			{
				$i++;
				if($i!=1)
				{
					$query_string .= ", ";
				}
				$query_string .= "`".$key."`='".mysqli_real_escape_string($this->_dbh, $value)."'";
			}
			$query_string = "UPDATE `".$this->_table_name."` SET ".$query_string." where ".$where;

			$this->_query_string = $query_string;
		}

		public function insert(array $data)
		{
		    //return json_encode($data);
		    $this->build_insert_query_string($data);
		    if($this->_query_string and !empty($this->_query_string))
		    {
    		    $this->query($this->_query_string);
    		    return mysqli_insert_id($this->_dbh);
		    }
		    return false;
		}

		public function update(array $data,$where)
		{
			$this->build_update_query_string($data,$where);
		    if($this->_query_string and !empty($this->_query_string))
		    {
    		    $this->query($this->_query_string);
    		    return true;
		    }
		    return false;

		}
		public function close_connection()
		{
		    if($this->_dbh)
		    {
		        mysqli_close($this->_dbh);
		    }
		}
	}
?>
