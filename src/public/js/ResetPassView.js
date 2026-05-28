let currentStep = 1;
let countdownInterval = null;

/* ══ Navigation ══════════════════════════════════════ */
function goStep(n) {
  document.getElementById("step" + currentStep).classList.remove("active");
  currentStep = n;
  document.getElementById("step" + n).classList.add("active");
  updateDots();
}

function updateDots() {
  for (let i = 1; i <= 3; i++) {
    const dot = document.getElementById("dot" + i);
    dot.classList.remove("active", "done");
    if (i < currentStep) dot.classList.add("done");
    else if (i === currentStep) dot.classList.add("active");
  }
  // Hide dots on final step
  document.getElementById("stepIndicator").style.display =
    currentStep === 4 ? "none" : "flex";
}

function goToLogin() {
  showToast("Redirecting to Sign In…", "info");
  // In a real app: window.location.href = '/login';
}

/* ══ Step 1: Request reset ═══════════════════════════ */
function submitStep1() {
  const email = document.getElementById("emailInput").value.trim();
  if (!isValidEmail(email)) {
    document.getElementById("emailErr").classList.add("show");
    document.getElementById("emailInput").classList.add("error");
    return;
  }
  // Simulate: if email is "notfound@test.com", show error
  if (email.toLowerCase() === "notfound@test.com") {
    showStep1Error("No account found with this email address.");
    return;
  }
  document.getElementById("sentEmail").textContent = email;
  showToast("Reset link sent to " + email, "success");
  goStep(2);
}

function showStep1Error(msg) {
  document.getElementById("step1ErrorMsg").textContent = msg;
  document.getElementById("step1Error").classList.add("show");
  document.getElementById("emailInput").classList.add("error");
}

function clearStep1Error() {
  document.getElementById("step1Error").classList.remove("show");
  document.getElementById("emailInput").classList.remove("error");
  document.getElementById("emailErr").classList.remove("show");
}

function isValidEmail(e) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(e);
}

/* ══ Step 2: Email sent ══════════════════════════════ */
function simulateClickLink() {
  showToast("Opening reset link…", "info");
  setTimeout(() => goStep(3), 500);
}

function resendEmail() {
  const btn = document.getElementById("resendBtn");
  const cd = document.getElementById("countdownText");
  btn.disabled = true;
  btn.style.display = "none";
  cd.style.display = "inline";
  showToast("Email resent!", "success");
  let secs = 30;
  cd.textContent = "Resend in " + secs + "s";
  clearInterval(countdownInterval);
  countdownInterval = setInterval(() => {
    secs--;
    if (secs <= 0) {
      clearInterval(countdownInterval);
      btn.disabled = false;
      btn.style.display = "inline";
      cd.style.display = "none";
    } else {
      cd.textContent = "Resend in " + secs + "s";
    }
  }, 1000);
}

/* ══ Step 3: New password ════════════════════════════ */
function onNewPassInput(val) {
  updateStrength(val);
  validateConfirm();
}

function onConfirmInput(val) {
  validateConfirm();
}

function validateConfirm() {
  const np = document.getElementById("newPass").value;
  const cp = document.getElementById("confirmPass").value;
  const matchErr = document.getElementById("matchErr");
  const matchOk = document.getElementById("matchOk");
  const btn = document.getElementById("step3Btn");

  if (cp.length === 0) {
    matchErr.classList.remove("show");
    matchOk.style.display = "none";
    btn.disabled = true;
    return;
  }
  if (np === cp && np.length >= 8) {
    matchErr.classList.remove("show");
    matchOk.style.display = "block";
    btn.disabled = false;
  } else {
    matchErr.classList.add("show");
    matchOk.style.display = "none";
    btn.disabled = true;
  }
}

function submitStep3() {
  const np = document.getElementById("newPass").value;
  const cp = document.getElementById("confirmPass").value;
  if (np !== cp) {
    document.getElementById("step3ErrorMsg").textContent =
      "Passwords do not match.";
    document.getElementById("step3Error").classList.add("show");
    return;
  }
  if (np.length < 8) {
    document.getElementById("step3ErrorMsg").textContent =
      "Password must be at least 8 characters.";
    document.getElementById("step3Error").classList.add("show");
    return;
  }
  showToast("Password updated successfully!", "success");
  goStep(4);
}

/* ══ Password strength ═══════════════════════════════ */
function updateStrength(pass) {
  const checks = {
    "req-len": pass.length >= 8,
    "req-upper": /[A-Z]/.test(pass),
    "req-num": /[0-9]/.test(pass),
    "req-special": /[^A-Za-z0-9]/.test(pass),
  };
  let score = Object.values(checks).filter(Boolean).length;

  Object.entries(checks).forEach(([id, met]) => {
    document.getElementById(id).classList.toggle("met", met);
  });

  const bar = document.getElementById("strengthBar");
  const label = document.getElementById("strengthLabel");
  const levels = [
    { pct: "0%", color: "#ccc", text: "Enter a password" },
    { pct: "25%", color: "#ed4956", text: "Weak" },
    { pct: "50%", color: "#f39c12", text: "Fair" },
    { pct: "75%", color: "#3498db", text: "Good" },
    { pct: "100%", color: "#2ecc71", text: "Strong 💪" },
  ];
  const lvl = pass.length === 0 ? levels[0] : levels[Math.min(score, 4)];
  bar.style.width = lvl.pct;
  bar.style.background = lvl.color;
  label.textContent = lvl.text;
  label.style.color = lvl.color;
}

/* ══ Toggle password visibility ══════════════════════ */
function togglePass(inputId, btn) {
  const inp = document.getElementById(inputId);
  const showing = inp.type === "text";
  inp.type = showing ? "password" : "text";
  btn.innerHTML = showing
    ? `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`
    : `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>`;
}

/* ══ Keyboard ════════════════════════════════════════ */
document.addEventListener("keydown", (e) => {
  if (e.key === "Enter") {
    if (currentStep === 1) submitStep1();
    if (currentStep === 3) {
      const btn = document.getElementById("step3Btn");
      if (!btn.disabled) submitStep3();
    }
  }
});

/* ══ Toast ═══════════════════════════════════════════ */
function showToast(msg, type = "info") {
  const icons = { success: "✓", error: "✕", info: "ℹ" };
  const container = document.getElementById("toastContainer");
  const toast = document.createElement("div");
  toast.className = `toast ${type}`;
  toast.innerHTML = `<span class="toast-icon">${icons[type] || "ℹ"}</span> ${msg}`;
  container.appendChild(toast);
  setTimeout(() => {
    toast.classList.add("removing");
    setTimeout(() => toast.remove(), 220);
  }, 3000);
}
