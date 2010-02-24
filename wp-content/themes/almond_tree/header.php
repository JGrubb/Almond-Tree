<?php
/**
 * @package WordPress
 * @subpackage Starkers
 */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<!-- an Almond Tree Marketing website -->
	<head>
		<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
		<meta http-equiv="X-UA-compatible" content="IE=7" />
		<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
		<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
		<?php wp_head(); ?>
		<script src="scripts/AC_RunActiveContent.js" type="text/javascript"></script>
	</head>
	
	<body <?php body_class(); ?>>
		
		<div class="page"> 
			<div id="wrapper"> 
				<div id="header"> 
					<h1><a href="<?php echo get_option('home'); ?>">AlmondTree</a></h1> 
			  			<div> 
						<strong>Contact us Today!<br /> 
						800.471.0801</strong>
						<ul class="nav">
							<?php wp_list_pages ("title_li=&sort_column=menu_order&depth=1&exclude=32"); ?>
						</ul>
					</div>
				</div>
		
		