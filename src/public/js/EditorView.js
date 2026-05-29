let VIDEO_STREAM;

async function startWebcam() {
  try {
    VIDEO_STREAM = await navigator.mediaDevices.getUserMedia({ video: true });
    const video = document.getElementById("webcam");
    video.srcObject = VIDEO_STREAM;
    video.style.display = "block";
    document.getElementById("webcamPlaceholder").style.display = "none";
    const captureButton = document.getElementById("captureBtn");
    captureButton.disabled = false;
    showToast("Camera enabled!", "success");
  } catch (e) {
    console.error(e);
    showToast("Camera access denied", "error");
  }
}

function capturePhoto() {
  console.log("capture called");
  const video = document.getElementById("webcam");
  const canvas = document.getElementById("preview");
  const context2d = canvas.getContext("2d");
  video.style.display = "none";
  canvas.style.display = "block";
  canvas.width = video.videoWidth;
  canvas.height = video.videoHeight;
  context2d.drawImage(video, 0, 0, video.videoWidth, video.videoHeight);
  stopMediaStream(VIDEO_STREAM);
  const captureButton = document.getElementById("captureBtn");
  captureButton.disabled = true;
  showToast("Screen captured!", "success");
}

function stopMediaStream(stream) {
  if (!stream) return;
  const tracks = stream.getTracks();
  tracks.forEach((track) => {
    track.stop();
  });

  const videoElement = document.getElementById("webcam");
  if (videoElement) {
    videoElement.srcObject = null;
  }
}
