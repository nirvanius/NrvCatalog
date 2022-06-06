<?php
get_header();
global $wpdb;
$table_name = $wpdb->get_blog_prefix() . 'nrv_catalog';
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
?>
<div class="single-nrvcatalog">
<div  class="nrv-offer-section">
<h2><?= $name ?></h2>
<span class="red"><?= $note ?></span><br>
<a href="<?= $refer ?>" target="blank" rel="nofollow"><img src=<?= $img ?> width='300' height='210' alt='<?= $name ?>'></a>
<table class="nrv-offer-table">
<tbody>
<tr>
    <td>
        <b>Возраст: </b><?= $age ?>
    </td>
    <td>
        <b>Ставка: </b><?= $rate ?>%
    </td>
</tr>
<tr>
    <td>
        <b>Сумма: </b><?= $credit ?>
    </td>
    <td>
        <b>Срок: </b><?= $period ?>
    </td>
</tr>
<tr>
    <td>
        <b>Рейтинг: </b><?= $rating ?>
    </td>
    <td>
        <b>Одобрение: </b><?= $approval ?>%
    </td>
</tr>
<tr>
    <td>
        <b>Телефон: </b><?= $telephone ?>
    </td>
    <td>
        <b>Лицензия ЦБ : №</b><?= $license ?>
    </td>
</tr>
<tr>
    <td colspan="2">
        <b>Адрес: </b><?= $address ?>
    </td>
</tr>
</tbody>
</table>
<?= $description ?><br><br>
<form action="<?= $refer ?>" method= "GET"  target= "blank">
	<button type="submit" class= "button_ofer">Оформить</button>
    </form>
</div>
<div class="nrv-content-section">
<?php the_content() ?>
</div>
</div>
<?php
get_footer();