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
        <?php foreach ($photos as $card): ?>
          <div class="card">
            <div class="card-img" onclick="openModal(${card.id})">
              <div class="card-img-placeholder" style="background:${card.bg}">
                <span style="font-size:56px">${card.emoji}</span>
              </div>
              <div class="card-img-overlay"></div>
            </div>
            <div class="card-body">
              <div class="card-user">
                <div class="avatar" style="background:${card.avatarColor}">${card.username.charAt(0).toUpperCase()}</div>
                <span class="card-username">${card.username}</span>
              </div>
              <div class="card-actions">
                <button class="heart-btn ${card.liked ? 'liked' : ''}" id="heart-${card.id}" onclick="toggleLike(event,${card.id})">
                  <svg width="17" height="17" viewBox="0 0 24 24" fill="${card.liked?'#ed4956':'none'}" stroke="${card.liked?'#ed4956':'currentColor'}" stroke-width="2">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                  </svg>
                  <span id="likeCount-${card.id}">${card.likes}</span>
                </button>
              </div>
              <div class="card-comment-preview">
                ${card.comments.length > 0
                ? `<strong>${card.comments[0].user}</strong> ${card.comments[0].text}`
                : '<span style="color:var(--text-light)">No comments yet</span>'}
              </div>
            </div>

          </div>



          <h3><?= htmlspecialchars($card['username']) ?></h3>
          <img src="/uploads/<?= $card['user_id'] ?>/<?= $card['id'] ?>.jpg" alt="User's photo">
        <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <p>No photo yet. Be the first ever who post on our website</p>
      <?php endif; ?>
    </div>
    <div class="pagination" id="pagination"></div>
  </div>
</section>