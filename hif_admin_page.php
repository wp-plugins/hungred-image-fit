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
	<div class="wrap">
	<?php    echo "<h2>" . __( 'Hungred Image Fit Configuration' ) . "</h2>"; ?>
	</div>
	<form name="hif_form" id="hif_form" class="hif_admin" onsubmit="return validate()" enctype="multipart/form-data" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<div class="postbox-container" id="hif_admin">
		<div class="metabox-holder">		
			<div class="meta-box-sortables ui-sortable" >
				<div class='postbox'>	
					<?php    echo "<h3  class='hndle'>" . __( 'Settings' ) . "</h3>"; ?>
					<div class='inside size'>
					<p><div class='label'><?php _e("Max Width" ); ?></div><input type="text" id="hif_max_width" name="hif_max_width" value="<?php echo $row['hif_max_width']; ?>" size="20"></p>	
					<p class="submit">
					<input type="submit" id="submit" value="<?php _e('Update Options' ) ?>" />
					</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	</form>
</div>
