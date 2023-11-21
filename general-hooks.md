###ADD SSl / HTTPS
1. Go to Settings-->General and 'https://' for website address and site address fields.
2. In wp-config.php file add the following code before
/* That's all, stop editing! Happy blogging. */
define('FORCE_SSL_ADMIN', true);
3. Add following code in .htaccess file
```
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
RewriteCond %{HTTPS} !=on
RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
# BEGIN WordPress
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>
```

###Update WordPress Automatically Without Using FTP
1. Open /wp-config.php
2. Paste the following code to your wp-config.php file, preferably just below every other line of code.
define('FS_METHOD','direct');


###Theme directory path
```
<?php echo get_template_directory_uri(); ?>/
```

###WordPress add parameters to the custom URL structure
Add the following code snippet in functions.php
```
// Custom URL rewriting for profiles
add_action('init', function(){
    add_rewrite_rule(
    '^profiles/([^/]+)([/]?)(.*)',
    'index.php?pagename=profiles&user=$matches[1]',
    'top'
    );
});

add_filter('query_vars', function( $vars ){
    $vars[] = 'profiles';
    $vars[] = 'user';
    return $vars;
});

```
Now on the page where we need to get variables add the following code
$member_username = get_query_var( 'user' );
Ref: https://stackoverflow.com/questions/40886630/using-custom-wordpress-url-link-parameter-instead-of-get-value-in-url

###Get all taxonomy term from any specific post type
If you want to show taxonomy images then use the plugin : Taxonomy images
https://wordpress.org/plugins/taxonomy-images/

For Taxonomy page title: <?php single_cat_title(); ?>

```
<?php
// For taxonomy image
$taximages = get_option( 'taxonomy_image_plugin' );

$taxonomy_name = 'product-category';
$terms = get_terms($taxonomy_name,'orderby=count&hide_empty=0&parent=0');
 foreach ( $terms as $term ) 
 {
    $args = array(
    'post_type' => 'product',
    'tax_query' => array(
        array(
        'taxonomy' => $taxonomy_name,
        'field' => 'id',
        'terms' => $term->term_id
         )
      )
    );
$loop = new WP_Query( $args );
// For taxonomy images
$taximageid = $taximages[intval($term->term_id)];
?>
    <h3><a href="<?php echo esc_url( get_term_link( $term ) );?>"><?php echo $term->name;?></a></h3>
<?php 
// Display taxonomy images
echo wp_get_attachment_image ($taximageid, 'image_size');
?>
<?php } ?>
```

###Show taxonomy terms as select drop down and query post
```
<select name="location">
  <option value="all">Select Location</option>
  <?php
 $taxonomy_locations = 'national-location';
  $terms_locations = get_terms($taxonomy_locations, 'orderby=id&hide_empty=0&parent=0');
  if ($terms_locations)
  {
foreach ($terms_locations as $terms_location)
{
  if ($terms_location->slug == $location)
{
  $location_selected = 'selected = "selected"';
}
else
{
  $location_selected = '';
}
  ?>
  <option value="<?php echo $terms_location->slug;?>" <?php echo $location_selected;?>><?php echo $terms_location->name;?></option>
  <?php
}
  }
  ?>
  </select>
<?php
$tax_query = array('relation' => 'AND');

  if ($location && $location!='all')
  {
$tax_query[] =  array(
'taxonomy' => $taxonomy_locations,
'terms' => $location,
'field' => 'slug'
);
  }
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args = array(
  'posts_per_page' =>  9,
  'post_type' => 'national-package',
  'post_status' => 'publish',
  'tax_query' => $tax_query,
  'paged' => $paged
);
query_posts ($args);
if (have_posts()) : while (have_posts()) : the_post();
.....
.....
endwhile; endif;
?>
```

###Get taxonomy terms of a post
```
$term_list = wp_get_post_terms($post->ID, 'taxonomy-slug', array("fields" => "all"));
foreach($term_list as $term_single) 
{
    echo $term_single->name; 
}
```

###Custom post type loop
```
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args = array(
                  'posts_per_page' =>  -1,
                  'post_type' => 'post-type-slug',
                  'post_status' => 'publish'
                );
query_posts ($args);
if (have_posts()) : while (have_posts()) : the_post();
$post_thumbnail_id = get_post_thumbnail_id();
$featured_image = wp_get_attachment_image_src($post_thumbnail_id, $size='image_size');
// Fleatured image ALT tag
$featured_image_alt = get_post_meta( $post_thumbnail_id, '_wp_attachment_image_alt', true );
..................
..................
endwhile; endif;
wp_reset_query();
```

###Detect the last post in the WordPress loop

```
<?php if (($wp_query->current_post +1) == ($wp_query->post_count)) {
  echo 'This is the last post';
} ?>

<?php if (($wp_query->current_post +1) != ($wp_query->post_count)) {
  echo 'This is the not the last post';
} ?>
```

###Types plugin render field
```
// Normal filed value as text
 $field_variable = get_post_meta($post->ID, 'wpcf-field-slug', true);
// filed value with HTML output
$field_variable = types_render_field("field-slug", array("output"=>"html"));

Repeatative fields
<ul>
<li>
<?php echo types_render_field( "survey-respondents", array( "separator" => "</li><li>"));?>
</li>
</ul>

<div class="img">
    <?php echo types_render_field( "photos-of-the-property", array( "size"=>"thumbnail", "separator" => "</div><div class='img'>") ); ?>
</div>
```
###Types multiple loop
```
<div class="WeeklyPrizePhotoCont">
	    <!-- Prize loop -->
	    <?php
		$args = array(
			'posts_per_page' => -1,
			'post_type' => 'weekly-prize',
			'post_status' => 'publish'
			);
		$loop = new WP_Query( $args );
		while ( $loop->have_posts() ) : $loop->the_post();
		$prize_image= get_post_meta( get_the_ID(), 'wpcf-prize-image');
		?>
		<h3><?php the_title();?></h3>
		<?php if ($prize_image):?>
		<ul id="gallery">
		    <?php
			
		    foreach($prize_image as $k => $v)
		   { 	
			$img_id = get_attachment_id_from_src($v);
			$attachment_title = get_the_title($img_id);
				

			echo sprintf ('<li>				
			<a href="%2$s" class="venobox" data-gall="myGallery"><img src="%2$s" alt="" />
			<h4>'.$attachment_title.'</h4></a>
			</li>',$k, $v);
		}
		?>
		</ul>
		<?php endif;?>
		<?php  endwhile;  wp_reset_query(); ?>
		 <!-- Prize loop -->
		
	</div>
	</div>
```
###Google Map Embed in WordPress Post / Page
Put the following code in functions.php
Usage: [googlemap src="you_url"]
```
function GoogleMapEmbed($atts, $content = null) {
   extract(shortcode_atts(array(
      "width" => '100%',
      "height" => '480',
      "src" => ''
   ), $atts));
   return '<iframe width="'.$width.'" height="'.$height.'" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'.$src.'" ></iframe>';
}
add_shortcode("googlemap", "GoogleMapEmbed");
```