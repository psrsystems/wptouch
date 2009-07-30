<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
	<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<meta name="viewport" content="initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />

	<title><?php $str = bnc_get_header_title(); echo stripslashes($str); ?></title>

	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="apple-touch-icon" href="<?php echo bnc_get_title_image(); ?>" />
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/style.css" type="text/css" media="screen" />
	<!--  <link rel="stylesheet" href="<?php echo compat_get_plugin_url( 'wptouch' ); ?>/themes/core/core-css/frog.css" type="text/css" /> -->
	<?php wptouch_core_header_styles(); wptouch_core_header_enqueue(); ?>
</head>