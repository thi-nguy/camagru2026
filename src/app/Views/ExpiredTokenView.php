<section class="page">

<div class="card">

    <!-- Logo -->
    <div class="auth-logo">Cam<span style="font-weight:300">agru</span></div>

    <!-- ── STATE: form ─────────────────────────── -->
    <div class="state active" id="stateForm">
    <div class="icon-zone">
      <div class="icon-ring" id="iconRing">
        <div class="icon-ring-bg" id="iconRingBg"></div>
        <div class="icon-inner" id="iconInner">⏰</div>
      </div>
    </div>

      <div class="alert alert-danger">
        <span class="alert-icon">⚠️</span>
        <div>
          <strong>Confirmation link expired</strong>
          Email confirmation links are only valid for <strong>3 days.</strong> Enter your email below to receive a new one.
        </div>
      </div>

      <!-- Email input -->
      <form class="form-group" method="POST" action="/expired-token">
          <div class="input-wrap">
        <label class="form-label" for="emailInput">Email address</label>
          <input
            class="input-field"
            name="email"
            type="email"
            id="emailInput"
            placeholder="you@example.com"
            autocomplete="email"
            oninput="validateEmail()"
          >
        </div>
        <div class="field-hint" id="errEmail"></div>
        <button class="btn btn-primary" type="submit" id="sendBtn" disabled>
          Send new confirmation link
        </button>
    </form>

    <?php if ($msg = success('recreateToken')): ?>
          <div class="msg success" id="recreateTokenSuccess">
            <span>✓ </span><?= $msg ?>
          </div>
        <?php endif ?>
        <?php if ($msg = flashMessage('info')): ?>
          <div class="msg success" id="resendTokenSuccess">
            <span>✓ </span><?= $msg ?>
          </div>
        <?php endif ?>
        <?php if ($msg = flashMessage('warning')): ?>
          <div class="msg fail" id="resendTokenFail">
            <?= $msg ?>
          </div>
        <?php endif ?>


      <div class="divider"><span>or</span></div>

      <button class="back-link" onclick="goHome()">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
        Back to sign in
      </button>
    </div>
  </div>
</section>