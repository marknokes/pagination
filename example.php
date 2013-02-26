<?php
include 'Pagination.class.php';

// this will serve as a pretend db query...
for ( $i = 1 ; $i <= 100 ; $i++ )
{
	$test_array[$i] = 'Result ' . $i;
}
$results 		= array_slice( $test_array , Pagination::get_offset() , Pagination::get_rows_per_page() );

$test_data		= implode( '<br />' , $results );

$total_records	= sizeof($test_array);

$pagination 	= new Pagination( $total_records );
?>
<html>
	<head>
		<style>
			body {
				width: 800px;
				margin: auto;
			}
			.results-per-page,
			.pagination,
			.items {
				float:left;
				clear:both;
				margin:10px 0
			}
		</style>
	</head>
	<body>
		<h1>Pagination Class Example</h1>
		<div class="pagination">
			<?php echo $pagination->links; ?>
		</div>
		<div class="items">
			<?php
			//Do your loop here. I'll just echo my array slice
			echo $test_data;
			?>
		</div>
		<div class="pagination">
			<?php echo $pagination->links; ?>
		</div>
		<div class="results-per-page">
			<?php
			// see class for defaults/optional args
			echo $pagination->results_per_page_form( $optional_args = array() );
			?>
		</div>
	</body>
</html>


