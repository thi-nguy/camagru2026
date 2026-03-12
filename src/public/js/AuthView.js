function validateRegister() {
  const user = document.getElementById("regUser").value.trim();
  const email = document.getElementById("regEmail").value.trim();
  const pass = document.getElementById("regPass").value;
  const btn = document.getElementById("registerBtn");

  let valid = true;

  if (!user) {
    document.getElementById("errUser").textContent = "Username is required";
    valid = false;
  } else if (user.length < 3) {
    document.getElementById("errUser").textContent = "At least 3 characters";
    valid = false;
  } else {
    document.getElementById("errUser").textContent = "";
  }

  const emailOk = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  if (!email) {
    document.getElementById("errEmail").textContent = "Email is required";
    valid = false;
  } else if (!emailOk) {
    document.getElementById("errEmail").textContent = "Invalid email format";
    valid = false;
  } else {
    document.getElementById("errEmail").textContent = "";
  }

  if (!pass) {
    document.getElementById("errPass").textContent = "Password is required";
    valid = false;
  } else if (pass.length < 8) {
    document.getElementById("errPass").textContent = "At least 8 characters";
    valid = false;
  } else {
    document.getElementById("errPass").textContent = "";
  }

  btn.disabled = !valid;
  btn.style.opacity = valid ? "1" : "0.5";
  btn.style.cursor = valid ? "pointer" : "not-allowed";
}

function togglePassword(inputId, btn) {
  const input = document.getElementById(inputId);
  const isHidden = input.type === 'password';

  input.type = isHidden ? 'text' : 'password';
  btn.querySelector('.eye-icon').style.display     = isHidden ? 'none'  : '';
  btn.querySelector('.eye-off-icon').style.display = isHidden ? ''      : 'none';
  btn.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
}

function switchTab(tab) {
  document.getElementById('tabLogin').classList.toggle('active', tab === 'login');
  document.getElementById('tabRegister').classList.toggle('active', tab === 'register');
  document.getElementById('formLogin').classList.toggle('active', tab === 'login');
  document.getElementById('formRegister').classList.toggle('active', tab === 'register');
}
