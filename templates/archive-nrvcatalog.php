<?php
get_header();
echo '
<div class="archive-body">
<h1>Все наши предложения</h1>
<br>
';
if(have_posts()) {
    while (have_posts()) {
        the_post();
        $id_post= get_the_ID();
        global $wpdb;
        $table_name = $wpdb->get_blog_prefix() . 'nrv_catalog';
        $product_key = $wpdb->get_var("SELECT key_name FROM $table_name WHERE id_post = $id_post");
        echo '<div class="archive-kard">'.do_shortcode("[nrvcatalog name='$product_key']<br><br>").'</div>';
    }
} else {
    echo 'Нет добавленных продуктов';
}
echo '</div>';
get_footer();