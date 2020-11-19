<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

title: Files (service)
description: 
slug: admin/files-servise
slug-static: -
layout: pages/admin/core/_layout.php
menu[title]: <i class="im-file-code"></i>Files (service)
menu[group]: General
menu[order]: 4
parser: -
compress: 1

 **/

verifyLoginRedirect(['admin'], 'You do not have permission to access the admin panel!');

?>

<h1 class="pad20-tb bg-yellow250 pad30-rl">Files (service)</h1>

<div class="pad30-rl pad10-rl-tablet pad20-b">
    <?php

    service_files(CONFIG_DIR, '<h4>Config</h4>');
    service_files(LAYOUT_DIR, '<h4 class="mar30-t">Layout</h4>');
    service_files(SNIPPETS_DIR, '<h4 class="mar30-t">Snippets</h4>');
    service_files(ADMIN_DIR . 'config/', '<h4 class="mar30-t">Admin</h4>', 2);
    service_files(DATA_DIR . 'backup/', '<h4 class="mar30-t">Backup</h4>');
    service_files(DATA_DIR, '<h4 class="mar30-t">Other</h4>', 2);

    ?>
</div>

<?php

/**
 * Вывод файлов из каталога
 */
function service_files($dir, $header = '', $mode = 1)
{
    $files = [];

    if (is_dir($dir)) {
        $directory = new \RecursiveDirectoryIterator($dir);
        $iterator = new \RecursiveIteratorIterator($directory);

        foreach ($iterator as $info) {
            if ($info->isFile()) {
                // исключить pages/admin
                if (strpos($info->getPathname(), DATA_DIR . 'pages' . DIRECTORY_SEPARATOR  . 'admin' . DIRECTORY_SEPARATOR) === FALSE)
                    $files[] = $info->getPathname();
            }
        }

        if ($mode == 1) {
            // убираем те, которые начинаются с «.»
            $files = array_filter($files, function ($x) {
                return (strpos(basename($x), '.') === 0) ? false : true;
            });
        }

        if ($mode == 2) {
            // убираем те, которые начинаются с «.»
            $files = array_filter($files, function ($x) {
                return (strpos(basename($x), '.') === 0) ? false : true;
            });

            // оставляем только файлы начинающиеся с «_»
            $files = array_filter($files, function ($x) {
                return (strpos(basename($x), '_') === 0) ? true : false;
            });
        }
    }

    if ($files) {
        echo $header;

        foreach ($files as $file) {
            $file1 = str_replace(DATA_DIR, '', $file);
            $editUrl = SITE_URL . 'admin/edit/' . encodeURL64($file1);

            echo '<div class="mar5-tb pad5-b pad10-rl bor1 bor-gray100 bor-solid-b t90"><a class="im-edit" href="' . $editUrl . '">' . str_replace('\\', '/', $file1) . '</a></div>';
        }
    }
}
