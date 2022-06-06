<?php
// $products = get_posts(array('post_type'=> 'catalog', 'numberposts'=> -1));
// foreach($products as $product){
//     wp_delete_post($product->ID, true);
// }
global $wpdb;
    $table_name = $wpdb->get_blog_prefix() . 'nrv_catalog';
    $sql = "DROP TABLE IF EXISTS $table_name";
    $wpdb->query($sql);