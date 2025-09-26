<?php
// Ambil base path (subfolder tempat index.php berada)
$scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$basePath   = $scriptName === '/' ? '' : $scriptName;

// Ambil URL path tanpa query string
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path       = rtrim(str_replace($basePath, '', $requestUri), '/');

// Normalisasi root
if ($path === '') {
    $path = '/';
}

switch (true) {
    // Halaman statis
    case ($path === '/'):
        require __DIR__ . '/../src/views/landingPage.php';
        break;

    case ($path === '/dashboard'):
        require __DIR__ . '/../src/views/dashboard.php';
        break;

    case ($path === '/register'):
        require __DIR__ . '/../src/controllers/kasirController.php';
        $controller = new KasirController();
        $controller->register();
        break;
        
    case ($path === '/login'):
        require __DIR__ . '/../src/controllers/kasirController.php';
        $controller = new KasirController();
        $controller->login();
        break;

    case ($path === '/forgetPassword'):
        require __DIR__ . '/../src/views/forgetPassword.php';
        break;

    case '/logout':
        require __DIR__ . '/../src/controllers/kasirController.php';
        $controller = new KasirController();
        $controller->logout();
        break;


    // 🔹 Route dinamis: /post/{id}
    case (preg_match('#^/post/([0-9]+)$#', $path, $matches)):
        $postId = $matches[1]; // ambil ID dari URL
        require __DIR__ . '/../src/views/post.php';
        break;

    // 🔹 Route dinamis: /user/{username}
    case (preg_match('#^/user/([a-zA-Z0-9_]+)$#', $path, $matches)):
        $username = $matches[1];
        require __DIR__ . '/../src/views/user.php';
        break;

    // Default: 404
    default:
        http_response_code(404);
        require __DIR__ . '/../src/views/404.php';
        break;
}

//Harusnya ke controller dulu, jangan langsung ke view
//Lalu controller yang panggil view
// switch (true) {
//     // Halaman statis
//     case ($path === '/'):
//         require __DIR__ . '/../src/controllers/PageController.php';
//         (new PageController())->landingPage();
//         break;

//     case ($path === '/about'):
//         require __DIR__ . '/../src/controllers/PageController.php';
//         (new PageController())->about();
//         break;

//     case ($path === '/register'):
//         require __DIR__ . '/../src/controllers/AuthController.php';
//         (new AuthController())->register();
//         break;

//     case ($path === '/login'):
//         require __DIR__ . '/../src/controllers/AuthController.php';
//         (new AuthController())->login();
//         break;

//     // Route dinamis: /post/{id}
//     case (preg_match('#^/post/([0-9]+)$#', $path, $matches)):
//         $postId = $matches[1];
//         require __DIR__ . '/../src/controllers/PostController.php';
//         (new PostController())->show($postId);
//         break;

//     // Default: 404
//     default:
//         http_response_code(404);
//         require __DIR__ . '/../src/views/404.php';
//         break;
// }

