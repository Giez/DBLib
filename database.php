<?php
/* 
	1. Content and Idea by : Anggie Aziz, Copyright (c) 2013;
	2. Leave this at the top to keep this script stil exists;
	3. Contact me for suggestions and support : me@anggieaziz;
	Git this for more update : https://github.com/Giez/DBLib.git
	This content is released under the MIT License (http://opensource.org/licenses/MIT).
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
		unset($query, $temp, $die); // Free up memory, tested
	}
	public static function get($query, $method = 'array', $dump = false)
	{
		if($dump) DB::dd($query);
		
		$column = array(
			'count' => substr_count(stristr($query, 'FROM', true), ','),
			'name' => str_replace(' ', '', str_replace('SELECT', '', stristr($query, 'FROM', true)))
		);
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
		elseif($method == 'one')
		{
			$i = 0;
			// Expecting number of column should be shown
			if($column['count'] > 0 or $column['name'] == '*')
				$temp = mysql_fetch_assoc($query);
			elseif($column['count'] == 0 and $column['name'] != '*')
			{
				$temp = mysql_fetch_row($query);
				if(isset($temp))
					$temp = $temp[0]; // Return the result as requested single column
				else
					$temp = null;
			}
		}
		if(isset($temp)) return $temp;
			else return null;
		unset($column['count'], $method, $result, $query, $rows, $temp); // Free up memory, tested
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
					if(! in_array($rows, $data)) { $data[] = $rows != '' ? "'".str_replace("'NOW()'", 'NOW()',"'$rows'") : 'null'; }
				}
				self::query("INSERT INTO `$table` (`".implode('`,`', $column)."`) VALUE (".implode(',', $data).")");
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
				if(! in_array($rows, $data)) { $data[] = $rows != '' ? str_replace("'NOW()'", 'NOW()',"'$rows'") : 'null'; }
			}
			self::query("INSERT INTO `$table` (`".implode('`,`', $column)."`) VALUE (".implode(',', $data).")");
		}
		unset($arr, $array, $column, $data, $key, $rows, $table); // Free up memory, tested
	}
	public static function update($array, $table, $id = FALSE, $where = FALSE)
	{
		if(is_array($id))
		{
			$colid = $id[0];
			$dataid = $id[1];
		}
		else
		{
			$colid = 'id';
			$dataid = $id;
		}
		if(isset($array[0]))
		{
			foreach($array as $arr)
			{
				$update = array();
				foreach ($arr as $column => $data)
				{
					$data = $data != '' ? "'$data'" : 'null';
					$data = str_replace("'NOW()'", 'NOW()', $data); // if using NOW() function
					$update[] .= "`$column` = $data";
				}
				if($id != FALSE && $where == FALSE)
				{
					print("UPDATE `$table` SET ".implode(', ', $update)." WHERE `$colid` = '$dataid'");
				}
				else
				{
					self::query("UPDATE `$table` SET ".implode(', ', $update)." WHERE $where");
				}
				unset($update);
				}
		}
		else
		{
			$update = array();
			foreach ($array as $column => $data)
			{
				$data = $data != '' ? "'$data'" : 'null';
				$data = str_replace("'NOW()'", 'NOW()', $data); // if using NOW() function
				$update[] .= "`$column` = $data";
			}
			if($id != FALSE && $where == FALSE)
			{
				self::query("UPDATE `$table` SET ".implode(', ', $update)." WHERE `$colid` = '$dataid'");
			}
			else
			{
				self::query("UPDATE `$table` SET ".implode(', ', $update)." WHERE $where");
			}
		}
		unset($array, $arr, $colid, $column, $data, $dataid, $id, $table, $update, $where); // Free up memory, tested
	}
	public static function delete($table, $id = FALSE, $where = FALSE)
	{
		if(is_array($id))
		{
			$colid = $id[0];
			$dataid = $id[1];
		}
		else
		{
			$colid = 'id';
			$dataid = $id;
		}
		if($id != FALSE && $where == FALSE)
		{
			self::query("DELETE FROM `$table` WHERE `$colid` = '$dataid'");
		}
		else
		{
			self::query("DELETE FROM `$table` WHERE $where");
		}
		unset($id, $colid, $dataid, $where, $table);
	}
	
	public static function dd()
	{
		$numargs = func_num_args();
		$arguments = func_get_args();
		for ($i = 0; $i < $numargs; $i++) 
		{
			var_dump($arguments[$i]);
		}
		die();
	}
}
?>