<!-- ── Scroll to Top Button ── -->
<button id="scrollTop" title="Kembali ke atas">
  <i class="bi bi-arrow-up"></i>
</button>

<!-- ── Album Modal ── -->
<div class="modal fade" id="albumModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content bg-transparent border-0">
      <div class="modal-header border-0 pb-0 justify-content-end">
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
      </div>
      <div class="modal-body p-0 text-center">
        <div id="albumCarousel" class="carousel slide" data-bs-ride="false">
          <div class="carousel-inner" id="carouselInner" style="border-radius: 12px; overflow: hidden; background: rgba(0,0,0,0.8);"></div>
          <button class="carousel-control-prev" type="button" data-bs-target="#albumCarousel" data-bs-slide="prev" style="width: 5%;">
            <span class="carousel-control-prev-icon"></span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#albumCarousel" data-bs-slide="next" style="width: 5%;">
            <span class="carousel-control-next-icon"></span>
          </button>
        </div>
        <h5 id="albumTitle" class="text-white mt-3 fw-bold"></h5>
      </div>
    </div>
  </div>
</div>

<!-- ── Footer ── -->
<footer class="pt-5 pb-3">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-4">
        <div class="footer-brand mb-2"><?= e(SITE_NAME) ?></div>
        <p class="small"><?= e(SITE_SLOGAN) ?></p>
        <div class="d-flex gap-3 mt-3">
          <?php
          $db = getDB();
          $fb  = $db->query("SELECT nilai FROM kontak WHERE kunci='facebook'")->fetchColumn();
          $ig  = $db->query("SELECT nilai FROM kontak WHERE kunci='instagram'")->fetchColumn();
          $yt  = $db->query("SELECT nilai FROM kontak WHERE kunci='youtube'")->fetchColumn();
          ?>
          <?php if($fb): ?><a href="<?= e($fb) ?>" target="_blank"><i class="bi bi-facebook fs-5"></i></a><?php endif; ?>
          <?php if($ig): ?><a href="<?= e($ig) ?>" target="_blank"><i class="bi bi-instagram fs-5"></i></a><?php endif; ?>
          <?php if($yt): ?><a href="<?= e($yt) ?>" target="_blank"><i class="bi bi-youtube fs-5"></i></a><?php endif; ?>
        </div>
      </div>
      <div class="col-lg-2 col-6">
        <h6 class="text-white fw-600 mb-3">Navigasi</h6>
        <ul class="list-unstyled small">
          <li class="mb-1"><a href="index.php#beranda">Beranda</a></li>
          <li class="mb-1"><a href="index.php#profil">Profil</a></li>
          <li class="mb-1"><a href="index.php#program">Program</a></li>
          <li class="mb-1"><a href="index.php#galeri">Galeri</a></li>
          <li class="mb-1"><a href="index.php#kontak">Kontak</a></li>
        </ul>
      </div>
      <div class="col-lg-3 col-6">
        <h6 class="text-white fw-600 mb-3">Program</h6>
        <ul class="list-unstyled small">
          <li class="mb-1"><a href="#">Iqra & Al-Quran</a></li>
          <li class="mb-1"><a href="#">Tahfidz Al-Quran</a></li>
          <li class="mb-1"><a href="#">Tilawah & Tajwid</a></li>
          <li class="mb-1"><a href="#">Akhlak & Adab</a></li>
          <li class="mb-1"><a href="#">Kaligrafi Islam</a></li>
        </ul>
      </div>
      <div class="col-lg-3">
        <h6 class="text-white fw-600 mb-3">Kontak Cepat</h6>
        <?php
        $wa   = $db->query("SELECT nilai FROM kontak WHERE kunci='whatsapp'")->fetchColumn();
        $waT  = $db->query("SELECT nilai FROM kontak WHERE kunci='whatsapp_text'")->fetchColumn();
        $email= $db->query("SELECT nilai FROM kontak WHERE kunci='email'")->fetchColumn();
        ?>
        <p class="small mb-1"><i class="bi bi-whatsapp me-2 text-success"></i><?= e($waT ?: '') ?></p>
        <p class="small mb-1"><i class="bi bi-envelope me-2" style="color:var(--gold)"></i><?= e($email ?: '') ?></p>
        <a href="https://wa.me/<?= e($wa ?: '') ?>" target="_blank"
           class="btn btn-sm btn-wa mt-2">
          <i class="bi bi-whatsapp me-1"></i> Chat WhatsApp
        </a>
      </div>
    </div>
    <hr class="border-secondary mt-4">
    <div class="row align-items-center">
      <div class="col-md-6 small text-center text-md-start">
        &copy; <?= date('Y') ?> <?= e(SITE_NAME) ?>. Hak Cipta Dilindungi.
      </div>
      <div class="col-md-6 small text-center text-md-end">
        Dibuat dengan <i class="bi bi-heart-fill text-danger"></i> untuk generasi Qurani
      </div>
    </div>
  </div>
</footer>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Scroll to top
  const scrollBtn = document.getElementById('scrollTop');
  window.addEventListener('scroll', () => {
    scrollBtn.style.display = window.scrollY > 400 ? 'flex' : 'none';
  });
  scrollBtn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

  // Active nav link on scroll
  const sections = document.querySelectorAll('section[id]');
  const navLinks  = document.querySelectorAll('.nav-link');
  window.addEventListener('scroll', () => {
    let current = '';
    sections.forEach(s => {
      if (window.scrollY >= s.offsetTop - 100) current = s.id;
    });
    navLinks.forEach(l => {
      l.classList.remove('active');
      if (l.getAttribute('href')?.includes(current)) l.classList.add('active');
    });
  });

  // Album Carousel Modal
  function openAlbum(el) {
    const title = el.getAttribute('data-title');
    const photos = el.getAttribute('data-photos').split(',');
    const inner = document.getElementById('carouselInner');
    document.getElementById('albumTitle').textContent = title;
    inner.innerHTML = '';
    
    photos.forEach((foto, i) => {
      const div = document.createElement('div');
      div.className = 'carousel-item ' + (i === 0 ? 'active' : '');
      div.innerHTML = `<img src="uploads/${foto}" class="d-block w-100" style="max-height: 85vh; object-fit: contain;">`;
      inner.appendChild(div);
    });
    
    const albumModal = new bootstrap.Modal(document.getElementById('albumModal'));
    albumModal.show();
  }

  // Counter animation
  function animateCounter(el) {
    const target = parseInt(el.dataset.target);
    let count = 0;
    const step = Math.ceil(target / 60);
    const timer = setInterval(() => {
      count = Math.min(count + step, target);
      el.textContent = count + (el.dataset.suffix || '');
      if (count >= target) clearInterval(timer);
    }, 30);
  }
  const observer = new IntersectionObserver(entries => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        e.target.querySelectorAll('[data-target]').forEach(animateCounter);
        observer.unobserve(e.target);
      }
    });
  }, { threshold: 0.3 });
  document.querySelectorAll('.stats-bar').forEach(el => observer.observe(el));
</script>
</body>
</html>
