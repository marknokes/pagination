<?php

class Pagination
{
	/*
	* The defaults array. If you need to change the defaults use Pagination::set_up( $args = array() )
	*/
	private static $defaults = array(
		'default_rows_per_page' => 10,
		'max_rows_per_page' 	=> 99,
		'page_get_var' 			=> 'page',
		'perpage_get_var' 		=> 'perpage',
		'visible_page_numbers' 	=> 5,
		'style'					=> true,
		'link_text'				=> array(
			'next'	=> 'Next',
			'prev'	=> 'Previous',
			'first'	=> '&#171;',
			'last'	=> '&#187;'
		)
	);
	
	/**
	* Constructs the pagination object within which is a link list
	*
	* @param int $total_records The total records from the query without the limit clause. See example.php for an example of how to get this.
	*
	* @return object The only use for the return object is the links, i.e., if you instantiate $pagination, you will echo $pagination->links.
	*				 Public methods include (static) get_offset(), result_per_page_form(), and (static) get_rows_per_page().
	*				 The two public static methods may be useful for your db query.
	*/
	function __construct( $total_records )
	{
		$this->total_records		= $total_records;
		$this->rows_per_page 		= self::get_rows_per_page();
		$this->current_page			= self::get_current_page();
		$this->prev_page			= $this->current_page -1;
		$this->next_page			= $this->current_page +1;
		$this->last_page 			= ceil( $this->total_records / $this->rows_per_page  );
		$this->links				= $this->links();
	}
	
	/**
	* Set up the variables for the GET vars and default values
	*
	* @return null This just sets up private static $defaults for use throughout the class
	*/
	public static function set_up( $args = array() )
	{
		$args = array_merge( self::$defaults, $args );
		self::$defaults = $args;
		return null;
	}
	
	/**
	* Get the offset for a paginated mysql query
	*
	* @return int
	*/
	public static function get_offset()
	{
		$offset	= ( self::get_current_page() - 1 ) * self::get_rows_per_page();
		return $offset;
	}
	
	/**
	* Gets the rows per page
	*
	* @return int Returns per_page_get_var|default
	*/
	public static function get_rows_per_page()
	{
		$rows_per_page 	= (
			$_GET[self::$defaults['perpage_get_var']] &&
			is_numeric( $_GET[self::$defaults['perpage_get_var']] ) &&
			( $_GET[self::$defaults['perpage_get_var']] <= self::$defaults['max_rows_per_page'] )
		) ? $_GET[self::$defaults['perpage_get_var']] : self::$defaults['default_rows_per_page'];
		return $rows_per_page;
	}
	
	/**
	* Gets the current page
	*
	* @return int Returns page_get_var|1
	*/
	private static function get_current_page()
	{
		$current_page 	= (
			$_GET[self::$defaults['page_get_var']] &&
			is_numeric( $_GET[self::$defaults['page_get_var']] )
		) ? $_GET[self::$defaults['page_get_var']] : 1;
		return $current_page;
	}

	/**
	* Display a results per page form
	*
	* @param array $args Array that serves as the defaults.
	*
	* @return string The html that produces the form
	*/
	public function results_per_page_form( $args = array() )
	{
		$defaults = array(
			'action'		=> basename( $_SERVER['PHP_SELF'] ),
			'method'		=> 'GET',
			'options'		=> array(10,20,30,40,50),
			'submit_text'	=> 'Update',
			'label'			=> 'Results per page: '
		);
		
		$args = array_merge( $defaults , $args );
		
		if ( $this->total_records > $args['options'][0] )
		{
			$form = '<form action="'. $args['action'] .'" method="'. $args['method'] .'">';
			foreach ( $_GET as $param => $value )
			{	
				if ( $param == self::$defaults['perpage_get_var'] || $param == self::$defaults['page_get_var'] ) continue;
				$form .= '<input type="hidden" name="'. $param .'" value="'. $value .'">';
			}
			if ( $args['label'] )
			{
				$form.=	'<label for="perpage">'. $args['label'] .'</label>';
			}
			$form .= '<select name="'. self::$defaults['perpage_get_var'] .'">';
					foreach ( $args['options'] as $num )
					{
						if ( $_GET[self::$defaults['perpage_get_var']] == $num )
						{
							$form .= '<option selected="selected" value="'. $num .'">'. $num .'</option>';
						}
						else
						{
							$form .= '<option value="'. $num .'">'. $num .'</option>';
						}
					}
					if ( $args['submit_text'] )
					{
						$form .= '<input type="submit" value="'. $args['submit_text'] .'">';
					}
					$form .= '
				</select>
			</form>';
			return $form;
		}
		else return null;
	}
		
	/**
	* Gets the current query string for use in render_link()
	*
	* @return string Returns a formatted query string with page and perpage and any other params that exist in the GET string
	*/
	private function get_query_string( $page )
	{
		$page_vars = array(
			self::$defaults['perpage_get_var'] 	=> $this->rows_per_page,
			self::$defaults['page_get_var']		=> $page
		);
		
		$query_vars = array_merge( $_GET, $page_vars );
				
		$query_string = '?' . http_build_query( $query_vars );
		
		return $query_string;
	}
	
	/**
	* Creates the list items and links for the visible page numbers
	*
	* @return string Returns html <li><a></a></li> with active class on li
	*/
	private function visible_page_number_links()
	{
		if ( $this->last_page != 1 && $this->last_page >= self::$defaults['visible_page_numbers'] )
		{
			$links_array = array();
			for( $i = 1 ; $i <= $this->last_page ; $i++ )
			{
				if ( $i == $this->current_page )
					$active = 'class="active"';
				else
					$active = '';
				
				$links_array[$i] = '<li '. $active .'>'. $this->render_link( 'visible' , $i ) .'</li>';
			}
			$numbers_before = ceil( self::$defaults['visible_page_numbers']/2 );
			$numbers_after 	= floor( self::$defaults['visible_page_numbers']/2 );
			
			if ( $this->current_page <= $numbers_before )
				// beginning
				$visible = array_slice( $links_array , 0 , self::$defaults['visible_page_numbers'] );			
			elseif ( $this->last_page - $this->current_page <= $numbers_after )
				// end
				$visible = array_slice( $links_array, $this->last_page - self::$defaults['visible_page_numbers'], self::$defaults['visible_page_numbers'] );			
			else 
				// middle
				$visible = array_slice( $links_array , $this->current_page - $numbers_before , self::$defaults['visible_page_numbers'] );
				
			$links = implode( '' , $visible );
			return $links;
		}
		return '';
	}
	
	/**
	* Create the html for the next link
	*
	* @return string The html for the next link
	*/
	private function next_link()
	{
		if ( $this->current_page != $this->last_page )
		{
			$next_link = $this->render_link( 'next' , $this->next_page );
			return $next_link;
		}
		return null;
	}
	
	/**
	* Create the html for the prev link
	*
	* @return string The html for the prev link
	*/
	private function prev_link()
	{
		if ( $this->current_page != 1 )
		{
			$prev_link = $this->render_link( 'prev' , $this->prev_page );
			return $prev_link;
		}
		return null;
	}
	
	/**
	* Create the html for the first page link
	*
	* @return string The html for the first page link
	*/
	private function first_link()
	{
	
		if ( $this->current_page != 1 )
		{
			$first_link = $this->render_link( 'first' , 1 );
			return $first_link;
		}
		return null;
	}
	
	/**
	* Create the html for the last page link
	*
	* @return string The html for the last page link
	*/
	private function last_link()
	{
		if ( $this->current_page != $this->last_page )
		{
			$last_link = $this->render_link( 'last' , $this->last_page );
			return $last_link;
		}
		return null;
	}
	
	private function render_link( $type , $page )
	{
		$url = $this->get_query_string( $page );
		if ( $type == 'visible' )
			$link = '<a class="pagination-anchor visible-page-number-'. $page .'" href="'. $url .'">'. $page .'</a>';
		else
			$link = '<li><a class="pagination-anchor '. $type .'" href="'. $url .'">'. self::$defaults['link_text'][$type] .'</a></li>';
		return $link;
	}
	
	/**
	* Combine all of the links to create an unordered list
	*
	* @return string The html for the ul that contains the First, Prev, Visible Pages, Next, and Last links
	*/	
	private function links()
	{
		if ( $this->last_page != 0 && $this->total_records > $this->rows_per_page )
		{
			$links = '';
			
			if ( self::$defaults['style'] )
			{
				ob_start();
				include 'pagination.css';
				$css = ob_get_clean();
				$links .= '<style>'.$css.'</style>';
			}
				
			$links .= '<ul>';
			$links .= $this->first_link();
			$links .= $this->prev_link();
			$links .= $this->visible_page_number_links();
			$links .= $this->next_link();
			$links .= $this->last_link();
			$links .= '</ul>';
			return $links;
		}
		return null;
	}
}