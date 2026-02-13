/* ===========================
   NORTHMARK — MAIN SCRIPT
=========================== */

document.addEventListener('DOMContentLoaded', () => {

  /* ================= ENABLE JS MODE ================= */
  document.documentElement.classList.add('js');

  /* ================= NAVBAR TOGGLE ================= */

  const toggle   = document.querySelector('.nav-toggle');
  const navWrap  = document.querySelector('.nav-wrap');
  const navLinks = document.querySelector('.nav-links');

  if (toggle && navWrap && navLinks) {

    toggle.addEventListener('click', (e) => {
      e.stopPropagation();
      navWrap.classList.toggle('open');
    });

    navLinks.addEventListener('click', (e) => {
      e.stopPropagation();
    });

    document.addEventListener('click', () => {
      navWrap.classList.remove('open');
    });
  }

  /* ================= STATS COUNTER ================= */

  const statNumbers  = document.querySelectorAll('.stat-number');
  const statsSection = document.querySelector('.stats-grid');
  let statsStarted   = false;

  function animateStats() {
    if (statsStarted) return;
    statsStarted = true;

    statNumbers.forEach(stat => {
      const target = Number(stat.dataset.target || 0);
      let current  = 0;
      const steps  = 60;
      const inc    = target / steps || 1;

      const counter = setInterval(() => {
        current += inc;
        if (current >= target) {
          stat.textContent = target;
          clearInterval(counter);
        } else {
          stat.textContent = Math.floor(current);
        }
      }, 20);
    });
  }

  if (statsSection && statNumbers.length) {
    const statsObserver = new IntersectionObserver(
      entries => {
        if (entries[0].isIntersecting) {
          animateStats();
          statsObserver.disconnect();
        }
      },
      { threshold: 0.3 }
    );
    statsObserver.observe(statsSection);
  }

  /* ================= REVEAL ON SCROLL ================= */

  const revealElements = document.querySelectorAll('.reveal');

  if (revealElements.length) {
    const revealObserver = new IntersectionObserver(
      entries => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('active');
            revealObserver.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.15 }
    );

    revealElements.forEach(el => revealObserver.observe(el));
  }

  /* ================= SERVICES HUBS ANIMATION ================= */

  const servicesHubs = document.querySelector('.services-hubs');
  const hubCards     = document.querySelectorAll('.operations-card');

  if (servicesHubs && hubCards.length) {

    /* Step 1 — hubs move from center after 2s */
    setTimeout(() => {
      servicesHubs.classList.add('hubs-enter');
    }, 1000);

    /* Step 2 — pills appear + rotate after 3s */
    setTimeout(() => {
      hubCards.forEach(card => card.classList.add('pills-active'));
    }, 2000);
  }

  /* ================= FOOTER INCLUDE ================= */

  const footerPlaceholder = document.getElementById('footer-placeholder');

  if (footerPlaceholder) {
    fetch('footer.html')
      .then(res => res.text())
      .then(html => footerPlaceholder.innerHTML = html)
      .catch(err => console.warn('Footer load failed:', err));
  }

  /* ================= CONTACT INTRO AUTO-REMOVE ================= */

  const intro = document.getElementById('contactIntro');

  if (intro) {
    setTimeout(() => intro.remove(), 3000);
  }

});
window.addEventListener("load", () => {
    document.querySelectorAll(".operations-card").forEach(card => {
      card.classList.add("pills-active");
    });
  });