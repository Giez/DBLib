#[DBLib] - Static Database Library System
DBLib is a library that contain such as short-cut normal querying system for PHP-Mysql System, you can query, get data from query, insert, update and delete with less line and character of code.

## Feature Overview

- So light so tight!
- Simple query with just write this : DB::Query('my query').
- It let you know your Query is! (useful for large scale of query / application).
- Simpicity to doing Insert and Update record.
- Support multiple insert and Update record.
- Easy to delete, you want delete by ID? Or what?

## Examples

### Standard Query
```php
<?php
  DB::query('SELECT * FROM mytable');
?>
```

### Get your Query
```php
<?php
  DB::query($complicatedQuery, TRUE);
  // Return your absolute query
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
  DB::update($data, 1); // 1 as id
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
  DB::delete('mytable', 1); // 1 as ID
// OR
  DB::delete('mytable', FALSE, "mycolumn = 'what'");
?>
```
