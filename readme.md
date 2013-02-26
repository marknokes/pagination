# Pagination

I hope you find this class useful. It is a simple class to implement and the example.php should provide you with all you need. 
Should you require more options take a look inside the class to find the defaults/optional arguments. You may use the provided
set_up() method if you need to change the defaults.

**Example**

```php
$pagination_args = array(
	'page_get_var' => 'the-page'
);

Pagination::set_up( $pagination_args );
```

The same is true for the results-per-page form.

**Example**
```php
$form_args = array(
	'method' 		=> 'POST', // Maybe if using ajax perhaps
	'options' 		=> array(40,50,60),
	'submit_text' 	=> 'Submit',
	'label' 		=> 'Items per page: '
);

echo $pagination->results_per_page_form( $form_args );
```