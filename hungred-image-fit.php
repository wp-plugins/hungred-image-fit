<?php
/*
Plugin Name: Hungred Image Fit
Plugin URI: http://hungred.com/2009/09/17/useful-information/wordpress-plugin-hungred-image-fit/
Description: This plugin confine post image in an advance way to a given width size.
Author: Clay lua
Version: 0.7.2
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
if ( ! defined( 'HIF_PLUGIN_DIR' ) )
	define( 'HIF_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . plugin_basename( dirname( __FILE__ ) ) );
if ( ! defined( 'HIF_PLUGIN_URL' ) )
	define( 'HIF_PLUGIN_URL', WP_PLUGIN_URL . '/' . plugin_basename( dirname( __FILE__ ) ) );
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
    $plugin_page = add_options_page("Hungred Image Fit", "Hungred Image Fit", 10, "Hungred-Image-Fit", "hif_admin");
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
	<div class="postbox-container" id="hungred_sidebar" style="width:20%;">
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
	add_option('hif_max_width', '600');
}
if ( function_exists('register_activation_hook') )
	register_activation_hook(__FILE__, 'hif_install');

/*
Name: hif_uninstall
Usage: delete hif table
Parameter: 	NONE
Description: the structure of our Wordpress plugin
*/
function hif_uninstall()
{
	delete_option('hif_max_width');
}
if ( function_exists('register_uninstall_hook') )
    register_uninstall_hook(__FILE__, 'hif_uninstall');


/*
Name: hif_modify_image
Usage: modify the image in the content
Parameter: content of the post
Description:
*/
function hif_modify_image($content)
{
	global $wpdb,$post;

	$table = $wpdb->prefix."hif_options";
	//retrieve new data
	$max_width = get_option('hif_max_width');
	$tags = get_option('hif_tags');;
	$post_tags = get_the_tags($post->ID);
	$flag = false;
	if(is_array($tags))
	foreach($post_tags as $tag){
		if(in_array($tag->term_id, $tags)){
			$flag = true;
			break;
		}
	}
	$value = get_post_meta($post->ID, 'HIF_RESIZE', true);

		//$content = get_the_content();
		preg_match_all('/(<img(.*?)"?\'?\s*?\/?>)/i', $content, $matches);
		if($matches[0] != NULL)
		foreach($matches[0] as $e)
		{

			$alt = hif_extract_options('alt="', $e);
			if(trim($alt) == "")
			$alt = hif_extract_options("alt='", $e);
			if($alt=="noresize")
				continue;
			else if($alt == "resize"){

			}else{
				if(!$flag && is_array($tags)){
				break;
				}
			}

			// $defined_width = hif_extract_options('width="', $e);
			// if(trim($defined_width) == "")
				// $defined_width = hif_extract_options2("width='", $e);
			// if(trim($defined_width) == "")
				// $defined_width = "";
			$flag = false;
			preg_match_all('/https?:\/\/[\S\w]+\.(jpg|jpeg|gif|png)/i', $e, $url, PREG_SET_ORDER);
			$container = $url[0];
			$find = strtolower(get_option('home'));
			$str = strtolower($container[0]);
			if(!strstr($str, $find)){
				continue;
			}
			if($container != NULL)
			{

				$url = $container[0];
				// $home = get_settings('siteurl');
				// $path = str_replace($home, getcwd(), $url);
				try{
					// list($width, $height) = getimagesize($path, $info) or die();
					// if($max_width < $width && ($max_width < $defined_width ||$defined_width ==""))
					// {
						// $flag = true;
						// $ratio= $width>$height?$width/$height:$height/$width;
						// $reduce_width = $width - $max_width;
						// $reduce_height = $reduce_width / $ratio;
						// $width = $max_width;
						// $height -= $reduce_height;
                                                try{
                                                   list($width, $height) = getjpegsize($url);
                                                   if($width < $max_width)
                                                         continue;
                                                }catch(Exception $e){

                                                }
						$post_title = the_title('','', false);
						$image_name = hif_extract_file_name($container[0]);
						$newImg = "<img src='". HIF_PLUGIN_URL.'/scripts/timthumb.php?src='.$container[0].'&h=0&w='.$max_width.'&zc=1&q=100'."' title='".$post_title."' alt='".$image_name." ".$post_title."'/>";

						$content = str_replace($e, $newImg,$content);
					//}
				}catch(customException $e){
					echo $e->errorMessage();
				}

			}
			if(!$flag)
			{
				preg_match_all('/\.\.\/[\S\w]+\.(jpg|jpeg|gif|png)/i', $e, $result, PREG_SET_ORDER);
				$container = $result[0];
				if($container != NULL)
				{
					$url = $container[0];
					// $path = str_replace('..', getcwd(), $url);
					// list($width, $height) = @getimagesize($path, $info);
					// if($max_width < $width && ($max_width < $defined_width ||$defined_width ==""))
					// {
						// $ratio= $width>$height?$width/$height:$height/$width;
						// $reduce_width = $width - $max_width;
						// $reduce_height = $reduce_width / $ratio;
						// $width = $max_width;
						// $height -= $reduce_height;
                                                try{
                                                   list($width, $height) = getjpegsize($url);
                                                   if($width < $max_width)
                                                         continue;
                                                }catch(Exception $e){

                                                }
						$post_title = the_title('','', false);
						$image_name = hif_extract_file_name($container[0]);
						$newImg = "<img src='". HIF_PLUGIN_URL.'/scripts/timthumb.php?src='.$container[0].'&h=0&w='.$max_width.'&zc=1&q=100'."' title='".$post_title."' alt='".$image_name." ".$post_title."'/>";
						$content = str_replace($e, $newImg,$content);
					// }
				}
			}

		}

	return $content;
}
add_filter('the_content', 'hif_modify_image');
function hif_extract_options($find, $src, $change="")
{
      try{
	$start = strpos($src, $find);
	if($start !== FALSE)
	{
		$end = strpos($src, '"', $start+10);
		$remove = substr($src, $start, $end-$start+1);
		if($remove != "")
		{
			$start = strpos($src, '"', $start+1);
			$value = substr($src, $start+1, $end-$start-1);
			//$src = str_replace($remove, $change, $src);
			return $value;
		}
	}
      }catch(Exception $e){

      }
	return "";
}

function hif_extract_options2($find, $src, $change="")
{
   try{
	$start = strpos($src, $find);
	if($start !== FALSE)
	{
		$end = strpos($src, "'", $start+10);
		$remove = substr($src, $start, $end-$start+1);
		if($remove != "")
		{
			$start = strpos($src, "'", $start+1);
			$value = substr($src, $start+1, $end-$start-1);
			//$src = str_replace($remove, $change, $src);
			return $value;
		}
	}
   }catch(Exception $e){
   }
	return "";
}
function hif_extract_file_name($string)
{
	$basename = basename($string);
	$name = preg_replace('/[^a-zA-Z0-9\s]|hpt|jpg|png|jpeg|gif/i', ' ', $basename);
	$string = preg_replace('/\s\s+/', ' ', $name);
	return $string;
}
// Retrieve JPEG width and height without downloading/reading entire image.
function getjpegsize($img_loc) {
    $handle = fopen($img_loc, "rb") or die("Invalid file stream.");
    $new_block = NULL;
    if(!feof($handle)) {
        $new_block = fread($handle, 32);
        $i = 0;
        if($new_block[$i]=="\xFF" && $new_block[$i+1]=="\xD8" && $new_block[$i+2]=="\xFF" && $new_block[$i+3]=="\xE0") {
            $i += 4;
            if($new_block[$i+2]=="\x4A" && $new_block[$i+3]=="\x46" && $new_block[$i+4]=="\x49" && $new_block[$i+5]=="\x46" && $new_block[$i+6]=="\x00") {
                // Read block size and skip ahead to begin cycling through blocks in search of SOF marker
                $block_size = unpack("H*", $new_block[$i] . $new_block[$i+1]);
                $block_size = hexdec($block_size[1]);
                while(!feof($handle)) {
                    $i += $block_size;
                    $new_block .= fread($handle, $block_size);
                    if($new_block[$i]=="\xFF") {
                        // New block detected, check for SOF marker
                        $sof_marker = array("\xC0", "\xC1", "\xC2", "\xC3", "\xC5", "\xC6", "\xC7", "\xC8", "\xC9", "\xCA", "\xCB", "\xCD", "\xCE", "\xCF");
                        if(in_array($new_block[$i+1], $sof_marker)) {
                            // SOF marker detected. Width and height information is contained in bytes 4-7 after this byte.
                            $size_data = $new_block[$i+2] . $new_block[$i+3] . $new_block[$i+4] . $new_block[$i+5] . $new_block[$i+6] . $new_block[$i+7] . $new_block[$i+8];
                            $unpacked = unpack("H*", $size_data);
                            $unpacked = $unpacked[1];
                            $height = hexdec($unpacked[6] . $unpacked[7] . $unpacked[8] . $unpacked[9]);
                            $width = hexdec($unpacked[10] . $unpacked[11] . $unpacked[12] . $unpacked[13]);
                            return array($width, $height);
                        } else {
                            // Skip block marker and read block size
                            $i += 2;
                            $block_size = unpack("H*", $new_block[$i] . $new_block[$i+1]);
                            $block_size = hexdec($block_size[1]);
                        }
                    } else {
                        return FALSE;
                    }
                }
            }
        }
    }
    return FALSE;
}
?>
