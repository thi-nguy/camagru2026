<section class="view active" id="viewAuth">
  <div class="auth-wrap">
    <div class="auth-card">
      <div class="auth-logo">Camagru</div>

      <div class="tab-row">
        <button class="tab-btn <?= $activeTab === 'login' ? 'active' : '' ?>" id="tabLogin" onclick="switchTab('login')">Sign In</button>
        <button class="tab-btn <?= $activeTab === 'register' ? 'active' : '' ?>" id="tabRegister" onclick="switchTab('register')">Register</button>
      </div>

      <!-- LOGIN FORM -->
      <form class="auth-form <?= $activeTab === 'login' ? 'active' : '' ?>" id="formLogin" onsubmit="return submitLogin(event)">
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
      <form class="auth-form <?= $activeTab === 'register' ? 'active' : '' ?>" id="formRegister" method="POST" action="/register">
        <div class="input-group">
          <label class="input-label" for="regUser">Username</label>
          <input 
            class="input-field" 
            id="regUser" 
            name="username" 
            type="text" 
            placeholder="choose_a_username" autocomplete="username" 
            oninput="validateRegister()"
            value="<?= old('username') ?>"
          >
          <span class="field-error" id="errUser">
            <?= error('username')?>
          </span>
        </div>
        <div class="input-group">
          <label class="input-label" for="regEmail">Email</label>
          <input 
            class="input-field" 
            id="regEmail" 
            name="email" 
            type="email" 
            placeholder="you@example.com" 
            autocomplete="email" 
            oninput="validateRegister()"
            value="<?= old('email') ?>"
          >
          <span class="field-error" id="errEmail">
            <?= error('email')?>
          </span>
        </div>
        <div class="input-group">
          <label class="input-label" for="regPass">Password</label>
          <div class="input-wrapper">
          <input 
            class="input-field" 
            id="regPass" 
            name="password" 
            type="password" 
            placeholder="create a strong password" autocomplete="new-password" 
            oninput="validateRegister()"
          >
          <button type="button" class="toggle-password" onclick="togglePassword('regPass', this)" aria-label="Show password" tabindex="-1">
            <svg class="eye-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
          <circle cx="12" cy="12" r="3"/>
        </svg>
      <svg class="eye-off-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none">
        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
        <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
        <line x1="1" y1="1" x2="23" y2="23"/>
      </svg>
    </button>
  </div>
  <span class="field-error" id="errPass">
    <?= error('password')?></span>
</div>
        <button class="btn btn-primary" type="submit" id="registerBtn" disabled style="width:100%;justify-content:center;padding:11px">Create Account</button>
       
        <?php if ($msg = flashMessage('success')): ?>
            <div class="msg success" id="registerSuccess">
              <span>✓ </span><?= $msg ?>
          </div>
        <?php endif ?>
        <?php if ($msg = flashMessage('info')): ?>
            <div class="msg success" id="sentEmailSuccess">
              <span>✓ </span><?= $msg ?>
          </div>
        <?php endif ?>
        <?php if ($msg = flashMessage('warning')): ?>
            <div class="msg fail" id="registerFail">
              <?= $msg ?>
          </div>
        <?php endif ?>
      </form>
    </div>
  </div>
</section>