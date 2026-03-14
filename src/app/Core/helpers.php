<?php

function render(string $view, array $data = []) {
    extract($data);

    $extraCssFile = '/css/' . pathinfo($view, PATHINFO_FILENAME) . '.css';
    $haveExtraCss = file_exists(__DIR__ . '/../../public' . $extraCssFile) ? $extraCssFile : null;

    $extraJsFile = '/js/' . pathinfo($view, PATHINFO_FILENAME) . '.js';
    $haveExtraJs = file_exists(__DIR__ . '/../../public' . $extraJsFile) ? $extraJsFile : null;

    require __DIR__ . '/../Views/layout/header.php';
    require('../app/Views/' . $view . '.php');
    require __DIR__ . '/../Views/layout/footer.php';
}

function loadEnv(string $path) {
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (!str_starts_with($line, '#')) {
            [$key, $value] = explode('=', $line, 2);
            $_ENV[$key] = $value;
        }
    }
}

function flashMessage(string $key): ?string {
    if(!isset($_SESSION['flash'][$key])) return null;
    $msg = htmlspecialchars($_SESSION['flash'][$key]);
    unset($_SESSION['flash'][$key]);
    return $msg;
}

function old($key, $default = '') {
    if (! isset($_SESSION['flash']['old'][$key])) return $default;
    $oldInfo = htmlspecialchars($_SESSION['flash']['old'][$key]);
    unset($_SESSION['flash']['old'][$key]);
    return $oldInfo;
}

function error($key) {
    if (! isset($_SESSION['flash']['errors'][$key])) return null;
    $flashMsg = htmlspecialchars($_SESSION['flash']['errors'][$key]);
    unset($_SESSION['flash']['errors'][$key]);
    return  $flashMsg;
}

function redirect(string $url) {
    header('Location: ' . $url);
    exit();
}

function sendConfirmEmail(string $toEmail, string $toName, string $token): bool {
   $camagruAdminEmail = $_ENV['MAIL_USER'] ?? getenv('MAIL_USER');
   $hostName = $_ENV['HOST'] ?? getenv('HOST');
   $confirmUrl = $hostName . '/confirm?token=' . $token;
   $subject = "Confirm your Camagru's account";

   $boundary = md5(uniqid(time()));

   $headers  = 'MIME-Version: 1.0' . "\r\n";
   $headers .= 'Content-Type: multipart/alternative; boundary="' . $boundary . '"' . "\r\n";
   $headers .= 'From: ' . $camagruAdminEmail . "\r\n";

   $plainText = 'Hello ' . htmlspecialchars($toName) . '!' . "\r\n"
           . 'Please confirm your email by copying this URL:' . "\r\n"
           . $confirmUrl;

    $html = '
           <html><body>
               <p>Hello ' . htmlspecialchars($toName) . '!</p>
               <p>Please confirm your email address:</p>
               <p><a href="' . $confirmUrl . '">Confirm Email Address</a></p>
               <p>Or copy this URL: ' . $confirmUrl . '</p>
           </body></html>';
    $message = "--{$boundary}\r\n"
           . "Content-Type: text/plain; charset=UTF-8\r\n\r\n"
           . $plainText . "\r\n\r\n"
           . "--{$boundary}\r\n"
           . "Content-Type: text/html; charset=UTF-8\r\n\r\n"
           . $html . "\r\n\r\n"
           . "--{$boundary}--";

   $result = mail($toEmail, $subject, $message, $headers);

   return $result;
}