<?php
/*
Plugin Name: Space gallery
Plugin URI: http://www.gopiplus.com/work/2010/08/14/space-gallery/
Description: Want to display images as a slideshow in the page or post? Then use space gallery WordPress plugin. Its just another image slideshow show gallery. Click on the below images to see it in action. Back up your existing gallery XML files before update this plugin.
Author: Gopi.R
Version: 4.0
Author URI: http://www.gopiplus.com/work/2010/08/14/space-gallery/
Donate link: http://www.gopiplus.com/work/2010/08/14/space-gallery/
*/

/**
 *     Space gallery
 *     Copyright (C) 2012  www.gopiplus.com
 * 
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 * 
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 * 
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

function space_show() 
{
	$space_siteurl = get_option('siteurl');
	$space_pluginurl = $space_siteurl . "/wp-content/plugins/space-gallery/";
	$space_package = "";
	
	$space_width = get_option('space_width');
	
	$space_xml_file = get_option('space_xml_file');
	if($space_xml_file==""){$space_xml_file = "space-gallery.xml";}
	
	$doc = new DOMDocument();
	$doc->load( $space_pluginurl . $space_xml_file );
	$images = $doc->getElementsByTagName( "image" );
	foreach( $images as $image )
	{
	  $paths = $image->getElementsByTagName( "path" );
	  $path = $paths->item(0)->nodeValue;
	  $targets = $image->getElementsByTagName( "target" );
	  $target = $targets->item(0)->nodeValue;
	  $titles = $image->getElementsByTagName( "title" );
	  $title = $titles->item(0)->nodeValue;
	  $links = $image->getElementsByTagName( "link" );
	  $link = $links->item(0)->nodeValue;
	  $space_package = $space_package .'<img src="'.$path.'" alt="" />';
	}
	
	$space_random = get_option('space_random');
	if($space_random==""){$space_random = "Y";}
	if($space_random=="Y")
	{
//		$space_package = explode("<", $space_package);
//		shuffle($space_package);
//		$space_package = implode("<", $space_package);
//		$space_package = '<' . $space_package;
//		$space_package = explode("<<", $space_package);
//		$space_package = implode("<", $space_package); // ugly hack to get rid of stray <<
	}
	
	?>
	<div id="myGallery" class="spacegallery">
		<?php echo $space_package; ?>
	</div>
	<script>
		$('#myGallery').spacegallery({loadingClass: 'loading'});
    </script>
	<?php
}


add_filter('the_content','space_show_filter');

function space_show_filter($content){
	return 	preg_replace_callback('/\[space-gallery=(.*?)\]/sim','space_show_filter_Callback',$content);
}

function space_show_filter_Callback($matches) 
{
	$var = $matches[1];
	$space_pp = "";
	$space_package = "";
	
	parse_str($var, $output);
	
	$filename = $output['filename'];
	if($filename==""){$filename = "space-gallery.xml";}
	
	$space_siteurl = get_option('siteurl');
	$space_pluginurl = $space_siteurl . "/wp-content/plugins/space-gallery/";

	$doc = new DOMDocument();
	$doc->load( $space_pluginurl . $filename );
	$images = $doc->getElementsByTagName( "image" );
	foreach( $images as $image )
	{
	  $paths = $image->getElementsByTagName( "path" );
	  $path = $paths->item(0)->nodeValue;
	  $targets = $image->getElementsByTagName( "target" );
	  $target = $targets->item(0)->nodeValue;
	  $titles = $image->getElementsByTagName( "title" );
	  $title = $titles->item(0)->nodeValue;
	  $links = $image->getElementsByTagName( "link" );
	  $link = $links->item(0)->nodeValue;
	  $space_package = $space_package .'<img src="'.$path.'" alt="" />';
	}
	
	$space_random = get_option('space_random');
	if($space_random==""){$space_random = "Y";}
	if($space_random=="Y")
	{
//		$space_package = explode("<", $space_package);
//		shuffle($space_package);
//		$space_package = implode("<", $space_package);
//		$space_package = '<' . $space_package;
//		$space_package = explode("<<", $space_package);
//		$space_package = implode("<", $space_package); // ugly hack to get rid of stray 
	}
	
	$space_pp = $space_pp . '<div id="myGallery" class="spacegallery">';
		$space_pp = $space_pp . $space_package;
	$space_pp = $space_pp . '</div>';
	$space_pp = $space_pp . '<script>';
		$space_pp = $space_pp . "$('#myGallery').spacegallery({loadingClass: 'loading'});";
    $space_pp = $space_pp . '</script>';
	
	return $space_pp;
}

function space_install() 
{
	add_option('space_xml_file', "space-gallery.xml");
	add_option('space_title', "Slideshow");
	add_option('space_random', "Y");
}

function space_admin_option() 
{
	echo "<div class='wrap'>";
	echo "<h2>"; 
	echo "Space gallery";
	echo "</h2>";
    
	$space_xml_file = get_option('space_xml_file');
	$space_title = get_option('space_title');
	$space_random = get_option('space_random');
	
	if (@$_POST['space_submit']) 
	{
		$space_xml_file = stripslashes($_POST['space_xml_file']);
		$space_title = stripslashes($_POST['space_title']);
		$space_random = stripslashes($_POST['space_random']);
		
		update_option('space_xml_file', $space_xml_file );
		update_option('space_title', $space_title );
		update_option('space_random', $space_random );
	}
	?>
	<form name="space_form" method="post" action="">
	<table width="100%" border="0" cellspacing="0" cellpadding="3"><tr><td align="left">
	<?php
	echo '<p>XML File:<br><input  style="width: 200px;" maxlength="500" type="text" value="';
	echo $space_xml_file . '" name="space_xml_file" id="space_xml_file" /><br>(Enter Name of the XML file,This is not for page & post gallery)</p>';
	
	echo '<p>Title:<br><input  style="width: 200px;" maxlength="400" type="text" value="';
	echo $space_title . '" name="space_title" id="space_title" /></p>';
	
	echo '<p>Random:<br><input  style="width: 100px;" maxlength="1" type="text" value="';
	echo $space_random . '" name="space_random" id="space_random" />(Y/N)</p>';
	
	echo '<input name="space_submit" id="space_submit" class="button-primary" value="Submit" type="submit" />';
	?>
	</td><td align="left" valign="top">  </td></tr></table>
	</form>
	<h2>We can use this plug-in in 2 different way.</h2>
	1.	Copy and past the below mentioned code to your desired template location.<br /><br />
	&lt;?php if (function_exists (space_show)) space_show(); ?&gt; <br /><br />
	2.	Past the given code to post or page.<br /><br />
	[space-gallery=filename=space-gallery.xml]
	<br /><br />
	<span style="color: #FF0000;font-weight: bold;">In future back up your existing space gallery XML files before update this plugin.</span>
	<br /><br />Check official website for more info <a target="_blank" href='http://www.gopiplus.com/work/2010/08/14/space-gallery/'>click here</a><br />
	<?php
	echo "</div>";
}

function space_add_javascript_files() 
{
	if (!is_admin())
	{
		wp_enqueue_style( 'spacegallery', get_option('siteurl').'/wp-content/plugins/space-gallery/css/spacegallery.css','','','screen');
		wp_enqueue_style( 'spacegallery-custom', get_option('siteurl').'/wp-content/plugins/space-gallery/css/custom.css','','','screen');
		wp_enqueue_script( 'spacegallery-jquery', get_option('siteurl').'/wp-content/plugins/space-gallery/js/jquery.js');
		wp_enqueue_script( 'spacegallery-eye', get_option('siteurl').'/wp-content/plugins/space-gallery/js/eye.js');
		wp_enqueue_script( 'spacegallery-utils', get_option('siteurl').'/wp-content/plugins/space-gallery/js/utils.js');
		wp_enqueue_script( 'spacegallery', get_option('siteurl').'/wp-content/plugins/space-gallery/js/spacegallery.js');
	}	
}

add_action('init', 'space_add_javascript_files');

function space_add_to_menu() 
{
	//add_options_page('Space gallery', 'Space gallery', 7, __FILE__, 'space_admin_option' );
	add_options_page('Space gallery', 'Space gallery', 'manage_options', __FILE__, 'space_admin_option' );
}

add_action('admin_menu', 'space_add_to_menu');
register_activation_hook(__FILE__, 'space_install');
?>
