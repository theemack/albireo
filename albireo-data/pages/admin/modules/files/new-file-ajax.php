<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

title: @new-file-ajax
description: 
slug: admin/new-file
method: AJAX
slug-static: -
layout: empty.php
parser: -
compress: 0
protect-pre: 0
init-file: pages/admin/core/_functions.php

 **/

// отсекаем всё, что без заголовка AJAX
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) exit('Error: AJAX only!');

// вывод входящего POST
// pr($_POST);

$file = $_POST['file'] ?? false;

if ($file === false) {
    exit('<div class="pad10 bg-red100 t-red600 bor-red bor1 bor-solid mar30-b rounded10"><i class="im-exclamation-triangle"></i>ERROR! Incorrect data</div>');
}

// подчистка для будущего имени
$command = $command0 = $file;

$command = str_replace('\\', '/', $command); // замены для windows
$command = str_replace(['<', '>', ':', '"',  '|', '?', '*'], '-', $command); // недопустимые символы
$command = str_replace('//', '/', $command); // двойные слэши
$command = trim($command, '/'); // удалим крайние слэши

if ($command0 !== $command) {
    exit('<div class="pad10 bg-red100 t-red600 bor-red bor1 bor-solid mar30-b rounded10"><i class="im-exclamation-triangle"></i>ERROR! File ' . $command0 . ' incorrect. Enter a new file name</div>');
}

$fn = DATA_DIR . $command . '.php'; // можно указывать файл без расширения .php
$fn = str_replace('.php.php', '.php', $fn); // а если указано, то исправляем

if (file_exists($fn)) {
    exit('<div class="pad10 bg-red100 t-red600 bor-red bor1 bor-solid mar30-b rounded10"><i class="im-exclamation-triangle"></i>ERROR! File ' . $command . ' already exists. Enter a new file name</div>');
} else {

    if (strpos($fn, '/') !== false) {
        $fn = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $fn);
        $path = pathinfo($fn);

        if (!file_exists($path['dirname'])) mkdir($path['dirname'], 0777, true);
    }

    file_put_contents($fn, '<?php if (!defined(\'BASE_DIR\')) exit(\'No direct script access allowed\');
/**

title: 
description: 
slug: 

**/
?>
');

    $edit = SITE_URL . 'admin/edit/' . encodeURL64(str_replace(DATA_DIR, '', $fn));

    echo '<div class="pad10 bg-green100 t-green600 bor-green bor1 bor-solid mar30-b rounded10"><i class="im-info-circle"></i>OK! File ' . $fn . ' create! <a href="' . $edit  . '">Edit file</a></div>';
}
