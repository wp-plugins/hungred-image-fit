<?php
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
global $wpdb;
$error = "";
$table = $wpdb->prefix."hif_options";

if(isset($_POST['hif_max_width']))
{
//update the database with Replace instead of insert to avoid duplication data in the table
	$query = "REPLACE INTO $table(hif_option_id, hif_max_width) 
	VALUES('1', '".$_POST['hif_max_width']."')";
	$wpdb->query($query);
}

//retrieve new data
$query = "SELECT * FROM `".$table."` WHERE 1 AND `hif_option_id` = '1' limit 1";
$row = $wpdb->get_row($query,ARRAY_A);


?>
<div class="hif_wrap">
	<?php    echo "<h1>" . __( 'Hungred Image Fit' ) . "</h1>"; ?>
	
	<form name="hif_form" id="hif_form" class="hif_admin" onsubmit="return validate()" enctype="multipart/form-data" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<?php    echo "<h4>" . __( 'Settings' ) . "</h4>"; ?>
		<p><div class='label'><?php _e("Max Width" ); ?></div><input type="text" id="hif_max_width" name="hif_max_width" value="<?php echo $row['hif_max_width']; ?>" size="20"></p>	
		
		
		<p class="submit">
		</div><input type="submit" id="submit" value="<?php _e('Update Options' ) ?>" />
		</p>

		<hr />
		<h2><?php _e("Support" ); ?></h2>
		<p>
		Please visit <a href="http://hungred.com/2009/07/14/useful-information/wordpress-plugin-hungred-post-thumbnail/">hungred.com</a> for any support enquiry or email <a href='clay@hungred.com'>clay@hungred.com</a>. You can also show your appreciation by saying 'Thanks' on the <a href='http://hungred.com/2009/07/14/useful-information/wordpress-plugin-hungred-post-thumbnail/'>plugin page</a> or visits our sponsors on <a href="http://hungred.com/2009/07/14/useful-information/wordpress-plugin-hungred-post-thumbnail/">hungred.com</a> to help us keep up with the maintanance. If you like this plugin, you can buy me a <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=i_ah_yong%40hotmail%2ecom&lc=MY&item_name=Coffee&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted">coffee</a>! You can also support this development with the donation button. Thanks!
		<p>
<a href='http://www.pledgie.com/campaigns/6096'><img alt='Click here to lend your support to: Hungred Image Fit and make a donation at www.pledgie.com !' src='http://www.pledgie.com/campaigns/6096.png?skin_name=chrome' border='0' /></a>
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="ppbutton" onclick="window.open('https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=i_ah_yong%40hotmail%2ecom&lc=MY&item_name=Support%20Hungred%20Feature%20Post%20List%20Development&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHostedGuest');return false;">
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</p>
		</p>
	</form>
</div>
