<?php
if(!function_exists('wp_get_current_user'))
	include(ABSPATH . "wp-includes/pluggable.php");

$def = new default_functions();
class default_functions{
	function access(){
		return TRUE;
		$user = wp_get_current_user();
		if($user->ID == 1)
			return true;
		else
			return false;
	}

	/*
	//Get the ID of the top parent in the hierarchy, of the current childpage
	function get_page_classes($show_topname = TRUE, $show_filename = TRUE){
		global $post;
		$wrapper_class = (is_front_page() || is_home()) ? "index" : "subpage";
		$topPage       = strtolower( get_post($this->get_top_parent())->post_name );
		$pageClasses = '';

		if($show_topname === TRUE)
			$pageClasses = $wrapper_class .' '. $topPage;

		if($topPage != strtolower($post->post_name))
			$pageClasses .= ' '. $post->post_name;

		if($show_filename === TRUE) {
			$pathinfo =	pathinfo(get_page_template());
			$pageClasses .= $pathinfo['filename'];
		}

		return $pageClasses;
	}
	*/

	function get_page_id($page_slug) {
		$page = get_page_by_path($page_slug);
		if ($page)
			return $page->ID;
		else
			return false;
	}

	function get_top_parent($return_title = false) {

		global $post;
		$ancestors = $post->ancestors;
		// Check if page is a child page (any level)
		if ($ancestors)
			$id = end($ancestors);//  Grab the ID of top-level page from the tree
		else
			$id = $post->ID; // Page is the top level, so use  it's own id

		if($return_title === FALSE)
			return $id;
		else
			return get_the_title($id);
	}
}


?>