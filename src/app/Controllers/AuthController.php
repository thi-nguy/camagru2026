<?php



class AuthController {
    public function __construct(private UserModel $userModel) {}

    private function _init_flash() {
        if (!isset($_SESSION['flash'])) {
            $_SESSION['flash'] = [
                'errors' => [],
                'old' => [],
                'success' => [],
                'info' => null,
                'warning' => null,
            ];
        }        
    }

    public function showRegister() {
        render("AuthView", ['activeTab' => 'register']);
    }

    public function register() {
        $this->_init_flash();
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
        $this->_init_flash();
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
                    redirect("/login");
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

    public function showLogin() {
        render("AuthView", ['activeTab' => 'login']);
    }

    public function showExpiredToken() {
        render("expiredTokenView");
    }
}