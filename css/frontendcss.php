<?php
header('Content-type: text/css');
require_once('../../../../wp-load.php');
require_once('../../../../wp-config.php');
  

$background_color = esc_attr( get_option('background_color') );
$border_color = esc_attr( get_option('border_color') );
$square_avatar = esc_attr( get_option('square_avatar') );
$author_name_font = esc_attr( get_option('author_name_font') );
$social_color = esc_attr( get_option('social_color') );
$social_background_color = esc_attr( get_option('social_background_color') );
$square_social = esc_attr( get_option('square_social') );
?>

.author_info input:checked + label,
.author_info_box_social {
	background: <?php echo $background_color; ?>;
}
.author_info_box_social,
.author_info_box,
.author_info input:checked + label{
	border-color: <?php echo $border_color; ?>;
}
<?php if($square_avatar){ ?>
.site .author_info_box_gravatar img{
	border-radius:0 !important;
}
<?php } ?>
.author_info_box_name{
	font-family:<?php echo $author_name_font; ?>;
}


.author_info_box_social a {
	<?php if($square_social){ ?>
		border-radius: 0%;
	<?php } ?>
    background: <?php echo $social_background_color; ?>;
    color: <?php echo $social_color; ?>;
}