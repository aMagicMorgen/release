<?php
/**
 * aMagic Markdown Site - Main Site with Tailwind CSS
 */
 
 /////Для вывода ошибок на экран  ini_set('display_errors','on'); on || of
#print_r(function_exists('mb_internal_encoding')); //проверка 1-подключено, 0 - не подключено
error_reporting(E_ALL);
ini_set('display_errors','on');
mb_internal_encoding('UTF-8');

include 'ammdr-config.php';
$pageName = pathinfo(__FILE__, PATHINFO_BASENAME);//название этой страницы PATHINFO_FILENAME , PATHINFO_EXTENSION
session_start();
$_SESSION['pageName'] = $pageName;



/*
 require_once 'ammdrSite.php';
 $content = './';
 $ammdr_site_json = 'ammdr-site.json';
 */
 /*
// Настройка параметров
ammdrSite::configure([
    'contentDir' => $content, //__DIR__ . '/content',  // Папка с Markdown-файлами
    'outputFile' => $ammdrite_json,  //__DIR__ . '/data/' . $ammdrite_json,  // Куда сохранить JSON
    'excludedDirs' => ['.', '..', '.git', 'node_modules'],  // Исключаемые папки
    'previewLines' => 5  // Сколько строк брать для превью
]);
$ammdr = "aMagic Markdown Site";
$ammdr_short = "ammdrSite";
$site_name = 'САЙТ из файлов *.md';
$site_intro = 'ТЕМЫ СЛЕВА';
$menu_name = 'ТЕМА: ';
$files = 'МАТЕРИАЛЫ: ';

// Запуск генерации
#ammdrSite::generate();
*/
/*
$ammdr = "Алексей Нечаев - разработки и идеи в <a href='./'>PHP, CSS, JS</a>";
$ammdr_short = "<a href='./'>PHP, CSS, JS</a>";
$site_name = 'ВНИМАНИЕ!!! <br> Идеи мои свежие но Разработки не все рабочие';
$site_intro = '<<==ТЕМЫ СЛЕВА';
$menu_name = 'ТЕМА: ';
$files = 'МАТЕРИАЛЫ: ';
*/

 if(!file_exists($ammdr_site_json) OR isset($_GET['r']) AND $_GET['r'] == 1) {
	 MDSite:: generate();
header("Location: $pageName");
 }
// Load the JSON index
$jsonFile = $ammdr_site_json;
if (!file_exists($jsonFile)) {
    die("Error: ammdrSite.json not found. Сделайте разрешение записи в Вашей дериктории");
}

$content = json_decode(file_get_contents($jsonFile), true);
$menuItems = $content['content'] ?? [];


// Function to render Markdown (simplified)
function renderMarkdown($text) {
    // Simple Markdown to HTML conversion (for more features use a library like Parsedown)
    $text = htmlspecialchars($text);
    $text = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $text);
    $text = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $text);
    $text = preg_replace('/\[(.*?)\]\((.*?)\)/', '<a href="$2" class="text-blue-600 hover:text-blue-800 hover:underline">$1</a>', $text);
    $text = preg_replace('/\#\s(.*?)\n/', '<h1 class="text-2xl font-bold mt-6 mb-4">$1</h1>', $text);
    $text = preg_replace('/\#\#\s(.*?)\n/', '<h2 class="text-xl font-bold mt-5 mb-3">$1</h2>', $text);
    $text = preg_replace('/\#\#\#\s(.*?)\n/', '<h3 class="text-lg font-bold mt-4 mb-2">$1</h3>', $text);
    $text = nl2br($text);
    return $text;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $ammdr_short ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
	
	<!--link rel="stylesheet" href="assets/css/ammdr-tailwindcss.css" type="text/tailwindcss"-->
	<style type="text/tailwindcss">
	@layer utilities {
            .full-title { display: block; }
            .short-title { display: none; }
            @media (max-width: 640px) {
                .full-title { display: none; }
                .short-title { display: block; }
            }
            
            #main-nav {
                transform: translateX(-100%);
                @apply fixed top-0 left-0 h-full w-64 bg-gray-800 text-white z-50 transition-transform duration-300 ease-in-out;
            }
            
            #main-nav.active {
                transform: translateX(0);
            }
            
            .mobile-menu-btn span {
                @apply block w-6 h-0.5 bg-white mb-1 transition-all duration-300;
            }
            
            .mobile-menu-btn.active span:nth-child(1) {
                transform: rotate(45deg) translate(5px, 5px);
            }
            
            .mobile-menu-btn.active span:nth-child(2) {
                opacity: 0;
            }
            
            .mobile-menu-btn.active span:nth-child(3) {
                transform: rotate(-45deg) translate(5px, -5px);
            }
            
            .content-wrapper {
                @apply pl-0;
            }
            
            @media (min-width: 768px) {
                #main-nav {
                    transform: translateX(0);
                    @apply relative h-auto w-auto bg-transparent text-gray-800 z-auto;
                }
                
                .content-wrapper {
                    @apply pl-64;
                }
            }
        }
		
	#main-nav {
        scrollbar-width: thin;
        scrollbar-color: #3b82f6 #1f2937;
    }
    
    #main-nav::-webkit-scrollbar {
        width: 6px;
    }
    
    #main-nav::-webkit-scrollbar-track {
        background: #1f2937;
    }
    
    #main-nav::-webkit-scrollbar-thumb {
        background-color: #3b82f6;
        border-radius: 3px;
    }
    
    .menu-item a.active {
        background-color: #1d4ed8;
        color: white;
        font-weight: 500;
    }
	footer {
    grid-area: footer;
    background: #f8f9fa;
    padding: 1rem;
    border-top: 1px solid #e1e4e8;
    text-align: center;
    font-size: 0.9rem;
    color: #6c757d;
}
	</style>
	
</head>
<body class="bg-gray-100 max-h-screen">
    <header class="bg-red-600 text-white shadow-md bg-gradient-to-l from-red-400 to-blue-500">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <h1 class="text-xl font-bold">
                <span class="full-title"><a href="<?= $pageName ?>"><?= $ammdr ?></a></span>
                <span class="short-title"><a href="<?= $pageName ?>"><?= $ammdr_short ?></a></span>
            </h1>
            <div class="mobile-menu-btn md:hidden">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </header>
	
	<div class="flex h-screen">
    <!-- Навигация - фиксированная -->
    <!--nav id="main-nav" class="md:block fixed h-full overflow-y-auto w-64 bg-gray-800 text-white z-50">
            <div class="p-4 md:p-6">
                <div id="nav-controls" class="mb-6">
                    <h1 class="text-xl font-bold text-white md:text-gray-800">< ?= $menu_name; ?></h1>
                </div>
                <div class="space-y-2"-->
                    <!--?php foreach ($menuItems as $name => $item): ?>
                        <div class="menu-item">
                            <a href="#< ?= htmlspecialchars($name) ?>" 
                               class="block px-3 py-2 rounded hover:bg-blue-700 hover:text-white md:hover:bg-blue-100 md:hover:text-blue-800 transition">
                                < ?= htmlspecialchars($name) ?>
                            </a>
                        </div>
                    < ?php endforeach; ?>
                </div>
            </div>
        </nav-->

		<!-- Навигация - фиксированная -->
<nav id="main-nav" class="md:block fixed h-full overflow-y-auto w-64 bg-gradient-to-b from-white-800 to-gray-900 text-white z-50 shadow-xl">
    <div class="p-4 md:p-6">
        <div id="nav-controls" class="mb-6">
            <h1 class="text-xl font-bold text-white md:text-gray-800 flex items-center"><!--a href='?r=1'-->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <!--path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /-->
					<path d="M17 22V20H20V17H22V20.5C22 20.89 21.84 21.24 21.54 21.54C21.24 21.84 20.89 22 20.5 22H17M7 22H3.5C3.11 22 2.76 21.84 2.46 21.54C2.16 21.24 2 20.89 2 20.5V17H4V20H7V22M17 2H20.5C20.89 2 21.24 2.16 21.54 2.46C21.84 2.76 22 3.11 22 3.5V7H20V4H17V2M7 2V4H4V7H2V3.5C2 3.11 2.16 2.76 2.46 2.46C2.76 2.16 3.11 2 3.5 2H7M10.5 6C13 6 15 8 15 10.5C15 11.38 14.75 12.2 14.31 12.9L17.57 16.16L16.16 17.57L12.9 14.31C12.2 14.75 11.38 15 10.5 15C8 15 6 13 6 10.5C6 8 8 6 10.5 6M10.5 8C9.12 8 8 9.12 8 10.5C8 11.88 9.12 13 10.5 13C11.88 13 13 11.88 13 10.5C13 9.12 11.88 8 10.5 8Z"></path>
                </svg><!--/a-->
                <?= $menu_name; ?>
            </h1>
        </div>
        <div class="space-y-1">
            <?php foreach ($menuItems as $name => $item): ?>
                <div class="menu-item group relative">
                    <a href="#<?= htmlspecialchars($name) ?>" 
                       class="flex items-center px-4 py-3 rounded-lg hover:bg-green-700 hover:text-white md:hover:bg-green-100 md:hover:text-green-800 transition-all duration-300 transform hover:translate-x-1 hover:shadow-md">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-3 group-hover:bg-white transition"></span>
                        <span><?= htmlspecialchars($name) ?></span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-auto opacity-0 group-hover:opacity-100 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Дополнительный раздел (пример) -->
        <div class="mt-8 pt-4 border-t border-gray-700">
            <div class="menu-item">
                <a href="#" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 hover:text-white transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Контакты
                </a>
            </div>
        </div>
    </div>
</nav>

		<main class="flex-1 overflow-auto"> 
			<div class="mx-auto w-full  sm:px-6 md:px-8 lg:px-8 max-w-7xl">
				<div class="container mx-auto sm:px-6 md:px-8 py-6 w-full">
                    <div class="bg-white rounded-lg shadow-md md:p-4 sm:py-4 sm:px-0">
                        <h1 class="text-3xl font-bold text-gray-800 mb-4"><?= $site_name; ?></h1>
                        <p class="text-gray-600 mb-6"><?= $site_intro; ?></p>
                        
                        <?php foreach ($menuItems as $name => $item): ?>
    <section id="<?= htmlspecialchars($name) ?>" class="mb-12 p-6 rounded-xl shadow-lg bg-gradient-to-br from-white to-gray-50 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
        <!-- Заголовок секции с градиентом -->
		<?php if(!strpos($name, '.')) { ?>
        <div class="mb-6 p-4 rounded-lg bg-gradient-to-r from-blue-500 to-purple-600">
            <h1 class="text-3xl font-bold text-white">
                <?= $menu_name; ?>
                <a href="ammdr.php?md=<?= htmlspecialchars($name."/") ?>README.md" class="hover:text-blue-200 transition-colors duration-200">
                    <?= htmlspecialchars($name) ?>
                </a>
            </h1>
        </div>
        <?php } ?>
        <?php if ($item['type'] === 'directory'): ?>
            <!-- Контент директории -->
            <div class="directory-content bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-inner border border-gray-200/50">
                <?php if (isset($item['readme'])): ?>
                    <!-- Блок с содержанием -->
                    <div class="mb-4">
                        <div class="flex items-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="text-xl font-semibold text-gray-800">СОДЕРЖАНИЕ</h3>
                        </div>
                        
                        <div class="directory-preview bg-gray-50/50 p-5 rounded-lg mb-5 text-gray-700 border-l-4 border-blue-400 pl-4">
                            <?= renderMarkdown($item['preview'] ?? 'Нет доступного описания') ?>
                        </div>
                        
                        <a href="ammdr.php?md=<?= $name ?>/README.md" 
                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-400 to-blue-600 text-white rounded-lg hover:from-blue-500 hover:to-blue-700 transition-all duration-300 shadow hover:shadow-md">
                            <span>ЧИТАТЬ ВСЕ</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                <?php endif; ?>
                
                <!-- Список файлов -->
                <div class="mt-8">
                    <div class="flex items-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-800"><?= $files; ?></h3>
                    </div>
                    
                    <ul class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        <?php foreach ($item['children'] as $childName => $child): ?>
                            <?php if ($child['type'] === 'file'): ?>
                                <li>
                                    <a href="ammdr.php?md=<?= htmlspecialchars($name .'/'.$childName) ?>" 
                                       class="flex items-center p-3 bg-white border border-gray-200 rounded-lg hover:bg-green-50 hover:border-green-200 transition-all duration-200 shadow-sm hover:shadow-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span class="text-green-600 hover:text-green-800 truncate">
                                            <?= htmlspecialchars($childName) ?>
                                        </span>
                                    </a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php else: ?>
            <!-- Одиночный файл -->
            <div class="file-preview bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-inner border border-gray-200/50">
                <div class="flex items-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-800"><?= $name ?></h3>
                </div>
                
                <div class="prose max-w-none bg-gray-50/50 p-5 rounded-lg mb-5 text-gray-700 border-l-4 border-green-400 pl-4">
                    <?= renderMarkdown($item['preview'] ?? 'Нет доступного описания') ?>
                </div>
                
                <a href="./ammdr.php?md=<?= htmlspecialchars($item['path']) ?>" 
                   class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-400 to-green-600 text-white rounded-lg hover:from-green-500 hover:to-green-700 transition-all duration-300 shadow hover:shadow-md">
                    <span>ПОСМОТРЕТЬ ПОЛНОСТЬЮ</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        <?php endif; ?>
        
        <!-- Декоративный элемент внизу секции -->
        <div class="mt-6 pt-4 border-t border-gray-200/50 flex justify-end">
            <span class="text-xs text-gray-400">Последнее обновление: <?= date('d.m.Y H:i') ?></span>
        </div>
    </section>
<?php endforeach; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
<!-- Подвал документа -->
    <footer>
        <p>СГЕНЕРИРОВАННО PHP aMagic Markdown Site </p>
    </footer>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
	 // Добавляем класс active для текущего пункта меню
    document.querySelectorAll('.menu-item a').forEach(link => {
        if(link.href === window.location.href.split('#')[0] + '#' + link.getAttribute('href').split('#')[1]) {
            link.classList.add('active');
        }
    });
	
    $(document).ready(function() {
        // Меню
        $('.mobile-menu-btn').click(function() {
            $(this).toggleClass('active');
            $('#main-nav').toggleClass('active');
        });
        
        // Закрытие меню при клике вне его области
        $(document).click(function(e) {
            if (!$(e.target).closest('#main-nav').length && !$(e.target).closest('.mobile-menu-btn').length) {
                $('.mobile-menu-btn').removeClass('active');
                $('#main-nav').removeClass('active');
            }
        });
    });
    </script>
</body>
</html>