# Pagination

I hope you find this class useful. It is a simple class to implement and the example.php should provide you with all you need. 
Should you require more options take a look inside the class to find the defaults/optional arguments. You may use the provided
set_up() method if you need to change the defaults.

**Changing Defaults Example**

```php
$pagination_args = array(
	'page_get_var' => 'the-page'
);

Pagination::set_up( $pagination_args );
```

**Form Example**
```php
$form_args = array(
	'method' 		=> 'POST', // Maybe if using ajax perhaps
	'options' 		=> array(40,50,60),
	'submit_text' 	=> 'Submit',
	'label' 		=> 'Items per page: '
);

echo $pagination->results_per_page_form( $form_args );
```

**Database Query Example**
```php
// Pagination::set_up( array() ); // Optional

$result = $db->query( SELECT SQL_CALC_FOUND_ROWS data FROM table WHERE column = 'var' LIMIT ". Pagination::get_rows_per_page() ." OFFSET ". Pagination::get_offset() ." );

// Total records without LIMIT. Note: The main query must contain SQL_CALC_FOUND_ROWS
$found_rows_result 	= $db->query("SELECT FOUND_ROWS()");

$found_rows		 	= $found_rows_result->fetch_row();

$total_records	 	= $found_rows[0];

$pagination 		= new Pagination( $total_records );
```