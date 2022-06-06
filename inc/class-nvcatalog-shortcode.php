<?php
class nrvCatalog_Shortcode {

    public function register(){

        add_action('init', [$this, 'register_shortcode']);

    }

    public function register_shortcode() {

        add_shortcode('nrvcatalog', [$this, 'product_shortcode']);
    }

    public function product_shortcode($atts= array()) {

extract(shortcode_atts(array('name' => 'zaim'), $atts));
$key_name= $name;
global $wpdb;
$table_name = $wpdb->get_blog_prefix() . 'nrv_catalog';
$nrvmetadata = $wpdb->get_row( "SELECT * FROM $table_name WHERE key_name='$key_name'");
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
$product_key = $nrvmetadata->key_name;
$slug = $nrvmetadata->id_post;
$product_link = get_post($slug)->post_name;


return "

    <div class= 'kartochka'>
    <div class= 'logo'>
    <p><a href='/product/$product_link' target= 'blank'>
    <picture>
    <img src=$img width='150' height='105' alt='$name'>
    </picture>
    </a>
    <br>
   </p>
    </div>
    <div class= 'ofer_body'>
    <a href='/product/$product_link' target= 'blank' ><b>$name</b></a>
    <span class= 'red'>$note</span><br>
        <!-- вкладки описания офера -->
        
        <div id='tabs$product_key'>
        <input type='radio' name='inset$product_key' value='' id='tab_1$product_key' checked>
        <label for='tab_1$product_key'>Условия</label>

        <input type='radio' name='inset$product_key' value='' id='tab_2$product_key'>
        <label for='tab_2$product_key'>Описание</label>

        <input type='radio' name='inset$product_key' value='' id='tab_3$product_key'>
        <label for='tab_3$product_key'>Реквизиты</label>

        <input type='radio' name='inset$product_key' value='' id='tab_4$product_key'>
        <label for='tab_4$product_key'>Рейтинг</label>

        <div id='txt_1$product_key'>
        <table>
            <tr>
            <td>
                <b> Сумма:</b> $credit
            </td>
            <td>
                <b>Срок:</b> $period
            </td>
            </tr>
            <tr>
            <td>
                <b>Ставка:</b> $rate
            </td>
            <td>
                <b>Возраст:</b> $age
            </td>
            </tr>
            </table>
        </div>
            <div id='txt_2$product_key'>
            <table>
            <tr>
            <td>
            $description
            </td>
            </tr>
            </table>
            </div>


        <div id='txt_3$product_key'>
        <table>
                <tr>
                <td>
                <b>Тел:</b> $telephone
                </td>
                <td rowspan='2'>
                <b>Адрес:</b>$address
                </td>
                </tr>
                <tr>
                <td>
                <b>Лицензия: № </b>$license
                </td>
                </tr>
            </table>
        </div>
        <div id='txt_4$product_key'>
            <table>
                <tr>
                <td>
                <b>Одобрение: </b>$approval%
                </td>
                <td>
                <b>Рейтинг: </b>$rating
                </td>
                </tr>
            </table>
        </div>
        </div>
        
        <!-- Конец вкладок -->
    </div>
    <div class= 'button'>
    <form action='$refer' method='GET'  target= 'blank'>
	<button type='submit' class= 'button_ofer'>Оформить</button>
    </form>
    </div>
</div>
";
    }
}
if (class_exists('nrvCatalogCpt')) {
    $nrvCatalog_Shortcode = new nrvCatalog_Shortcode();
    $nrvCatalog_Shortcode->register();
}