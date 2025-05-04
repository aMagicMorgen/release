<?php
// index.php Markdown Reader v4.1
//AMMDr ver. 4.1 06.04.2025 Алексей Нечаев, г. Москва, +7(999)003-90-23, nechaev72@list.ru
/*
/////Для вывода ошибок на экран  ini_set('display_errors','on'); on || of
#print_r(function_exists('mb_internal_encoding')); //проверка 1-подключено, 0 - не подключено
error_reporting(E_ALL);
ini_set('display_errors','on');
mb_internal_encoding('UTF-8');
*/

// Конфигурация приложения
/*
$ammdr = "Алексей Нечаев - разработки и идеи в PHP, CSS, JS";
$ammdr_short = "PHP, CSS, JS";
$site_name = 'ВНИМАНИЕ!!! <br> Идеи мои свежие но Разработки не все рабочие';
$site_intro = '<<==ТЕМЫ СЛЕВА';
$menu_name = 'ТЕМА: ';
$files = 'МАТЕРИАЛЫ: ';
#$pageName = $_SESSION ['pageName'];//название страницы запуска
*/
$ammdr = "aMagic Markdown Site Tailwind";
$ammdr_short = "MDSite";
$site_name = 'САЙТ из файлов *.md';
$site_intro = 'ТЕМЫ СЛЕВА';
$menu_name = 'ТЕМА: ';
$files = 'МАТЕРИАЛЫ: ';

// Запуск генерации
#MDSite::generate();


require_once 'MDSiteMenu.php';

// Инициализация меню
$menu = new MDSiteMenu();

require_once 'MDSite.php';

 $contentDir = './';
 $ammdr_site_json = 'a-mdsite.json';
 $excludedDirs = ['.', '..', '.github', 'node_modules', 'assets', '_data', '_layouts', 'blog'];  // Исключаемые папки
 $previewLines = 10;
 
// Настройка параметров
MDSite::configure([
    'contentDir' => $contentDir, //__DIR__ . '/content',  // Папка с Markdown-файлами
    'outputFile' => $ammdr_site_json,  //__DIR__ . '/data/' . $ammdrite_json,  // Куда сохранить JSON
    'excludedDirs' => $excludedDirs,  // Исключаемые папки
    'previewLines' => $previewLines // Сколько строк брать для превью
]);

