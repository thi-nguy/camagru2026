/* ══════════════════════════════════════════════════════
   TOASTS
══════════════════════════════════════════════════════ */
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
