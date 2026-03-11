<section class="view active" id="viewAuth">
  <div class="auth-wrap">
    <div class="auth-card">
      <div class="auth-logo">Camagru</div>

      <div class="tab-row">
        <button class="tab-btn" id="tabLogin" onclick="switchTab('login')">Sign In</button>
        <button class="tab-btn active" id="tabRegister" onclick="switchTab('register')">Register</button>
      </div>

      <!-- LOGIN FORM -->
      <form class="auth-form" id="formLogin" onsubmit="return submitLogin(event)">
        <div class="input-group">
          <label class="input-label" for="loginUser">Username</label>
          <input class="input-field" id="loginUser" type="text" placeholder="your_username" autocomplete="username">
        </div>
        <div class="input-group">
          <label class="input-label" for="loginPass">Password</label>
          <input class="input-field" id="loginPass" type="password" placeholder="••••••••" autocomplete="current-password">
        </div>
        <a class="forgot-link" href="#">Forgot password?</a>
        <button class="btn btn-primary" type="submit" style="width:100%;justify-content:center;padding:11px">Sign In</button>
        <div class="success-msg" id="loginSuccess">
          <span>✓</span> Logged in successfully!
        </div>
      </form>

      <!-- REGISTER FORM -->
      <form class="auth-form active" id="formRegister" method="POST" action="/register">
        <div class="input-group">
          <label class="input-label" for="regUser">Username</label>
          <input class="input-field" id="regUser" name="username" type="text" placeholder="choose_a_username" autocomplete="username" oninput="validateRegister()">
          <span class="field-error" id="errUser"><?php
  if(isset($_SESSION['serverErrUser'])) {
    echo htmlspecialchars($_SESSION['serverErrUser']);
    unset($_SESSION['serverErrUser']);
  }
?>
        </div>
        <div class="input-group">
          <label class="input-label" for="regEmail">Email</label>
          <input class="input-field" id="regEmail" name="email" type="email" placeholder="you@example.com" autocomplete="email" oninput="validateRegister()">
          <span class="field-error" id="errEmail"><?php
  if(isset($_SESSION['serverErrEmail'])) {
    echo htmlspecialchars($_SESSION['serverErrEmail']);
    unset($_SESSION['serverErrEmail']);
  }
?></span>
        </div>
        <div class="input-group">
          <label class="input-label" for="regPass">Password</label>
          <input class="input-field" id="regPass" name="password" type="password" placeholder="create a strong password" autocomplete="new-password" oninput="validateRegister()">
          <span class="field-error" id="errPass"><?php
  if(isset($_SESSION['serverErrPass'])) {
    echo htmlspecialchars($_SESSION['serverErrPass']);
    unset($_SESSION['serverErrPass']);  }
?></span>
        </div>
        <button class="btn btn-primary" type="submit" id="registerBtn" disabled style="width:100%;justify-content:center;padding:11px">Create Account</button>
       
        <?php if($msg = flashMessage('duplicateUserErr')): ?>
            <div class="msg fail" id="duplicateUserErr">
              <?= $msg ?>
          </div>
        <?php endif ?>
        <?php if($msg = flashMessage('createAccountOk')): ?>
            <div class="msg success" id="registerSuccess">
              <span>✓ </span><?= $msg ?>
          </div>
        <?php endif ?>
        <?php if($msg = flashMessage('createAccountNotOk')): ?>
            <div class="msg fail" id="registerFail">
              <?= $msg ?>
          </div>
        <?php endif ?>
      </form>
    </div>
  </div>
</section>