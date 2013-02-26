# Pagination

I hope you find this class useful. It is a simple class to implement and the example.php should provide you with all you need. 
Should you require more options take a look inside the class to find the defaults/optional arguments. You may use the provided
set_up() method if you need to change the defaults.

**Example**

`$pagination_args = array( 'page_get_var' => 'the-page' );`

`Pagination::set_up( $pagination_args );`

The same is true for the results-per-page form.

**Example**

`$form_args = array( 'method' => 'GET', 'options' => array(40,50,60), 'submit_text' => 'Update', 'label' => 'Results per page: ' );`

`echo $pagination->results_per_page_form( $form_args = array() );`