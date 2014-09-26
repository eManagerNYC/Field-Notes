<?php 
/*
Plugin Name: Field Notes
Plugin URI: http://turneremanager.com
Description: Field notes post type and form
Author: Robert Carmosino and Matthew M. Emma
Version: 1.0
Author URI: http://www.turneremanager.com
*/

add_action('init', 'observation_posttype');
add_action('init', 'register_field_tags_taxonomy');
add_action( 'init', 'build_field_tags_taxonomies' );
add_action('init', 'register_observation_field_group');
add_shortcode( 'observation', 'edit_form_field_tags_shortcode');
add_shortcode( 'observations', 'query_observations');

function books_plugin_notice(){    
   echo '<div class="updated"><p>The field notes plugin requires <a href="https://wordpress.org/plugins/advanced-custom-fields/">Advanced Custom Fields</a> to be installed.</p></div>';
}

function load_datepicker_script() {
  wp_enqueue_script('date-picker', plugins_url('date-picker.js', __FILE__), array('jquery-ui-datepicker'));
}
add_action( 'wp_enqueue_scripts', 'load_datepicker_script' );  

function observation_posttype() {
    // Custom Post Type Labels      
    $labels = array(
      'name'               => _x( 'Observations', 'post type general name' ),
      'singular_name'      => _x( 'Observation', 'post type singular name' ),
      'add_new'            => _x( 'Add new', 'em_observation' ),
      'add_new_item'       => __( 'Add new Observation' ),
      'edit_item'          => __( 'Edit Observation' ),
      'new_item'           => __( 'New Observation' ),
      'all_items'          => __( 'Observations' ),
      'view_item'          => __( 'View Observation' ),
      'search_items'       => __( 'Search Observations' ),
      'not_found'          => __( 'No Observation found' ),
      'not_found_in_trash' => __( 'No Observation found in trash' ),
      'parent_item_colon'  => __( 'Parent Observation' ),
      'menu_name'          => __( 'Observations' )
    );

    // Custom Post Type Capabilities  
    $capabilities = array(
      'edit_post'          => 'edit_post',
      'edit_posts'         => 'edit_posts',
      'edit_others_posts'  => 'edit_others_posts',
      'publish_posts'      => 'publish_posts',
      'read_post'          => 'read_post',
      'read_private_posts' => 'read_private_posts',
      'delete_post'        => 'delete_post'
    );

    // Custom Post Type Taxonomies  
    $taxonomies = array('field-tags');

    // Custom Post Type Supports  
    $supports = array('comments', 'revisions', 'post-formats');

    // Custom Post Type Arguments  
    $args = array(
        'labels'             => $labels,
        'hierarchical'       => true,
        'description'        => 'Observations',
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_nav_menus'  => true,
        'show_in_admin_bar'  => true,
        'exclude_from_search'=> true,
        'query_var'          => true,
        'rewrite'            => false,
        'can_export'         => true,
        'has_archive'        => true,
        'menu_position'      => 25,
        'taxonomies'   => $taxonomies,
        'supports'           => $supports,
/*        'capabilities'   => $capabilities, */
        'capability_type'    => 'post',
        'menu_icon'      => '',
    );
    register_post_type('em_observation', $args);
}

function register_field_tags_taxonomy() {
  $labels = array(
    'name'                       => _x( 'field-tags', 'taxonomy general name', 'emanager'),
    'singular_name'              => _x( 'Field Tag', 'taxonomy singular name', 'emanager'),
    'search_items'               => __( 'Search Field Tag', 'emanager'),
    'popular_items'              => __( 'Popular Field Tag', 'emanager'),
    'all_items'                  => __( 'All Field Tag', 'emanager'),
    'parent_item'                => __( 'Parent Field Tag', 'emanager'),
    'parent_item_colon'          => __( 'Parent: Field Tag', 'emanager'),
    'edit_item'                  => __( 'Edit Field Tag', 'emanager'),
    'view_item'                  => __( 'View Field Tag', 'emanager'),
    'update_item'                => __( 'Update Field Tag', 'emanager'),
    'add_new_item'               => __( 'Add New Field Tag', 'emanager'),
    'new_item_name'              => __( 'New Field Tag Name', 'emanager'),
    'add_or_remove_items'        => __( 'Add or remove Field Tag', 'emanager'),
    'choose_from_most_used'      => __( 'Choose from the most used Field Tag', 'emanager'),
    'separate_items_with_commas' => __( 'Separate Field Tag with commas', 'emanager'),
    'menu_name'                  => __( 'Field Tag', 'emanager'),
  );

  // Taxonomy Capabilities  
  $capabilities = array(
      'edit_terms'   => 'manage_categories',
      'manage_terms' => 'manage_categories',
      'delete_terms' => 'manage_categories',
      'assign_terms' => 'edit_posts'
  );

  // Linked Custom Post Types
  $cpts = array('em_observation');

  // Taxonomy Arguments  
  $args = array(
      'labels'             => $labels,
      'hierarchical'       => true,
      'description'        => '',
      'public'             => true,
      'show_ui'            => true,
      'show_tagcloud'      => true,
      'show_in_nav_menus'  => false,
      'show_admin_column'  => true,
      'query_var'          => true,
      'rewrite'            => true,
/*      'capabilities'   => $capabilities, */
  );
  register_taxonomy( 'field-tags', $cpts, $args );
}

function build_field_tags_taxonomies() { 
  $parent_term = term_exists( 'field-tags', 'field-tags' ); // array is returned if taxonomy is given
  $parent_term_id = $parent_term['term_id']; // get numeric term id
  
  wp_insert_term('Company A','field-tags', array('description'=> '','slug' => 'company-a','parent'=> $parent_term_id));
  wp_insert_term('Company B','field-tags', array('description'=> '','slug' => 'company-b','parent'=> $parent_term_id));
  wp_insert_term('Company C','field-tags', array('description'=> '','slug' => 'company-c','parent'=> $parent_term_id));
}

function register_observation_field_group() {
  if(function_exists("register_field_group")) {
    register_field_group(array (
      'id' => 'acf_field-notes',
      'title' => 'Field Notes',
      'fields' => array (
        array (
          'key' => 'field_53e0e9ad5bdb7',
          'label' => 'Date & Time',
          'name' => 'datetime',
          'type' => 'date_time_picker',
          'required' => 1,
          'show_date' => 'true',
          'date_format' => 'm/d/y',
          'time_format' => 'h:mm tt',
          'show_week_number' => 'false',
          'picker' => 'select',
          'save_as_timestamp' => 'true',
          'get_as_timestamp' => 'true',
        ),
        array (
          'key' => 'field_53e0ea9b5cf22',
          'label' => 'Field Tag',
          'name' => 'field_tag',
          'type' => 'taxonomy',
          'taxonomy' => 'field-tags',
          'field_type' => 'select',
          'allow_null' => 0,
          'load_save_terms' => 0,
          'return_format' => 'object',
          'multiple' => 0,
        ),
        array (
          'key' => 'field_53e0eba250ca7',
          'label' => 'Field Notes',
          'name' => 'field_notes',
          'type' => 'wysiwyg',
          'required' => 1,
          'default_value' => '',
          'toolbar' => 'full',
          'media_upload' => 'yes',
        ),
      ),
      'location' => array (
        array (
          array (
            'param' => 'post_type',
            'operator' => '==',
            'value' => 'em_observation',
            'order_no' => 0,
            'group_no' => 0,
          ),
        ),
      ),
      'options' => array (
        'position' => 'normal',
        'layout' => 'default',
        'hide_on_screen' => array (
        ),
      ),
      'menu_order' => 0,
    ));
  }
}

function edit_form_field_tags_shortcode( $atts ) {
  add_action('the_post', 'render_acf_form');
}

function render_acf_form() {
  $form_html = '';
  $fields = get_fields();

  foreach ($fields as $field_name => $field_obj) {
    $form_html .= '<div id="'.$field_name.'" class="editable-field">'.the_field($field_name).'</div>';
  }

}

function query_observations( $atts ) {
  extract( shortcode_atts( array(
    'y' => date('Y'),
    'm' => date('n'),
    'd' => date('j')
  ), $atts, 'observations' ) );

  if( isset($_POST['datepicker']) )
  {
  	$date_unix = strtotime($_POST['datepicker']);
    $args = array(
      'post_type' => 'em_observation',
      'order' => 'ASC',
      'orderby' => 'datetime',
      'meta_query' => array(
        array(
          'key' => 'datetime',
          'value' => array($date_unix, $date_unix+86399),
          'compare' => 'BETWEEN',
          'type' => 'NUMERIC'
        )
      )
    );
  } else {
  	$date = $y.'-'.$m.'-'.$d;
  	$date_unix = strtotime($date);
  	$date_tom = $y.'-'.$m.'-'.(intval($d)+1);
  	$date_tom_unix = strtotime($date_tom)-1;
    $args = array(
      'post_type' => 'em_observation',
      'order' => 'ASC',
      'orderby' => 'datetime',
      'meta_query' => array(
        array(
          'key' => 'datetime',
          'value' => array($date_unix, $date_tom_unix),
          'compare' => 'BETWEEN',
          'type' => 'NUMERIC'
        )
      )
    );
  }
  $the_query = new WP_Query( $args );

  $html = '<form role="form" action="" method="post"><div class="form-group">
    <label for="datepicker">Date</label> <input type="text" id="datepicker" name="datepicker" value=""/>
    <button type="submit" class="btn btn-default">Submit</button></div></form>

    <p style="text-align: right">'
    .acf_form( array(
    	'post_id' => 'new_post'
		) ).
    '</p>';
    
  if( isset($_POST['datepicker']) ) {
    $date_weather = strtotime($_POST['datepicker']);
    if (date('Ymd') == date('Ymd', $date_weather) && shortcode_exists( 'fw' )) {
      $html .= do_shortcode('[fw city="New_York" state="NY" days="1"]');
    } elseif (date('Ymd') !== date('Ymd', $date_weather) && shortcode_exists( 'hw' )) {
      $html .= do_shortcode('[hw city="New_York" state="NY" d="'.date('d', $date_weather).'" m="'.date('m', $date_weather).'" y="'.date('Y', $date_weather).'"]');
    } else {
      $html .='';
    }
  } else {
    if (shortcode_exists( 'fw' )) {
      $html .= do_shortcode('[fw city="New_York" state="NY" days="1"]');
    }
  }
  if ($the_query->have_posts()) {
    $html .= '<ul class="list-group">';
    while ($the_query->have_posts()) {
      $the_query->the_post();

      $date_pretty = date('h:i a', get_field('datetime'));

      $html .= '<li class="list-group-item">';
      $html .= '<span class="badge pull-right">'.get_field('f_tags')->name.'</span>';
      $html .= '<span class="badge alert-info pull-right">'.get_the_author().'</span>';
      $html .= '<h4 class="list-group-item-heading">'.$date_pretty.'</h4><hr>';
      $html .= '<p class="list-group-item-text">'.get_field('f_notes').'</p>';
      $html .= '</li>';
    }
    $html .= '</ul>';
  }
  wp_reset_postdata();
  return $html;
}