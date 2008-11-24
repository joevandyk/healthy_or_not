<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<meta name ="author" content = "FixieConsulting.com" />
<title><?php bloginfo('name'); ?> <?php if ( is_single() ) { ?> &raquo; Blog Archive <?php } ?> <?php wp_title(); ?></title>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/favicon.ico" />


<?php wp_head(); ?>
<?php
if ( is_home() || is_single() ) { $current = 'reviews'; }
elseif ( is_page('about') ) { $current = 'about'; }
elseif ( is_page('shopping') ) { $current = 'shopping'; }
?>
<style type="text/css">
#<?php echo $current; ?> a:link, #<?php echo $current; ?> a:visited{
  color: #CA5035;
  font-weight: bold;
}
#main-nav ul li#<?php echo $current; ?> a {
  background: url(<?php bloginfo('stylesheet_directory'); ?>/images/header-<?php echo $current; ?>-active.png) no-repeat;
  width: 85px;
  height: 37px;
  margin: 0.3em;
}

</style>

</head>
<body>
<div id="header">
  <div id="branding">
    <h1><a href="<?php echo get_option('home'); ?>/"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/healthyornot.gif" alt="<?php bloginfo('name'); ?>" /></a></h1>
  </div>
  <div id="main-nav">
    <?php include (TEMPLATEPATH . '/searchform.php'); ?>
    <ul>
      <li id="reviews"><a href="/">Reviews</a></li>
      <li id="about"><a href="/about">About</a></li>
      <li id="shopping"><a href="/shopping">Shopping</a></li>
    </ul>
  </div>
  <!--<div class="description"><?php bloginfo('description'); ?></div>-->
</div>
<div id="seperator">&nbsp;</div>
<div id="page">
