<?php
if (!class_exists('nrvCatalogCpt')) {
    class nrvCatalogCpt
    {
        public function register()
        {
            add_action('init', [$this, 'custom_post_type']);
            add_action('add_meta_boxes', [$this, 'ad_meta_boxes']);
            add_action('save_post', [$this, 'save_meta_boxes'], 10, 2);
        }

        public function ad_meta_boxes()
        {
            add_meta_box(
                'nrvcatalog_settings',
                esc_html__('Product Settings', 'nrvcatalog'),
                [$this, 'metabox_catalog_html'],
                'catalog',
                'normal',
                'default'
            );
        }

        public function save_meta_boxes($post_id, $post) {
            if (!isset($_POST['_nrvcatalog']) || !wp_verify_nonce($_POST['_nrvcatalog'], 'nirvana_filds')) {
                return $post_id;
            }
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return $post_id;
            }
            if ($post->post_type != 'catalog') {
                return $post_id;
            }
            $post_type = get_post_type_object($post->post_type);
            if (!current_user_can($post_type->cap->edit_post, $post_id)) {
                return $post_id;
            }


            global $wpdb;

            $db_id_post= $post_id;
            $db_key_name= sanitize_text_field($_POST['nrvcatalog_key_name']);
            $db_name=  sanitize_text_field($_POST['nrvcatalog_name']);
            $db_image_link=  sanitize_text_field($_POST['nrvcatalog_img']);
            $db_referal_link=  sanitize_text_field($_POST['nrvcatalog_refer']);
            $db_credit=  sanitize_text_field($_POST['nrvcatalog_credit']);
            $db_period=  sanitize_text_field($_POST['nrvcatalog_period']);
            $db_rate=  sanitize_text_field($_POST['nrvcatalog_rate']);
            $db_age=  sanitize_text_field($_POST['nrvcatalog_age']);
            $db_rating=  sanitize_text_field($_POST['nrvcatalog_rating']);
            $db_approval=  sanitize_text_field($_POST['nrvcatalog_approval']);
            $db_telephone=  sanitize_text_field($_POST['nrvcatalog_telephone']);
            $db_license=  sanitize_text_field($_POST['nrvcatalog_license']);
            $db_address=  sanitize_text_field($_POST['nrvcatalog_address']);
            $db_description=  sanitize_text_field($_POST['nrvcatalog_description']);
            $db_note=  sanitize_text_field($_POST['nrvcatalog_note']);

            $table_name = $wpdb->get_blog_prefix() . 'nrv_catalog';
            $wpdb->replace($table_name, array(
                    'key_name' => $db_key_name,
                    'id_post' => $db_id_post,
                    'name' => $db_name,
                    'image_link' => $db_image_link,
                    'referal_link' => $db_referal_link,
                    'credit' => $db_credit,
                    'period' => $db_period,
                    'rate' => $db_rate,
                    'age' => $db_age,
                    'rating' => $db_rating,
                    'approval' => $db_approval,
                    'telephone' => $db_telephone,
                    'license' => $db_license,
                    'address' => $db_address,
                    'description' => $db_description,
                    'note' => $db_note
                ));

            
        }

        public function metabox_catalog_html($post)
        {
            global $wpdb;
            $table_name = $wpdb->get_blog_prefix() . 'nrv_catalog';

            if ($wpdb->get_var( "SELECT 1 FROM $table_name WHERE id_post = $post->ID limit 1")){

            $nrvmetadata = $wpdb->get_row( "SELECT * FROM $table_name WHERE id_post = $post->ID");
            $credit = $nrvmetadata->credit;
            $period = $nrvmetadata->period;
            $name = $nrvmetadata->name;
            $refer = $nrvmetadata->referal_link;
            $rate = $nrvmetadata->rate;
            $rating = $nrvmetadata->rating;
            $telephone = $nrvmetadata->telephone;
            $license = $nrvmetadata->license;
            $approval = $nrvmetadata->approval;
            $age = $nrvmetadata->age;
            $address = $nrvmetadata->address;
            $description = $nrvmetadata->description;
            $note = $nrvmetadata->note;
            $img = $nrvmetadata->image_link;
            $key_name = $nrvmetadata->key_name;
            }
            else {
                $credit = '';
                $period = '';
                $name = '';
                $refer = '';
                $rate = '';
                $rating = '';
                $telephone = '';
                $license = '';
                $approval = '';
                $age = '';
                $address = '';
                $description = '';
                $note = '';
                $img = '';
                $key_name = '';
            }
            wp_nonce_field('nirvana_filds', '_nrvcatalog');

            echo '
        <table class="product_settings">
        <tbody>
        <tr>
        <td>
        <label for "nrvcatalog_key_name"><span class="red">' . esc_html__('Key name', 'nrvcatalog') . ': </span></label><br>
        <input type="text" id="nrvcatalog_key_name" name="nrvcatalog_key_name" value="' . esc_attr($key_name) . '">
        </td>
        <td colspan="2">
        <span class="red">Все поля обязательны к заполнению, кроме "Note(Примечание)"!<br>
        Значение поля "ID продукта" должно быть уникальным.
            </span>
        </td>
        </tr>
        <tr>
        <td>
        <label for "nrvcatalog_img">' . esc_html__('Image link', 'nrvcatalog') . ': </label><br>
        <input type="text" id="nrvcatalog_img" name="nrvcatalog_img" value="' . esc_attr($img) . '">
        </td>
        <td>
        <label for "nrvcatalog_credit">' . esc_html__('Credit', 'nrvcatalog') . ': </label><br>
        <input type="text" id="nrvcatalog_credit" name="nrvcatalog_credit" value="' . esc_attr($credit) . '">
        </td>
        <td>
        <label for "nrvcatalog_period">' . esc_html__('Period', 'nrvcatalog') . ': </label><br>
        <input type="text" id="nrvcatalog_period" name="nrvcatalog_period" value="' . esc_attr($period) . '">
        </td>
        </tr>
        <tr>
        <td>
        <label for "nrvcatalog_name">' . esc_html__('Name', 'nrvcatalog') . ': </label><br>
        <input type="text" id="nrvcatalog_name" name="nrvcatalog_name" value="' . esc_attr($name) . '">
        </td>
        <td>
        <label for "nrvcatalog_refer">' . esc_html__('Referal link', 'nrvcatalog') . ': </label><br>
        <input type="text" id="nrvcatalog_refer" name="nrvcatalog_refer" value="' . esc_attr($refer) . '">
        </td>
        <td>
        <label for "nrvcatalog_rate">' . esc_html__('Rate', 'nrvcatalog') . ': </label><br>
        <input type="text" id="nrvcatalog_rate" name="nrvcatalog_rate" value="' . esc_attr($rate) . '">
        </td>
        </tr>
        <tr>
        <td>
        <label for "nrvcatalog_rating">' . esc_html__('Rating', 'nrvcatalog') . ': </label><br>
        <input type="text" id="nrvcatalog_rating" name="nrvcatalog_rating" value="' . esc_attr($rating) . '">
        </td>
        <td>
        <label for "nrvcatalog_telephone">' . esc_html__('Telephone', 'nrvcatalog') . ': </label><br>
        <input type="text" id="nrvcatalog_telephone" name="nrvcatalog_telephone" value="' . esc_attr($telephone) . '">
        </td>
        <td>
        <label for "nrvcatalog_license">' . esc_html__('License', 'nrvcatalog') . ': </label><br>
        <input type="text" id="nrvcatalog_license" name="nrvcatalog_license" value="' . esc_attr($license) . '">
        </td>
        </tr>
        <tr>
        <td>
        <label for "nrvcatalog_approval">' . esc_html__('Approval', 'nrvcatalog') . ': </label><br>
        <input type="text" id="nrvcatalog_approval" name="nrvcatalog_approval" value="' . esc_attr($approval) . '">
        </td>
        <td>
        <label for "nrvcatalog_age">' . esc_html__('Age', 'nrvcatalog') . ': </label><br>
        <input type="text" id="nrvcatalog_age" name="nrvcatalog_age" value="' . esc_attr($age) . '">
        </td>
        <td>
        <label for "nrvcatalog_address">' . esc_html__('Address', 'nrvcatalog') . ': </label><br>
        <textarea id="nrvcatalog_address" name="nrvcatalog_address" cols="30" rows="3">' . esc_attr($address) . '</textarea>
        </td>
        </tr>
        <tr>
        <td colspan="2">
        <label for "nrvcatalog_description">' . esc_html__('Description', 'nrvcatalog') . ': </label><br>
        <textarea id="nrvcatalog_description" name="nrvcatalog_description" cols="50" rows="5">' . esc_attr($description) . '</textarea>
        </td>
        <td>
        <label for "nrvcatalog_note">' . esc_html__('Note (optional)', 'nrvcatalog') . ': </label><br>
        <input type="text" id="nrvcatalog_note" name="nrvcatalog_note" value="' . esc_attr($note) . '">
        </td>
        </tr>
        </tbody>
        </table>
        ';
            echo '<br>'.esc_html__('Shorcode is', 'nrvcatalog').': [nrvcatalog name="' . $key_name . '"]';
            if (strpos(file_get_contents(plugin_dir_path(__DIR__) . '/assets/css/front/catalog-style.css'), '#tabs' . $key_name) === false) {
                $new_styles = "
          #tabs$key_name { 
            width: 100%;
            padding: 0px;
            margin: 0 auto;
            text-align: left;
            }
         #tabs$key_name>input { display: none; }
         #tabs$key_name>div {
            display: none;
            /* border: 1px solid #C0C0C0; */
            text-align: center;
            
         }
         #tabs$key_name>label {
            display: inline-block;
            padding: 2px;
            background: #EEEEEE;
            margin: 0 -5px -1px 0;
            text-align: center;
            color: #666666;
            border: 1px solid #C0C0C0;
            cursor: pointer;
         }
         #tabs$key_name>input:checked + label {
            color: #191970;
            font-weight:bold;
            border: 1px solid #C0C0C0;
            border-bottom: 1px solid #FFFFFF;
            background: #FFFFFF;
         
         }
         #tab_1$key_name:checked ~ #txt_1$key_name,
         #tab_2$key_name:checked ~ #txt_2$key_name,
         #tab_3$key_name:checked ~ #txt_3$key_name,
         #tab_4$key_name:checked ~ #txt_4$key_name { display: block; }
        
          ";
                file_put_contents(plugin_dir_path(__DIR__) . '/assets/css/front/catalog-style.css', $new_styles, FILE_APPEND);
            }
        }

        public function custom_post_type()
        {
            register_post_type('catalog', array(
                'public' => true,
                'has_archive' => true,
                'rewrite' => ['slug' => 'product'],
                'supports' => array('title', 'editor'),
                'label' => esc_html__('Catalog', 'nrvcatalog'),
                'labels' => array(
                    'add_new' => esc_html__('New product', 'nrvcatalog'),
                    'edit_item' => esc_html__('Edit product', 'nrvcatalog'),
                    'add_new_item' => esc_html__('Add new product', 'nrvcatalog')
                )
            ));
        }
    }
}
if (class_exists('nrvCatalogCpt')) {
    $nrvCatalogVpt = new nrvCatalogCpt();
    $nrvCatalogVpt->register();
}
