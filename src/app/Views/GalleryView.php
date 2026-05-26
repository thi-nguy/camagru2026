<?php if ($msg = success('login')): ?>
  <div class="msg success" id="loginSuccess">
    <span>✓ </span><?= $msg ?>
  </div>
<?php endif ?>
<section class="view active" id="viewGallery">
  <div class="gallery-wrap">
    <div class="gallery-header">
      <span class="gallery-title">Recent photos</span>
      <span class="badge">5 posts</span>
    </div>
    <div class="gallery-grid" id="galleryGrid">
      <?php if (!empty($photos)): ?>
        <ul>
          <?php foreach ($photos as $photo): ?>
            <li>
              <h3><?= htmlspecialchars($photo['filename']) ?></h3>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <p>No photo yet. Be the first ever who post on our website</p>
      <?php endif; ?>
    </div>
    <div class="pagination" id="pagination"></div>
  </div>
</section>