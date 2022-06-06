<?php

use Shuchkin\SimpleXLSX;

if (!class_exists('nrvCatalogExcel')) {
    class nrvCatalogExcel
    {
        public function register()
        {
            add_action('admin_menu', [$this, 'add_catalog_submenu']);
        }

        public function add_catalog_submenu()
        {
            add_submenu_page(
                'edit.php?post_type=catalog',
                esc_html__('Excel import-export', 'nrvcatalog'),
                esc_html__('Excel import/export', 'nrvcatalog'),
                'manage_options',
                'excel-import',
                [$this, 'excel_import_page']
            );
        }

        public function excel_import_page()
        {
            echo '
            <h2>Импортировать таблицу Excel с изменениями и дополнениями на сайт</h2>
            <p class="red">Внимание! После нажатия "Upload" база данных будет изменена!</p>
            <form id="featured_upload" method="post" action="#" enctype="multipart/form-data">
            <input type="file" name="my_file_upload" id="my_file_upload"  multiple="false" />
            <input id="submit_my_file_upload" name="submit_my_file_upload" type="submit" value="Import" />
            </form>';
            if (isset($_POST['submit_my_file_upload'])) {
                $upload_file_path = ABSPATH . 'wp-content/uploads/upload.xlsx';
                // . $_FILES['my_file_upload']['name'];
                move_uploaded_file($_FILES['my_file_upload']['tmp_name'], $upload_file_path);
                echo "Медиафайл был успешно загружен!<br><br>";
                require plugin_dir_path(__DIR__) . '/inc/SimpleXLSX.php';

                // Simple XLSX Parser Template
                if ($xlsx = SimpleXLSX::parse($upload_file_path)) {
                    $sheetData = $xlsx->rows();

                    $excel = array();
                    $names = array();
                    foreach ($sheetData as $keyD => $sheetRow) {
                        if ($keyD == 0) {
                            foreach ($sheetRow as $keyC => $sheetCol) {
                                if ($sheetCol) $names[$keyC] = $sheetCol;
                            }
                        } else {

                            if ($sheetRow['0']) $title = $sheetRow['0'];

                            foreach ($sheetRow as $keyC => $sheetCol) {
                                if (isset($title) && $sheetCol) {
                                    $excel[$title][$names[$keyC]] = $sheetCol;
                                }
                            }

                            unset($title);
                        }
                    }
                } else {
                    var_dump(SimpleXLSX::parseError());
                }
                if (file_exists($upload_file_path)) {
                    unlink($upload_file_path);

                    foreach ($excel as $string => $element) {
                        global $wpdb;
                        $table_name = $wpdb->get_blog_prefix() . 'nrv_catalog';
                        $wpdb->replace($table_name, $element);
                    }
                } else {
                    echo '<br>Ошибка загрузки файла или ошибка в имени файла<br>';
                }
            }

            echo '
            <h2>Скачать таблицу с действующими параметрами оферов</h2>
            <br>
            <form id="xlxc_file" method="post" action="#" enctype="multipart/form-data">
            <input id="xlxc_file_dowmload" name="xlxc_file_dowmload" type="submit" value="Сreate" />
            </form>';
            if (isset($_POST['xlxc_file_dowmload'])) {
                global $wpdb;
                $table_name = $wpdb->get_blog_prefix() . 'nrv_catalog';

                $results = $wpdb->get_results("SHOW COLUMNS FROM $table_name");

                $table_titles = array();
                foreach ($results as $result) {
                    array_push($table_titles, $result->Field);
                }


                $fivesdrafts = $wpdb->get_results("SELECT * FROM $table_name");
                $offer[0] = $table_titles;
                $i = 1;
                foreach ($fivesdrafts as $fivesdraft) {
                    $offer[$i] = array();
                    foreach ($table_titles as $table_title) {
                        array_push($offer[$i], $fivesdraft->$table_title);
                    }
                    $i++;
                }
                require plugin_dir_path(__DIR__) . '/inc/SimpleXLSXGen.php';
                $xlsx = Shuchkin\SimpleXLSXGen::fromArray($offer);
                $xlsx->saveAs(ABSPATH . 'wp-content/uploads/offers.xlsx');
                echo 'Файл таблицы Excel актуальных оферов: <a href="' . WP_CONTENT_URL . '/uploads/offers.xlsx">Скачать</a>';
            }
        }
    }
}
if (class_exists('nrvCatalogExcel')) {
    $nrvCatalogExcel = new nrvCatalogExcel();
    $nrvCatalogExcel->register();
}
