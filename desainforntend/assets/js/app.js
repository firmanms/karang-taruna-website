
const menuBtn = document.querySelector('.menu-btn');
const navLinks = document.querySelector('.nav-links');
const dropdowns = document.querySelectorAll('.nav-dropdown');

// Mobile drawer toggle
menuBtn?.addEventListener('click', (e) => {
  e.stopPropagation();
  navLinks.classList.toggle('open');
});

// Dropdown click handler (useful for touch devices and mobile)
dropdowns.forEach(dropdown => {
  const toggle = dropdown.querySelector('.dropdown-toggle');
  toggle?.addEventListener('click', (e) => {
    e.stopPropagation();
    const isOpen = dropdown.classList.contains('is-open');
    dropdowns.forEach(d => d.classList.remove('is-open'));
    if (!isOpen) {
      dropdown.classList.add('is-open');
    }
  });
});

// Close nav & dropdowns when clicking links or outside
document.querySelectorAll('.dropdown-link, .nav-item').forEach(link => {
  link.addEventListener('click', () => {
    navLinks.classList.remove('open');
    dropdowns.forEach(d => d.classList.remove('is-open'));
  });
});

document.addEventListener('click', (e) => {
  if (!e.target.closest('.header')) {
    navLinks.classList.remove('open');
    dropdowns.forEach(d => d.classList.remove('is-open'));
  }
});

/* =========================================
   Hero Slider Logic
   ========================================= */
const heroSlides = document.querySelectorAll('.hero-slide');
const dots = document.querySelectorAll('.slider-dots .dot');
const prevBtn = document.getElementById('prevSlide');
const nextBtn = document.getElementById('nextSlide');
const sliderSection = document.getElementById('heroSlider');

let currentSlide = 0;
let slideInterval = null;

// Initialize background images from data-bg
heroSlides.forEach(slide => {
  if (slide.dataset.bg) {
    slide.style.background = slide.dataset.bg;
  }
});

function goToSlide(index) {
  heroSlides[currentSlide]?.classList.remove('active');
  dots[currentSlide]?.classList.remove('active');

  currentSlide = (index + heroSlides.length) % heroSlides.length;

  heroSlides[currentSlide]?.classList.add('active');
  dots[currentSlide]?.classList.add('active');
}

function nextSlide() {
  goToSlide(currentSlide + 1);
}

function prevSlide() {
  goToSlide(currentSlide - 1);
}

// Event Listeners
nextBtn?.addEventListener('click', () => {
  nextSlide();
  resetAutoPlay();
});

prevBtn?.addEventListener('click', () => {
  prevSlide();
  resetAutoPlay();
});

dots.forEach(dot => {
  dot.addEventListener('click', (e) => {
    const idx = parseInt(e.target.dataset.index, 10);
    goToSlide(idx);
    resetAutoPlay();
  });
});

// Auto Play & Pause on Hover
function startAutoPlay() {
  if (!slideInterval) {
    slideInterval = setInterval(nextSlide, 5500);
  }
}

function stopAutoPlay() {
  if (slideInterval) {
    clearInterval(slideInterval);
    slideInterval = null;
  }
}

function resetAutoPlay() {
  stopAutoPlay();
  startAutoPlay();
}

sliderSection?.addEventListener('mouseenter', stopAutoPlay);
sliderSection?.addEventListener('mouseleave', startAutoPlay);

// Touch Swipe Support for Mobile
let startX = 0;
sliderSection?.addEventListener('touchstart', (e) => {
  startX = e.touches[0].clientX;
  stopAutoPlay();
}, { passive: true });

sliderSection?.addEventListener('touchend', (e) => {
  const diffX = e.changedTouches[0].clientX - startX;
  if (diffX > 40) {
    prevSlide();
  } else if (diffX < -40) {
    nextSlide();
  }
  startAutoPlay();
}, { passive: true });

// Start slider
startAutoPlay();

/* =========================================
   Program Landscape Poster Slider
   ========================================= */
const programWrapper = document.querySelector('.program-slider-wrapper');
const prevProgramBtn = document.getElementById('prevProgram');
const nextProgramBtn = document.getElementById('nextProgram');

if (programWrapper) {
  const getScrollAmount = () => {
    const poster = programWrapper.querySelector('.program-poster');
    return poster ? poster.offsetWidth + 22 : 400;
  };

  nextProgramBtn?.addEventListener('click', () => {
    programWrapper.scrollBy({ left: getScrollAmount(), behavior: 'smooth' });
  });

  prevProgramBtn?.addEventListener('click', () => {
    programWrapper.scrollBy({ left: -getScrollAmount(), behavior: 'smooth' });
  });

  // Mouse drag support for smooth scrolling
  let isDown = false;
  let startPageX;
  let scrollLeft;

  programWrapper.addEventListener('mousedown', (e) => {
    isDown = true;
    programWrapper.style.cursor = 'grabbing';
    startPageX = e.pageX - programWrapper.offsetLeft;
    scrollLeft = programWrapper.scrollLeft;
  });

  programWrapper.addEventListener('mouseleave', () => {
    isDown = false;
    programWrapper.style.cursor = 'default';
  });

  programWrapper.addEventListener('mouseup', () => {
    isDown = false;
    programWrapper.style.cursor = 'default';
  });

  programWrapper.addEventListener('mousemove', (e) => {
    if (!isDown) return;
    e.preventDefault();
    const x = e.pageX - programWrapper.offsetLeft;
    const walk = (x - startPageX) * 1.5;
    programWrapper.scrollLeft = scrollLeft - walk;
  });
}

/* =========================================
   Visitor Counter Dynamic Simulation
   ========================================= */
const visitorOnline = document.getElementById('visitorOnline');
if (visitorOnline) {
  setInterval(() => {
    // Random subtle online count fluctuation (15-28)
    const count = Math.floor(Math.random() * 12) + 16;
    visitorOnline.innerHTML = `<span class="pulse-dot"></span> ${count} User`;
  }, 7000);
}

/* =========================================
   FAQ Accordion Logic
   ========================================= */
const faqItems = document.querySelectorAll('.faq-item');
faqItems.forEach(item => {
  const question = item.querySelector('.faq-question');
  question?.addEventListener('click', () => {
    const isActive = item.classList.contains('active');
    // Close all other items
    faqItems.forEach(i => i.classList.remove('active'));
    // Toggle clicked item
    if (!isActive) {
      item.classList.add('active');
    }
  });
});
/* =========================================
   Photo Gallery Category Filter Logic
   ========================================= */
const filterBtns = document.querySelectorAll('.gallery-filters .filter-btn');
const photoCards = document.querySelectorAll('.photo-gallery-grid .photo-card');

filterBtns.forEach(btn => {
  btn.addEventListener('click', () => {
    filterBtns.forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const filter = btn.dataset.filter;
    photoCards.forEach(card => {
      if (filter === 'all' || card.dataset.category === filter) {
        card.style.display = 'block';
      } else {
        card.style.display = 'none';
      }
    });
  });
});

/* =========================================
   Download Center Search & Category Filter
   ========================================= */
const downloadSearch = document.getElementById('downloadSearch');
const downloadCategory = document.getElementById('downloadCategory');
const downloadRows = document.querySelectorAll('#downloadTableBody tr');

function filterDownloads() {
  const query = downloadSearch ? downloadSearch.value.toLowerCase().trim() : '';
  const cat = downloadCategory ? downloadCategory.value : 'all';

  downloadRows.forEach(row => {
    const text = row.textContent.toLowerCase();
    const rowCat = row.dataset.cat || '';
    const matchQuery = text.includes(query);
    const matchCat = cat === 'all' || rowCat === cat;

    if (matchQuery && matchCat) {
      row.style.display = '';
    } else {
      row.style.display = 'none';
    }
  });
}

/* =========================================
   Program Matrix Search & Category Filter
   ========================================= */
const matrixSearch = document.getElementById('programMatrixSearch');
const matrixBtns = document.querySelectorAll('.matrix-filters .matrix-btn');
const matrixRows = document.querySelectorAll('#matrixTableBody tr');
const currentSeksiLabel = document.getElementById('currentSeksiLabel');

let activeSeksi = 'all';

function filterMatrix() {
  const query = matrixSearch ? matrixSearch.value.toLowerCase().trim() : '';

  matrixRows.forEach(row => {
    const text = row.textContent.toLowerCase();
    const rowSeksi = row.dataset.seksi || '';
    const matchQuery = text.includes(query);
    const matchSeksi = activeSeksi === 'all' || rowSeksi === activeSeksi;

    if (matchQuery && matchSeksi) {
      row.style.display = '';
    } else {
      row.style.display = 'none';
    }
  });
}

matrixBtns.forEach(btn => {
  btn.addEventListener('click', () => {
    matrixBtns.forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    activeSeksi = btn.dataset.seksi;
    if (currentSeksiLabel) {
      if (activeSeksi === 'all') {
        currentSeksiLabel.textContent = 'Semua Bidang & Program Kerja';
      } else {
        currentSeksiLabel.textContent = `Seksi / Bidang: ${btn.textContent}`;
      }
    }
    filterMatrix();
  });
});

matrixSearch?.addEventListener('input', filterMatrix);

/* =========================================
   Berita List Search & Filter (Pusat / Daerah)
   ========================================= */
const newsFilterBtns = document.querySelectorAll('.news-category-filters button[data-news-filter]');
const newsCards = document.querySelectorAll('#newsGridList .news-card-item');
const featuredBanner = document.querySelector('.featured-news-banner');
const newsSearchInput = document.getElementById('newsSearchInput');

let activeNewsFilter = 'all';

function filterNews() {
  const query = newsSearchInput ? newsSearchInput.value.toLowerCase().trim() : '';

  // Filter featured banner
  if (featuredBanner) {
    const featCat = featuredBanner.dataset.category || '';
    const featText = featuredBanner.textContent.toLowerCase();
    const matchCat = activeNewsFilter === 'all' || featCat === activeNewsFilter;
    const matchQuery = featText.includes(query);
    featuredBanner.style.display = (matchCat && matchQuery) ? 'grid' : 'none';
  }

  // Filter grid cards
  newsCards.forEach(card => {
    const text = card.textContent.toLowerCase();
    const cat = card.dataset.category || '';
    const matchCat = activeNewsFilter === 'all' || cat === activeNewsFilter;
    const matchQuery = text.includes(query);

    if (matchCat && matchQuery) {
      card.style.display = 'flex';
    } else {
      card.style.display = 'none';
    }
  });
}

newsFilterBtns.forEach(btn => {
  btn.addEventListener('click', () => {
    newsFilterBtns.forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    activeNewsFilter = btn.dataset.newsFilter;
    filterNews();
  });
});

newsSearchInput?.addEventListener('input', filterNews);

/* =========================================
   Pengumuman Archive Search & Filter
   ========================================= */
const announcementFilterBtns = document.querySelectorAll('.announcement-filters button[data-announcement-filter]');
const announcementCards = document.querySelectorAll('#announcementGrid .announcement-archive-card');
const announcementSearchInput = document.getElementById('announcementSearchInput');

let activeAnnFilter = 'all';

function filterAnnouncements() {
  const query = announcementSearchInput ? announcementSearchInput.value.toLowerCase().trim() : '';

  announcementCards.forEach(card => {
    const text = card.textContent.toLowerCase();
    const type = card.dataset.type || '';
    const matchType = activeAnnFilter === 'all' || type === activeAnnFilter;
    const matchQuery = text.includes(query);

    if (matchType && matchQuery) {
      card.style.display = 'block';
    } else {
      card.style.display = 'none';
    }
  });
}

announcementFilterBtns.forEach(btn => {
  btn.addEventListener('click', () => {
    announcementFilterBtns.forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    activeAnnFilter = btn.dataset.announcementFilter;
    filterAnnouncements();
  });
});

announcementSearchInput?.addEventListener('input', filterAnnouncements);

/* =========================================
   Agenda List Search & Filter
   ========================================= */
const agendaFilterBtns = document.querySelectorAll('.news-category-filters button[data-agenda-filter]');
const agendaCards = document.querySelectorAll('#agendaGrid .agenda-full-card');
const agendaSearchInput = document.getElementById('agendaSearchInput');

let activeAgendaFilter = 'all';

function filterAgendas() {
  const query = agendaSearchInput ? agendaSearchInput.value.toLowerCase().trim() : '';

  agendaCards.forEach(card => {
    const text = card.textContent.toLowerCase();
    const status = card.dataset.status || '';
    const type = card.dataset.type || '';
    
    let matchFilter = true;
    if (activeAgendaFilter === 'upcoming') {
      matchFilter = status === 'upcoming';
    } else if (activeAgendaFilter !== 'all') {
      matchFilter = type === activeAgendaFilter;
    }

    const matchQuery = text.includes(query);

    if (matchFilter && matchQuery) {
      card.style.display = 'grid';
    } else {
      card.style.display = 'none';
    }
  });
}

agendaFilterBtns.forEach(btn => {
  btn.addEventListener('click', () => {
    agendaFilterBtns.forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    activeAgendaFilter = btn.dataset.agendaFilter;
    filterAgendas();
  });
});

agendaSearchInput?.addEventListener('input', filterAgendas);

/* =========================================
   Peta Sebaran - Direktori Kecamatan Search
   ========================================= */
/* =========================================
   Direktori Karang Taruna Tabs & Filters
   ========================================= */
const dirLevelBtns = document.querySelectorAll('.dir-level-btn');
const dirPanes = {
  kabupaten: document.getElementById('paneKabupaten'),
  kecamatan: document.getElementById('paneKecamatan'),
  desa: document.getElementById('paneDesa')
};
const dirSearchInput = document.getElementById('directorySearchInput');
const wilayahSelectFilter = document.getElementById('wilayahSelectFilter');
const wilayahFilterWrap = document.getElementById('wilayahFilterWrap');

let currentDirLevel = 'kabupaten';

dirLevelBtns.forEach(btn => {
  btn.addEventListener('click', () => {
    dirLevelBtns.forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    currentDirLevel = btn.dataset.level;

    // Toggle panes
    Object.keys(dirPanes).forEach(key => {
      if (dirPanes[key]) {
        if (key === currentDirLevel) {
          dirPanes[key].classList.add('active');
        } else {
          dirPanes[key].classList.remove('active');
        }
      }
    });

    // Show zona filter for kecamatan
    if (wilayahFilterWrap) {
      wilayahFilterWrap.style.display = currentDirLevel === 'kecamatan' ? 'block' : 'none';
    }

    filterDirectory();
  });
});

function filterDirectory() {
  const query = dirSearchInput ? dirSearchInput.value.toLowerCase().trim() : '';
  const selectedZona = wilayahSelectFilter ? wilayahSelectFilter.value : 'all';

  if (currentDirLevel === 'kabupaten') {
    const memberCards = document.querySelectorAll('#paneKabupaten .dir-member-card');
    memberCards.forEach(card => {
      const text = card.textContent.toLowerCase();
      card.style.display = text.includes(query) ? 'flex' : 'none';
    });
  } else if (currentDirLevel === 'kecamatan') {
    const kecCards = document.querySelectorAll('#kecamatanDirList .dir-kec-card');
    kecCards.forEach(card => {
      const text = card.textContent.toLowerCase();
      const zona = card.dataset.zona || '';
      const matchZona = selectedZona === 'all' || zona === selectedZona;
      const matchQuery = text.includes(query);
      card.style.display = (matchZona && matchQuery) ? 'flex' : 'none';
    });
  } else if (currentDirLevel === 'desa') {
    const desaRows = document.querySelectorAll('#desaTableBody tr');
    desaRows.forEach(row => {
      const text = row.textContent.toLowerCase();
      row.style.display = text.includes(query) ? '' : 'none';
    });
  }
}

dirSearchInput?.addEventListener('input', filterDirectory);
wilayahSelectFilter?.addEventListener('change', filterDirectory);

/* =========================================
   Halaman Standalone: Karang Taruna Kecamatan
   ========================================= */
const kecSearchPage = document.getElementById('kecamatanSearchPage');
const zonaFilterSelect = document.getElementById('zonaFilterSelect');
const kecMainCards = document.querySelectorAll('#kecamatanMainGrid .dir-kec-card');

function filterKecamatanStandalone() {
  const query = kecSearchPage ? kecSearchPage.value.toLowerCase().trim() : '';
  const selectedZona = zonaFilterSelect ? zonaFilterSelect.value : 'all';

  kecMainCards.forEach(card => {
    const text = card.textContent.toLowerCase();
    const zona = card.dataset.zona || '';
    const matchZona = selectedZona === 'all' || zona === selectedZona;
    const matchQuery = text.includes(query);

    if (matchZona && matchQuery) {
      card.style.display = 'flex';
    } else {
      card.style.display = 'none';
    }
  });
}

kecSearchPage?.addEventListener('input', filterKecamatanStandalone);
zonaFilterSelect?.addEventListener('change', filterKecamatanStandalone);

/* =========================================
   Halaman Standalone: Karang Taruna Desa
   ========================================= */
const desaSearchInput = document.getElementById('desaSearchInput');
const filterKecamatanSelect = document.getElementById('filterKecamatanSelect');
const desaRows = document.querySelectorAll('#desaStandaloneTableBody tr');

// Support URL params e.g. ?kec=soreang
const urlParams = new URLSearchParams(window.location.search);
const kecParam = urlParams.get('kec');

if (filterKecamatanSelect && kecParam) {
  // Find option matching lowercase value
  for (let opt of filterKecamatanSelect.options) {
    if (opt.value.toLowerCase() === kecParam.toLowerCase()) {
      filterKecamatanSelect.value = opt.value;
      break;
    }
  }
}

function filterDesaStandalone() {
  const query = desaSearchInput ? desaSearchInput.value.toLowerCase().trim() : '';
  const selectedKec = filterKecamatanSelect ? filterKecamatanSelect.value.toLowerCase() : 'all';

  desaRows.forEach(row => {
    const text = row.textContent.toLowerCase();
    const rowKec = (row.dataset.kec || '').toLowerCase();
    const matchKec = selectedKec === 'all' || rowKec === selectedKec;
    const matchQuery = text.includes(query);

    if (matchKec && matchQuery) {
      row.style.display = '';
    } else {
      row.style.display = 'none';
    }
  });
}

// Initial filter if param exists
if (kecParam) {
  filterDesaStandalone();
}

desaSearchInput?.addEventListener('input', filterDesaStandalone);
filterKecamatanSelect?.addEventListener('change', filterDesaStandalone);

/* =========================================
   Pengecekan Data PPKS & Captcha Logic
   ========================================= */
const ppksForm = document.getElementById('ppksForm');
const nikInput = document.getElementById('nikInput');
const captchaInput = document.getElementById('captchaInput');
const captchaCodeText = document.getElementById('captchaCodeText');
const refreshCaptchaBtn = document.getElementById('refreshCaptchaBtn');
const ppksLoading = document.getElementById('ppksLoading');
const ppksErrorAlert = document.getElementById('ppksErrorAlert');
const ppksErrorText = document.getElementById('ppksErrorText');
const ppksResultSuccess = document.getElementById('ppksResultSuccess');
const ppksResultNotFound = document.getElementById('ppksResultNotFound');
const btnResetPPKS = document.getElementById('btnResetPPKS');

let currentCaptchaCode = '';

function generateRandomCaptcha(length = 5) {
  const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
  let result = '';
  for (let i = 0; i < length; i++) {
    result += chars.charAt(Math.floor(Math.random() * chars.length));
  }
  return result;
}

function refreshCaptcha() {
  currentCaptchaCode = generateRandomCaptcha(5);
  if (captchaCodeText) {
    captchaCodeText.textContent = currentCaptchaCode;
  }
  if (captchaInput) {
    captchaInput.value = '';
  }
}

// Format input NIK to allow numbers only
nikInput?.addEventListener('input', (e) => {
  e.target.value = e.target.value.replace(/[^0-9]/g, '').slice(0, 16);
});

// Refresh captcha on button click
refreshCaptchaBtn?.addEventListener('click', () => {
  refreshCaptcha();
});

// Reset button handler
btnResetPPKS?.addEventListener('click', () => {
  if (ppksLoading) ppksLoading.style.display = 'none';
  if (ppksErrorAlert) ppksErrorAlert.style.display = 'none';
  if (ppksResultSuccess) ppksResultSuccess.style.display = 'none';
  if (ppksResultNotFound) ppksResultNotFound.style.display = 'none';
  setTimeout(refreshCaptcha, 50);
});

// Initial captcha generation on page load
if (captchaCodeText) {
  refreshCaptcha();
}

// PPKS Form Submit Handler
ppksForm?.addEventListener('submit', (e) => {
  e.preventDefault();

  const nik = nikInput ? nikInput.value.trim() : '';
  const captcha = captchaInput ? captchaInput.value.trim().toUpperCase() : '';

  // Hide previous notifications
  if (ppksErrorAlert) ppksErrorAlert.style.display = 'none';
  if (ppksResultSuccess) ppksResultSuccess.style.display = 'none';
  if (ppksResultNotFound) ppksResultNotFound.style.display = 'none';

  // Validation 1: NIK 16 Digits
  if (nik.length !== 16) {
    if (ppksErrorAlert && ppksErrorText) {
      ppksErrorText.textContent = 'Nomor Induk Kependudukan (NIK) harus terdiri dari 16 digit angka!';
      ppksErrorAlert.style.display = 'flex';
    }
    nikInput?.focus();
    return;
  }

  // Validation 2: Captcha Matching
  if (captcha !== currentCaptchaCode) {
    if (ppksErrorAlert && ppksErrorText) {
      ppksErrorText.textContent = 'Kode Captcha salah atau tidak sesuai! Silakan periksa kembali.';
      ppksErrorAlert.style.display = 'flex';
    }
    refreshCaptcha();
    captchaInput?.focus();
    return;
  }

  // Show Loading Spinner
  if (ppksLoading) ppksLoading.style.display = 'flex';

  // Simulated Asynchronous Query to PPKS database
  setTimeout(() => {
    if (ppksLoading) ppksLoading.style.display = 'none';

    // Mock rule: If NIK ends with an even digit or 0/2/4/6/8 -> Found / Registered
    // If NIK ends with 9 -> Not Found
    const lastDigit = parseInt(nik.slice(-1), 10);
    const isFound = isNaN(lastDigit) ? true : lastDigit % 2 === 0 || lastDigit === 1 || lastDigit === 5;

    if (isFound) {
      // Mask NIK for privacy
      const maskedNik = nik.substring(0, 6) + '******' + nik.substring(12);
      const resNik = document.getElementById('resNik');
      if (resNik) resNik.textContent = maskedNik;

      if (ppksResultSuccess) ppksResultSuccess.style.display = 'block';
    } else {
      if (ppksResultNotFound) ppksResultNotFound.style.display = 'block';
    }

    // Refresh captcha after each search attempt
    refreshCaptcha();
  }, 1000);
});



