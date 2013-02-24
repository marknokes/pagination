<?php
include 'Pagination.class.php';

/**
* example usage:
*
* $default_rows_per_page	= 10;
* $max_rows_per_page 		= 99;
* $page_GET_var 			= 'page-item';
* $perpage_GET_var 			= 'items-per-page';
* Pagination::set_up_vars( $default_rows_per_page , $max_rows_per_page , $page_GET_var , $perpage_GET_var );
* $result 				= $db->query( SELECT SQL_CALC_FOUND_ROWS data FROM table WHERE column = 'var' LIMIT " . Pagination::get_rows_per_page() . " OFFSET " . Pagination::get_offset() . " );
* 
* // The following will get the total records as if not using the LIMIT above as long as the main query contains SQL_CALC_FOUND_ROWS
* $found_rows_res		= $db->query("SELECT FOUND_ROWS()");
* $found_rows			= $found_rows_res->fetch_row();
* $total_records		= $found_rows[0];
*/

$default_rows_per_page	= 10;
$max_rows_per_page 		= 99;
$page_GET_var 			= 'page-item';
$perpage_GET_var 		= 'items-per-page';
Pagination::set_up_vars( $default_rows_per_page , $max_rows_per_page , $page_GET_var , $perpage_GET_var );
// This will serve as the example query
for ( $i = 1 ; $i <= 100 ; $i++ ){$test_array[$i] = 'Result ' . $i;}
$results 			= array_slice( $test_array , Pagination::get_offset() , Pagination::get_rows_per_page() );
$total_records		= sizeof($test_array);
$visible_results	= implode( '<br />' , $results );

// see class for defaults/other args
$pagination_args = array(
	'total_records'			=> $total_records,
	'visible_page_numbers' 	=> 5,
	'style'					=> true,
	'container_class'		=> 'custom-pagination-class',
	'link_text'				=> array(
		'next'	=> 'Next',
		'prev'	=> 'Previous',
		'first'	=> '&#171;',
		'last'	=> '&#187;'
	)
);
$pagination = new Pagination( $pagination_args );

// see class for defaults/other args
$form_args = array(
	'total_records'	=> $total_records,
	'action'		=> basename( $_SERVER['PHP_SELF'] ),
	'method'		=> 'GET',
	'options' 		=> array(10,20,30),
	'class'			=> 'custom-form-class',
	'submit_text'	=> 'Update',
	'label'			=> 'Results per page: '
);
?>
<html>
	<head>
		<style>
			.custom-form-class,
			.custom-pagination-class,
			.items {
				float:left;
				clear:both;
				margin:10px 0
			}
		</style>
	</head>
	<body>
		<h1>Pagination Class Example</h1>
		<div class="items">
			<?php
			/**
			* //Do your loop here
			* while ( $item = $result->fetch_object() )
			* {
			*	 echo '<li>' . $item->data . '</li>';
			* }
			*/
			// I'll just echo my array slice
			echo $visible_results;
			?>
		</div>
		<?php echo $pagination->links; ?>
		<?php echo $pagination->results_per_page_form( $form_args ); ?>
	</body>
</html>


