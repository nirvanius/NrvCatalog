<?php
class nrvCatalog_Template_Loader {

public function register() {
add_filter('template_include', [$this, 'nrvcatalog_templates']);
}

public function nrvcatalog_templates($template) {

    if(is_post_type_archive('catalog')){
        $theme_files = ['archive-nrvcatalog.php', 'nrvcatalog/archive-nrvcatalog.php'];
        $exist = locate_template($theme_files, false);
        if($exist != ''){
            return $exist;
        }
        else{
            return plugin_dir_path(__DIR__).'templates/archive-nrvcatalog.php';
        }
    }
    elseif(is_singular('catalog')){
        $theme_files = ['single-nrvcatalog.php', 'nrvcatalog/single-nrvcatalog.php'];
        $exist = locate_template($theme_files, false);
        if($exist != ''){
            return $exist;
        }
        else{
            return plugin_dir_path(__DIR__).'templates/single-nrvcatalog.php';
        }
    }

    return $template;
}

}
$nrvCatalog_template = new nrvCatalog_Template_Loader();
$nrvCatalog_template->register();