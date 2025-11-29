<?php
/**
 * PopularFX functions and definitions
 *
 * @link https://popularfx.com/docs/
 *
 * @package PopularFX
 */

if ( ! defined( 'POPULARFX_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( 'POPULARFX_VERSION', '1.2.6' );
}

if ( ! defined( 'POPULARFX_PAGELAYER_API' ) ) {
	define( 'POPULARFX_PAGELAYER_API', 'https://api.pagelayer.com/' );
}

if ( ! defined( 'POPULARFX_WWW_URL' ) ) {
	define( 'POPULARFX_WWW_URL', 'https://popularfx.com' );
}

if ( ! defined( 'POPULARFX_PRO_URL' ) ) {
	define( 'POPULARFX_PRO_URL', 'https://popularfx.com/pricing?from=pfx-theme' );
}

if ( ! defined( 'POPULARFX_URL' ) ) {
	define( 'POPULARFX_URL', get_template_directory_uri() );
}

if(!defined('PAGELAYER_VERSION')){
	define('POPULARFX_PAGELAYER_PRO_URL', 'https://pagelayer.com/pricing?from=pfx-theme');
}

if ( ! function_exists( 'popularfx_setup' ) ){
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function popularfx_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on PopularFX, use a find and replace
		 * to change 'popularfx' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'popularfx', get_stylesheet_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in two locations.
		register_nav_menus(
			array(
				'primary' => __( 'Primary Menu', 'popularfx' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
		
		global $pagelayer, $popularfx;
		if(!empty($pagelayer->settings)){
			$pagelayer->template_call_sidebar = 1;
		}
		
		// Show promos
		popularfx_promos();
		
		$template = popularfx_get_template_name();
		if(empty($template)){
		
			// Set up the WordPress core custom background feature.
			add_theme_support('custom-background',
				apply_filters(
					'popularfx_custom_background_args',
					array(
						'default-color' => 'ffffff',
						'default-image' => '',
					)
				)
			);
			
			add_theme_support( 'custom-header',
				apply_filters(
					'popularfx_custom_header_args',
					array(
						'default-image'      => '',
						'default-text-color' => '000000',
						'width'              => 1200,
						'height'             => 250,
						'flex-height'        => true,
						'wp-head-callback'   => 'popularfx_header_style',
					)
				)
			);
			
			add_theme_support(
				'custom-logo',
				array(
					'height'      => 250,
					'width'       => 250,
					'flex-width'  => true,
					'flex-height' => true,
				)
			);
		
		}
		
		// Add woocommerce support
		add_theme_support( 'woocommerce', array(
			'product_grid' => array(
				'min_columns'=> 1,
				'max_columns' => 6,
			),
		) );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}
}
add_action( 'after_setup_theme', 'popularfx_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function popularfx_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'popularfx_content_width', 640 );
}

// To get activated template for parent and child theme
function popularfx_get_template_name(){
	$tmp = get_option('theme_mods_popularfx');
	$mods = !empty($tmp['popularfx_template']) ? $tmp['popularfx_template'] : '';
	return $mods;
}

// Backward compat
function popularfx_copyright(){
	return popularfx_theme_credits();
}

// Show credit of our theme
function popularfx_theme_credits(){
	return '<a href="'.esc_url(POPULARFX_WWW_URL).'">'.__('PopularFX Theme', 'popularfx').'</a>';
}

// Shows the promos
function popularfx_promos(){
	
	if(is_admin() && current_user_can('install_themes')){
		
		//remove_theme_mod('popularfx_getting_started');
		//remove_theme_mod('popularfx_templates_promo');
		//remove_theme_mod('popularfx_show_promo');
		
		// Show the getting started video option
		$seen = get_theme_mod('popularfx_getting_started');
		if(empty($seen)){
			add_action('admin_notices', 'popularfx_getting_started_notice');
		}
	
		// Show the promo
		popularfx_maybe_promo([
			'after' => 1,// In days
			'interval' => 30,// In days
			'pro_url' => POPULARFX_PRO_URL,
			'rating' => 'https://wordpress.org/themes/popularfx/#reviews',
			'twitter' => 'https://twitter.com/PopularFXthemes?status='.rawurlencode('I love #PopularFX Theme by @pagelayer team for my #WordPress site - '.esc_url(home_url())),
			'facebook' => 'https://facebook.com/popularfx',
			'website' => POPULARFX_WWW_URL,
			'image' => POPULARFX_URL.'/images/popularfx-logo.png',
			'name' => 'popularfx_show_promo'
		]);
		
		$template = popularfx_get_template_name();
		if(empty($template)){
		
			// Show the image promo
			popularfx_maybe_promo([
				'after' => 0,// In days
				'interval' => 30,// In days
				'pro_url' => POPULARFX_PRO_URL,
				'rating' => 'https://wordpress.org/themes/popularfx/#reviews',
				'twitter' => 'https://twitter.com/PopularFXthemes?status='.rawurlencode('I love #PopularFX Theme by @pagelayer team for my #WordPress site - '.esc_url(home_url())),
				'facebook' => 'https://facebook.com/popularfx',
				'website' => POPULARFX_WWW_URL,
				'image' => POPULARFX_URL.'/images/popularfx-logo.png',
				'name' => 'popularfx_templates_promo'
			]);
		
		}
		//delete_option('popularfx_templates_promo');
	
	}
	
}
add_action( 'after_switch_theme', 'popularfx_promos', 10 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function popularfx_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'popularfx' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'popularfx' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'popularfx_widgets_init' );

// URL of the PFX templates uploads dir
function popularfx_templates_dir_url(){
	
	$template = popularfx_get_template_name();
	$style = get_template_directory().'/templates/'.$template.'/style.css';
	
	if(file_exists($style)){
		return get_stylesheet_directory_uri().'/templates';
	}
	
	$dir = wp_upload_dir(NULL, false);
	return $dir['baseurl'].'/popularfx-templates';
	
}

// URL of the PFX templates uploads dir
function popularfx_templates_dir($suffix = true){
	
	$template = popularfx_get_template_name();
	$style = get_template_directory().'/templates/'.$template.'/style.css';
	
	if(file_exists($style)){
		return get_template_directory().'/templates'.($suffix ? '/'.$template : '');
	}
	
	$dir = wp_upload_dir(NULL, false);
	return $dir['basedir'].'/popularfx-templates'.($suffix ? '/'.$template : '');
	
}

/**
 * Enqueue scripts and styles.
 */
function popularfx_scripts() {
	
	$template = popularfx_get_template_name();
	if(!empty($template) && defined('PAGELAYER_VERSION')){
		wp_enqueue_style( 'popularfx-style', popularfx_templates_dir_url().'/'.$template.'/style.css', array(), POPULARFX_VERSION );
	}else{
		wp_enqueue_style( 'popularfx-style', get_template_directory_uri().'/style.css', array(), POPULARFX_VERSION );
		wp_style_add_data( 'popularfx-style', 'rtl', 'replace' );
	}
	
	// Enqueue sidebar.css
	wp_enqueue_style( 'popularfx-sidebar', get_template_directory_uri().'/sidebar.css', array(), POPULARFX_VERSION );
	
	// Dashicons needed for WooCommerce and Scroll to Top
	if(class_exists( 'WooCommerce' ) || get_theme_mod('pfx_enable_scrolltop')){
		wp_enqueue_style('dashicons');
	}
	
	// Enqueue WooCommerce CSS
	if(class_exists( 'WooCommerce' )){
		wp_enqueue_style( 'popularfx-woocommerce', get_template_directory_uri().'/woocommerce.css', array(), POPULARFX_VERSION );
		wp_style_add_data( 'popularfx-woocommerce', 'rtl', 'replace' );
	}
	
	wp_enqueue_script( 'popularfx-navigation', get_template_directory_uri().'/js/navigation.js', array('jquery'), POPULARFX_VERSION, true );
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'popularfx_scripts' );

function popularfx_the_title($before = '', $after = '', $echo = true){
	if(is_page()){
		return;
	}
	
	the_title($before, $after, $echo);
}

// Show the templates promo
function popularfx_templates_promo(){
	
	global $popularfx_promo_opts, $popularfx;
	
	if(!empty($GLOBALS['pfx_notices_shown'])){
		return;
	}
	
	$GLOBALS['pfx_notices_shown'] = 1;
	
	$opts = $popularfx_promo_opts['popularfx_templates_promo'];
	
	echo '<style>
#popularfx_templates_promo{
border-left: 1px solid #ccd0d4;
}

.popularfx-templates-promo{
background: #fff;
padding: 5px;
}

.popularfx_promo_button {
background-color: #4CAF50; /* Green */
border: none;
color: white;
padding: 6px 10px;
text-align: center;
text-decoration: none;
display: inline-block;
font-size: 13px;
margin: 4px 2px;
-webkit-transition-duration: 0.4s; /* Safari */
transition-duration: 0.4s;
cursor: pointer;
}
.popularfx_promo_button:focus,
.popularfx_promo_button:hover{
border: none;
color: white;
box-shadow: 0 6px 8px 0 rgba(0,0,0,0.24), 0 9px 25px 0 rgba(0,0,0,0.19);
color: white;
}
.popularfx_promo_buy {
color: white;
padding: 8px 12px;
font-size: 14px;
}
.popularfx_promo_button1 {
color: white;
background-color: #4CAF50;
border:3px solid #4CAF50;
}
.popularfx_promo_button1:hover {
border:3px solid #4CAF50;
}
.popularfx_promo_button2 {
color: white;
background-color: #0085ba;
}
.popularfx_promo_button3 {
color: white;
background-color: #365899;
}
.popularfx_promo_button4 {
color: white;
background-color: rgb(66, 184, 221);
}
.popularfx_promo-close{
float:right;
text-decoration:none;
margin: 5px 10px 0px 0px;
}
.popularfx_promo-close:hover{
color: red;
}

.popularfx_promo-left{
display: inline-block; max-width: 34%; vertical-align: top;
text-align: center;
}

.popularfx_promo-right{
display: inline-block; max-width: 65%; width: 65%; vertical-align: middle;
}

@media all and (max-width:599px){
.popularfx_promo-left, .popularfx_promo-right{
max-width: 100%;
}
}

</style>
<script type="application/javascript">
	jQuery(document).ready(function(){
		jQuery("#popularfx_templates_promo .popularfx_promo-close").click(function(){
			var data;
			jQuery("#popularfx_templates_promo").hide();
			// Save this preference
			jQuery.post("'.esc_url(admin_url('?'.$opts['name'].'=0')).'", data, function(response) {
				//alert(response);
			});
		});
	});
</script>

	<div class="'.(empty($opts['class']) ? 'notice notice-success' : 'popularfx-templates-promo').'" id="popularfx_templates_promo" style="min-height:90px">
		<div class="popularfx_promo-left">
			<img src="'.esc_url(POPULARFX_URL.'/images/templates.png').'" width="85%" />
		</div>
		<div class="popularfx_promo-right">
			<p align="center">
		<a class="popularfx_promo-close" href="javascript:" aria-label="Dismiss this Notice">
			<span class="dashicons dashicons-dismiss"></span> Dismiss
		</a>
				<a href="'.esc_url(POPULARFX_WWW_URL.'/templates').'" style="text-decoration: none; font-size: 15px;">
					'.__('Did you know PopularFX comes with 500+ Templates to design your website. <br>Click to choose your template !', 'popularfx').'<br>
				</a>
				<br>
				'.(empty($opts['pro_url']) || !empty($popularfx['license']) ? '' : '<a class="button button-primary" target="_blank" href="'.esc_url($opts['pro_url']).'">'.__('Buy PopularFX Pro', 'popularfx').'</a>').'
				'.(empty($opts['rating']) ? '' : '<a class="button button-primary" target="_blank" href="'.esc_url($opts['rating']).'">'.__('Rate it 5★\'s', 'popularfx').'</a>').'
				'.(empty($opts['facebook']) ? '' : '<a class="button button-secondary" target="_blank" href="'.esc_url($opts['facebook']).'"><span class="dashicons dashicons-thumbs-up" style="vertical-align: middle;"></span> '.__('Facebook', 'popularfx').'</a>').'
				'.(empty($opts['twitter']) ? '' : '<a class="button button-secondary" target="_blank" href="'.esc_url($opts['twitter']).'"><span class="dashicons dashicons-twitter" style="vertical-align: middle;"></span> '.__('Tweet', 'popularfx').'</a>').'
				'.(empty($opts['website']) ? '' : '<a class="button button-secondary" target="_blank" href="'.esc_url($opts['website']).'">'.__('Visit our website', 'popularfx').'</a>').'
			</p>
		</div>
	</div>';
	
}

function popularfx_getting_started_notice(){
	
	if(!empty($GLOBALS['pfx_notices_shown'])){
		return;
	}
	
	$GLOBALS['pfx_notices_shown'] = 1;
	
	// Are we to disable the promo
	if(isset($_GET['popularfx-getting-started']) && (int)$_GET['popularfx-getting-started'] == 0){
		check_ajax_referer('popularfx_getting_started_nonce', 'popularfx_nonce');
		set_theme_mod('popularfx_getting_started', time());
		die('DONE');
	}
	
	echo '
<script type="application/javascript">
jQuery(document).ready(function(){
	jQuery("#popularfx-getting-started-notice").click(function(e){
		
		if(jQuery(e.target).hasClass("notice-dismiss")){
			var data;
			jQuery("#popularfx-getting-started-notice").hide();
			// Save this preference
			jQuery.post("'.admin_url('?popularfx-getting-started=0&popularfx_nonce='.wp_create_nonce("popularfx_getting_started_nonce")).'", data, function(response) {
				//alert(response);
			});
			return false;
		}
		
	});
});
</script>

	<div id="popularfx-getting-started-notice" class="notice notice-success is-dismissible">
		<p style="font-size: 14px; font-weight: 600">
			<a href="'.esc_url(POPULARFX_WWW_URL).'"><img src="'.esc_url(POPULARFX_URL).'/images/popularfx-logo.png" style="vertical-align: middle; margin:0px 10px" width="24" /></a>'.__('Thanks for choosing PopularFX. We recommend that you see the <a href="https://www.youtube.com/watch?v=DCisrbrmjgI" target="_blank">PopularFX Theme Guide Video</a> to know the basics of PopularFX.', 'popularfx').'
		</p>
	</div>';
	
}

// Show promo notice on dashboard
function popularfx_show_promo($opts = []){
	
	global $popularfx_promo_opts, $popularfx;
	
	if(!empty($GLOBALS['pfx_notices_shown'])){
		return;
	}
	
	$GLOBALS['pfx_notices_shown'] = 1;

	$opts = $popularfx_promo_opts['popularfx_show_promo'];
	
	echo '<style>
#popularfx_promo{
border-left: 1px solid #ccd0d4;
}

.popularfx_promo_button {
background-color: #4CAF50; /* Green */
border: none;
color: white;
padding: 6px 10px;
text-align: center;
text-decoration: none;
display: inline-block;
font-size: 13px;
margin: 4px 2px;
-webkit-transition-duration: 0.4s; /* Safari */
transition-duration: 0.4s;
cursor: pointer;
}
.popularfx_promo_button:focus,
.popularfx_promo_button:hover{
border: none;
color: white;
box-shadow: 0 6px 8px 0 rgba(0,0,0,0.24), 0 9px 25px 0 rgba(0,0,0,0.19);
color: white;
}
.popularfx_promo_buy {
color: white;
padding: 8px 12px;
font-size: 14px;
}
.popularfx_promo_button1 {
color: white;
background-color: #4CAF50;
border:3px solid #4CAF50;
}
.popularfx_promo_button1:hover {
border:3px solid #4CAF50;
}
.popularfx_promo_button2 {
color: white;
background-color: #0085ba;
}
.popularfx_promo_button3 {
color: white;
background-color: #365899;
}
.popularfx_promo_button4 {
color: white;
background-color: rgb(66, 184, 221);
}
.popularfx_promo-close{
float:right;
text-decoration:none;
margin: 5px 10px 0px 0px;
}
.popularfx_promo-close:hover{
color: red;
}

.popularfx_promo-left-1{
display: inline-block; width: 65%; vertical-align: top;
}

.popularfx_promo-right-1{
display: inline-block; width: 35%; vertical-align: top
}

@media all and (max-width:599px){
.popularfx_promo-left, .popularfx_promo-right{
width: 100%;
}
}

</style>
<script type="application/javascript">
	jQuery(document).ready(function(){
		jQuery("#popularfx_promo .popularfx_promo-close").click(function(){
			var data;
			jQuery("#popularfx_promo").hide();
			// Save this preference
			jQuery.post("'.esc_url(admin_url('?'.$opts['name'].'=0')).'", data, function(response) {
				//alert(response);
			});
		});
	});
</script>

	<div class="notice notice-success" id="popularfx_promo" style="min-height:90px">
		<div class="popularfx_promo-left-1">';
	
	if(!empty($opts['image'])){
		echo '<a href="'.esc_url($opts['website']).'"><img src="'.esc_url($opts['image']).'" style="float:left; margin:25px 20px 10px 10px" width="67" /></a>';
	}
	
	echo '
		<p style="font-size:14px; line-height: 1.6">'.sprintf( __('We are glad you are using %1$s to build your website. We really appreciate it ! <br>We would like to request you to give us a 5 Star rating on %2$s. <br>It will greatly boost our motivation !', 'popularfx'), '<a href="'.$opts['website'].'"><b>PopularFX</b></a>', '<a href="'.$opts['rating'].'" target="_blank">WordPress.org</a>').'</p>
		<p>
			'.(empty($opts['pro_url']) || !empty($popularfx['license']) ? '' : '<a class="button button-primary" target="_blank" href="'.esc_url($opts['pro_url']).'">'.__('Buy PopularFX Pro', 'popularfx').'</a>').'
			'.(empty($opts['rating']) ? '' : '<a class="button button-primary" target="_blank" href="'.esc_url($opts['rating']).'">'.__('Rate it 5★\'s', 'popularfx').'</a>').'
			'.(empty($opts['facebook']) ? '' : '<a class="button button-secondary" target="_blank" href="'.esc_url($opts['facebook']).'"><span class="dashicons dashicons-thumbs-up" style="vertical-align: middle;"></span> '.__('Facebook', 'popularfx').'</a>').'
			'.(empty($opts['twitter']) ? '' : '<a class="button button-secondary" target="_blank" href="'.esc_url($opts['twitter']).'"><span class="dashicons dashicons-twitter" style="vertical-align: middle;"></span> '.__('Tweet', 'popularfx').'</a>').'
			'.(empty($opts['website']) ? '' : '<a class="button button-secondary" target="_blank" href="'.esc_url($opts['website']).'">'.__('Visit our website', 'popularfx').'</a>').'
		</p>
		'.(empty($opts['pro_url']) || !empty($popularfx['license']) ? '' : '<p style="font-size:13px">'.sprintf( __('%1$s has many more features like 500+ Website %2$s Templates%3$s, 90+ widgets, 500+ sections, Theme Builder, WooCommerce Builder, Theme Creator and Exporter, Form Builder, Popup Builder, etc. You get a Pagelayer Pro license with the PopularFX Pro version.', 'popularfx'), '<a href="'.esc_url($opts['pro_url']).'"><b>PopularFX Pro</b></a>', '<a href="'.esc_url($opts['website'].'/templates').'">', '</a>').'
		</p>').'
		</div>';
	

	
	echo '<div class="popularfx_promo-right-1">
			<a class="popularfx_promo-close" href="javascript:" aria-label="'.__('Dismiss this Notice', 'popularfx').'">
				<span class="dashicons dashicons-dismiss"></span> '.__('Dismiss', 'popularfx').'
			</a>
			<br>
			<center style="margin:10px;">
				<a href="'.esc_url(POPULARFX_WWW_URL.'/templates').'" style="text-decoration: none; font-size: 15px;"><img src="'.esc_url(POPULARFX_URL.'/images/templates.png').'" width="100%" /><br><br>'.__('Install from 500+ Website Templates', 'popularfx').'</a>
			</center>
		</div>
	</div>';

}

// Are we to show a promo ?
function popularfx_maybe_promo($opts){
	
	global $popularfx_promo_opts;
	
	// There must be an interval
	if(empty($opts['interval'])){
		return false;
	}
	
	// Are we to show a promo	
	$opt_name = empty($opts['name']) ? 'popularfx_show_promo' : $opts['name'];
	$func = empty($opts['name']) ? $opt_name : $opts['name'];
	$promo_time = get_theme_mod($opt_name);
	//echo $opt_name.' - '.$func.' - '.$promo_time.' - '.date('Ymd', $promo_time).'<br>';die();
	
	// First time access
	if(empty($promo_time)){
		set_theme_mod($opt_name, time() + (!empty($opts['after']) ? $opts['after'] * 86400 : 0));
		$promo_time = get_theme_mod($opt_name);
	}
	
	// Is there interval elapsed
	if(time() >= $promo_time){
		$popularfx_promo_opts[$opt_name] = $opts;
		add_action('admin_notices', $func);
	}
	
	// Are we to disable the promo
	if(isset($_GET[$opt_name]) && (int)$_GET[$opt_name] == 0){
		set_theme_mod($opt_name, time() + ($opts['interval'] * 86400));
		die('DONE');
	}
	
}

// Is the sidebar enabled
function popularfx_sidebar(){	

	// If no widgets in sidebar
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		return;
	}

	$enabled = NULL;

	// For page
	if(is_page()){
		$enabled = get_theme_mod('popularfx_sidebar_page', 'default');
	}
	
	$is_product = class_exists('WooCommerce') && is_product() ? true : false;

	// For products none !
	if(is_single() && $is_product){
		$enabled = '0';
	
	// For posts
	}elseif(is_single()){
		$enabled = get_theme_mod('popularfx_sidebar_post', 'right');
	}

	// For Archives
	if(is_archive() || is_home()){
		$enabled = get_theme_mod('popularfx_sidebar_archives', 'right');
	}
	
	// For Woocommerce
	if(class_exists( 'WooCommerce' ) && is_shop()){
		$enabled = get_theme_mod('popularfx_sidebar_woocommerce', 0);
	}
	
	// Load the default
	if($enabled == 'default' || is_front_page()){
		$enabled = get_theme_mod('popularfx_sidebar_default', 0);
	}

	// If its disabled
	if(empty($enabled)){
		return;
	}

	// In live mode of templates dont show this for header and footer
	if(function_exists('pagelayer_is_live') && pagelayer_is_live()){
		$pl_post_type = get_post_meta($GLOBALS['post']->ID, 'pagelayer_template_type', true);
		if(in_array($pl_post_type, ['header', 'footer'])){
			return;
		}
	}
	
	return $enabled;
}

add_action('wp_enqueue_scripts', 'popularfx_sidebar_css', 1000);
function popularfx_sidebar_css(){

	// Sidebar CSS
	$enabled = popularfx_sidebar();

	if(empty($enabled)){
		return;
	}

	$width = (int) get_theme_mod('popularfx_sidebar_width', 20);
	
	$custom_css = '
aside {
width: '.esc_attr($width).'%;
float: '.esc_attr($enabled).';
}

main, .pagelayer-content{
width: '.round(99 - esc_attr($width)).'% !important;
display: inline-block;
float: '.esc_attr($enabled == 'left' ? 'right' : 'left').';
}'.PHP_EOL;

	wp_add_inline_style('popularfx-style', $custom_css);
	
}

if ( ! function_exists( 'popularfx_header_style' ) ) :
	/**
	 * Styles the header image and text displayed on the blog.
	 *
	 * @see popularfx_custom_header_setup().
	 */
	function popularfx_header_style() {
		$header_text_color = get_header_textcolor();

		/*
		 * If no custom options for text are set, let's bail.
		 * get_header_textcolor() options: Any hex value, 'blank' to hide text. Default: add_theme_support( 'custom-header' ).
		 */
		if ( get_theme_support( 'custom-header', 'default-text-color' ) === $header_text_color ) {
			return;
		}

		// If we get this far, we have custom styles. Let's do this.
		?>
		<style type="text/css">
		<?php
		// Has the text been hidden?
		if ( ! display_header_text() ) :
			?>
			.site-title,
			.site-description {
				position: absolute;
				clip: rect(1px, 1px, 1px, 1px);
				}
			<?php
			// If the user has set a custom color for the text use that.
		else :
			?>
			.site-title a,
			.site-description {
				color: #<?php echo esc_attr( $header_text_color ); ?>;
			}
		<?php endif; ?>
		</style>
		<?php
	}
endif;

add_action( 'wp_footer', 'popularfx_footer_setup' );
if( ! function_exists( 'popularfx_footer_setup' )){	
	function popularfx_footer_setup(){
	
		$pfx_enable_scrolltop = get_theme_mod('pfx_enable_scrolltop');
		if(empty($pfx_enable_scrolltop)){
			return;
		}
		
		echo '<a id="pfx-scroll-top" class="pfx-scroll-top"><span class="dashicons dashicons-arrow-up-alt2"></span><span class="screen-reader-text">Scroll to Top</span></a>';	
	}
}

/**
 * TALLERES
 * Funciones para la integración de talleres de la Casa de la Cultura de Tungurahua
 */
 
/**
 * Shortcode para mostrar el listado de talleres cargando el archivo archive-taller.php existente
 */
/**
 * Shortcode optimizado para mostrar talleres
 */
function ccct_mostrar_talleres_optimizado($atts) {
    // Extraer y definir atributos
    $atts = shortcode_atts(array(
        'cantidad' => 9,
        'categorias' => '',
        'orden' => 'date',
        'direccion' => 'DESC'
    ), $atts);
    
    // Asegurarnos que los estilos se cargan
    wp_enqueue_style('archive-taller-styles', get_template_directory_uri() . '/plantillas/taller/archive-taller-styles.css');
    
    // Construir argumentos para la consulta
    $args = array(
        'post_type' => 'taller',
        'posts_per_page' => intval($atts['cantidad']),
        'orderby' => $atts['orden'],
        'order' => $atts['direccion'],
        'no_found_rows' => true, // Optimización: evita contar filas
        'update_post_meta_cache' => false, // Optimización: no actualizar caché
        'update_post_term_cache' => false, // Optimización: no actualizar caché de términos
    );
    
    // Añadir filtro de categorías si se especifican
    if (!empty($atts['categorias'])) {
        $cat_array = explode(',', $atts['categorias']);
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'category',
                'field'    => 'slug',
                'terms'    => $cat_array,
            ),
        );
    }
    
    // Iniciar buffer de salida
    ob_start();
    
    // Realizar la consulta
    $talleres_query = new WP_Query($args);
    
    if ($talleres_query->have_posts()) : ?>
        <div class="talleres-archive-container">
            <div class="talleres-grid">
                <?php while ($talleres_query->have_posts()) : $talleres_query->the_post(); 
                    // Obtener solo los datos necesarios
                    $imagen_url = '';
                    $imagen_alt = get_the_title();
                    
                    $imagenes = get_field('slider_imagenes');
                    if(!empty($imagenes['imagen_1'])) {
                        $imagen_url = $imagenes['imagen_1']['url'];
                        $imagen_alt = $imagenes['imagen_1']['alt'] ?: $imagen_alt;
                    } elseif (has_post_thumbnail()) {
                        $imagen_url = get_the_post_thumbnail_url(null, 'medium_large');
                    } else {
                        $imagen_url = get_template_directory_uri() . '/assets/img/taller-placeholder.jpg';
                    }
                    
                    $instructor = get_field('instructor');
                    $costo = get_field('costo');
                ?>
                    <article class="taller-card">
                        <div class="taller-card-image">
                            <img src="<?php echo esc_url($imagen_url); ?>" alt="<?php echo esc_attr($imagen_alt); ?>">
                        </div>
                        
                        <div class="taller-card-content">
                            <h2 class="taller-card-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            
                            <?php if ($instructor) : ?>
                                <div class="taller-card-instructor">
                                    <span class="instructor-label">Instructor:</span>
                                    <span class="instructor-name"><?php echo esc_html($instructor); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($costo) : ?>
                                <div class="taller-card-costo">
                                    <span class="costo-valor"><?php echo number_format($costo, 2); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <a href="<?php the_permalink(); ?>" class="taller-card-btn">Ver detalles</a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        </div>
    <?php else : ?>
        <div class="talleres-empty">
            <p>No hay talleres disponibles en este momento.</p>
        </div>
    <?php endif;
    
    wp_reset_postdata();
    
    return ob_get_clean();
}
add_shortcode('mostrar_talleres', 'ccct_mostrar_talleres_optimizado');

/**
 * Asegurar que los estilos se cargan cuando se usa el shortcode
 */
function ccct_check_for_talleres_shortcode($posts) {
    if (empty($posts)) return $posts;
    
    $shortcode_found = false;
    
    // Buscar el shortcode en los posts
    foreach ($posts as $post) {
        if (stripos($post->post_content, '[mostrar_talleres') !== false) {
            $shortcode_found = true;
            break;
        }
    }
    
    // Si se encuentra el shortcode, cargar los estilos necesarios
    if ($shortcode_found) {
        add_action('wp_enqueue_scripts', 'ccct_load_talleres_styles', 100);
    }
    
    return $posts;
}
add_action('the_posts', 'ccct_check_for_talleres_shortcode');

/**
 * ========================================
 * NOTA: Las funciones de los Custom Post Types (Artistas, Talleres, Blog, Eventos, Noticias)
 * ahora están en sus respectivos archivos -functions.php
 * ========================================
 */

/**
 * Custom template tags for this theme.
 */
require dirname( __FILE__ ) . '/inc/template-tags.php';

/**
 * Asegurar la carga de Font Awesome 6 para iconos
 */
function ccct_enqueue_fontawesome() {
    // Usar la versión 6.4.2 que incluye todos los iconos que necesitamos
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css', array(), '6.4.2');
}
add_action('wp_enqueue_scripts', 'ccct_enqueue_fontawesome', 9); // Prioridad 9 para cargar antes de otros scripts



/**
 * NOTICIAS
 * Funciones para el sistema de Noticias
 * Casa de la Cultura - WordPress
 */

// Evitar acceso directo
if (!defined('ABSPATH')) {
    exit;
}

/**
 * ==============================================
 * REGISTRO DE POST TYPE PERSONALIZADO: NOTICIA
 * ==============================================
 */
function cc_registrar_post_type_noticia() {
    $labels = array(
        'name'                  => 'Noticias',
        'singular_name'         => 'Noticia',
        'menu_name'             => 'Noticias',
        'add_new'               => 'Agregar Nueva',
        'add_new_item'          => 'Agregar Nueva Noticia',
        'edit_item'             => 'Editar Noticia',
        'new_item'              => 'Nueva Noticia',
        'view_item'             => 'Ver Noticia',
        'view_items'            => 'Ver Noticias',
        'search_items'          => 'Buscar Noticias',
        'not_found'             => 'No se encontraron noticias',
        'not_found_in_trash'    => 'No se encontraron noticias en la papelera',
        'all_items'             => 'Todas las Noticias',
        'archives'              => 'Archivo de Noticias',
        'attributes'            => 'Atributos de Noticia',
        'insert_into_item'      => 'Insertar en noticia',
        'uploaded_to_this_item' => 'Subido a esta noticia',
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-megaphone',
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions'),
        'has_archive'         => true,
        'rewrite'             => array('slug' => 'noticias'),
        'show_in_rest'        => true,
        'rest_base'           => 'noticias',
    );

    register_post_type('noticia', $args);
}
add_action('init', 'cc_registrar_post_type_noticia');

/**
 * ==============================================
 * FUNCIONES HELPER PARA GALERÍA
 * ==============================================
 */

/**
 * Obtener galería de imágenes
 */
function cc_get_galeria_imagenes($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $imagenes = array();
    
    // Recopilar imágenes individuales (imagen_2 hasta imagen_5)
    for ($i = 2; $i <= 5; $i++) {
        $imagen = get_field('noticia_imagen_' . $i, $post_id);
        if ($imagen && is_array($imagen)) {
            $imagenes[] = $imagen;
        }
    }
    
    return $imagenes;
}

/**
 * Mostrar galería de imágenes
 */
function cc_mostrar_galeria_noticia($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $imagenes = cc_get_galeria_imagenes($post_id);
    
    if (!empty($imagenes)) {
        echo '<div class="noticia-galeria-section">';
        echo '<h3>🖼️ Galería de Imágenes</h3>';
        echo '<div class="noticia-galeria-grid">';
        
        foreach ($imagenes as $index => $imagen) {
            echo '<div class="galeria-item">';
            echo '<a href="' . esc_url($imagen['url']) . '" data-lightbox="galeria-' . $post_id . '" data-title="' . esc_attr($imagen['caption']) . '">';
            echo '<img src="' . esc_url($imagen['sizes']['medium'] ?? $imagen['url']) . '" alt="' . esc_attr($imagen['alt']) . '" loading="lazy">';
            echo '</a>';
            if (!empty($imagen['caption'])) {
                echo '<p class="galeria-caption">' . esc_html($imagen['caption']) . '</p>';
            }
            echo '</div>';
        }
        
        echo '</div>';
        echo '</div>';
    }
}

/**
 * ==============================================
 * FUNCIONES HELPER PARA ARCHIVOS ADJUNTOS
 * ==============================================
 */

/**
 * Obtener archivos adjuntos
 */
function cc_get_archivos_adjuntos($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $archivos = array();
    
    // Recopilar hasta 3 archivos
    for ($i = 1; $i <= 3; $i++) {
        $archivo = get_field('noticia_archivo_' . $i, $post_id);
        $titulo = get_field('noticia_archivo_' . $i . '_titulo', $post_id);
        
        if ($archivo && is_array($archivo)) {
            $archivos[] = array(
                'archivo' => $archivo,
                'titulo' => $titulo ? $titulo : 'Archivo ' . $i
            );
        }
    }
    
    return $archivos;
}

/**
 * Mostrar archivos adjuntos
 */
function cc_mostrar_archivos_adjuntos($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $archivos = cc_get_archivos_adjuntos($post_id);
    
    if (!empty($archivos)) {
        echo '<div class="noticia-archivos-adjuntos">';
        echo '<h3>📎 Archivos Descargables</h3>';
        echo '<ul class="lista-archivos">';
        
        foreach ($archivos as $item) {
            $archivo = $item['archivo'];
            $titulo = $item['titulo'];
            
            echo '<li class="archivo-item">';
            echo '<a href="' . esc_url($archivo['url']) . '" target="_blank" class="archivo-link" download>';
            
            // Icono según extensión
            $extension = pathinfo($archivo['filename'], PATHINFO_EXTENSION);
            $icono = '📎';
            $tipo_archivo = 'Archivo';
            
            switch (strtolower($extension)) {
                case 'pdf':
                    $icono = '📄';
                    $tipo_archivo = 'PDF';
                    break;
                case 'doc':
                case 'docx':
                    $icono = '📝';
                    $tipo_archivo = 'Documento Word';
                    break;
                case 'xls':
                case 'xlsx':
                    $icono = '📊';
                    $tipo_archivo = 'Excel';
                    break;
                case 'jpg':
                case 'jpeg':
                case 'png':
                    $icono = '🖼️';
                    $tipo_archivo = 'Imagen';
                    break;
            }
            
            echo '<span class="archivo-icono" aria-label="' . esc_attr($tipo_archivo) . '">' . $icono . '</span>';
            echo '<span class="archivo-info">';
            echo '<strong class="archivo-titulo">' . esc_html($titulo) . '</strong>';
            echo '<span class="archivo-detalles">';
            echo '<span class="archivo-tipo">' . esc_html(strtoupper($extension)) . '</span>';
            echo '<span class="archivo-separador">•</span>';
            echo '<span class="archivo-size">' . size_format($archivo['filesize']) . '</span>';
            echo '</span>';
            echo '</span>';
            
            echo '<svg class="archivo-download-icon" width="20" height="20" viewBox="0 0 16 16" fill="currentColor">';
            echo '<path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>';
            echo '<path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>';
            echo '</svg>';
            
            echo '</a>';
            echo '</li>';
        }
        
        echo '</ul>';
        echo '</div>';
    }
}

/**
 * ==============================================
 * FUNCIONES DE CONSULTA
 * ==============================================
 */

/**
 * Obtener noticias destacadas
 */
function cc_get_noticias_destacadas($limit = 3) {
    $args = array(
        'post_type' => 'noticia',
        'posts_per_page' => $limit,
        'meta_query' => array(
            array(
                'key' => 'noticia_destacada',
                'value' => '1',
                'compare' => '='
            )
        ),
        'orderby' => 'date',
        'order' => 'DESC'
    );
    
    return new WP_Query($args);
}

/**
 * Obtener noticias urgentes
 */
function cc_get_noticias_urgentes($limit = -1) {
    $args = array(
        'post_type' => 'noticia',
        'posts_per_page' => $limit,
        'meta_query' => array(
            array(
                'key' => 'noticia_urgente',
                'value' => '1',
                'compare' => '='
            )
        ),
        'orderby' => 'date',
        'order' => 'DESC'
    );
    
    return new WP_Query($args);
}

/**
 * Obtener noticias por categoría
 */
function cc_get_noticias_por_categoria($categoria, $limit = 10) {
    $args = array(
        'post_type' => 'noticia',
        'posts_per_page' => $limit,
        'meta_query' => array(
            array(
                'key' => 'noticia_categoria',
                'value' => $categoria,
                'compare' => '='
            )
        ),
        'orderby' => 'date',
        'order' => 'DESC'
    );
    
    return new WP_Query($args);
}

/**
 * ==============================================
 * SHORTCODES
 * ==============================================
 */

/**
 * Shortcode para noticias destacadas
 * Uso: [noticias_destacadas limit="3"]
 */
function cc_shortcode_noticias_destacadas($atts) {
    $atts = shortcode_atts(array(
        'limit' => 3,
    ), $atts);
    
    $query = cc_get_noticias_destacadas($atts['limit']);
    
    ob_start();
    
    if ($query->have_posts()) {
        echo '<div class="noticias-destacadas-grid">';
        
        while ($query->have_posts()) {
            $query->the_post();
            
            $imagen = get_field('noticia_imagen_principal');
            $categoria = get_field('noticia_categoria');
            $resumen = get_field('noticia_resumen');
            $urgente = get_field('noticia_urgente');
            
            $clase_urgente = $urgente ? ' noticia-urgente' : '';
            
            echo '<article class="noticia-destacada' . $clase_urgente . '">';
            
            if ($imagen) {
                echo '<div class="noticia-imagen-container">';
                echo '<a href="' . get_permalink() . '">';
                echo '<img src="' . esc_url($imagen['sizes']['medium'] ?? $imagen['url']) . '" alt="' . esc_attr($imagen['alt']) . '" loading="lazy">';
                echo '</a>';
                if ($categoria) {
                    $badge_class = $urgente ? ' badge-urgente' : '';
                    echo '<span class="noticia-badge' . $badge_class . '">' . esc_html($categoria) . '</span>';
                }
                echo '</div>';
            }
            
            echo '<div class="noticia-contenido">';
            echo '<h3 class="noticia-titulo"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
            
            if ($resumen) {
                echo '<p class="noticia-resumen">' . esc_html($resumen) . '</p>';
            }
            
            echo '<div class="noticia-meta">';
            echo '<span class="noticia-fecha">';
            echo '<svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor"><path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/><path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/></svg>';
            echo ' ' . get_the_date('j M, Y');
            echo '</span>';
            echo '<a href="' . get_permalink() . '" class="noticia-leer-mas">Leer más →</a>';
            echo '</div>';
            
            echo '</div>';
            echo '</article>';
        }
        
        echo '</div>';
        wp_reset_postdata();
    } else {
        echo '<p class="no-noticias-mensaje">No hay noticias destacadas en este momento.</p>';
    }
    
    return ob_get_clean();
}
add_shortcode('noticias_destacadas', 'cc_shortcode_noticias_destacadas');

/**
 * Shortcode para noticias por categoría
 * Uso: [noticias_categoria categoria="eventos" limit="5"]
 */
function cc_shortcode_noticias_categoria($atts) {
    $atts = shortcode_atts(array(
        'categoria' => 'general',
        'limit' => 5,
    ), $atts);
    
    $query = cc_get_noticias_por_categoria($atts['categoria'], $atts['limit']);
    
    ob_start();
    
    if ($query->have_posts()) {
        echo '<div class="noticias-lista-categoria">';
        
        while ($query->have_posts()) {
            $query->the_post();
            
            $imagen = get_field('noticia_imagen_principal');
            $resumen = get_field('noticia_resumen');
            
            echo '<article class="noticia-item-categoria">';
            
            if ($imagen) {
                echo '<div class="noticia-thumb">';
                echo '<a href="' . get_permalink() . '">';
                echo '<img src="' . esc_url($imagen['sizes']['thumbnail'] ?? $imagen['url']) . '" alt="' . esc_attr($imagen['alt']) . '" loading="lazy">';
                echo '</a>';
                echo '</div>';
            }
            
            echo '<div class="noticia-info">';
            echo '<h4><a href="' . get_permalink() . '">' . get_the_title() . '</a></h4>';
            
            if ($resumen) {
                echo '<p>' . esc_html(wp_trim_words($resumen, 15)) . '</p>';
            }
            
            echo '<span class="noticia-fecha">';
            echo '<svg width="12" height="12" viewBox="0 0 16 16" fill="currentColor"><path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/><path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/></svg>';
            echo ' ' . get_the_date('j M, Y');
            echo '</span>';
            echo '</div>';
            
            echo '</article>';
        }
        
        echo '</div>';
        wp_reset_postdata();
    } else {
        echo '<p class="no-noticias-mensaje">No hay noticias en esta categoría.</p>';
    }
    
    return ob_get_clean();
}
add_shortcode('noticias_categoria', 'cc_shortcode_noticias_categoria');

/**
 * ==============================================
 * WIDGET DASHBOARD ADMIN
 * ==============================================
 */

/**
 * Widget de noticias urgentes en admin dashboard
 */
function cc_dashboard_widget_noticias_urgentes() {
    $query = cc_get_noticias_urgentes();
    
    if ($query->have_posts()) {
        echo '<div class="noticias-urgentes-dashboard">';
        echo '<p style="color: #d63638; font-weight: bold; font-size: 14px;">';
        echo '⚠️ Hay ' . $query->post_count . ' noticia(s) urgente(s) activa(s)';
        echo '</p>';
        echo '<ul style="list-style: none; padding: 0; margin: 15px 0 0 0;">';
        
        while ($query->have_posts()) {
            $query->the_post();
            echo '<li style="padding: 8px 0; border-bottom: 1px solid #f0f0f1;">';
            echo '<a href="' . get_edit_post_link() . '" style="text-decoration: none;">';
            echo '<strong>' . get_the_title() . '</strong>';
            echo '</a>';
            echo '<br><span style="color: #787c82; font-size: 12px;">Publicado: ' . get_the_date() . '</span>';
            echo '</li>';
        }
        
        echo '</ul>';
        wp_reset_postdata();
        echo '</div>';
    } else {
        echo '<p style="color: #46b450;">✅ No hay noticias urgentes en este momento.</p>';
        echo '<p style="color: #787c82; font-size: 13px;">Las noticias marcadas como urgentes aparecerán aquí.</p>';
    }
}

function cc_agregar_dashboard_widget() {
    wp_add_dashboard_widget(
        'cc_noticias_urgentes',
        '🔔 Noticias Urgentes - Casa de la Cultura',
        'cc_dashboard_widget_noticias_urgentes'
    );
}
add_action('wp_dashboard_setup', 'cc_agregar_dashboard_widget');

/**
 * ==============================================
 * FLUSH REWRITE RULES (solo en activación)
 * ==============================================
 */
function cc_noticias_flush_rewrites() {
    cc_registrar_post_type_noticia();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'cc_noticias_flush_rewrites');

/**
 * Encolar estilos y scripts de noticias
 */
function cc_enqueue_noticias_assets() {
    // Solo cargar en páginas de noticias
    if (is_singular('noticia') || is_post_type_archive('noticia')) {
        
        // CSS
        wp_enqueue_style(
            'cc-noticias-styles',
            get_template_directory_uri() . '/plantillas/agenda/noticia/noticias-styles.css',
            array(),
            '1.0.0'
        );
        
        // JavaScript
        wp_enqueue_script(
            'cc-noticias-filtros',
            get_template_directory_uri() . '/plantillas/agenda/noticia/noticias-filtros.js',
            array(),
            '1.0.0',
            true
        );
        
        // Lightbox para galería (opcional - puedes usar cualquier librería)
        wp_enqueue_script(
            'lightbox',
            'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js',
            array('jquery'),
            '2.11.3',
            true
        );
        
        wp_enqueue_style(
            'lightbox-css',
            'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css',
            array(),
            '2.11.3'
        );
    }
}
add_action('wp_enqueue_scripts', 'cc_enqueue_noticias_assets');

/**
 * ========================================
 * SISTEMA DE EVENTOS CULTURALES - ACTUALIZADO
 * Casa de la Cultura
 * ========================================
 */

// Evitar acceso directo
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Registrar Custom Post Type: Evento
 */
function cc_registrar_post_type_evento() {
    $labels = array(
        'name'                  => 'Eventos Culturales',
        'singular_name'         => 'Evento',
        'menu_name'             => 'Eventos',
        'add_new'               => 'Agregar Nuevo',
        'add_new_item'          => 'Agregar Nuevo Evento',
        'edit_item'             => 'Editar Evento',
        'new_item'              => 'Nuevo Evento',
        'view_item'             => 'Ver Evento',
        'view_items'            => 'Ver Eventos',
        'search_items'          => 'Buscar Eventos',
        'not_found'             => 'No se encontraron eventos',
        'not_found_in_trash'    => 'No se encontraron eventos en la papelera',
        'all_items'             => 'Todos los Eventos',
        'archives'              => 'Archivo de Eventos',
        'attributes'            => 'Atributos de Evento',
        'insert_into_item'      => 'Insertar en evento',
        'uploaded_to_this_item' => 'Subido a este evento',
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => 6,
        'menu_icon'           => 'dashicons-tickets-alt',
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions'),
        'has_archive'         => true,
        'rewrite'             => array('slug' => 'eventos'),
        'show_in_rest'        => true,
    );

    register_post_type('evento', $args);
}
add_action('init', 'cc_registrar_post_type_evento');

/**
 * Flush rewrite rules en activación
 */
function cc_eventos_flush_rewrites() {
    cc_registrar_post_type_evento();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'cc_eventos_flush_rewrites');

/**
 * ========================================
 * FUNCIONES HELPER PARA EVENTOS
 * ========================================
 */

/**
 * Obtener estado del evento con estilo
 */
function cc_get_estado_evento($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $estado = get_field('evento_estado', $post_id);
    
    $estados = array(
        'proximo' => array(
            'label' => 'Próximamente',
            'color' => '#3498db',
            'icon' => '⏳'
        ),
        'inscripcion_abierta' => array(
            'label' => 'Inscripción Abierta',
            'color' => '#27ae60',
            'icon' => '✅'
        ),
        'cupos_limitados' => array(
            'label' => 'Cupos Limitados',
            'color' => '#f39c12',
            'icon' => '⚠️'
        ),
        'agotado' => array(
            'label' => 'Entradas Agotadas',
            'color' => '#e74c3c',
            'icon' => '🚫'
        ),
        'en_curso' => array(
            'label' => 'En Curso',
            'color' => '#9b59b6',
            'icon' => '▶️'
        ),
        'finalizado' => array(
            'label' => 'Finalizado',
            'color' => '#95a5a6',
            'icon' => '✓'
        ),
        'cancelado' => array(
            'label' => 'Cancelado',
            'color' => '#c0392b',
            'icon' => '✖️'
        )
    );
    
    return $estados[$estado] ?? $estados['proximo'];
}

/**
 * Calcular porcentaje de ocupación
 */
function cc_calcular_ocupacion($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $capacidad = get_field('evento_capacidad_total', $post_id);
    $disponibles = get_field('evento_cupos_disponibles', $post_id);
    
    if (!$capacidad || $capacidad == 0) {
        return null;
    }
    
    $ocupados = $capacidad - $disponibles;
    $porcentaje = ($ocupados / $capacidad) * 100;
    
    return array(
        'capacidad' => $capacidad,
        'disponibles' => $disponibles,
        'ocupados' => $ocupados,
        'porcentaje' => round($porcentaje, 1)
    );
}

/**
 * Verificar si el evento ya pasó
 */
function cc_evento_ha_pasado($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $fecha_inicio = get_field('evento_fecha_inicio', $post_id);
    
    if (!$fecha_inicio) {
        return false;
    }
    
    $ahora = current_time('timestamp');
    $fecha_evento = strtotime($fecha_inicio);
    
    return $fecha_evento < $ahora;
}

/**
 * Obtener fecha formateada en español
 */
function cc_get_fecha_evento_formateada($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $fecha_inicio = get_field('evento_fecha_inicio', $post_id);
    $fecha_fin = get_field('evento_fecha_fin', $post_id);
    
    if (!$fecha_inicio) {
        return '';
    }
    
    $meses = array(
        'January' => 'Enero',
        'February' => 'Febrero',
        'March' => 'Marzo',
        'April' => 'Abril',
        'May' => 'Mayo',
        'June' => 'Junio',
        'July' => 'Julio',
        'August' => 'Agosto',
        'September' => 'Septiembre',
        'October' => 'Octubre',
        'November' => 'Noviembre',
        'December' => 'Diciembre'
    );
    
    $dias = array(
        'Monday' => 'Lunes',
        'Tuesday' => 'Martes',
        'Wednesday' => 'Miércoles',
        'Thursday' => 'Jueves',
        'Friday' => 'Viernes',
        'Saturday' => 'Sábado',
        'Sunday' => 'Domingo'
    );
    
    $timestamp_inicio = strtotime($fecha_inicio);
    
    $dia_semana = date('l', $timestamp_inicio);
    $dia = date('j', $timestamp_inicio);
    $mes = date('F', $timestamp_inicio);
    $anio = date('Y', $timestamp_inicio);
    $hora = date('H:i', $timestamp_inicio);
    
    $fecha_formateada = $dias[$dia_semana] . ', ' . $dia . ' de ' . $meses[$mes] . ' de ' . $anio . ' - ' . $hora;
    
    if ($fecha_fin) {
        $timestamp_fin = strtotime($fecha_fin);
        $hora_fin = date('H:i', $timestamp_fin);
        
        // Si es el mismo día
        if (date('Y-m-d', $timestamp_inicio) === date('Y-m-d', $timestamp_fin)) {
            $fecha_formateada .= ' a ' . $hora_fin;
        } else {
            $dia_fin = date('j', $timestamp_fin);
            $mes_fin = date('F', $timestamp_fin);
            $anio_fin = date('Y', $timestamp_fin);
            $fecha_formateada .= ' hasta ' . $dia_fin . ' de ' . $meses[$mes_fin] . ' de ' . $anio_fin . ' - ' . $hora_fin;
        }
    }
    
    return $fecha_formateada;
}

/**
 * Obtener precio formateado - ACTUALIZADO
 */
function cc_get_precio_evento($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $es_gratuito = get_field('evento_es_gratuito', $post_id);
    
    if ($es_gratuito) {
        return array(
            'gratuito' => true,
            'texto' => 'Entrada Gratuita',
            'icono' => '🎁'
        );
    }
    
    $precio = get_field('evento_precio', $post_id);
    $precios_multiples = get_field('evento_precios_multiples', $post_id);
    
    if ($precios_multiples) {
        return array(
            'gratuito' => false,
            'multiple' => true,
            'texto' => 'Desde $' . $precio,
            'detalles' => $precios_multiples
        );
    }
    
    return array(
        'gratuito' => false,
        'multiple' => false,
        'texto' => '$' . $precio,
        'valor' => $precio
    );
}

/**
 * Obtener slider de imágenes - ACTUALIZADO (solo imágenes 4 y 5)
 */
function cc_get_slider_evento($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $imagenes = array();
    
    // Imagen principal primero
    $imagen_principal = get_field('evento_imagen_principal', $post_id);
    if ($imagen_principal && is_array($imagen_principal)) {
        $imagenes[] = $imagen_principal;
    }
    
    // Banner si existe
    $imagen_banner = get_field('evento_imagen_banner', $post_id);
    if ($imagen_banner && is_array($imagen_banner)) {
        $imagenes[] = $imagen_banner;
    }
    
    // Imágenes adicionales 4 y 5
    for ($i = 4; $i <= 5; $i++) {
        $imagen = get_field('evento_imagen_' . $i, $post_id);
        if ($imagen && is_array($imagen)) {
            $imagenes[] = $imagen;
        }
    }
    
    return $imagenes;
}

/**
 * Obtener requisitos formateados - ACTUALIZADO
 */
function cc_get_requisitos_evento($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $requisitos = get_field('evento_requisitos', $post_id);
    
    if (!$requisitos || !is_array($requisitos)) {
        return null;
    }
    
    $labels = array(
        'cedula' => 'Cédula de identidad',
        'amabilidad' => 'Amabilidad',
        'puntualidad' => 'Puntualidad'
    );
    
    $lista = array();
    foreach ($requisitos as $req) {
        $lista[] = $labels[$req] ?? $req;
    }
    
    return $lista;
}

/**
 * Obtener lo que incluye el evento - ACTUALIZADO
 */
function cc_get_incluye_evento($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $incluye = get_field('evento_incluye', $post_id);
    
    if (!$incluye || !is_array($incluye)) {
        return null;
    }
    
    $labels = array(
        'refrigerio' => 'Refrigerio',
        'material_didactico' => 'Material didáctico',
        'certificado' => 'Certificado de asistencia'
    );
    
    $lista = array();
    foreach ($incluye as $item) {
        $lista[] = $labels[$item] ?? $item;
    }
    
    return $lista;
}

/**
 * ========================================
 * QUERIES Y LISTADOS
 * ========================================
 */

/**
 * Obtener eventos próximos
 */
function cc_get_eventos_proximos($limit = 6) {
    $ahora = current_time('Y-m-d H:i:s');
    
    $args = array(
        'post_type' => 'evento',
        'posts_per_page' => $limit,
        'meta_query' => array(
            array(
                'key' => 'evento_fecha_inicio',
                'value' => $ahora,
                'compare' => '>=',
                'type' => 'DATETIME'
            )
        ),
        'meta_key' => 'evento_fecha_inicio',
        'orderby' => 'meta_value',
        'order' => 'ASC'
    );
    
    return new WP_Query($args);
}

/**
 * Obtener eventos destacados
 */
function cc_get_eventos_destacados($limit = 3) {
    $ahora = current_time('Y-m-d H:i:s');
    
    $args = array(
        'post_type' => 'evento',
        'posts_per_page' => $limit,
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => 'evento_destacado',
                'value' => '1',
                'compare' => '='
            ),
            array(
                'key' => 'evento_fecha_inicio',
                'value' => $ahora,
                'compare' => '>=',
                'type' => 'DATETIME'
            )
        ),
        'meta_key' => 'evento_fecha_inicio',
        'orderby' => 'meta_value',
        'order' => 'ASC'
    );
    
    return new WP_Query($args);
}

/**
 * Obtener eventos por tipo
 */
function cc_get_eventos_por_tipo($tipo, $limit = 10) {
    $ahora = current_time('Y-m-d H:i:s');
    
    $args = array(
        'post_type' => 'evento',
        'posts_per_page' => $limit,
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => 'evento_tipo',
                'value' => $tipo,
                'compare' => '='
            ),
            array(
                'key' => 'evento_fecha_inicio',
                'value' => $ahora,
                'compare' => '>=',
                'type' => 'DATETIME'
            )
        ),
        'meta_key' => 'evento_fecha_inicio',
        'orderby' => 'meta_value',
        'order' => 'ASC'
    );
    
    return new WP_Query($args);
}

/**
 * Obtener eventos pasados
 */
function cc_get_eventos_pasados($limit = 10) {
    $ahora = current_time('Y-m-d H:i:s');
    
    $args = array(
        'post_type' => 'evento',
        'posts_per_page' => $limit,
        'meta_query' => array(
            array(
                'key' => 'evento_fecha_inicio',
                'value' => $ahora,
                'compare' => '<',
                'type' => 'DATETIME'
            )
        ),
        'meta_key' => 'evento_fecha_inicio',
        'orderby' => 'meta_value',
        'order' => 'DESC'
    );
    
    return new WP_Query($args);
}

/**
 * ========================================
 * SHORTCODES
 * ========================================
 */

/**
 * Shortcode: Eventos destacados
 * Uso: [eventos_destacados limit="3"]
 */
function cc_shortcode_eventos_destacados($atts) {
    $atts = shortcode_atts(array(
        'limit' => 3,
    ), $atts);
    
    $query = cc_get_eventos_destacados($atts['limit']);
    
    ob_start();
    
    if ($query->have_posts()) {
        echo '<div class="eventos-destacados-slider">';
        
        while ($query->have_posts()) {
            $query->the_post();
            
            $imagen = get_field('evento_imagen_principal');
            $fecha = cc_get_fecha_evento_formateada();
            $precio = cc_get_precio_evento();
            $estado = cc_get_estado_evento();
            
            echo '<div class="evento-destacado-item">';
            
            if ($imagen) {
                echo '<div class="evento-destacado-image">';
                echo '<img src="' . esc_url($imagen['url']) . '" alt="' . esc_attr($imagen['alt']) . '">';
                echo '<div class="evento-overlay"></div>';
                echo '</div>';
            }
            
            echo '<div class="evento-destacado-content">';
            echo '<span class="evento-estado" style="background: ' . $estado['color'] . ';">';
            echo $estado['icon'] . ' ' . $estado['label'];
            echo '</span>';
            echo '<h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
            echo '<p class="evento-fecha">📅 ' . $fecha . '</p>';
            echo '<p class="evento-precio">' . $precio['texto'] . '</p>';
            echo '<a href="' . get_permalink() . '" class="btn-ver-evento">Ver Detalles</a>';
            echo '</div>';
            
            echo '</div>';
        }
        
        echo '</div>';
        wp_reset_postdata();
    }
    
    return ob_get_clean();
}
add_shortcode('eventos_destacados', 'cc_shortcode_eventos_destacados');

/**
 * Shortcode: Próximos eventos
 * Uso: [proximos_eventos limit="6"]
 */
function cc_shortcode_proximos_eventos($atts) {
    $atts = shortcode_atts(array(
        'limit' => 6,
    ), $atts);
    
    $query = cc_get_eventos_proximos($atts['limit']);
    
    ob_start();
    
    if ($query->have_posts()) {
        echo '<div class="proximos-eventos-grid">';
        
        while ($query->have_posts()) {
            $query->the_post();
            
            $imagen = get_field('evento_imagen_principal');
            $tipo = get_field('evento_tipo');
            $fecha = get_field('evento_fecha_inicio');
            $precio = cc_get_precio_evento();
            
            echo '<article class="evento-card-mini">';
            
            if ($imagen) {
                echo '<div class="evento-card-image">';
                echo '<a href="' . get_permalink() . '">';
                echo '<img src="' . esc_url($imagen['sizes']['medium'] ?? $imagen['url']) . '" alt="' . esc_attr($imagen['alt']) . '">';
                echo '</a>';
                echo '</div>';
            }
            
            echo '<div class="evento-card-content">';
            echo '<h4><a href="' . get_permalink() . '">' . get_the_title() . '</a></h4>';
            echo '<p class="evento-fecha-mini">📅 ' . date('j M, H:i', strtotime($fecha)) . '</p>';
            echo '<p class="evento-precio-mini">' . $precio['texto'] . '</p>';
            echo '</div>';
            
            echo '</article>';
        }
        
        echo '</div>';
        wp_reset_postdata();
    } else {
        echo '<p class="no-eventos">No hay eventos próximos programados.</p>';
    }
    
    return ob_get_clean();
}
add_shortcode('proximos_eventos', 'cc_shortcode_proximos_eventos');

/**
 * ========================================
 * WIDGET DASHBOARD ADMIN
 * ========================================
 */

/**
 * Dashboard widget: Resumen de eventos
 */
function cc_dashboard_widget_eventos() {
    $proximos = cc_get_eventos_proximos(5);
    $total_proximos = $proximos->found_posts;
    
    echo '<div class="eventos-dashboard">';
    
    echo '<div class="evento-stats">';
    echo '<div class="stat-box">';
    echo '<span class="stat-number">' . $total_proximos . '</span>';
    echo '<span class="stat-label">Eventos Próximos</span>';
    echo '</div>';
    echo '</div>';
    
    if ($proximos->have_posts()) {
        echo '<h4 style="margin-top: 20px; margin-bottom: 10px;">📅 Próximos Eventos</h4>';
        echo '<ul style="list-style: none; padding: 0; margin: 0;">';
        
        while ($proximos->have_posts()) {
            $proximos->the_post();
            $fecha = get_field('evento_fecha_inicio');
            $estado = cc_get_estado_evento();
            
            echo '<li style="padding: 10px 0; border-bottom: 1px solid #f0f0f1;">';
            echo '<strong><a href="' . get_edit_post_link() . '">' . get_the_title() . '</a></strong>';
            echo '<br><span style="font-size: 12px; color: #666;">📅 ' . date('j M Y, H:i', strtotime($fecha)) . '</span>';
            echo '<br><span style="display: inline-block; padding: 3px 8px; background: ' . $estado['color'] . '; color: #fff; border-radius: 10px; font-size: 11px; margin-top: 5px;">';
            echo $estado['icon'] . ' ' . $estado['label'];
            echo '</span>';
            echo '</li>';
        }
        
        echo '</ul>';
        wp_reset_postdata();
    }
    
    echo '<p style="margin-top: 15px;">';
    echo '<a href="' . admin_url('edit.php?post_type=evento') . '" class="button button-primary">Ver Todos los Eventos</a>';
    echo '</p>';
    
    echo '</div>';
}

function cc_agregar_dashboard_widget_eventos() {
    wp_add_dashboard_widget(
        'cc_eventos_dashboard',
        '🎭 Eventos Culturales - Casa de la Cultura',
        'cc_dashboard_widget_eventos'
    );
}
add_action('wp_dashboard_setup', 'cc_agregar_dashboard_widget_eventos');

/**
 * ========================================
 * ENQUEUE STYLES & SCRIPTS
 * ========================================
 */

/**
 * Cargar estilos y scripts para eventos
 */
function cc_enqueue_eventos_assets() {
    if (is_singular('evento') || is_post_type_archive('evento')) {
        
        // CSS
        wp_enqueue_style(
            'cc-eventos-styles',
            get_template_directory_uri() . '/plantillas/agenda/evento/eventos-styles.css',
            array(),
            '1.0.1'
        );
        
        // JavaScript
        wp_enqueue_script(
            'cc-eventos-scripts',
            get_template_directory_uri() . '/plantillas/agenda/evento/eventos-scripts.js',
            array('jquery'),
            '1.0.1',
            true
        );
        
        // Pasar datos PHP a JavaScript
        wp_localize_script('cc-eventos-scripts', 'eventosData', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('eventos_nonce')
        ));
    }
}
add_action('wp_enqueue_scripts', 'cc_enqueue_eventos_assets');

/**
 * ========================================
 * COLUMNAS PERSONALIZADAS EN ADMIN
 * ========================================
 */

/**
 * Agregar columnas personalizadas
 */
function cc_eventos_columnas_admin($columns) {
    $new_columns = array();
    
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        
        if ($key === 'title') {
            $new_columns['evento_fecha'] = '📅 Fecha';
            $new_columns['evento_tipo'] = 'Tipo';
            $new_columns['evento_estado'] = 'Estado';
            $new_columns['evento_cupos'] = 'Cupos';
        }
    }
    
    return $new_columns;
}
add_filter('manage_evento_posts_columns', 'cc_eventos_columnas_admin');

/**
 * Rellenar columnas personalizadas
 */
function cc_eventos_columnas_contenido($column, $post_id) {
    switch ($column) {
        case 'evento_fecha':
            $fecha = get_field('evento_fecha_inicio', $post_id);
            if ($fecha) {
                echo date('j M Y, H:i', strtotime($fecha));
                
                if (cc_evento_ha_pasado($post_id)) {
                    echo '<br><span style="color: #999;">✓ Finalizado</span>';
                }
            } else {
                echo '—';
            }
            break;
            
        case 'evento_tipo':
            $tipo = get_field('evento_tipo', $post_id);
            $tipos = array(
                'teatro' => 'Teatro',
                'musica' => 'Música',
                'danza' => 'Danza',
                'exposicion' => 'Exposición',
                'taller' => 'Taller',
                'conferencia' => 'Conferencia',
                'conversatorio' => 'Conversatorio',
                'cine' => 'Cine',
                'literario' => 'Literario',
                'concurso' => 'Concurso',
                'festival' => 'Festival',
                'otro' => 'Otro'
            );
            echo $tipos[$tipo] ?? $tipo;
            break;
            
        case 'evento_estado':
            $estado = cc_get_estado_evento($post_id);
            echo '<span style="display: inline-block; padding: 5px 10px; background: ' . $estado['color'] . '; color: #fff; border-radius: 12px; font-size: 11px; font-weight: 600;">';
            echo $estado['icon'] . ' ' . $estado['label'];
            echo '</span>';
            break;
            
        case 'evento_cupos':
            $ocupacion = cc_calcular_ocupacion($post_id);
            if ($ocupacion) {
                echo '<strong>' . $ocupacion['disponibles'] . '</strong> / ' . $ocupacion['capacidad'];
                echo '<br><small>' . $ocupacion['porcentaje'] . '% ocupado</small>';
            } else {
                echo '—';
            }
            break;
    }
}
add_action('manage_evento_posts_custom_column', 'cc_eventos_columnas_contenido', 10, 2);

/**
 * Hacer columnas ordenables
 */
function cc_eventos_columnas_ordenables($columns) {
    $columns['evento_fecha'] = 'evento_fecha_inicio';
    $columns['evento_tipo'] = 'evento_tipo';
    return $columns;
}
add_filter('manage_edit-evento_sortable_columns', 'cc_eventos_columnas_ordenables');

/**
 * ========================================
 * CUSTOM POST TYPE: BLOG INSTITUCIONAL
 * Casa de la Cultura
 * ========================================
 */

// Evitar acceso directo
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Registrar Custom Post Type: Blog
 */
function cc_registrar_post_type_blog() {
    $labels = array(
        'name' => 'Blog Institucional',
        'singular_name' => 'Entrada de Blog',
        'menu_name' => 'Blog',
        'name_admin_bar' => 'Entrada de Blog',
        'add_new' => 'Agregar Nueva',
        'add_new_item' => 'Agregar Nueva Entrada',
        'new_item' => 'Nueva Entrada',
        'edit_item' => 'Editar Entrada',
        'view_item' => 'Ver Entrada',
        'view_items' => 'Ver Entradas',
        'all_items' => 'Todas las Entradas',
        'search_items' => 'Buscar Entradas',
        'parent_item_colon' => 'Entrada Padre:',
        'not_found' => 'No se encontraron entradas',
        'not_found_in_trash' => 'No se encontraron entradas en la papelera',
        'archives' => 'Archivo de Blog',
        'attributes' => 'Atributos de Entrada',
        'insert_into_item' => 'Insertar en entrada',
        'uploaded_to_this_item' => 'Subido a esta entrada',
        'featured_image' => 'Imagen destacada',
        'set_featured_image' => 'Establecer imagen destacada',
        'remove_featured_image' => 'Remover imagen destacada',
        'use_featured_image' => 'Usar como imagen destacada',
        'filter_items_list' => 'Filtrar lista de entradas',
        'items_list_navigation' => 'Navegación de lista de entradas',
        'items_list' => 'Lista de entradas',
    );

    $args = array(
        'labels' => $labels,
        'description' => 'Blog institucional de la Casa de la Cultura',
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-admin-post',
        'capability_type' => 'post',
        'hierarchical' => false,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'comments', 'revisions', 'author'),
        'has_archive' => true,
        'rewrite' => array(
            'slug' => 'blog',
            'with_front' => false,
            'feeds' => true,
            'pages' => true
        ),
        'show_in_rest' => true,
        'rest_base' => 'blog',
        'rest_controller_class' => 'WP_REST_Posts_Controller',
    );

    register_post_type('blog', $args);
}
add_action('init', 'cc_registrar_post_type_blog');

/**
 * Registrar Taxonomías para Blog
 */
function cc_registrar_taxonomias_blog() {
    
    // Taxonomía: Categorías del Blog
    $labels_categoria = array(
        'name' => 'Categorías del Blog',
        'singular_name' => 'Categoría',
        'search_items' => 'Buscar Categorías',
        'all_items' => 'Todas las Categorías',
        'parent_item' => 'Categoría Padre',
        'parent_item_colon' => 'Categoría Padre:',
        'edit_item' => 'Editar Categoría',
        'update_item' => 'Actualizar Categoría',
        'add_new_item' => 'Agregar Nueva Categoría',
        'new_item_name' => 'Nombre de Nueva Categoría',
        'menu_name' => 'Categorías',
    );

    $args_categoria = array(
        'hierarchical' => true,
        'labels' => $labels_categoria,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'blog-categoria'),
        'show_in_rest' => true,
    );

    register_taxonomy('blog_categoria_tax', array('blog'), $args_categoria);

    // Taxonomía: Etiquetas del Blog
    $labels_etiqueta = array(
        'name' => 'Etiquetas del Blog',
        'singular_name' => 'Etiqueta',
        'search_items' => 'Buscar Etiquetas',
        'popular_items' => 'Etiquetas Populares',
        'all_items' => 'Todas las Etiquetas',
        'edit_item' => 'Editar Etiqueta',
        'update_item' => 'Actualizar Etiqueta',
        'add_new_item' => 'Agregar Nueva Etiqueta',
        'new_item_name' => 'Nombre de Nueva Etiqueta',
        'menu_name' => 'Etiquetas',
    );

    $args_etiqueta = array(
        'hierarchical' => false,
        'labels' => $labels_etiqueta,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'blog-etiqueta'),
        'show_in_rest' => true,
    );

    register_taxonomy('blog_etiqueta_tax', array('blog'), $args_etiqueta);
}
add_action('init', 'cc_registrar_taxonomias_blog');

/**
 * Flush rewrite rules en activación
 */
function cc_blog_flush_rewrites() {
    cc_registrar_post_type_blog();
    cc_registrar_taxonomias_blog();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'cc_blog_flush_rewrites');

/**
 * ========================================
 * FUNCIONES HELPER PARA BLOG
 * ========================================
 */

/**
 * Obtener información de la categoría del blog
 */
function cc_get_blog_categoria_info($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $categoria = get_field('blog_categoria', $post_id);
    
    $categorias = array(
        'mensaje_directora' => array('label' => 'Mensaje de la Directora', 'icon' => 'fa-message', 'color' => '#8e44ad'),
        'conmemoracion' => array('label' => 'Conmemoración', 'icon' => 'fa-calendar-star', 'color' => '#e74c3c'),
        'rendicion_cuentas' => array('label' => 'Rendición de Cuentas', 'icon' => 'fa-chart-line', 'color' => '#16a085'),
        'logros' => array('label' => 'Logros y Reconocimientos', 'icon' => 'fa-trophy', 'color' => '#f39c12'),
        'proyectos' => array('label' => 'Proyectos en Curso', 'icon' => 'fa-lightbulb', 'color' => '#3498db'),
        'reflexion' => array('label' => 'Reflexión Cultural', 'icon' => 'fa-brain', 'color' => '#9b59b6'),
        'opinion' => array('label' => 'Opinión y Análisis', 'icon' => 'fa-comments', 'color' => '#34495e'),
        'general' => array('label' => 'General', 'icon' => 'fa-pen-to-square', 'color' => '#95a5a6')
    );
    
    return $categorias[$categoria] ?? $categorias['general'];
}

/**
 * Obtener galería de imágenes del blog
 */
function cc_get_blog_galeria($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $imagenes = array();
    
    // Imagen destacada primero
    $imagen_destacada = get_field('blog_imagen_destacada', $post_id);
    if ($imagen_destacada && is_array($imagen_destacada)) {
        $imagenes[] = $imagen_destacada;
    }
    
    // Imágenes adicionales (2-5)
    for ($i = 2; $i <= 5; $i++) {
        $imagen = get_field('blog_imagen_' . $i, $post_id);
        if ($imagen && is_array($imagen)) {
            $imagenes[] = $imagen;
        }
    }
    
    return $imagenes;
}

/**
 * Obtener archivos adjuntos de rendición de cuentas
 */
function cc_get_blog_archivos($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $archivos = array();
    
    for ($i = 1; $i <= 3; $i++) {
        $titulo = get_field('blog_archivo_' . $i . '_titulo', $post_id);
        $archivo = get_field('blog_archivo_' . $i, $post_id);
        
        if ($archivo && is_array($archivo)) {
            $archivos[] = array(
                'titulo' => $titulo ? $titulo : 'Archivo ' . $i,
                'archivo' => $archivo
            );
        }
    }
    
    return $archivos;
}

/**
 * Calcular tiempo de lectura
 */
function cc_calcular_tiempo_lectura($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    // Si hay un tiempo personalizado, usarlo
    $tiempo_custom = get_field('blog_tiempo_lectura', $post_id);
    if ($tiempo_custom) {
        return intval($tiempo_custom);
    }
    
    // Calcular automáticamente
    $contenido = get_post_field('post_content', $post_id);
    $contenido_limpio = strip_tags($contenido);
    $palabras = str_word_count($contenido_limpio);
    
    // Promedio de 200 palabras por minuto
    $minutos = ceil($palabras / 200);
    
    return max(1, $minutos);
}

/**
 * Obtener información del autor (personalizado o WP)
 */
function cc_get_blog_autor_info($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $usar_personalizado = get_field('blog_autor_personalizado', $post_id);
    
    if ($usar_personalizado) {
        $nombre = get_field('blog_autor_nombre', $post_id);
        $cargo = get_field('blog_autor_cargo', $post_id);
        $bio = get_field('blog_autor_bio', $post_id);
        $foto = get_field('blog_autor_foto', $post_id);
        
        return array(
            'nombre' => $nombre ? $nombre : get_the_author_meta('display_name'),
            'cargo' => $cargo,
            'bio' => $bio,
            'foto' => $foto,
            'personalizado' => true
        );
    }
    
    // Usar datos de WordPress
    $author_id = get_post_field('post_author', $post_id);
    
    return array(
        'nombre' => get_the_author_meta('display_name', $author_id),
        'cargo' => get_the_author_meta('description', $author_id),
        'bio' => get_the_author_meta('description', $author_id),
        'foto' => get_avatar_url($author_id, array('size' => 150)),
        'personalizado' => false
    );
}

/**
 * Obtener entradas relacionadas
 */
function cc_get_entradas_relacionadas($post_id = null, $limit = 3) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $categoria = get_field('blog_categoria', $post_id);
    
    $args = array(
        'post_type' => 'blog',
        'posts_per_page' => $limit,
        'post__not_in' => array($post_id),
        'meta_query' => array(
            array(
                'key' => 'blog_categoria',
                'value' => $categoria,
                'compare' => '='
            )
        ),
        'orderby' => 'date',
        'order' => 'DESC'
    );
    
    $query = new WP_Query($args);
    
    // Si no hay suficientes, obtener las más recientes
    if ($query->post_count < $limit) {
        $args = array(
            'post_type' => 'blog',
            'posts_per_page' => $limit,
            'post__not_in' => array($post_id),
            'orderby' => 'date',
            'order' => 'DESC'
        );
        $query = new WP_Query($args);
    }
    
    return $query;
}

/**
 * Obtener entradas destacadas
 */
function cc_get_entradas_destacadas($limit = 3) {
    $args = array(
        'post_type' => 'blog',
        'posts_per_page' => $limit,
        'meta_query' => array(
            array(
                'key' => 'blog_destacada',
                'value' => '1',
                'compare' => '='
            )
        ),
        'orderby' => 'date',
        'order' => 'DESC'
    );
    
    return new WP_Query($args);
}

/**
 * Obtener entradas por categoría ACF
 */
function cc_get_entradas_por_categoria($categoria, $limit = 10) {
    $args = array(
        'post_type' => 'blog',
        'posts_per_page' => $limit,
        'meta_query' => array(
            array(
                'key' => 'blog_categoria',
                'value' => $categoria,
                'compare' => '='
            )
        ),
        'orderby' => 'date',
        'order' => 'DESC'
    );
    
    return new WP_Query($args);
}

/**
 * ========================================
 * SHORTCODES
 * ========================================
 */

/**
 * Shortcode: Entradas destacadas
 * Uso: [blog_destacadas limit="3"]
 */
function cc_shortcode_blog_destacadas($atts) {
    $atts = shortcode_atts(array(
        'limit' => 3,
    ), $atts);
    
    $query = cc_get_entradas_destacadas($atts['limit']);
    
    ob_start();
    
    if ($query->have_posts()) {
        echo '<div class="blog-destacadas-grid">';
        
        while ($query->have_posts()) {
            $query->the_post();
            
            $imagen = get_field('blog_imagen_destacada');
            $categoria = cc_get_blog_categoria_info();
            $resumen = get_field('blog_resumen');
            $tiempo = cc_calcular_tiempo_lectura();
            
            echo '<article class="blog-destacada-card">';
            
            if ($imagen) {
                echo '<div class="blog-card-imagen">';
                echo '<a href="' . get_permalink() . '">';
                echo '<img src="' . esc_url($imagen['url']) . '" alt="' . esc_attr($imagen['alt']) . '">';
                echo '</a>';
                echo '<span class="blog-categoria-badge" style="background: ' . $categoria['color'] . ';">';
                echo '<i class="fas ' . $categoria['icon'] . '"></i> ' . $categoria['label'];
                echo '</span>';
                echo '</div>';
            }
            
            echo '<div class="blog-card-contenido">';
            echo '<h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
            if ($resumen) {
                echo '<p>' . esc_html($resumen) . '</p>';
            }
            echo '<div class="blog-card-meta">';
            echo '<span><i class="far fa-calendar"></i> ' . get_the_date() . '</span>';
            echo '<span><i class="far fa-clock"></i> ' . $tiempo . ' min</span>';
            echo '</div>';
            echo '<a href="' . get_permalink() . '" class="btn-leer-mas">Leer más <i class="fas fa-arrow-right"></i></a>';
            echo '</div>';
            
            echo '</article>';
        }
        
        echo '</div>';
        wp_reset_postdata();
    }
    
    return ob_get_clean();
}
add_shortcode('blog_destacadas', 'cc_shortcode_blog_destacadas');

/**
 * Shortcode: Últimas entradas
 * Uso: [ultimas_entradas limit="6"]
 */
function cc_shortcode_ultimas_entradas($atts) {
    $atts = shortcode_atts(array(
        'limit' => 6,
    ), $atts);
    
    $args = array(
        'post_type' => 'blog',
        'posts_per_page' => $atts['limit'],
        'orderby' => 'date',
        'order' => 'DESC'
    );
    
    $query = new WP_Query($args);
    
    ob_start();
    
    if ($query->have_posts()) {
        echo '<div class="ultimas-entradas-grid">';
        
        while ($query->have_posts()) {
            $query->the_post();
            
            $imagen = get_field('blog_imagen_destacada');
            $categoria = cc_get_blog_categoria_info();
            $resumen = get_field('blog_resumen');
            
            echo '<article class="entrada-mini-card">';
            
            if ($imagen) {
                echo '<div class="entrada-mini-imagen">';
                echo '<a href="' . get_permalink() . '">';
                echo '<img src="' . esc_url($imagen['sizes']['medium'] ?? $imagen['url']) . '" alt="' . esc_attr($imagen['alt']) . '">';
                echo '</a>';
                echo '</div>';
            }
            
            echo '<div class="entrada-mini-contenido">';
            echo '<span class="mini-categoria" style="color: ' . $categoria['color'] . ';">';
            echo '<i class="fas ' . $categoria['icon'] . '"></i> ' . $categoria['label'];
            echo '</span>';
            echo '<h4><a href="' . get_permalink() . '">' . get_the_title() . '</a></h4>';
            echo '<span class="mini-fecha">' . get_the_date() . '</span>';
            echo '</div>';
            
            echo '</article>';
        }
        
        echo '</div>';
        wp_reset_postdata();
    }
    
    return ob_get_clean();
}
add_shortcode('ultimas_entradas', 'cc_shortcode_ultimas_entradas');

/**
 * ========================================
 * DASHBOARD WIDGET
 * ========================================
 */

/**
 * Dashboard widget: Resumen del blog
 */
function cc_dashboard_widget_blog() {
    $recientes = new WP_Query(array(
        'post_type' => 'blog',
        'posts_per_page' => 5,
        'orderby' => 'date',
        'order' => 'DESC'
    ));
    
    $total_entradas = wp_count_posts('blog')->publish;
    
    echo '<div class="blog-dashboard">';
    
    echo '<div class="blog-stats">';
    echo '<div class="stat-box">';
    echo '<span class="stat-number">' . $total_entradas . '</span>';
    echo '<span class="stat-label">Entradas Publicadas</span>';
    echo '</div>';
    echo '</div>';
    
    if ($recientes->have_posts()) {
        echo '<h4 style="margin-top: 20px; margin-bottom: 10px;"><i class="fas fa-blog"></i> Entradas Recientes</h4>';
        echo '<ul style="list-style: none; padding: 0; margin: 0;">';
        
        while ($recientes->have_posts()) {
            $recientes->the_post();
            $categoria = cc_get_blog_categoria_info();
            
            echo '<li style="padding: 10px 0; border-bottom: 1px solid #f0f0f1;">';
            echo '<strong><a href="' . get_edit_post_link() . '">' . get_the_title() . '</a></strong>';
            echo '<br><span style="font-size: 12px; color: #666;"><i class="far fa-calendar"></i> ' . get_the_date() . '</span>';
            echo '<br><span style="display: inline-block; padding: 3px 8px; background: ' . $categoria['color'] . '; color: #fff; border-radius: 10px; font-size: 11px; margin-top: 5px;">';
            echo '<i class="fas ' . $categoria['icon'] . '"></i> ' . $categoria['label'];
            echo '</span>';
            echo '</li>';
        }
        
        echo '</ul>';
        wp_reset_postdata();
    }
    
    echo '<p style="margin-top: 15px;">';
    echo '<a href="' . admin_url('edit.php?post_type=blog') . '" class="button button-primary">Ver Todas las Entradas</a> ';
    echo '<a href="' . admin_url('post-new.php?post_type=blog') . '" class="button">Nueva Entrada</a>';
    echo '</p>';
    
    echo '</div>';
}

function cc_agregar_dashboard_widget_blog() {
    wp_add_dashboard_widget(
        'cc_blog_dashboard',
        '<i class="fas fa-blog"></i> Blog Institucional - Casa de la Cultura',
        'cc_dashboard_widget_blog'
    );
}
add_action('wp_dashboard_setup', 'cc_agregar_dashboard_widget_blog');

/**
 * ========================================
 * ENQUEUE STYLES & SCRIPTS
 * ========================================
 */

/**
 * Cargar estilos y scripts para blog
 */
function cc_enqueue_blog_assets() {
    if (is_singular('blog') || is_post_type_archive('blog') || is_tax('blog_categoria_tax') || is_tax('blog_etiqueta_tax')) {
        
        // CSS
        wp_enqueue_style(
            'cc-blog-styles',
            get_template_directory_uri() . '/plantillas/agenda/blog/blog-styles.css',
            array(),
            '1.0.0'
        );
        
        // JavaScript
        wp_enqueue_script(
            'cc-blog-scripts',
            get_template_directory_uri() . '/plantillas/agenda/blog/blog-scripts.js',
            array('jquery'),
            '1.0.0',
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'cc_enqueue_blog_assets');

/**
 * ========================================
 * COLUMNAS PERSONALIZADAS EN ADMIN
 * ========================================
 */

/**
 * Agregar columnas personalizadas al listado de entradas
 */
function cc_blog_columnas_admin($columns) {
    $new_columns = array();
    
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        
        if ($key === 'title') {
            $new_columns['blog_categoria'] = 'Categoría';
            $new_columns['blog_destacada'] = 'Destacada';
            $new_columns['blog_tiempo_lectura'] = 'Lectura';
        }
    }
    
    return $new_columns;
}
add_filter('manage_blog_posts_columns', 'cc_blog_columnas_admin');

/**
 * Rellenar columnas personalizadas
 */
function cc_blog_columnas_contenido($column, $post_id) {
    switch ($column) {
        case 'blog_categoria':
            $categoria = cc_get_blog_categoria_info($post_id);
            echo '<span style="display: inline-block; padding: 5px 10px; background: ' . $categoria['color'] . '; color: #fff; border-radius: 12px; font-size: 11px; font-weight: 600;">';
            echo '<i class="fas ' . $categoria['icon'] . '"></i> ' . $categoria['label'];
            echo '</span>';
            break;
            
        case 'blog_destacada':
            $destacada = get_field('blog_destacada', $post_id);
            if ($destacada) {
                echo '<span style="color: #f39c12; font-size: 18px;" title="Destacada"><i class="fas fa-star"></i></span>';
            } else {
                echo '<span style="color: #ddd; font-size: 18px;"><i class="far fa-star"></i></span>';
            }
            break;
            
        case 'blog_tiempo_lectura':
            $tiempo = cc_calcular_tiempo_lectura($post_id);
            echo '<i class="far fa-clock"></i> ' . $tiempo . ' min';
            break;
    }
}
add_action('manage_blog_posts_custom_column', 'cc_blog_columnas_contenido', 10, 2);

/**
 * Hacer columnas ordenables
 */
function cc_blog_columnas_ordenables($columns) {
    $columns['blog_categoria'] = 'blog_categoria';
    return $columns;
}
add_filter('manage_edit-blog_sortable_columns', 'cc_blog_columnas_ordenables');

/**
 * ========================================
 * MENSAJES PERSONALIZADOS
 * ========================================
 */

/**
 * Personalizar mensajes de actualización
 */
function cc_blog_mensajes_personalizados($messages) {
    global $post;

    $messages['blog'] = array(
        0  => '',
        1  => 'Entrada actualizada. <a href="' . esc_url(get_permalink($post->ID)) . '">Ver entrada</a>',
        2  => 'Campo personalizado actualizado.',
        3  => 'Campo personalizado eliminado.',
        4  => 'Entrada actualizada.',
        5  => isset($_GET['revision']) ? 'Entrada restaurada a revisión de ' . wp_post_revision_title((int) $_GET['revision'], false) : false,
        6  => 'Entrada publicada. <a href="' . esc_url(get_permalink($post->ID)) . '">Ver entrada</a>',
        7  => 'Entrada guardada.',
        8  => 'Entrada enviada. <a target="_blank" href="' . esc_url(add_query_arg('preview', 'true', get_permalink($post->ID))) . '">Vista previa</a>',
        9  => sprintf(
            'Entrada programada para: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Vista previa</a>',
            date_i18n('M j, Y @ g:i a', strtotime($post->post_date)),
            esc_url(get_permalink($post->ID))
        ),
        10 => 'Borrador de entrada actualizado. <a target="_blank" href="' . esc_url(add_query_arg('preview', 'true', get_permalink($post->ID))) . '">Vista previa</a>',
    );

    return $messages;
}
add_filter('post_updated_messages', 'cc_blog_mensajes_personalizados');

/**
 * Custom template tags for this theme.
 */
require dirname( __FILE__ ) . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require dirname( __FILE__ ) . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require dirname( __FILE__ ) . '/inc/customizer.php';

/**
 * Load WooCommerce compatibility file.
 */
if ( class_exists( 'WooCommerce' ) ) {
	require dirname( __FILE__ ) . '/inc/woocommerce.php';
}

// Update the theme
require_once dirname( __FILE__ ) . '/inc/popularfx.php';

/**
 * ========================================
 * CARGAR FUNCIONES DE CUSTOM POST TYPES
 * ========================================
 */

// Funciones para Artistas
require dirname( __FILE__ ) . '/artista/artista-functions.php';

// Funciones para Talleres
require dirname( __FILE__ ) . '/taller/taller-functions.php';

// Funciones para Blog
require dirname( __FILE__ ) . '/agenda/blog/blog-functions.php';

// Funciones para Eventos
require dirname( __FILE__ ) . '/agenda/evento/evento-functions.php';

// Funciones para Noticias
require dirname( __FILE__ ) . '/agenda/noticia/noticia-functions.php';

/**
 * ========================================
 * CARGAR CAMPOS PERSONALIZADOS ACF
 * ========================================
 */
if ( function_exists( 'acf_add_local_field_group' ) ) {
	// ACF para Artistas
	require dirname( __FILE__ ) . '/artista/acf_fields-artista.php';
	
	// ACF para Talleres
	require dirname( __FILE__ ) . '/taller/acf_fields-taller.php';
	
	// ACF para Blog
	require dirname( __FILE__ ) . '/agenda/blog/acf_fields-blog.php';
	
	// ACF para Eventos
	require dirname( __FILE__ ) . '/agenda/evento/acf_fields-evento.php';
	
	// ACF para Noticias
	require dirname( __FILE__ ) . '/agenda/noticia/acf_fields-noticia.php';
}

