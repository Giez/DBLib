#[MyLib] - MySQL Database Library System
MyLib is a library that contain such as short-cut normal querying system for PHP-Mysql System, you can query, get data from table, insert, update and delete with less line and character of code. It also using static class to make everthing faster and easy to use but still powerful.

## Feature Overview

- So light so tight!
- Simple query with just write this : DB::Query('my query').
- It let you know your Query is! (useful for large scale of query / application).
- Simpicity to doing Insert and Update record.
- Support multiple insert and Update record.
- Easy to delete, you want delete by ID? Or what?
- Getting one row data is now smarter! It'll follow your query now.
- Die Dumping with unlimited parameter! (faster than u write var_dump(), then die()).

## Using

### Connecting to Database (Not Exactly Required)
```php
<?php
  DB::connect();
?>
```

### Standard Query
```php
<?php
  DB::query('SELECT * FROM mytable');
?>
```

### Return your Query (Work on Query, Get, Insert, Update and Delete)
```php
<?php
  DB::$dumpQuery = true;
  DB::$dumpType = 'print'; // Optional, use it when you need print_r to echo query.
  DB::get($complicatedQuery);
  // Return your absolute query
?>
```

### Get Data
```php
<?php
  DB::get('SELECT * FROM mytable', 'method'); // You can use like 'row' or 'assoc' or 'array' as method or also 'one' for one record (or column*) of data returning. 
  $foov = DB::get('SELECT foo FROM mytable order by id DESC', 'one'); // You will get last ID of foo on your $foov variable. 
?>
```

### Insert
```php
<?php
  $data = array(
    'column' => 'data'
  );
  $insert = DB::insert($data, 'mytable');
  var_dump($insert); // Will return the last Insert ID. If multiple insert, it will return an array of last Insert ID.
?>
```

### Update
```php
<?php
  $data = array(
    'column' => 'data'
  );
  /* BY ID */
  DB::update($data, 1); // 1 as id number (will using 'id' as default table id)
  DB::update($data, array('tableID', 1)) // tableID as custom table ID, 1 as id number
  /* USING WHERE */
  DB::update($data, FALSE, "mycolumn = 'what'")
?>
```

### Multiple Insert or Update
```php
<?php
  $data = array(
    array(
      'column' => 'data'
    ),
    array(
      'column' => 'data'
    )
  );
  // Call here
?>
```

### Deleting Record
```php
<?php
/* BY ID */
  DB::delete('mytable', 1); // 1 as id number (will using 'id' as default table id)
  DB::delete('mytable', array('tableID', 1)) // tableID as custom table ID, 1 as id number); 
/* USING WHERE */
  DB::delete('mytable', FALSE, "mycolumn = 'what'");
?>
```

### Die Dumping
```php
<?php
  DB::dd($query, $value, 'wow', 1, 2, 3); // Die Dumping with unlimited parameter.
?>
```

## Note
- If no stristr() function exists in your PHP you can use this at line 46 and 47 (replacing : stristr($query, 'FROM', true))  : substr($query, 0, stripos($query, 'FROM')).

## License
Released under the [MIT license](http://www.opensource.org/licenses/MIT).
