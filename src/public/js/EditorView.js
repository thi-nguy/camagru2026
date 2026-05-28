async function startWebcam() {
  try {
    const stream = await navigator.mediaDevices.getUserMedia({ video: true });
    const video = document.getElementById("webcam");
    video.srcObject = stream;
    video.style.display = "block";
    document.getElementById("webcamPlaceholder").style.display = "none";
    showToast("Camera enabled!", "success");
  } catch (e) {
    console.error(e);
    showToast("Camera access denied", "error");
  }
}
