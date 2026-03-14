function validateEmail() {
    const email = document.getElementById("emailInput").value.trim();
    const btn = document.getElementById("sendBtn");
  
    let valid = true;
  
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
  
    btn.disabled = !valid;
    btn.style.cursor = valid ? "pointer" : "not-allowed";
  }