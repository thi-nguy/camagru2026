<!-- ─── MAIN ─────────────────────────────────────────── -->
<main class="auth-wrap">
  <div class="auth-card">
    <div class="auth-logo">Camagru</div>

    <!-- ══════════════════════════════════════
         STEP 1 — Request reset
    ══════════════════════════════════════ -->
    <div class="step " id="step1">
      <div class="step-icon-wrap blue">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#0095F6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
          <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
        </svg>
      </div>
      <h1 class="step-heading">Forgot your password?</h1>
      <p class="step-sub">No worries! Enter your email and we'll send you a link to reset your password.</p>

      <div class="error-alert" id="step1Error">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <span id="step1ErrorMsg">No account found with this email address.</span>
      </div>

      <div class="input-group">
        <label class="input-label" for="emailInput">Email address</label>
        <div class="input-wrapper">
          <span class="input-icon">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
          </span>
          <input class="input-field" id="emailInput" type="email" placeholder="you@example.com" autocomplete="email" oninput="clearStep1Error()">
        </div>
        <div class="input-hint err" id="emailErr">Please enter a valid email address.</div>
      </div>

      <div class="info-box">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" style="flex-shrink:0;margin-top:1px"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4m0-4h.01"/></svg>
        <span>The reset link expires in <strong>15 minutes</strong> for your security.</span>
      </div>

      <button class="btn btn-primary" style="width:100%;justify-content:center;padding:11px" onclick="submitStep1()" id="step1Btn">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
        Send Reset Link
      </button>

      <div class="auth-divider"><span>or</span></div>

      <button class="back-link" style="margin:0 auto;display:flex;" onclick="goToLogin()">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="m15 18-6-6 6-6"/></svg>
        Back to Sign In
      </button>
    </div>

    <!-- ══════════════════════════════════════
         STEP 2 — Email sent
    ══════════════════════════════════════ -->
    <div class="step" id="step2">
      <div class="step-icon-wrap green">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#2ecc71" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.15 12 19.79 19.79 0 0 1 1.1 3.4 2 2 0 0 1 3.07 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.09 8.91a16 16 0 0 0 5.99 5.99l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21 16z"/>
        </svg>
      </div>
      <h1 class="step-heading">Check your inbox</h1>
      <p class="step-sub">We sent a reset link to <strong id="sentEmail">your@email.com</strong>. It may take a few seconds to arrive.</p>

      <div class="email-card">
        <div class="email-card-icon">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
        </div>
        <div class="email-card-text">
          <div class="title">Password reset email</div>
          <div class="sub">Sent from noreply@camagru.app</div>
        </div>
      </div>

      <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:20px">
        <button class="btn btn-primary" style="width:100%;justify-content:center;padding:11px" onclick="simulateClickLink()">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
          Open Reset Link
        </button>
      </div>

      <div class="resend-row">
        Didn't receive it?
        <button class="resend-btn" id="resendBtn" onclick="resendEmail()">Resend email</button>
        <span class="countdown" id="countdownText" style="display:none;color:var(--text-light)"></span>
      </div>

      <div class="auth-divider" style="margin-top:20px"><span>or</span></div>

      <button class="back-link" style="margin:0 auto;display:flex;margin-top:4px" onclick="goStep(1)">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="m15 18-6-6 6-6"/></svg>
        Use a different email
      </button>
    </div>

    <!-- ══════════════════════════════════════
         STEP 3 — Set new password
    ══════════════════════════════════════ -->
    <div class="step" id="step3">
      <div class="step-icon-wrap blue">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#0095F6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
          <polyline points="9 12 11 14 15 10"/>
        </svg>
      </div>
      <h1 class="step-heading">Set new password</h1>
      <p class="step-sub">Choose a strong password you haven't used before.</p>

      <div class="error-alert" id="step3Error">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <span id="step3ErrorMsg">Passwords do not match.</span>
      </div>

      <div class="input-group">
        <label class="input-label" for="newPass">New Password</label>
        <div class="input-wrapper">
          <span class="input-icon">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
          </span>
          <input class="input-field" id="newPass" type="password" placeholder="Create a strong password" autocomplete="new-password" oninput="onNewPassInput(this.value)">
          <button class="toggle-pass" onclick="togglePass('newPass', this)" tabindex="-1" type="button" aria-label="Show password">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" id="eyeIcon1"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
        </div>
      </div>

      <button class="btn btn-primary" style="width:100%;justify-content:center;padding:11px;margin-top:4px" onclick="submitStep3()" id="step3Btn" disabled>
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
        Reset Password
      </button>
    </div>

    <!-- ══════════════════════════════════════
         STEP 4 — All done
    ══════════════════════════════════════ -->
    <div class="step active" id="step4">
      <div class="step-icon-wrap green" style="background:linear-gradient(135deg,#f0fff4,#dcfce7)">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#2ecc71" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
          <polyline points="22 4 12 14.01 9 11.01"/>
        </svg>
      </div>
      <h1 class="step-heading">Password updated!</h1>
      <p class="step-sub">Your password has been reset successfully. You can now sign in with your new password.</p>

      <div class="success-panel show" style="margin-bottom:24px">
        <div class="sp-icon">🎉</div>
        <p>Your account is now secure. <strong>Welcome back!</strong></p>
      </div>

      <button class="btn btn-primary" style="width:100%;justify-content:center;padding:11px" onclick="goToLogin()">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
        Sign In to Camagru
      </button>
    </div>

  </div><!-- /auth-card -->
</main>

