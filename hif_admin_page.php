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
if(isset($_POST['submit']))
{
	update_option('hif_max_width', $_POST['hif_max_width']);
	$max_width = $_POST['hif_max_width'];
	update_option('hif_tags', $_POST['hif_tags']);
	
}else{
	$max_width = get_option('hif_max_width');
	$_POST['hif_tags'] = get_option('hif_tags');
}

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
					<p><div class='label'><?php _e("Max Width" ); ?></div><input type="text" id="hif_max_width" name="hif_max_width" value="<?php echo $max_width; ?>" size="20"></p>	
					<p><div class='label'><?php _e("Tagging" ); ?></div>

					<select multiple="multiple" size="5" name="hif_tags[]" style="height:100px">
					<?php
						$tags = get_terms('post_tag', 'orderby=count&hide_empty=0');
						foreach($tags as $tag){
							if(!is_array($_POST['hif_tags']))
								$_POST['hif_tags'] = array();
							if(in_array($tag->term_id, $_POST['hif_tags']))
							echo "<option selected value='".$tag->term_id."'>".$tag->name."</option>";
							else
							echo "<option value='".$tag->term_id."'>".$tag->name."</option>";
						}
					?>
					</select>
					<br/>
					<small>select more than one to resize only these tags. Deselect all to resize all.</small>
					</p>	
					<p class="submit">
					<input type="submit" id="submit" name="submit" value="<?php _e('Update Options' ) ?>" />
					</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	</form>
</div>
