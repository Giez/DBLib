#[DBLib] - Static Database Library System
DBLib is a library that contain such as short-cut normal querying system for PHP-Mysql System, you can query, get data from query, insert, update and delete with less line and character of code.

## Feature Overview

- So light so tight!
- Simple query with just write this : DB::Query('my query').
- It let you know your Query is! (useful for large scale of query / application).
- Simpicity to doing Insert and Update record.
- Support multiple insert and Update record.
- Easy to delete, you want delete by ID? Or what?

## Using

### Standard Query
```php
<?php
  DB::query('SELECT * FROM mytable');
?>
```

### Return your Query
```php
<?php
  DB::query($complicatedQuery, TRUE);
  // Return your absolute query
?>
```

### Get Data
```php
<?php
  DB::get('SELECT * FROM mytable', 'method'); // You can use assoc or array as method and it will return all data
  DB::get('SELECT * FROM mytable order by id DESC', 'method', TRUE); // You will get one data to return
?>
```

### Insert
```php
<?php
  $data = array(
    'column' => 'data'
  );
  DB::insert($data, 'mytable');
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
