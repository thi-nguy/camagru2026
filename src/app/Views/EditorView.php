<section class="view.active" id="viewEditor">
    <div class="editor-wrap">
        <h2 class="section-heading">Photo Editor</h2>
        <p class="section-sub">Capture a photo or upload one, then apply a fun overlay.</p>

        <!-- Auth gate -->
        <!-- <div id="editorAuthGate" class="auth-gate-notice" style="display:none">
            <div class="icon">🔒</div>
            <h2>Sign in required</h2>
            <p>You need to be logged in to use the editor.</p>
            <button class="btn btn-primary" onclick="navTo('auth')">Sign In / Register</button>
        </div> -->

        <!-- Editor content -->
        <div class="editor-layout" id="editorContent">
            <div class="editor-main">

                <!-- Webcam preview -->
                <div class="editor-section">
                    <div class="editor-label">Camera preview</div>
                    <div class="webcam-container" id="webcamContainer">
                        <div class="webcam-placeholder" id="webcamPlaceholder">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M23 7l-7 5 7 5V7z" />
                                <rect x="1" y="5" width="15" height="14" rx="2" />
                            </svg>
                            <p>Camera not active</p>
                            <button class="btn btn-ghost btn-sm" onclick="startWebcam()" style="margin-top:4px">Enable Camera</button>
                        </div>
                        <div class="webcam-placeholder" id="imagePlaceholder">
                            <img id="imagePreview" alt="Selected Image">
                        </div>
                        <video id="webcam" autoplay muted playsinline></video>
                        <canvas id="preview" class="view"></canvas>
                        <div class="webcam-overlay-layer" id="overlayLayer" style="display:none">
                            <div class="webcam-overlay-preview">
                                <img id="overlayPreview" alt="Selected Overlay">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Overlay selector -->
                <div class="editor-section">
                    <div class="editor-label">Choose overlay</div>
                    <div class="overlay-strip" id="overlayStrip">
                        <?php
                        $dirPath = __DIR__ . '/../../public/assets/overlay/';
                        $overlays = glob($dirPath . '*.{png,jpg}', GLOB_BRACE);
                        if ($overlays) {
                            foreach ($overlays as $file):
                                $filename = basename($file);
                                $url = '/assets/overlay/' . $filename; ?>
                                <div class="overlay-thumb" data-url="<?php echo $url; ?>">
                                    <img src=" <?php echo $url; ?>" alt="Overlay" style="width: 100%; height: auto; object-fit: cover;">
                                </div>
                        <?php
                            endforeach;
                        } else {
                            echo '<span>Error on loading overlay</span>';
                        }
                        ?>
                    </div>
                </div>

                <!-- Capture / upload -->
                <div class="capture-row">
                    <button class="btn btn-primary" disabled id="captureBtn" onclick="capturePhoto()">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <circle cx="12" cy="12" r="3" />
                            <path d="M20 7H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2zm-7-4H9l-2 4h10l-2-4z" />
                        </svg>
                        Capture
                    </button>
                    <label class="upload-label" for="fileUpload">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                            <polyline points="17 8 12 3 7 8" />
                            <line x1="12" y1="3" x2="12" y2="15" />
                        </svg>
                        Upload image
                    </label>
                    <input type="file" id="fileUpload" accept="image/*" onchange="handleSelectImage(event)">
                </div>
            </div>

            <!-- Sidebar: captured thumbnails -->
            <div class="editor-sidebar">
                <div class="sidebar-title">Captured photos</div>
                <div class="thumb-strip" id="thumbStrip">
                    <div class="thumb-empty" id="thumbEmpty">No photos yet.<br>Capture or upload to start.</div>
                </div>
            </div>
        </div>
    </div>
</section>