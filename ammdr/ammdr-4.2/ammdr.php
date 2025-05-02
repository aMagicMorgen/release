<?php
// index.php Markdown Reader v4.2
//AMMDr ver. 4.2 06.04.2025 Алексей Нечаев, г. Москва, +7(999)003-90-23, nechaev72@list.ru
/*
/////Для вывода ошибок на экран  ini_set('display_errors','on'); on || of
#print_r(function_exists('mb_internal_encoding')); //проверка 1-подключено, 0 - не подключено
error_reporting(E_ALL);
ini_set('display_errors','on');
mb_internal_encoding('UTF-8');
*/
include 'ammdr-config.php';
/*
require_once 'ammdrMenu.php';

// Конфигурация приложения
$ammdr = 'AMMDr ver. 4.2 - aMagic Markdown Reader';
$ammdr_short = 'AMMDr 4.2';

// Инициализация меню
$menu = new ammdrMenu();
*/
session_start();
$pageName = (isset($_SESSION ['pageName']))? $_SESSION ['pageName'] : './';

// Обработка AJAX запросов
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: text/html');
    
    $mode = $_POST['v'] ?? 'tree';
    $searchQuery = $_POST['search'] ?? '';
    $shouldScan = isset($_POST['scan']);
    
    // Принудительное сканирование если нужно
    if ($shouldScan) {
        $menu = new MarkdownMenu(); // Автоматически сканирует при создании
    }
    
    // Получаем файлы с учетом поиска
    $files = $searchQuery ? $menu->search($searchQuery) : $menu->getFiles();
    
    // Генерируем и возвращаем меню
    echo $menu->generateMenu($files, $mode);
    exit;
}

// Генерация основной страницы
$menuHtml = $menu->generateMenu($menu->getFiles(), 'tree');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $ammdr_short ?></title>
   
    <!-- Подключение CSS -->
	<link rel="stylesheet" href="assets/css/ammdr.css">
	 <script src="https://cdn.tailwindcss.com"></script>
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
                @apply fixed top-0 left-0 h-full w-64 bg-gray-100 z-50 transition-transform duration-300 ease-in-out;
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
		
	header {
		padding: 0rem;
	}
		
	#main-nav {
        scrollbar-width: thin;
        scrollbar-color: #3b82f6 #1f2937;
    }
    
    #main-nav::-webkit-scrollbar {
        width: 6px;
    }
    
    #main-nav::-webkit-scrollbar-track {
        background: #3b82f6;
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
	</style>
	<!--link rel="stylesheet" href="assets/css/ammdr-tailwindcss.css"-->
	    
    
    <!-- Подключение ZeroMD для рендеринга Markdown -->
    <script type="module" src="https://cdn.jsdelivr.net/gh/zerodevx/zero-md@2/dist/zero-md.min.js"></script>
    
    
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Шапка документа -->
    <header class="bg-red-600 text-white shadow-md bg-gradient-to-l from-red-400 to-blue-500">
	
        <div class="container mx-auto px-2  flex justify-between items-center">
            <h1 class="text-xl font-bold">
                <span class="full-title"><a href="<?= $pageName; ?>"><?= $ammdr ?></a></span>
                <span class="short-title"><a href="<?= $pageName; ?>"><?= $ammdr_short ?></a></span>
				
            </h1>
			
            <div class="mobile-menu-btn md:hidden">
                <span></span>
                <span></span>
                <span></span>
				
            </div>
        </div>
			<center><h1 id="md1" class="text-xl font-bold"></h1></center>
			
        <!--h1>
            <span class="full-title"><a href="< ?= $pageName; ?>">< ?= htmlspecialchars($ammdr) ?></a></span>
            <span class="short-title"><a href="< ?= $pageName; ?>">< ?= htmlspecialchars($ammdr_short) ?></a></span>
        </h1>
        <div class="mobile-menu-btn">
            <span></span>
            <span></span>
            <span></span>
        </div-->
    </header>
    
    <!-- Основная навигация -->
    <nav id="main-nav" >
        <!-- Панель управления навигацией -->
        <div id="nav-controls">
            <!-- Поле поиска -->
            <input type="search" id="search" class="form-control" placeholder="Поиск...">
        </div>
        
        <!-- Вторая панель с кнопками -->
        <div id="nav-controls">
            <!-- Кнопка сканирования -->
            <button class="nav-btn" id="scan-btn" title="Пересканировать всю директорию">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                    <path d="M17 22V20H20V17H22V20.5C22 20.89 21.84 21.24 21.54 21.54C21.24 21.84 20.89 22 20.5 22H17M7 22H3.5C3.11 22 2.76 21.84 2.46 21.54C2.16 21.24 2 20.89 2 20.5V17H4V20H7V22M17 2H20.5C20.89 2 21.24 2.16 21.54 2.46C21.84 2.76 22 3.11 22 3.5V7H20V4H17V2M7 2V4H4V7H2V3.5C2 3.11 2.16 2.76 2.46 2.46C2.76 2.16 3.11 2 3.5 2H7M10.5 6C13 6 15 8 15 10.5C15 11.38 14.75 12.2 14.31 12.9L17.57 16.16L16.16 17.57L12.9 14.31C12.2 14.75 11.38 15 10.5 15C8 15 6 13 6 10.5C6 8 8 6 10.5 6M10.5 8C9.12 8 8 9.12 8 10.5C8 11.88 9.12 13 10.5 13C11.88 13 13 11.88 13 10.5C13 9.12 11.88 8 10.5 8Z"/>
                </svg>
            </button>
            
            <!-- Кнопки переключения вида -->
            <button class="nav-btn active" data-view="tree" title="Древовидный список">
                <svg fill="none" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path clip-rule="evenodd" d="M9 1H1V9H9V6H11V20H15V23H23V15H15V18H13V6H15V9H23V1H15V4H9V1ZM21 3H17V7H21V3ZM17 17H21V21H17V17Z" fill="currentColor" fill-rule="evenodd"/></svg>
            </button>
            
            <button class="nav-btn" data-view="last-dirs" title="По папкам" style="color: #f39c12;">
                📂
            </button>
            
            <button class="nav-btn" data-view="flat" title="Только файлы *.md" style="color: #ca2ecc;">
                📄
            </button>
        </div>
        
        <!-- Контейнер для меню -->
        <div id="menu-container" class="md:block fixed mx-fixed overflow-y-auto z-50">
            <?= $menuHtml ?>
        </div>
    </nav>
    
    <!-- Основное содержимое -->
    <main class="flex-1 overflow-auto"> 
			<div class="mx-auto w-full  sm:px-6 md:px-8 lg:px-8 max-w-7xl">
				<div class="container mx-auto sm:px-6 md:px-8 py-6 w-full">
                    <div class="bg-white rounded-lg shadow-md md:p-4 sm:py-4 sm:px-0">
                        <h1 class="text-3xl font-bold text-gray-800 mb-4"><?= $site_name; ?></h1>
                        <p class="text-gray-600 mb-6"><?= $site_intro; ?></p>
        <!--div class="content-wrapper"-->
		<section class="mb-12 p-6 rounded-xl shadow-lg bg-gradient-to-br from-white to-gray-50 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
        <!-- Заголовок секции с градиентом -->
        <div class="mb-6 p-4 rounded-lg bg-gradient-to-r from-blue-500 to-purple-600">
            <h1 class="text-3xl font-bold text-white">
                <!-- ?= $menu_name; ?--> 
                
            <?php if (isset($_GET['md'])): ?>
                <?php
                $safeFile = basename($_GET['md']);
                if (pathinfo($safeFile, PATHINFO_EXTENSION) === 'md') {
					echo '<span id="md">ВЫБИРАЙТЕ ТЕМЫ В МЕНЮ СЛЕВА</span>
            </h1>
        </div></section>';
                    echo "<zero-md src='" . htmlspecialchars($_GET['md']) . "'></zero-md>";
                }?>
				<?php else: ?>
                <h1>Добро пожаловать!</h1>
                <p>Выберите документ из меню слева.</p>
            <?php endif; ?>
        <!--/div-->
		
		</div>
		</div>
		</div>
    </main>
    
    <!-- Подвал документа -->
    <footer>
        <p>СГЕНЕРИРОВАННО PHP aMagic Markdown Reader </p>
    </footer>
    
    <!-- Индикатор загрузки (изначально скрыт) -->
    <div class="loading">Загрузка...</div>
    <!-- Подключение jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Подключение основного JavaScript -->
    <script src="assets/js/ammdr.js"></script>
</body>
</html>
