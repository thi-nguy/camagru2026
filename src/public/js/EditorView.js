let VIDEO_STREAM;
const WEBCAM = document.getElementById("webcam");
const CAPTURE_BTN = document.getElementById("captureBtn");

// Event Delegation Technique
document
  .querySelector(".sticker-strip")
  .addEventListener("click", function (e) {
    const thumbnail = e.target.closest(".sticker-thumb");
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
  const sticker = document.getElementById("stickerLayer");
  sticker.style.display = "flex";
  sticker.style.justifyContent = "center";
  sticker.style.alignItems = "center";
  const stickerPreview = document.getElementById("stickerPreview");
  stickerPreview.src = url;
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
    const sticker = document.getElementById("stickerLayer");
    if (sticker.style.display === "flex") {
      CAPTURE_BTN.disabled = false;
    } else {
      showToast("Choose Sticker to take photo!");
    }
  } catch (e) {
    console.error(e);
    showToast("Camera access denied", "error");
  }
}

function handleUploadImageFromComputer(e) {
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
  WEBCAM.style.display = "none";
  CAPTURE_BTN.disabled = false;
}

function capturePhoto() {
  const canvas = document.createElement("canvas");
  canvas.width = WEBCAM.videoWidth / 4;
  canvas.height = WEBCAM.videoHeight / 4;
  const context2d = canvas.getContext("2d");
  if (
    // document.getElementById("webcamPlaceholder").style.display === "none" ||
    // WEBCAM.style.display === "none"
    false
  ) {
    context2d.drawImage(WEBCAM, 0, 0, canvas.width, canvas.height);
  } else {
    const uploadedImg = document.getElementById("imagePreview");
    context2d.drawImage(uploadedImg, 0, 0, canvas.width, canvas.height);
  }
  document.getElementById("thumbEmpty").style.display = "none";
  const thumbStrip = document.getElementById("thumbStrip");
  thumbStrip.appendChild(canvas);

  const sticker = document.getElementById("stickerPreview");
  context2d.drawImage(sticker, sticker.clientX, sticker.clientY);
}

class ElementTransformer {
  constructor(element) {
    this.element = element;
    this.mode = "move"; // move, resize, rotate
    this.element.setAttribute("data-mode", this.mode);
    this.element.ondblclick = () => this.toggleMode();
    this.enableDrag();
  }

  toggleMode() {
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
    let deltaX = 0,
      deltaY = 0,
      originalX = 0,
      originalY = 0;

    this.element.onmousedown = (e) => {
      e.preventDefault();
      originalX = e.clientX;
      originalY = e.clientY;
      document.onmousemove = (e) => {
        deltaX = originalX - e.clientX;
        deltaY = originalY - e.clientY;
        originalX = e.clientX;
        originalY = e.clientY;
        this.element.style.top = this.element.offsetTop - deltaY + "px";
        this.element.style.left = this.element.offsetLeft - deltaX + "px";
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
