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
  const overlay = document.getElementById("overlayLayer");
  overlay.style.display = "flex";
  overlay.style.justifyContent = "center";
  overlay.style.alignItems = "center";
  const overlayPreview = document.getElementById("overlayPreview");
  overlayPreview.src = url;
  if (VIDEO_STREAM) {
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

function handleUploadImage(e) {
  const file = e.target.files[0];
  if (!file) return;
  if (!file.type.startsWith("image/")) {
    showToast("Please select an image!");
    return;
  }
  const imageURL = URL.createObjectURL(file);
  const imagePreview = document.getElementById("imagePreview");
  imagePreview.src = imageURL;
  document.getElementById("imagePlaceholder").style.display = "flex";
  document.getElementById("webcamPlaceholder").style.display = "none";
}

function capturePhoto() {
  const context2d = CANVAS.getContext("2d");
  WEBCAM.style.display = "none";
  CANVAS.style.display = "block";
  CANVAS.width = WEBCAM.videoWidth;
  CANVAS.height = WEBCAM.videoHeight;
  context2d.drawImage(WEBCAM, 0, 0, WEBCAM.videoWidth, WEBCAM.videoHeight);
  // stopMediaStream(VIDEO_STREAM);
  // VIDEO_STREAM = null;
  // CAPTURE_BTN.disabled = true;
  showToast("Screen captured!", "success");
}

// function stopMediaStream(stream) {
//   if (!stream) return;
//   const tracks = stream.getTracks();
//   tracks.forEach((track) => {
//     track.stop();
//   });
//   if (WEBCAM) {
//     WEBCAM.srcObject = null;
//   }
// }

class ElementTransformer {
  constructor(element) {
    this.element = element;
    this.mode = "move"; // move, resize, rotate

    this.element.setAttribute("data-mode", this.mode);

    this.element.ondblclick = () => this.toggleMode();

    this.enableDrag();
  }

  toggleMode() {
    // Reset các sự kiện cũ
    this.element.onmousedown = null;

    if (this.mode === "move") {
      this.mode = "rotate";
      this.enableRotate();
    } else if (this.mode === "rotate") {
      this.mode = "resize";
      this.enableResize();
    } else {
      this.mode = "move";
      this.enableDrag();
    }
    this.element.setAttribute("data-mode", this.mode);
  }

  // --- LOGIC KÉO THẢ (DRAG) ---
  enableDrag() {
    let pos1 = 0,
      pos2 = 0,
      pos3 = 0,
      pos4 = 0;

    this.element.onmousedown = (e) => {
      e.preventDefault();
      pos3 = e.clientX;
      pos4 = e.clientY;
      document.onmousemove = (e) => {
        pos1 = pos3 - e.clientX;
        pos2 = pos4 - e.clientY;
        pos3 = e.clientX;
        pos4 = e.clientY;
        this.element.style.top = this.element.offsetTop - pos2 + "px";
        this.element.style.left = this.element.offsetLeft - pos1 + "px";
      };
      document.onmouseup = () => {
        document.onmousemove = null;
        document.onmouseup = null;
      };
    };
  }

  // --- LOGIC THAY ĐỔI KÍCH THƯỚC (RESIZE) ---
  enableResize() {
    let size = this.element.clientWidth;
    let pos3 = 0;

    this.element.onmousedown = (e) => {
      e.preventDefault();
      pos3 = e.clientX;
      document.onmousemove = (e) => {
        let delta = e.clientX - pos3;
        size += delta;
        this.element.style.width = size + "px";
        pos3 = e.clientX;
      };
      document.onmouseup = () => {
        document.onmousemove = null;
        document.onmouseup = null;
      };
    };
  }

  // --- LOGIC XOAY (ROTATE) ---
  enableRotate() {
    let angle = 0;
    let pos4 = 0;

    this.element.onmousedown = (e) => {
      e.preventDefault();
      pos4 = e.clientY;
      document.onmousemove = (e) => {
        let delta = pos4 - e.clientY;
        angle -= delta;
        this.element.style.transform = `rotate(${angle}deg)`;
        pos4 = e.clientY;
      };
      document.onmouseup = () => {
        document.onmousemove = null;
        document.onmouseup = null;
      };
    };
  }
}

window.onload = () => {
  const transformableImages = document.querySelectorAll("img.transformable");

  transformableImages.forEach((img) => {
    new ElementTransformer(img);
  });
};
