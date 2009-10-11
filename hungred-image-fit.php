<?php
/*
Plugin Name: Hungred Image Fit
Plugin URI: http://hungred.com/2009/09/17/useful-information/wordpress-plugin-hungred-image-fit/
Description: This plugin confine post image in an advance way to a given width size.
Author: Clay lua
Version: 0.5.1
Author URI: http://hungred.com
*/

/*  Copyright 2009  Clay Lua  (email : clay@hungred.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require_once("hungred.php");
$hungredObj = new Hungred_Tools();
add_action('wp_dashboard_setup', array($hungredObj,'widget_setup'));	
/*
Structure of the plugin
*/
/*
Name: add_hif_to_admin_panel_actions
Usage: use to add an options on the Setting section of Wordpress
Parameter: 	NONE
Description: this method depend on hif_admin for the interface to be produce when the option is created
			 on the Setting section of Wordpress
*/
function add_hif_to_admin_panel_actions() {
    $plugin_page = add_options_page("Hungred Image Fit", "Hungred Image Fit", 10, "Hungred Image Fit", "hif_admin");  
	add_action( 'admin_head-'. $plugin_page, 'hif_admin_header' );

}
/*
Name: hif_admin_header
Usage: stop hif admin page from caching
Parameter: 	NONE
Description: this method is to stop hif admin page from caching so that the preview is shown.
*/
function hif_admin_header()
{
nocache_headers();
}
/*
Name: hif_admin
Usage: provide the GUI of the admin page
Parameter: 	NONE
Description: this method depend on hif_admin_page.php to display all the relevant information on our admin page
*/
function hif_admin(){
	global $hungredObj;
	$support_links = "";
	$plugin_links = array();
	$plugin_links["url"] = "http://hungred.com/useful-information/wordpress-plugin-hungred-image-fit/";
	$plugin_links["wordpress"] = "hungred-image-fit";
	$plugin_links["development"] = "https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=i_ah_yong%40hotmail%2ecom&lc=MY&item_name=Support%20Hungred%20Post%20Thumbnail%20Development&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHostedGuest";
	$plugin_links["donation"] = "https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=i_ah_yong%40hotmail%2ecom&lc=MY&item_name=Coffee&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted";
	$plugin_links["pledge"] = "<a href='http://www.pledgie.com/campaigns/6187'><img alt='Click here to lend your support to: Hungred Wordpress Development and make a donation at www.pledgie.com !' src='http://www.pledgie.com/campaigns/6187.png?skin_name=chrome' border='0' /></a>";
	$support_links = "http://wordpress.org/tags/hungred-image-fit";
	include('hif_admin_page.php');  
	?>
	<div class="postbox-container" id="hpt_sidebar" style="width:20%;">
		<div class="metabox-holder">	
			<div class="meta-box-sortables">
				<?php
					$hungredObj->news(); 
					$hungredObj->plugin_like($plugin_links);
					$hungredObj->plugin_support($support_links);
				?>
			</div>
			<br/><br/><br/>
		</div>
	</div>
	<?php
	
}

add_action('admin_menu', 'add_hif_to_admin_panel_actions');

/*
Name: hif_loadcss
Usage: load the relevant CSS external files into Wordpress post section
Parameter: 	NONE
Description: uses wp_enqueue_style for safe printing of CSS style sheets
*/
function hif_loadcss()
{
	wp_enqueue_style('hif_ini',WP_PLUGIN_URL.'/hungred-image-fit/css/hif_ini.css');
}
add_action('admin_print_styles', 'hif_loadcss');
function hif_id()
{
	echo "
	<!-- This site is power up by Hungred Image Fit -->
	";
}
add_action('wp_head', 'hif_id');
/*
Name: hif_install
Usage: upload all the table required by this plugin upon activation for the first time
Parameter: 	NONE
Description: the structure of our Wordpress plugin
*/
function hif_install()
{
	global $wpdb;
    $table = $wpdb->prefix."hif_options";
    $structure = "CREATE TABLE IF NOT EXISTS `".$table."` (
		hif_option_id DOUBLE NOT NULL AUTO_INCREMENT ,
        hif_max_width Double NOT NULL DEFAULT 600,
		UNIQUE KEY id (hif_option_id)
    );";
    $wpdb->query($structure);
	$wpdb->query("INSERT INTO $table(hif_option_id)
	VALUES('1')");
}
if ( function_exists('register_activation_hook') )
	register_activation_hook('hungred-image-fit/hungred-image-fit.php', 'hif_install');
	
/*
Name: hif_uninstall
Usage: delete hif table
Parameter: 	NONE
Description: the structure of our Wordpress plugin
*/
function hif_uninstall()
{
	global $wpdb;
	$table = $wpdb->prefix."hif_options";
	$structure = "DROP TABLE `".$table."`";
	$wpdb->query($structure);
}
if ( function_exists('register_uninstall_hook') )
    register_uninstall_hook(__FILE__, 'hif_uninstall');
	
function hif_modify_image($content)
{
	global $wpdb;
	$table = $wpdb->prefix."hif_options";
	//retrieve new data
	$query = "SELECT * FROM `".$table."` WHERE 1 AND `hif_option_id` = '1' limit 1";
	$row = $wpdb->get_row($query,ARRAY_A);
	//$content = get_the_content();
	
	preg_match_all('/<img(.*)\"\s+\/>/i', $content, $matches);

	if($matches[0] != NULL)
	foreach($matches[0] as $e)
	{
		$flag = false;
		preg_match_all('/https?:\/\/[\S\w]+\.(jpg|jpeg|gif|png)/i', $e, $url, PREG_SET_ORDER);
		$container = $url[0];
		if($container != NULL)
		{
			
			$url = $container[0];
			$home = get_settings('siteurl');
			$path = str_replace($home, getcwd(), $url);
			list($width, $height) = @getimagesize($path);
			if($row['hif_max_width'] < $width)
			{
				$flag = true;
				$width>$height?$ratio=$width/$height:$ratio=$height/$width;
				$reduce_width = $width - $row['hif_max_width'];
				$reduce_height = $reduce_width / $ratio;
				$width = $row['hif_max_width'];
				$height -= $reduce_height;
				$post_title = the_title('','', false);
				$image_name = hif_extract_file_name($container[0]);
				$newImg = "<img src='".$container[0]."' width='".$width."px' height='".$height."px' title='".$post_title."' alt='".$image_name." ".$post_title."'/>";
	
				$content = str_replace($e, $newImg,$content);
			}
		}
		if(!$flag)
		{
			preg_match_all('/\.\.\/[\S\w]+\.(jpg|jpeg|gif|png)/i', $e, $result, PREG_SET_ORDER);
			$container = $result[0];
			if($container != NULL)
			{
				$url = $container[0];
				$path = str_replace('..', getcwd(), $url);
				list($width, $height) = @getimagesize($path);
				if($row['hif_max_width'] < $width)
				{
					$width>$height?$ratio=$width/$height:$ratio=$height/$width;
					$reduce_width = $width - $row['hif_max_width'];
					$reduce_height = $reduce_width / $ratio;
					$width = $row['hif_max_width'];
					$height -= $reduce_height;
					$post_title = the_title('','', false);
					$image_name = hif_extract_file_name($container[0]);
					$newImg = "<img src='".$container[0]."' width='".$width."px' height='".$height."px' title='".$post_title."' alt='".$image_name." ".$post_title."'/>";
					$content = str_replace($e, $newImg,$content);
				}
			}
		}

	}
	return $content;
}
add_filter('the_content', 'hif_modify_image');
function hif_extract_file_name($string)
{
	$basename = basename($string);
	$name = preg_replace('/[^a-zA-Z0-9\s]|hpt|jpg|png|jpeg|gif/i', ' ', $basename);
	$string = preg_replace('/\s\s+/', ' ', $name);
	return $string;
}

?>