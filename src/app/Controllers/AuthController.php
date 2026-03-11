<?php

function redirect(string $url) {
    header('Location: ' . $url);
    exit();
}

function sendConfirmEmail(string $toEmail, string $toName, string $token): bool {
   $mailUser = $_ENV['MAIL_USER'] ?? getenv('MAIL_USER');
   $hostName = $_ENV['HOST'] ?? getenv('HOST');
   $subject = "Confirm your Camagru's account";
   $message = 'Hello ' . $toName . "! Please confirm your email address by clicking this link: " . $hostName . '?=' . $token;
   $headers = "From: " . $mailUser;

   $result = mail($toEmail, $subject, $message, $headers);

   return $result;
}

class AuthController {
    public function showRegister() {
        render("AuthView");
    }

    public function register() {
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (!$username ) {
            $_SESSION['serverErrUser'] = 'Username is required';
            redirect("/register");
        } elseif (strlen($username) < 3) {
            $_SESSION['serverErrUser'] = 'Username must be at least 3 characters';
            redirect("/register");
        }
        if (!$email) {
            $_SESSION['serverErrEmail'] = 'Email is required';
            redirect("/register");
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            redirect("/register");
        }
        if (!$password ) {
            $_SESSION['serverErrPass'] = 'Password is required';
            redirect("/register");
        } elseif (strlen($password) < 8) {
            $_SESSION['serverErrPass'] = 'Password must be at least 8 characters';
            redirect("/register");
        }

        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
        $stmt->execute([':username' => $username, ':email' => $email]);
        $existUser = $stmt->fetch();

        if ($existUser) {
            $_SESSION['duplicateUserErr'] = 'Username or Email already used';
            redirect("/register");
        }

        $hashPass = password_hash($password, PASSWORD_BCRYPT);
        $confirmToken = bin2hex(random_bytes(32));
        $confirmTokenExpireAt = date("Y-m-d H:i:s", time() + 3 * 86400);

        $uuid = bin2hex(random_bytes(16));
        $uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split($uuid, 4));

        try {
            $stmt = $db->prepare("INSERT INTO users (id, username, email, password_hash, confirm_token, confirm_token_expires_at) VALUES (:uuid, :username, :email, :password_hash, :confirm_token, :confirm_token_expires_at)");
            $stmt->execute([
                ':uuid'         => $uuid,
                ':username'      => $username,
                ':email'         => $email,
                ':password_hash' => $hashPass,
                ':confirm_token'     => $confirmToken,
                ':confirm_token_expires_at' => $confirmTokenExpireAt
            ]);
            if ($stmt->rowCount() > 0) {
                $_SESSION['createAccountOk'] = 'Your account has been created';
                $sent = sendConfirmEmail($email, $username, $confirmToken);
                if (!$sent) {
                    error_log("Problem while sending confirm email to user ID: " . $uuid);
                    $_SESSION['createAccountNotOk'] = 'Can not send confirm email';
                } else {
                    $_SESSION['createAccountOk'] .= '. A confirmation email has been sent to you';
                }
                redirect("/register");
            } else {
                $_SESSION['createAccountNotOk'] = 'No new account is created';
                redirect("/register");
            }
        } catch (PDOException $e) {
            error_log("DB Error: " . $e->getMessage());
            $_SESSION['createAccountNotOk'] = 'Unable to create new account';
            redirect("/register");
        }
    }
}