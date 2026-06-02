let VIDEO_STREAM;
const WEBCAM = document.getElementById("webcam");
const CANVAS = document.getElementById("preview");
const CAPTURE_BTN = document.getElementById("captureBtn");

// Event Delegation Technique
document
  .querySelector(".overlay-strip")
  .addEventListener("click", function (e) {
    const thumbnail = e.target.closest(".overlay-thumb");
    if (thumbnail) {
      const currentSelected = this.querySelector(".selected");
      if (currentSelected) {
        currentSelected.classList.remove("selected");
      }
      thumbnail.classList.add("selected");
      const url = thumbnail.getAttribute("data-url");
      handleSelectLayout(url);
    }
  });

function handleSelectLayout(url) {
  console.log("URL of the image is: ", url);
  const overlay = document.getElementById("overlayLayer");
  overlay.style.display = "flex";
  overlay.style.justifyContent = "center";
  overlay.style.alignItems = "center";
  const overlayPreview = document.getElementById("overlayPreview");
  overlayPreview.src = url;
  if (!VIDEO_STREAM) {
    CAPTURE_BTN.disabled = false;
  }
}

async function startWebcam() {
  try {
    VIDEO_STREAM = await navigator.mediaDevices.getUserMedia({ video: true });
    const video = document.getElementById("webcam");
    video.srcObject = VIDEO_STREAM;
    video.style.display = "block";
    document.getElementById("webcamPlaceholder").style.display = "none";
    showToast("Camera enabled!", "success");
    const overlay = document.getElementById("overlayLayer");
    if (overlay.style.display === "flex") {
      CAPTURE_BTN.disabled = false;
    } else {
      showToast("Choose Overlay to take photo!");
    }
  } catch (e) {
    console.error(e);
    showToast("Camera access denied", "error");
  }
}

function handleSelectImage(e) {
  const file = e.target.files[0];
  if (!file) return;
  if (!file.type.startsWith("image/")) {
    showToastt("Please select an image!");
    return;
  }
  const imageURL = URL.createObjectURL(file);
  const imagePreview = document.getElementById("imagePreview");
  imagePreview.src = imageURL;
}

function capturePhoto() {
  const context2d = CANVAS.getContext("2d");
  WEBCAM.style.display = "none";
  CANVAS.style.display = "block";
  CANVAS.width = WEBCAM.videoWidth;
  CANVAS.height = WEBCAM.videoHeight;
  context2d.drawImage(WEBCAM, 0, 0, WEBCAM.videoWidth, WEBCAM.videoHeight);
  stopMediaStream(VIDEO_STREAM);
  CAPTURE_BTN.disabled = true;
  showToast("Screen captured!", "success");
}

function stopMediaStream(stream) {
  if (!stream) return;
  const tracks = stream.getTracks();
  tracks.forEach((track) => {
    track.stop();
  });
  if (WEBCAM) {
    WEBCAM.srcObject = null;
  }
}
