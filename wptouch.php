<?php
/*
   Plugin Name: WPtouch iPhone Theme
   Plugin URI: http://bravenewcode.com/wptouch/
   Description: A plugin which formats your site with a mobile theme for the Apple <a href="http://www.apple.com/iphone/">iPhone</a> & <a href="http://www.apple.com/ipodtouch/">iPod touch</a>, <a href="http://www.android.com/">Google Android</a> or <a href="http://www.rim.com/storm/">Blackberry Storm</a> touch mobile devices. Set options by visiting the <a href="options-general.php?page=wptouch/wptouch.php">WPtouch admin panel</a>. &nbsp;
   Author: Dale Mugford & Duane Storey
   Version: 1.9b1
   Author URI: http://www.bravenewcode.com
   
   # Special thanks to ContentRobot and the iWPhone theme/plugin
   # (http://iwphone.contentrobot.com/) which the detection feature
   # of the plugin was based on.
 
   # Copyright (c) 2009 Duane Storey & Dale Mugford (BraveNewCode Inc.)
 
   # This plugin is free software; you can redistribute it and/or
   # modify it under the terms of the GNU Lesser General Public
   # License as published by the Free Software Foundation; either
   # version 2.1 of the License, or (at your option) any later version.

	# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
	# EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
	# MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
	# NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
	# LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
	# OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
	# WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE. 
   # See the GNU lesser General Public License for more details.
*/


// WPtouch Theme Options
global $bnc_wptouch_version;
$bnc_wptouch_version = '1.9 Beta 1';

// The Master Kill Switch
 function wptouch_restore() {
	 if ( isset( $_POST['reset'] ) ) {
		update_option( 'bnc_iphone_pages', '' );
	}
}

require_once( 'include/plugin.php' );
require_once( 'include/compat.php' );

//No need to manually change these, they're all admin options saved to the database
global $wptouch_defaults;
$wptouch_defaults = array(
	'header-title' => get_bloginfo('name'),
	'main_title' => 'Default.png',
	'enable-post-excerpts' => true,
	'enable-page-coms' => false,
	'enable-cats-button' => true,
	'enable-tags-button' => true,
	'enable-login-button' => false,
	'enable-redirect' => true,
	'enable-js-header' => true,
	'enable-gravatars' => true,
	'enable-main-home' => true,
	'enable-main-rss' => true,
	'enable-main-name' => true,
	'enable-main-tags' => true,
	'enable-main-categories' => true,
	'enable-main-email' => true,
	'header-background-color' => '222222',
	'header-border-color' => '333333',
	'header-text-color' => 'eeeeee',
	'link-color' => '006bb3',
	'style-text-justify' => 'full-justified',
	'style-text-size' => 'small-text',
	'bnc-zoom-state' => 'auto',
	'style-background' => 'classic-wptouch-bg',
	'enable-exclusive' => false
);

function wptouch_get_plugin_dir_name() {
	global $wptouch_plugin_dir_name;
	return $wptouch_plugin_dir_name;
}

function wptouch_get_upload_path() {
	
}

function wptouch_delete_icon( $icon ) {
	if ( !current_user_can( 'upload_files' ) ) {
		// don't allow users to delete who don't have access to upload (security feature)
		return;	
	}
			
	$dir = explode( 'wptouch', $icon );
	$loc = compat_get_upload_dir() . "/wptouch/" . ltrim( $dir[1], '/' );

	unlink( $loc );
}

function wptouch_init() {
	
	if ( isset( $_GET['delete_icon'] ) ) {
		wptouch_delete_icon( $_GET['delete_icon'] );
		
		header( 'Location: ' . get_bloginfo('wpurl') . '/wp-admin/options-general.php?page=wptouch/wptouch.php#available_icons' );
		die;
	}	
}

function wptouch_content_filter( $content ) {
	$settings = bnc_wptouch_get_settings();
	if ( isset($settings['adsense-id']) && strlen($settings['adsense-id']) && is_single() ) {
		require_once( 'adsense.php' );
		
		$channel = '';
		if ( isset($settings['adsense-channel']) ) {
			$channel = $settings['adsense-channel'];
		}
		
		$ad = google_show_ad( $settings['adsense-id'], $channel );
		return $content . '<div class="wptouch-adsense-ad">' . $ad . '</div>';	
	} else {
		return $content;
	}
}

	add_filter('init', 'wptouch_init');

	function WPtouch($before = '', $after = '') {
		global $bnc_wptouch_version;
		echo $before . 'WPtouch ' . $bnc_wptouch_version . $after;
	}
 
	// WP Admin stylesheet, jQuery + Ajax Upload
	function wptouch_admin_css() {		
		if ( isset( $_GET['page'] ) && $_GET['page'] == 'wptouch/wptouch.php' ) {		
			echo "<link rel='stylesheet' type='text/css' href='" . compat_get_plugin_url( 'wptouch' ) . "/admin-css/wptouch-admin.css' />\n";
			echo "<link rel='stylesheet' type='text/css' href='" . compat_get_plugin_url( 'wptouch' ) . "/admin-css/jquery.fancybox.css' />\n";
			
			$version = (float)get_bloginfo('version');
			if ( $version <= 2.3 ) {
				echo '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>\n';
			}
			echo "<script type='text/javascript' src='" . compat_get_plugin_url( 'wptouch' ) . "/js/jquery.ajax_upload.1.1.js'></script>\n";
			echo "<script type='text/javascript' src='" . compat_get_plugin_url( 'wptouch' ) . "/js/fancybox1.2.1.js'></script>\n";
			echo "<script type='text/javascript' src='" . compat_get_plugin_url( 'wptouch' ) . "/js/wptouch-admin.js'></script>\n";
		}
	}
  
class WPtouchPlugin {
	var $applemobile;
	var $desired_view;
	var $output_started;
		
	function WPtouchPlugin() {
		$this->output_started = false;
		$this->applemobile = false;
		
		add_action( 'plugins_loaded', array(&$this, 'detectAppleMobile') );
		add_filter( 'stylesheet', array(&$this, 'get_stylesheet') );
		add_filter( 'theme_root', array(&$this, 'theme_root') );
		add_filter( 'theme_root_uri', array(&$this, 'theme_root_uri') );
		add_filter( 'template', array(&$this, 'get_template') );
		add_filter( 'init', array(&$this, 'bnc_filter_iphone') );
		add_filter( 'wp', array(&$this, 'bnc_do_redirect') );
		add_filter( 'wp_head', array(&$this, 'bnc_head') );
		add_filter( 'query_vars', array( &$this, 'wptouch_query_vars' ) );
		add_filter( 'parse_request', array( &$this, 'wptouch_parse_request' ) );
		
		$this->detectAppleMobile();
	}

	function wptouch_query_vars( $vars ) {
		$vars[] = "wptouch";
		return $vars;
	}
	
	function wptouch_parse_request( $wp ) {
		if  (array_key_exists( "wptouch", $wp->query_vars ) ) {
			switch ( $wp->query_vars["wptouch"] ) {
				case "upload":
					include( 'ajax/file_upload.php' );	
					break;
				case "news":
					include( 'ajax/load-news.php' );
					break;
				case "beta":
					include( 'ajax/load-beta.php' );
					break;
			}
			exit;
		}	
	}

	function bnc_head() {
		if ( $this->applemobile && $this->desired_view == 'normal' ) {
			echo "<link rel='stylesheet' type='text/css' href='" . compat_get_plugin_url( 'wptouch' ) . "/themes/core/core-css/wptouch-switch-link.css'></link>\n";
		}
	}

	function bnc_do_redirect() {
	   global $post;
	   if ( $this->applemobile && $this->desired_view == 'mobile' ) {
			$version = (float)get_bloginfo('version');
			$is_front = 0;
			
			if ( $version <= 2.3 ) {
				$is_front = (is_home() && (bnc_get_selected_home_page() > 0));
			} else {
				$is_front = (is_front_page() && (bnc_get_selected_home_page() > 0));
			}
			
			if ( $is_front ) {
	         $url = get_permalink( bnc_get_selected_home_page() );
	         header('Location: ' . $url);
	         die;
	      }
	   }
	}
	
	function bnc_filter_iphone() {
		$key = 'bnc_mobile_' . md5(get_bloginfo('siteurl'));
		
	   	if (isset($_GET['bnc_view'])) {
	   		if ($_GET['bnc_view'] == 'mobile') {
				setcookie($key, 'mobile', 0); 
			} elseif ($_GET['bnc_view'] == 'normal') {
				setcookie($key, 'normal', 0);
			}
			
			header('Location: ' . get_bloginfo('siteurl'));
			die;
		}
			
		$settings = bnc_wptouch_get_settings();
		if (isset($_COOKIE[$key])) {
			$this->desired_view = $_COOKIE[$key];
		} else {
			if ( $settings['enable-regular-default'] ) {
				$this->desired_view = 'normal';
			} else {
		  		$this->desired_view = 'mobile';
			}
		}
	}
	
	function detectAppleMobile($query = '') {
		$container = $_SERVER['HTTP_USER_AGENT'];
		// The below prints out the user agent array. Uncomment to see it shown on the page.
		// print_r($container); 
		
		// Add whatever user agents you want here to the array if you want to make this show on a Blackberry 
		// or something. No guarantees it'll look pretty, though!
		$useragents = array(		
	 	//	"safari",			// *Developer mode*
			"iphone",  
			"ipod", 
			"aspen", 		// iPhone simulator
			"dream", 		// Pre 1.5 Android
			"android", 	// 1.5+ Android
			"incognito", 
			"webmate", 
			"blackberry9500", 
			"blackberry9530"
		);
		
		$this->applemobile = false;
		foreach ( $useragents as $useragent ) {
			if ( eregi( $useragent, $container ) ) {
				$this->applemobile = true;
			}
		}
	}
		  
	function get_stylesheet( $stylesheet ) {
		if ($this->applemobile && $this->desired_view == 'mobile') {
			return 'default';
		} else {
			return $stylesheet;
		}
	}
		  
	function get_template( $template ) {
		$this->bnc_filter_iphone();
		if ($this->applemobile && $this->desired_view === 'mobile') {
			return 'default';
		} else {	   
			return $template;
		}
	}
		  
	function get_template_directory( $value ) {
		$theme_root = compat_get_plugin_dir( 'wptouch' );
		if ($this->applemobile && $this->desired_view === 'mobile') {
				return $theme_root . '/themes';
		} else {
				return $value;
		}
	}
		  
	function theme_root( $path ) {
		$theme_root = compat_get_plugin_dir( 'wptouch' );
		if ($this->applemobile && $this->desired_view === 'mobile') {
			return $theme_root . '/themes';
		} else {
			return $path;
		}
	}
		  
	function theme_root_uri( $url ) {
		if ($this->applemobile && $this->desired_view === 'mobile') {
			$dir = compat_get_plugin_url( 'wptouch' ) . "/themes";
			return $dir;
		} else {
			return $url;
		}
	}
}
  
global $wptouch_plugin;
$wptouch_plugin = & new WPtouchPlugin();

//Thanks to edyoshi:
function bnc_is_iphone() {
	global $wptouch_plugin;
// Insert this begin
	$wptouch_plugin->bnc_filter_iphone();
// Insert this end
	return $wptouch_plugin->applemobile;
}
  
// The Automatic Footer Template Switch Code (into "wp_footer()" in regular theme's footer.php)
function wptouch_switch() {
	global $wptouch_plugin;
	if ( bnc_is_iphone() && $wptouch_plugin->desired_view == 'normal' ) {
		echo '<div id="wptouch-switch-link">';
		_e( "Mobile Theme", "wptouch" ); 
		echo "<a onclick=\"javascript:document.getElementById('switch-on').style.display='block';javascript:document.getElementById('switch-off').style.display='none';\" href=\"" . get_bloginfo('siteurl') . "/?bnc_view=mobile\"><img id=\"switch-on\" src=\"" . compat_get_plugin_url( 'wptouch' ) . "/images/on.jpg\" alt=\"on switch image\" class=\"wptouch-switch-image\" style=\"display:none\" /><img id=\"switch-off\" src=\"" . compat_get_plugin_url( 'wptouch' ) .  "/images/off.jpg\" alt=\"off switch image\" class=\"wptouch-switch-image\" /></a>";
 		echo '</div>';
	}
}
  
function bnc_options_menu() {
	add_options_page( __( 'WPtouch Theme', 'wptouch' ), 'WPtouch', 9, __FILE__, bnc_wp_touch_page );
}

function bnc_get_ordered_cat_list() {
	// We created our own function for this as wp_list_categories doesn't make the count linkable

	global $table_prefix;
	global $wpdb;

	$sql = "select * from " . $table_prefix . "term_taxonomy inner join " . $table_prefix . "terms on " . $table_prefix . "term_taxonomy.term_id = " . $table_prefix . "terms.term_id where taxonomy = 'category' order by count desc";	
	$results = $wpdb->get_results( $sql );
	foreach ($results as $result) {
		echo "<li><a href=\"" . get_category_link( $result->term_id ) . "\">" . $result->name . " (" . $result->count . ")</a></li>";
	}

}

function bnc_wptouch_get_settings() {
	return bnc_wp_touch_get_menu_pages();
}

function bnc_validate_wptouch_settings( &$settings ) {
	global $wptouch_defaults;
	foreach ( $wptouch_defaults as $key => $value ) {
		if ( !isset( $settings[$key] ) ) {
			$settings[$key] = $value;
		}
	}
}

function bnc_wptouch_is_exclusive() {
	$settings = bnc_wptouch_get_settings();
	return $settings['enable-exclusive'];
}

function bnc_wp_touch_get_menu_pages() {
	$v = get_option('bnc_iphone_pages');
	if (!$v) {
		$v = array();
	}
	
	if (!is_array($v)) {
		$v = unserialize($v);
	}
	
	bnc_validate_wptouch_settings( $v );

	return $v;
}

function bnc_get_selected_home_page() {
   $v = bnc_wp_touch_get_menu_pages();
   return $v['home-page'];
}

function wptouch_get_stats() {
	$options = bnc_wp_touch_get_menu_pages();
	if (isset($options['statistics'])) {
		echo stripslashes($options['statistics']);
	}
}
  
function bnc_get_title_image() {
	$ids = bnc_wp_touch_get_menu_pages();
	$title_image = $ids['main_title'];

	if ( file_exists( compat_get_plugin_dir( 'wptouch' ) . '/images/icon-pool/' . $title_image ) ) {
		$image = compat_get_plugin_url( 'wptouch' ) . '/images/icon-pool/' . $title_image;
	} else if ( file_exists( compat_get_upload_dir() . '/wptouch/custom-icons/' . $title_image ) ) {
		$image = compat_get_upload_url() . '/wptouch/custom-icons/' . $title_image;
	}

	return $image;
}

function bnc_excerpt_enabled() {
	$ids = bnc_wp_touch_get_menu_pages();
	return $ids['enable-post-excerpts'];
}	

function bnc_is_page_coms_enabled() {
	$ids = bnc_wp_touch_get_menu_pages();
	return $ids['enable-page-coms'];
}		

function bnc_is_cats_button_enabled() {
	$ids = bnc_wp_touch_get_menu_pages();
	return $ids['enable-cats-button'];
}	

function bnc_is_tags_button_enabled() {
	$ids = bnc_wp_touch_get_menu_pages();
	return $ids['enable-tags-button'];
}	

function bnc_is_login_button_enabled() {
	$ids = bnc_wp_touch_get_menu_pages();
	return $ids['enable-login-button'];
}		

function bnc_is_redirect_enable() {
	$ids = bnc_wp_touch_get_menu_pages();
	return $ids['enable-redirect'];
}
	
function bnc_is_js_enabled() {
	$ids = bnc_wp_touch_get_menu_pages();
	return $ids['enable-js-header'];
}	
	
function bnc_is_gravatars_enabled() {
	$ids = bnc_wp_touch_get_menu_pages();
	return $ids['enable-gravatars'];
}	
	
function bnc_is_home_enabled() {
	$ids = bnc_wp_touch_get_menu_pages();
	return $ids['enable-main-home'];
}	

function bnc_is_rss_enabled() {
	$ids = bnc_wp_touch_get_menu_pages();
	return $ids['enable-main-rss'];
}	

function bnc_show_author() {
	$ids = bnc_wp_touch_get_menu_pages();
	return $ids['enable-main-name'];
}

function bnc_show_tags() {
	$ids = bnc_wp_touch_get_menu_pages();
	return $ids['enable-main-tags'];
}

function bnc_show_categories() {
	$ids = bnc_wp_touch_get_menu_pages();
	return $ids['enable-main-categories'];
}

function bnc_is_email_enabled() {
	$ids = bnc_wp_touch_get_menu_pages();
		if (!isset($ids['enable-main-email'])) {
		return true;
		}
	return $ids['enable-main-email'];
}	

  
function bnc_wp_touch_get_pages() {
	global $table_prefix;
	global $wpdb;
	
	$ids = bnc_wp_touch_get_menu_pages();
	$a = array();
	$keys = array();
	foreach ($ids as $k => $v) {
		if ($k == 'main_title' || $k == 'enable-post-excerpts' || $k == 'enable-page-coms' || 
			 $k == 'enable-cats-button'  || $k == 'enable-tags-button'  || $k == 'enable-login-button' || 
			 $k == 'enable-redirect' || $k == 'enable-js-header' || $k == 'enable-gravatars' || 
			 $k == 'enable-main-home' || $k == 'enable-main-rss' || $k == 'enable-main-email' || 
			 $k == 'enable-main-name' || $k == 'enable-main-tags' || $k == 'enable-main-categories') {
			} else {
				if (is_numeric($k)) {
					$keys[] = $k;
				}
			}
	}
	 
	$menu_order = array(); 
	$results = false;

	if ( count( $keys ) > 0 ) {
		$query = "select * from {$table_prefix}posts where ID in (" . implode(',', $keys) . ") order by post_title asc";
		$results = $wpdb->get_results( $query, ARRAY_A );
	}

	if ( $results ) {
		foreach ( $results as $row ) {
			$row['icon'] = $ids[$row['ID']];
			$a[$row['ID']] = $row;
			if (isset($menu_order[$row['menu_order']])) {
				$menu_order[$row['menu_order']*100 + $inc] = $row;
			} else {
				$menu_order[$row['menu_order']*100] = $row;
			}
			$inc = $inc + 1;
		}
	}

	if (isset($ids['sort-order']) && $ids['sort-order'] == 'page') {
		asort($menu_order);
		return $menu_order;
	} else {
		return $a;
	}
}

function bnc_get_header_title() {
	$v = bnc_wp_touch_get_menu_pages();
	return $v['header-title'];
}

function bnc_get_header_background() {
	$v = bnc_wp_touch_get_menu_pages();
	return $v['header-background-color'];
}
  
function bnc_get_header_border_color() {
	$v = bnc_wp_touch_get_menu_pages();
	return $v['header-border-color'];
}

function bnc_get_header_color() {
	$v = bnc_wp_touch_get_menu_pages();
	return $v['header-text-color'];
}

function bnc_get_link_color() {
	$v = bnc_wp_touch_get_menu_pages();
	return $v['link-color'];
}

function bnc_get_zoom_state() {
	$v = bnc_wp_touch_get_menu_pages();
	return $v['bnc-zoom-state'];
}

require_once( 'include/icons.php' );
  
function bnc_wp_touch_page() {
	if (isset($_POST['submit'])) {
		echo('<div class="wrap"><div id="wptouch-theme">');
		echo('<div id="wptouchupdated">' . __( "Your new WPtouch settings were saved.", "wptouch" ) . '</div>');
	} 
	elseif (isset($_POST['reset'])) {
		echo('<div class="wrap"><div id="wptouch-theme"><div id="wptouchupdated">');
		echo sprintf(__( "WPtouch has been restored to its default settings. %sClick Here%s to refresh the page.", "wptouch" ), '<a href="options-general.php?page=wptouch/wptouch.php">','</a>');
		echo('</div>');
	} else {
		echo('<div class="wrap"><div id="wptouch-theme">');
}
?>

<?php $icons = bnc_get_icon_list(); ?>

<?php require_once( 'include/submit.php' ); ?>

	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<?php require_once( 'html/head-area.php' ); ?>
		<?php require_once( 'html/home-redirect-area.php' ); ?>
		<?php require_once( 'html/advanced-area.php' ); ?>
		<?php require_once( 'html/post-listings-area.php' ); ?>
		<?php require_once( 'html/style-area.php' ); ?>
		<?php require_once( 'html/icon-area.php' ); ?>
		<?php require_once( 'html/page-area.php' ); ?>
		<?php require_once( 'html/ads-stats-area.php' ); ?>
		<?php require_once( 'html/plugin-compat-area.php' ); ?>		
		<?php echo('' . WPtouch('<div class="wptouch-version"> This is ','</div>') . ''); ?>
		<input type="submit" name="submit" value="<?php _e('Save Options', 'wptouch' ); ?>" id="wptouch-button" class="button-primary" />
	</form>
	
<form method="post" action="<?php wptouch_restore(); ?>">
		<input type="submit" onclick="return confirm('Restore the default WPtouch settings?');" name="reset" value="<?php _e('Restore Defaults', 'wptouch' ); ?>" id="wptouch-button-reset" class="button-highlighted" />
	</form>
	<div class="wptouch-clearer"></div>
</div>
<?php 
echo('</div>'); } 
add_action('wp_footer', 'wptouch_switch');
add_action('admin_head', 'wptouch_admin_css');
add_action('admin_menu', 'bnc_options_menu'); 
//Thanks to edyoshi:
if (bnc_is_iphone() && $wptouch_plugin->desired_view == 'mobile') {
	add_action('the_content', 'wptouch_content_filter');
	add_filter('the_content_rss', 'do_shortcode', 11);
	add_filter('the_content', 'do_shortcode', 11);
}
?>