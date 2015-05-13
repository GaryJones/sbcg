<?php
/**
 * Adds sponsors shortcode
 *
 */
 
function _sbcg_sponsors( $atts, $content = '' ) {
  $args = shortcode_atts( array(
    'id' => ''
  ), $atts );
  
  $out = null;
  $posts = array();
  
  // If a id is specified get that sponsor.
  if ( !empty( $args['id'] ) ) :
    $posts[] = get_post( $args['id'] );
  
  // Otherwise loop over all active sponsors.
  else :
    $query = new WP_Query( 
      array(
        'post_type' => '_sbcg_sponsors',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'caller_get_posts'=> 1,
        'order' => 'ASC',
        'orderby' => 'title'
      )
    );
      
    if( $query->have_posts() ) :
      while ($query->have_posts()) : $query->the_post();
        $posts[] = get_post();
      endwhile;
    endif;
    
    wp_reset_query();
    
  endif;  
  
  foreach ($posts as $post) {
    $url = get_post_meta( $post->ID, '_sbcg_sponsors_value_key', true );
    $out .= sprintf( 
        ( empty( $args['id'] ) ) ? "\n<li class=\"sponsor\">%s%s%s</li>" : "\n<div class=\"sponsor-single\">%s%s%s</div>", 
        ( $url ) ? "<a href=\"{$url}\">" : "",
        ( has_post_thumbnail( $post->ID ) ) ? "<img src=\"" . wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' )[0] . "\" alt=\"" . get_the_title( $post->ID ) . "\">" : get_the_title( $post->ID ),
        ( $url ) ? "</a>" : ""
      );
  }
  $out .="\n";
  if ( empty( $args['id'] ) ) { return "<ul class=\"sponsors\">{$out}</ul>"; };
  
  return $out;
}
add_shortcode( 'sponsors', '_sbcg_sponsors' );


?>