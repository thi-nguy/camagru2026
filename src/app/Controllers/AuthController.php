<?php



class AuthController {
    public function __construct(private UserModel $userModel) {}

    public function showRegister() {
        render("AuthView");
    }

    public function register() {
        if (!isset($_SESSION['flash'])) {
            $_SESSION['flash'] = [
                'errors' => [],
                'old' => [],
                'success' => null,
                'info' => null,
                'warning' => null,
            ];
        }
        $flash = &$_SESSION['flash'];
        $flash['errors'] = []; 
        $errors = &$flash['errors'];

        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (!$username) {
            $errors['username'] = 'Username is required';
        } elseif (strlen($username) < 3) {
            $errors['username'] = 'Username must be at least 3 characters';
        }

        if (!$email) {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email is invalid';
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
            $flash['success'] = 'Your account has been created';
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
        } catch (DuplicateEntryException $e) {
            error_log("DB Error: " . $e->getMessage());
            $flash['warning'] = 'Unable to create new account';
            $flash['old'] = $_POST;
            redirect("/register");
        } catch (DatabaseException $e) {
            error_log("DB Error: " . $e->getMessage());
            $flash['warning'] = 'Unable to create new account';
            $flash['old'] = $_POST;
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
                            redirect("/login");
                        } else {
                            $this->userModel->removeConfirmToken($existUser['id']);
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

    public function login() {
        echo $_SESSION['confirmOk'];
        echo "You're at login page";
    }
}