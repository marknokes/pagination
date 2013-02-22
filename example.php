<?php
include 'Pagination.class.php';

/**
* example usage:
*
* $rows_per_page 		= Pagination::get_rows_per_page();
* $offset				= Pagination::get_offset();
* $result 				= $db->query( SELECT SQL_CALC_FOUND_ROWS data FROM table WHERE column = 'var' LIMIT " . $rows_per_page . " OFFSET " . $offset . " );
* 
* // The following will get the total records as if not using the LIMIT above as long as the main query contains SQL_CALC_FOUND_ROWS
* $found_rows_res		= $db->query("SELECT FOUND_ROWS()");
* $found_rows			= $found_rows_res->fetch_row();
* $total_records		= $found_rows[0];
*/

$total_records = 100;

// see class for defaults
$pagination_args = array(
	'total_records'			=> $total_records,
	'visible_page_numbers' 	=> 5,
	'page_get_var'			=> 'page-number',
	'perpage_get_var'		=> 'items-per-page',
	'style'					=> true,
	'class'					=> 'custom-pagination-class',
	'link_text'				=> array(
		'next'	=> 'Next',
		'prev'	=> 'Previous',
		'first'	=> '&#171;',
		'last'	=> '&#187;'
	)
);
$pagination = new Pagination( $pagination_args );

// see class for defaults
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
			.custom-pagination-class {
				float:left;
				clear:both;
				margin:10px 0
			}
		</style>
	</head>
	<body>
		<h1>Pagination Class Example</h1>
		
		<ul class="items">
			<?php
			/*
			* Example loop from example query above
			* while ( $item = $result->fetch_object() )
			* {
			*	 echo '<li>' . $item->data . '</li>';
			* }
			*/
			?>
		</ul>
		<?php echo $pagination->links; ?>
		<?php echo $pagination->results_per_page_form( $form_args ); ?>
	</body>
</html>


