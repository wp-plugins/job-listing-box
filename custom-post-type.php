<?php

/*
*Instruction - if you are using this as a template for a plugin, change the class name, the call to create an object from this class *at the bottom, and modify the private variables to meet your needs.
*/

class JobListingCustomPostType{

private $post_type = 'joblisting';
private $post_label = 'Job Listing';
private $prefix = '_job_listing_';
function __construct() {
	
	add_filter( 'cmb_meta_boxes', array(&$this,'metaboxes' ));
	add_action( 'init', array(&$this,'initialize_meta_boxes'), 9999 );
	add_action("init", array(&$this,"create_post_type"));
	add_action( 'init', array(&$this, 'job_listing_register_shortcodes'));
	add_action( 'wp_footer', array(&$this, 'enqueue_styles'));
	register_activation_hook( __FILE__, array(&$this,'activate' ));
}

function create_post_type(){
	register_post_type($this->post_type, array(
	         'label' => _x($this->post_label, $this->post_type.' label'), 
	         'singular_label' => _x('All '.$this->post_label, $this->post_type.' singular label'), 
	         'public' => true, // These will be public
	         'show_ui' => true, // Show the UI in admin panel
	         '_builtin' => false, // This is a custom post type, not a built in post type
	         '_edit_link' => 'post.php?post=%d',
	         'capability_type' => 'page',
	         'hierarchical' => false,
	         'rewrite' => array("slug" => $this->post_type), // This is for the permalinks
	         'query_var' => $this->post_type, // This goes to the WP_Query schema
	         //'supports' =>array('title', 'editor', 'custom-fields', 'revisions', 'excerpt'),
	         'supports' =>array('title', 'author'),
	         'add_new' => _x('Add New', 'Event')
	         ));
}


/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function metaboxes( array $meta_boxes ) {
	
	// Start with an underscore to hide fields from custom fields list
	//$prefix = '_job_listing_';
	

	$meta_boxes[] = array(
		'id'         => 'adsense_metabox',
		'title'      => 'Ad',
		'pages'      => array( $this->post_type ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
			array(
				'name' => 'Job Title',
				'desc' => 'Enter your job title here.',
				'id'   => $this->prefix . 'job_title',
				'type' => 'text',
				//'std'  => 'This is the headline, man!',
			),
			array(
			            'name' => 'Background Color',
			            'desc' => 'Use this to set the backgroud of the listing.',
			            'id'   => $this->prefix . 'background_color',
			            'type' => 'colorpicker',
					'std'  => '#ffffff'
		        ),
		        array(
			            'name' => 'Apply Button Color',
			            'desc' => 'Use this to set the apply button color.',
			            'id'   => $this->prefix . 'apply_button_color',
			            'type' => 'colorpicker',
					'std'  => '#8a8a8a'
		        ),
		        array(
				'name' => 'Apply Button Text',
				'desc' => 'Enter the apply button text here.',
				'id'   => $this->prefix . 'apply_button_text',
				'type' => 'text',
				'std'  => 'Apply Now',
			),
			array(
				'name' => 'Apply Button URL',
				'desc' => 'Enter the apply button url here.',
				'id'   => $this->prefix . 'apply_button_url',
				'type' => 'text',
				'std'  => '',
			),
			array(
				'name'    => 'Job Description',
				//'desc'    => 'field description (optional)',
				'id'      => $this->prefix . 'job_description',
				'type'    => 'wysiwyg',
				'options' => array(	'textarea_rows' => 20, 'wpautop' => true ),
				
			),

		),
	);

	

	// Add other metaboxes as needed

	return $meta_boxes;
}


function job_listing_shortcode($atts){
		extract( shortcode_atts( array(
			'id' => '',
		), $atts ) );
		//$meta_data = get_post_meta( $id, $this->prefix . 'adsense_code', true );
		//$meta_data = get_post_meta($id);
		$dir = plugin_dir_path( __FILE__ );

		$job_title = get_post_meta($id, $this->prefix . 'job_title', true);
		$job_background_color = get_post_meta($id, $this->prefix . 'background_color', true);
		$apply_button_color = get_post_meta($id, $this->prefix . 'apply_button_color', true);
		$apply_button_text = get_post_meta($id, $this->prefix . 'apply_button_text', true);
		$apply_button_url = get_post_meta($id, $this->prefix . 'apply_button_url', true);
		$job_description = get_post_meta($id, $this->prefix . 'job_description', true);
		
		ob_start();
		include $dir.'template/jobListingTemplate.php';
		return ob_get_clean();
}



function job_listing_register_shortcodes(){
		add_shortcode( 'job_listing', array(&$this,'job_listing_shortcode' ));
	}


function activate() {
	// register taxonomies/post types here
	$this->create_post_type();
	global $wp_rewrite;
	$wp_rewrite->flush_rules();
}

function enqueue_styles(){
	wp_register_style( 'job-listing-css', plugin_dir_url(__FILE__).'css/jobListing.css' );
	wp_enqueue_style('job-listing-css');
}


/*
 * Initialize the metabox class.
 */
 
function initialize_meta_boxes() {

	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once 'lib/metabox/init.php';

}


}

new JobListingCustomPostType();


?>