<?php

class AuthController {
    public function __construct(private UserModel $userModel) {}

    public function showRegister() {
        render("AuthView", ['activeTab' => 'register']);
    }

    public function register() {
        init_flash();
        $flash = &$_SESSION['flash'];
        $errors = &$flash['errors'];

        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (!$username) {
            $errors['username'] = 'Username is required';
        } elseif (strlen($username) < 3) {
            $errors['username'] = 'Username must be at least 3 characters';
        } elseif (strlen($username) > 50) {
            $errors['username'] = 'Username is too long';
        }

        if (!$email) {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email is invalid';
        } elseif(strlen($email) > 255){
            $errors['email'] = 'Email is too long';
        }

        if (!$password ) {
            $errors['password'] = 'Password is required';
        } elseif (strlen($password) < 8) {
            $errors['password'] = 'Password must be at least 8 characters';
        }

        if (!empty($errors)) {
            $flash['old'] = $_POST;
            redirect("/register");
        }

        try {
            $tokenGenerated = $this->userModel->insertNewUser($username, $email, $password);
            $flash['success']['accountCreated'] = 'Your account has been created';
            $isEmailSent = sendConfirmEmail($email, $username, $tokenGenerated);
            if ($isEmailSent) {
                $flash['info'] = 'A confirmation email has been sent to you. Please check your inbox';
            } else {
                error_log("Problem while sending confirm email to user's email: " . $email);
                $flash['warning'] = 'Can not send confirm email';
            }
            $flash['old'] = [];
            redirect('/register');

        } catch (DuplicateEmailException $e) {
            error_log("DB Error: " . $e->getMessage());
            $flash['warning'] = 'Email already used';
            $flash['old'] = $_POST;
            redirect("/register");
        } catch (DuplicateUsernameException $e) {
            error_log("DB Error: " . $e->getMessage());
            $flash['warning'] = 'Username already used';
            $flash['old'] = $_POST;
            redirect("/register");
        } catch (DuplicateEntryException | DatabaseException $e) {
            error_log("DB Error: " . $e->getMessage());
            $flash['warning'] = 'Unable to create new account';
            $flash['old'] = $_POST;
            redirect("/register");
        }
    }

    public function confirmEmail() {
        init_flash();
        $flash = &$_SESSION['flash'];

        if (isset($_GET['token'])) {
            $tokenFromUser = $_GET['token'] ?? '';
            if (preg_match('/^[a-f0-9]{64}$/', $tokenFromUser)) {
                $existUser = $this->userModel->findByConfirmToken($tokenFromUser);
                if ($existUser) {
                    if ($existUser['is_confirmed'] != 1) {
                        $expireDate = new DateTime($existUser['confirm_token_expires_at']);
                        $today = new DateTime();
                        if ($expireDate > $today) {
                            $this->userModel->confirmUser($existUser['id']);
                            $flash['success']['confirmEmail'] = 'Successfully confirm your account. You can login now.';
                            redirect("/login");
                        } else {
                            $this->userModel->removeConfirmToken($existUser['id']);
                            $flash['warning'] = 'Your confirm link is expired.';
                            redirect("/expired-token");
                        }
                    } else {
                        $flash['warning'] = 'You have already confirmed your email. You can login now.';
                        redirect("/login");
                    }
                } else {
                    http_response_code(404);
                    $flash['warning'] = 'Your confirm link does not exist.';
                    redirect("/expired-token");
                }
            } else {
                http_response_code(400);
                exit('Invalid token format');
            }
        } else {
            http_response_code(404);
            exit('Token not found.');
        }
    }

    public function showExpiredToken() {
        render("expiredTokenView");
    }

    public function resendToken() { // ! Todo: flash message (bug when new token created and come back to the same page -> still got flash message from before?!)
        init_flash();
        $flash = &$_SESSION['flash'];
        $errors = &$flash['errors'];

        $email = $_POST['email'] ?? '';

        if (!$email) {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email is invalid';
        } elseif(strlen($email) > 255){
            $errors['email'] = 'Email is too long';
        }

        if (!empty($errors)) {
            $flash['old'] = $_POST;
            redirect("/expired-token");
        }

        try {
            $existUser = $this->userModel->insertNewToken($email);
            $flash['success']['recreateToken'] = 'A new token has been created';
            $isEmailSent = sendConfirmEmail($email, $existUser['username'], $existUser['confirm_token']);
            if ($isEmailSent) {
                $flash['info'] = 'An email with a new token has been sent to you. Please check your inbox';
            } else {
                error_log("Problem while sending a new token to user's email: " . $email);
                $flash['warning'] = 'Can not send new token to your email.';
            }
            $flash['old'] = [];
            redirect('/expired-token');
        } catch (UserNotFoundException $e) {
            error_log("DB Error: " . $e->getMessage());
            $flash['warning'] = 'Your account does not exist, please Sign Up.';
            $flash['old'] = $_POST;
            redirect("/expired-token");
        } catch (DatabaseException $e) {
            error_log("DB Error: " . $e->getMessage());
            $flash['warning'] = 'Unable to create new confirm link';
            $flash['old'] = $_POST;
            redirect("/expired-token");
        }
    }

    public function showLogin() {
        if (!isset($_SESSION['csrfToken'])) {
            $csrfToken = bin2hex(random_bytes(32));
            $_SESSION['csrfToken'] = $csrfToken;
        }
        render("AuthView", ['activeTab' => 'login', 'csrfToken' => $_SESSION['csrfToken']]);
    }

    public function handleLogin() {
        init_flash();
        $flash = &$_SESSION['flash'];
        $errors = &$flash['errors'];

        if (!hash_equals($_POST['csrfToken'] ?? '', $_SESSION['csrfToken'])) {
            http_response_code(403);
            exit();
        } 
        $existUser = $this->userModel->findByUsername($_POST['username']);
        if (!$existUser) {
            $errors['login'] = "Invalid username or password.";
            redirect('/login');
        } 
        if (!password_verify($_POST['password'], $existUser['password_hash'])) {
            $errors['login'] = "Invalid username or password.";
            redirect('/login');
        }
        if ($existUser['account_status'] !== 'active') {
            $flash['warning'] = 'Account not verified. Please check your email.';
            redirect('/login');
        } 
        session_regenerate_id(true);
        $_SESSION['id'] = $existUser['id'];
        unset($_SESSION['csrfToken']);
        $flash['success']['login'] = "Logged in successfully!";
        redirect('/gallery');
    }

    
}