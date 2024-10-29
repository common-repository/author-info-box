<?php
/*
Plugin Name: Author Infor Box
Plugin URI: http://www.alessiomoretto.com
Description: Easy and customizable author infor box
Version: 1.0
Author: Alessio Moretto
Author URI: http://www.alessiomoretto.com
 */

$arr = array('name','email','website','description','facebook','twitter','google','youtube','vimeo','linkedin','instagram','pinterest','whatsapp','snapchat','telegram','facebooktab','twittertab','pinteresttab','googletab','custom_title','custom_content','author_contact_form');

/*ADMIN USER PROFILE*/
function author_info_box_user( $user ) {
	global $arr;
    ?>
	<div class="author_info_box_user">
	<h3>Author Info Box </h3>
	<div class="author_info_box_description">Add your contact information here, leave blank for hide a element</div>
	<?php
	
	foreach ($arr as &$value) { ?>
		<div class="author_info_box_input <?php print $value; ?>">
			<label><?php print str_replace("_"," ",$value); ?></label>
			<?php if($value == "description") { ?>
				<?php
				$content = (get_the_author_meta(  $value.'_profile', $user->ID ));;
				$editor_id = $value.'_profile';

				wp_editor( $content, $editor_id );
				?>
				<div name="<?php print $value; ?>_profile" id="<?php print $value; ?>_profile" /></div>
			<?php } elseif($value == "facebooktab" || $value == "twittertab" || $value == "pinteresttab" || $value == "googletab" || $value == "custom_content"  || $value == "author_contact_form") { ?>
				<textarea name="<?php print $value; ?>_profile"><?php echo esc_attr(get_the_author_meta(  $value.'_profile', $user->ID )); ?></textarea>
				<?php if($value == "facebooktab") { ?>
				<small><a href="https://developers.facebook.com/docs/plugins/page-plugin" target="_blank">Widget</a></small>
				<?php } ?>
				<?php if($value == "twittertab") { ?>
				<small><a href="https://twitter.com/settings/widgets" target="_blank">Widget</a></small>
				<?php } ?>				
				<?php if($value == "pinteresttab") { ?>
				<small><a href="https://developers.pinterest.com/tools/widget-builder/" target="_blank">Widget</a></small>
				<?php } ?>			
				<?php if($value == "googletab") { ?>
				<small><a href="https://developers.google.com/+/web/badge/" target="_blank">Widget</a></small>
				<?php } ?>
				
			<?php } else { ?>
			<input type="text" name="<?php print $value; ?>_profile" value="<?php echo esc_attr(get_the_author_meta(  $value.'_profile', $user->ID )); ?>"  /> 
				<?php if($value == "name") { ?>
					Full name to display 
				<?php }elseif($value == "email") { ?>
					email@example.com <a target="_blank" href="http://en.gravatar.com/">change profile image</a>
				<?php }elseif($value == "website") { ?>
					www.example.com
				<?php }elseif($value == "whatsapp") { ?>
					whatsapp://0024123456789
				<?php }elseif($value == "snapchat") { ?>
					snapchat://add/username
				<?php }elseif($value == "custom_title") { ?>
					
				<?php }elseif($value == "custom_content") { ?>
					
				<?php }elseif($value == "author_contact_form") { ?>
					Contact form 7 shortcode
				<?php }else{ ?>
				Full url: http://www....
				<?php } ?>
			<?php } ?>
		</div>
	<?php
	}
	?>
	</div>
	<?php 
}
add_action( 'show_user_profile', 'author_info_box_user' );
add_action( 'edit_user_profile', 'author_info_box_user' );

/*SAVE ADMIN*/
function author_info_box_user_save( $user_id )
{
	global $arr;
	foreach ($arr as &$value) {
		update_user_meta( $user_id,$value.'_profile', ( $_POST[$value.'_profile'] ) );
	}
}
add_action( 'personal_options_update', 'author_info_box_user_save' );
add_action( 'edit_user_profile_update', 'author_info_box_user_save' );


/*ADMIN CSS*/
function my_custom_fonts() {
	wp_enqueue_style('admin_styles' , plugin_dir_url(__FILE__ ).'/css/admin_style.css');
}
add_action('admin_head', 'my_custom_fonts');

/*FRONTEND*/
function author_info_box_user_add_post_content($content) {
	global $arr;
	$aftercontent = "";
	$user_id = get_current_user_id();
	
	if((esc_attr( get_option('display_post') )=="on" && is_single())
	|| (esc_attr( get_option('display_page') )=="on" && is_page())){
	
		$author_name_font = esc_attr( get_option('author_name_font') );
		if($author_name_font == ""){
			$author_name_font = "Lobster";
		}
		$aftercontent .= '<link href="https://fonts.googleapis.com/css?family='.$author_name_font.'" rel="stylesheet">';
		
		$aftercontent .= "<div class='author_info'>";
		$aftercontent .= "<input id='tab1' type='radio' name='tabs' checked>";
		$aftercontent .= "<label for='tab1'>". __( 'Information', 'author_info_box' )."</label>";
		if(get_the_author_meta( 'facebooktab_profile' )){
			$aftercontent .= "<input id='tab2' type='radio' name='tabs'>";
			$aftercontent .= "<label for='tab2'>". __( 'Facebook', 'author_info_box' )."</label>";
		}
		if(get_the_author_meta( 'twittertab_profile' )){
			$aftercontent .= "<input id='tab3' type='radio' name='tabs'>";
			$aftercontent .= "<label for='tab3'>". __( 'Twitter', 'author_info_box' )."</label>";
		}
		if(get_the_author_meta( 'pinteresttab_profile' )){
			$aftercontent .= "<input id='tab4' type='radio' name='tabs'>";
			$aftercontent .= "<label for='tab4'>". __( 'Pinterest', 'author_info_box' )."</label>";
		}
		if(get_the_author_meta( 'googletab_profile' )){
			$aftercontent .= "<input id='tab5' type='radio' name='tabs'>";
			$aftercontent .= "<label for='tab5'>". __( 'Google', 'author_info_box' )."</label>";
		}
		if( get_option('hide_last_post')!="on"){
			$aftercontent .= "<input id='tab6' type='radio' name='tabs'>";
			$aftercontent .= "<label for='tab6'>". __( 'Latest post', 'author_info_box' )."</label>";
		}
		
		
		if(get_the_author_meta( 'custom_content_profile' )){
			$aftercontent .= "<input id='tab7' type='radio' name='tabs'>";
			$aftercontent .= "<label for='tab7'>".get_the_author_meta( 'custom_title_profile' )."</label>";
		}
		
		if(get_option( 'author_contact' )=="on"){
			$aftercontent .= "<input id='tab8' type='radio' name='tabs'>";
			$aftercontent .= "<label for='tab8'>". __( 'Contact', 'author_info_box' )."</label>";
		}
		
		$avatarsize = esc_attr( get_option('author_avatar_size') );
		if($avatarsize <= 0){
			$avatarsize = 120;
		}
		$aftercontent .= "<div class='author_info_content'>";
			$aftercontent .= "<div id='content1'>";
				$aftercontent .= "<div class='author_info_box'>";
					$aftercontent .= "<div class='author_info_box_gravatar'>".get_avatar( get_the_author_meta( 'email_profile' ), $avatarsize )."</div>";
					$aftercontent .= "<div class='author_info_box_name'>".get_the_author_meta( 'name_profile' )."</div>";
					$aftercontent .= "<div class='author_info_box_website'><a target='_blank' href='http://".get_the_author_meta( 'website_profile' )."'>".get_the_author_meta( 'website_profile' )."</a></div>";
					$aftercontent .= "<div class='author_info_box_description'>".get_the_author_meta( 'description_profile' )."</div>";
				$aftercontent .=  "</div>";
				$aftercontent .=  "<div class='author_info_box_social'>";
				foreach ($arr as &$value) {
					if($value!="facebooktab" && $value!="twittertab" && $value!="pinteresttab" && $value!="googletab" && $value!="custom_title" && $value!="custom_content" && $value!="name" && $value!="email" && $value!="website" && $value!="description" && get_the_author_meta( $value.'_profile' )!="" && $value!="author_contact_form"){
						$aftercontent .=  "<a href='".get_the_author_meta( $value.'_profile' )."'><i class='socicon-".$value."'></i></a>";
					}
				}		
				$aftercontent .=  "</div>";
			$aftercontent .=  "</div>";
		
			if(get_the_author_meta( 'facebooktab_profile' )){
				$aftercontent .= '<div id="content2">';
					$aftercontent .= "<div class='author_info_box text-center'>";
					$aftercontent .= get_the_author_meta( 'facebooktab_profile' );
					$aftercontent .= '</div>';
				$aftercontent .= '</div>';
			}
		
			if(get_the_author_meta( 'twittertab_profile' )){
				$aftercontent .= '<div id="content3">';
					$aftercontent .= "<div class='author_info_box text-center'>";
					$aftercontent .= get_the_author_meta( 'twittertab_profile' );
					$aftercontent .= '</div>';
				$aftercontent .= '</div>';
			}
		
			if(get_the_author_meta( 'pinteresttab_profile' )){
				$aftercontent .= '<div id="content4">';
					$aftercontent .= "<div class='author_info_box text-center'>";
					$aftercontent .= get_the_author_meta( 'pinteresttab_profile' );
					$aftercontent .= '</div>';
				$aftercontent .= '</div>';
			}
		
			if(get_the_author_meta( 'googletab_profile' )){
				$aftercontent .= '<div id="content5">';
					$aftercontent .= "<div class='author_info_box text-center'>";
					$aftercontent .= get_the_author_meta( 'googletab_profile' );
					$aftercontent .= '</div>';
				$aftercontent .= '</div>';
			}
			
			$aftercontent .= "<div id='content6'>";
				$aftercontent .= "<div class='author_info_box author_info_box_last_post'>";
				$recent = get_posts(array(
					'author'=>$user_id,
					'orderby'=>'date',
					'order'=>'desc',
					'numberposts'=>5
				));
				if( $recent ){
					foreach ($recent as &$value) {
						$aftercontent .= '<a href="' . esc_url( get_permalink($value->ID) ).'">'.'<small>'.get_the_date("d/m/Y",$value->ID).'</small>'.get_the_title($value->ID).'</a>';
				  }
				}else{
				  //No published posts
				}
				$aftercontent .= "</div>";
			$aftercontent .= "</div>";
			
			if(get_the_author_meta( 'custom_content_profile' )){
				$aftercontent .= '<div id="content7">';
					$aftercontent .= "<div class='author_info_box'>";
						$aftercontent .= "<div class='author_info_box_description'>";
						$aftercontent .= get_the_author_meta( 'custom_content_profile' );
						$aftercontent .= '</div>';
					$aftercontent .= '</div>';
				$aftercontent .= '</div>';
			}
			
			$aftercontent .= '<div id="content8">';
				$aftercontent .= "<div class='author_info_box'>";
				$aftercontent .= get_the_author_meta( 'author_contact_form_profile' );
				$aftercontent .= '</div>';
			$aftercontent .= '</div>';
		
		$aftercontent .=  "</div>";
	}
	if( get_option('display_before')!="on"){
		$fullcontent = $content . $aftercontent;
	}else{
		$fullcontent =  $aftercontent . $content;		
	}
	return $fullcontent;
	
}
add_filter ('the_content', 'author_info_box_user_add_post_content', 0);
add_shortcode("author_info_box", "author_info_box_user_add_post_content"); 




add_action( 'wp_enqueue_scripts', 'author_info_box_user_stylesheet' );
function author_info_box_user_stylesheet() {
    wp_register_style( 'author_info_box_user_style', plugins_url('css/style.css', __FILE__) );
    wp_enqueue_style( 'author_info_box_user_style' );
    wp_register_style( 'author_info_box_user_socicon_style', plugins_url('socicon/socicon.css', __FILE__) );
    wp_enqueue_style( 'author_info_box_user_socicon_style' );
	
	wp_register_style('myStyleSheet', plugins_url('css/frontendcss.php', __FILE__));
	wp_enqueue_style( 'myStyleSheet');
}


add_action( 'admin_enqueue_scripts', 'author_info_box_enqueue_color_picker' );
function author_info_box_enqueue_color_picker( $hook_suffix ) {
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'my-script-handle', plugins_url('js/custom.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}

add_action('admin_menu', 'author_info_box_settings_create_menu');
function author_info_box_settings_create_menu() {
	add_menu_page('Author Info Box', 'Author Info Box', 'administrator', __FILE__, 'author_info_box_settings_page' );
	add_action( 'admin_init', 'author_info_box_settings' );
}

function author_info_box_settings() {
	register_setting( 'author-info-box-settings-group', 'display_post' );
	register_setting( 'author-info-box-settings-group', 'display_page' );
	register_setting( 'author-info-box-settings-group', 'square_avatar' );
	register_setting( 'author-info-box-settings-group', 'hide_last_post' );
	register_setting( 'author-info-box-settings-group', 'display_before' );
	register_setting( 'author-info-box-settings-group', 'background_color' );
	register_setting( 'author-info-box-settings-group', 'border_color' );
	register_setting( 'author-info-box-settings-group', 'author_name_font' );
	register_setting( 'author-info-box-settings-group', 'author_avatar_size' );
	register_setting( 'author-info-box-settings-group', 'social_background_color' );
	register_setting( 'author-info-box-settings-group', 'social_color' );
	register_setting( 'author-info-box-settings-group', 'square_social' );
	register_setting( 'author-info-box-settings-group', 'author_contact' );
	
	
}
function author_info_box_settings_page() {
?>
<div class="wrap">
<div class='author_info_box_user'>
<h3>Author Info Box </h3>
<form method="post" action="options.php">
<?php settings_fields( 'author-info-box-settings-group' ); ?>
<?php do_settings_sections( 'author-info-box-settings-group' ); ?>
Shortcode: [author_info_box]<br><br>

<br>
<br><br>

	<div class='author_info_box_input'>
		<label>Display in post</label>
		<div class="switch">
		  <input id="cmn-toggle-1" name="display_post" class="cmn-toggle cmn-toggle-round" type="checkbox"  <?php if(esc_attr( get_option('display_post') )=="on"){print "checked";} ?>>
		  <label for="cmn-toggle-1"></label>
		</div>
	</div>

	<div class='author_info_box_input'>
		<label>Display in page</label>
		<div class="switch">
		  <input id="cmn-toggle-2" name="display_page" class="cmn-toggle cmn-toggle-round" type="checkbox" <?php if(esc_attr( get_option('display_page') )=="on"){print "checked";} ?>>
		  <label for="cmn-toggle-2"></label>
		</div>
	</div>

	<div class='author_info_box_input'>
		<label>Square Avatar</label>
		<div class="switch">
		  <input id="cmn-toggle-3" class="cmn-toggle cmn-toggle-yes-no" type="checkbox" name="square_avatar" <?php if(esc_attr( get_option('square_avatar') )=="on"){print "checked";} ?>>
		  <label for="cmn-toggle-3" data-on="Yes" data-off="No"></label>
		</div>
	</div>

	<div class='author_info_box_input'>
		<label>Hide latest post</label>
		<div class="switch">
		  <input id="cmn-toggle-4" name="hide_last_post" class="cmn-toggle cmn-toggle-round" type="checkbox" <?php if(esc_attr( get_option('hide_last_post') )=="on"){print "checked";} ?>>
		  <label for="cmn-toggle-4"></label>
		</div>
	</div>


	<div class='author_info_box_input'>
		<label>Display Before</label>
		<div class="switch">
		  <input id="cmn-toggle-5" class="cmn-toggle cmn-toggle-yes-no" type="checkbox" name="display_before" <?php if(esc_attr( get_option('display_before') )=="on"){print "checked";} ?>>
		  <label for="cmn-toggle-5" data-on="Yes" data-off="No"></label>
		</div>
	</div>

	<div class='author_info_box_input'>
		<label>Border Color</label>
		<input type='text' class="my-color-field" name="border_color" value="<?php echo esc_attr( get_option('border_color') ); ?>" data-default-color="#f1f1f1" >
	</div>

	<div class='author_info_box_input'>
		<label>Background Color<br><small>(Tab & Footer)</small></label>
		<input type='text' class="my-color-field" name="background_color" value="<?php echo esc_attr( get_option('background_color') ); ?>" data-default-color="#f9f9f9" >
	</div>

	<div class='author_info_box_input'>
		<label>Name Font</label>
		<input type='text'  name="author_name_font" value="<?php echo esc_attr( get_option('author_name_font') ); ?>"> 
		<small><a href="https://fonts.google.com/" target="_blank">Google Fonts</a> Name (eg: Lobster)</small>
	</div>
	
	<div class='author_info_box_input'>
		<label>Avatar Size</label>
		<input type='text'  name="author_avatar_size" value="<?php echo esc_attr( get_option('author_avatar_size') ); ?>"> <small>px (eg: 120)</small>
	</div>	

	<div class='author_info_box_input'>
		<label>Social Background Color</label>
		<input type='text' class="my-color-field" name="social_background_color" value="<?php echo esc_attr( get_option('social_background_color') ); ?>" data-default-color="#ffffff" >
	</div>

	<div class='author_info_box_input'>
		<label>Social Color</label>
		<input type='text' class="my-color-field" name="social_color" value="<?php echo esc_attr( get_option('social_color') ); ?>" data-default-color="#797979" >
	</div>
	<div class='author_info_box_input'>
		<label>Square Social</label>
		<div class="switch">
		  <input id="cmn-toggle-7" class="cmn-toggle cmn-toggle-yes-no" type="checkbox" name="square_social" <?php if(esc_attr( get_option('square_social') )=="on"){print "checked";} ?>>
		  <label for="cmn-toggle-7" data-on="Yes" data-off="No"></label>
		</div>
	</div>
	<div class='author_info_box_input'>
		<label>Contact form</label>
		<div class="switch">
		  <input id="cmn-toggle-8" class="cmn-toggle cmn-toggle-yes-no" type="checkbox" name="author_contact" <?php if(esc_attr( get_option('author_contact') )=="on"){print "checked";} ?>>
		  <label for="cmn-toggle-8" data-on="Yes" data-off="No"></label>
		</div>
	</div>
	<div class="clearfix"></div>
	<br><br>
	
	
    <?php submit_button(); ?>
</form>
</div>
</div>

<?php } ?>