<?php
/* 
	1. Content and Idea by : Anggie Aziz, Copyright (c) 2013;
	2. Leave this at the top to keep this script stil exists;
	3. Contact me for suggestions and support : anggieaziz@gmail.com;
	Fork This : https://github.com/Giez/MyLib
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

	/**
	 * Connecting to Database.
	 * @return void
	 */
	public static function connect()
	{
		if( mysql_connect(HOSTNAME, UNAME, PASS)) 
		{
			mysql_select_db(DBNAME);
		}
	}

	/**
	 * Crucial / Basic function to query.
	 * @param string $query 
	 * @param boolean $die 
	 * @return object
	 */
	public static function query($query, $die = false)
	{
		// Connect first
		self::connect();

		if($die == false)
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

	/**
	 * Getting based on query and other parameter.
	 * @param string $query 
	 * @param string $method 
	 * @param boolean $dump 
	 * @return array|string
	 */
	public static function get($query, $method = 'array', $dump = false)
	{
		if($dump) 
			self::dd($query);
		
		$column = array(
			'count' => substr_count(stristr($query, 'FROM', true), ','),
			'name' => str_replace(' ', '', str_replace('SELECT', '', stristr($query, 'FROM', true)))
		);
		$query = self::query($query);
		
		if($method == 'row' or $method == 'assoc' or $method == 'array')
		{
			$method = 'mysql_fetch_'.$method;
			while($rows = $method($query))
			{
				$temp[] = $rows;
			}
		}
		elseif($method == 'one')
		{
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

	/**
	 * Insert data to database.
	 * @param array $array 
	 * @param string $table 
	 * @return array|integer
	 */
	public static function insert($array, $table)
	{
		$insID = null;
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
				$insID[] = mysql_insert_id();
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
			$insID = mysql_insert_id();
		}
		return $insID;
		unset($arr, $array, $column, $data, $key, $rows, $table, $insID); // Free up memory, tested
	}

	/**
	 * Update data from database.
	 * @param array $array 
	 * @param string $table 
	 * @param integer $id 
	 * @param string $where 
	 * @return void
	 */
	public static function update($array, $table, $id = null, $where = null)
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
				if($id != null && $where == null)
				{
					self::query("UPDATE `$table` SET ".implode(', ', $update)." WHERE `$colid` = '$dataid'");
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
			if($id != null && $where == null)
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

	/**
	 * Delete data from database.
	 * @param string $table 
	 * @param integer $id 
	 * @param string $where 
	 * @return void
	 */
	public static function delete($table, $id = null, $where = null)
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
		if($id != null && $where == null)
		{
			self::query("DELETE FROM `$table` WHERE `$colid` = '$dataid'");
		}
		else
		{
			self::query("DELETE FROM `$table` WHERE $where");
		}
		unset($id, $colid, $dataid, $where, $table);
	}
	
	/**
	 * Die and Dump all in parameter.
	 * @return void
	 */
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