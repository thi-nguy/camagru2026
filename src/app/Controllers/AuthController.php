<?php


class AuthController {
    public function __construct(private UserModel $userModel) {}

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

        $existUser = $this->userModel->findByEmailOrUsername($email, $username);

        if ($existUser) {
            $_SESSION['duplicateUserErr'] = 'Username or Email already used';
            redirect("/register");
        }
        try {
            if ($this->userModel->insertNewUser($username, $email, $password) > 0) {
                
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

    public function confirmEmail() {
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
                            $_SESSION['confirmOk'] = 'Successfully confirm your account. You can login now.';
                            redirect("/register");
                        } else {
                            $this->userModel->removeConfirmToken($existUser['id']);
                            http_response_code(410);
                            $_SESSION['expiredConfirmLink'] = 'Your confirm link is expired.';
                            // Todo: Button Resend confirmation email (new token will be created)
                            redirect("/register");
                        }
                    } else {
                        $_SESSION['alreadyConfirm'] = 'You have confirmed your email. You can login now.';
                        redirect("/register");
                    }
                } else {
                    http_response_code(404);
                    $_SESSION['notExistAccount'] = 'Your account does not exist.';
                    redirect("/register");
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
}