<?php 
/**
* @author xninedesign.pro
* @copyright (c) 2023 xninedesign.pro
* @description A Wordpress function to get random posts ID's. 
* @version 1.0
* @license http://creativecommons.org/licenses/by/3.0/ Creative Commons 3.0
* 
* Description:
* $post_type - (string) Specify what type of posts you want to draw an IDs from
* $termid - (int) The term ID used to filter posts by taxonomy terms
* $multiplier - (int) A multiplier to increase the number of posts queried to ensure randomness.
* $numberids - (int) The number of post IDs to return
* $issticked - (boolean) A boolean indicating whether to include sticky posts.
*
*/

  function random_postsids( $post_type = "post" , $termid, $multiplier = 2, $numberids, $issticked = false ) {

		if ( !$post_type  ) { return array(); exit; }
		if ( term_exists( (int)$termid ) == null ) $termid = '';

		if ( $termid == 0 || $termid == "" ) {
			
			$cat = '';
			$topicq = '';
			$sticky = '';
			
		} else {
			
			if ( $post_type == "post" ) {
				
				$cat = $termid;
				$topicq = '';
				
				if ( $issticked == true ) $sticky = get_option( 'sticky_posts' );
				else 
					$sticky = array();				
				
			} else {
				
				$cat = '';
				$topicq = array ( array(
							'taxonomy' => 'bsnewscat',
							'field' => 'term_taxonomy_id',
							'terms' => $termid,
							)
						);
				$sticky = array();
				
			}
		
		}
		
		$args = array(
			'post_type' 		=> $post_type,
			'post_status' 		=> 'publish',
			'numberposts' 		=> $numberids * $multiplier,
			'tax_query' 			=> $topicq,
			'cat' 					=> $cat,
			'fields' 			=> 'ids',
			'post__in' 			=> $sticky,
			'no_found_rows' 	=> true
		);
		
		$posts = get_posts( $args );

		$ids = [];
		$i=0;
		foreach ( $posts as $post ) : 
			
			$ids[] = $post;
			
		$i++;
		endforeach;
		
		$countids = count($ids);
		if ( $countids < ( $numberids * $multiplier ) ) { 
			$numberids = $countids;
		}
		
		$keys = array_rand($ids,$numberids) ;
		if ( $keys !== 0 ) {
			
			$post_in = [];
			foreach ($keys as $key => $k) :
			$post_in[] = $ids[$k];
			endforeach;
		
			return $post_in;
		
		} else return $ids;

	}

?>
