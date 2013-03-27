<?php
/* 
	1. Content and Idea by : Anggie Aziz, all rights accepted. Copy 2013;
	2. Leave this at the top to keep this script stil exists;
	3. Contact me for suggestions and support : me@anggieaziz;
	Git this for more update : https://github.com/Giez/DBLib.git
*/

/************************
		 SETTINGS
*************************/
define('HOSTNAME', 'localhost'); // Server Hostname
define('UNAME', 'uname'); // Database Username
define('PASS', 'pass'); // Database Password
define('DBNAME', 'dbname'); // Database Name

/************************
		  SYSTEM
*************************/
class DB
{
	public static function connect()
	{
		if( mysql_connect(HOSTNAME, UNAME, PASS)) mysql_select_db(DBNAME);
	}
	public static function query($query, $die = FALSE)
	{
		self::connect();
		if($die == FALSE)
		{
			$temp = mysql_query($query) or die(mysql_error().'<br /> Query :'.$query);
			return $temp;
		}
		else
		{
			die($query);
		}
	}
	public static function get($query, $method = 'array', $one = FALSE)
	{
		$query = self::query($query);
		if($method == 'array')
		{
			while($rows = mysql_fetch_array($query))
			{
				$temp[] = $rows;
			}
		}
		elseif($method == 'assoc')
		{
			while($rows = mysql_fetch_assoc($query))
			{
				$temp[] = $rows;
			}
		}
		if(isset($temp) && $one == FALSE) return $temp; elseif(isset($temp) && $one == TRUE) return $temp[0]; else return false;
	}
	public static function insert($array, $table)
	{
		if(isset($array[0]))
		{
			foreach($array as $arr)
			{
				$column = array();
				$data = array();
				foreach($arr as $key => $rows)
				{
					if(! in_array($key, $column)){ $column[] = $key; }
					if(! in_array($rows, $data)) { $data[] = $rows != '' or $rows != 'null' or $rows != null ? "'$rows'" : 'null'; }
				}
				self::query("INSERT INTO $table (".implode(',', $column).") VALUE (".implode(',', $data).")");
				unset($column); unset($data);
			}
		}
		else
		{
			$column = array();
			$data = array();
			foreach($array as $key => $rows)
			{
				if(! in_array($key, $column)){ $column[] = $key; }
				if(! in_array($rows, $data)) { $data[] = $rows != '' ? "'$rows'" : 'null'; }
			}
			self::query("INSERT INTO $table (`".implode('`,`', $column)."`) VALUE (".implode(',', $data).")");
		}
	}
	public static function update($array, $table, $id = FALSE, $where = FALSE)
	{
		if(isset($array[0]))
		{
			foreach($array as $arr)
			{
				$update = array();
				foreach ($arr as $column => $data)
				{
					$update[] .= "$column = '$data'";
				}
				if($id != FALSE && $where == FALSE)
				{
					self::query("UPDATE $table SET ".implode(', ', $update)." WHERE id = '$id'");
				}
				else
				{
					self::query("UPDATE $table SET ".implode(', ', $update)." WHERE $where");
				}
				unset($update);
				}
		}
		else
		{
			$update = array();
			foreach ($array as $column => $data)
			{
				$update[] .= "$column = '$data'";
			}
			if($id != FALSE && $where == FALSE)
			{
				self::query("UPDATE $table SET ".implode(', ', $update)." WHERE id = '$id'");
			}
			else
			{
				self::query("UPDATE $table SET ".implode(', ', $update)." WHERE $where");
			}
		}
	}
	public static function delete($table, $id = FALSE, $where = FALSE)
	{
		if($id != FALSE && $where == FALSE)
		{
			self::query("DELETE FROM $table WHERE id = '$id'");
		}
		else
		{
			self::query("DELETE FROM $table WHERE $where");
		}
	}
}
?>