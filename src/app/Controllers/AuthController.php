<?php

class AuthController {
    public function showRegister() {
        render("AuthView");
    }

    public function register() {
        echo $_POST['username'];
        echo $_POST['email'];
        echo $_POST['password'];
    }
}