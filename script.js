/* ================= DOM READY WRAPPER ================= */

document.addEventListener('DOMContentLoaded', () => {

  /* Enable JS class for animations */
  document.documentElement.classList.add('js');

  /* ================= ANIMATED STAT COUNTERS ================= */

  const statNumbers = document.querySelectorAll('.stat-number');
  const statsSection = document.querySelector('.stats-grid');
  let statsStarted = false;

  function animateStats() {
    if (statsStarted) return;
    statsStarted = true;

    statNumbers.forEach(stat => {
      const target = Number(stat.dataset.target || 0);
      let current = 0;
      const steps = 60;
      const increment = target / steps || 1;

      const counter = setInterval(() => {
        current += increment;

        if (current >= target) {
          stat.textContent = target;
          clearInterval(counter);
        } else {
          stat.textContent = Math.floor(current);
        }
      }, 20);
    });
  }

  if (statsSection && statNumbers.length > 0) {
    const statsObserver = new IntersectionObserver(
      entries => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            animateStats();
            statsObserver.disconnect();
          }
        });
      },
      { threshold: 0.3 }
    );

    statsObserver.observe(statsSection);
  }

  /* ================= REVEAL ANIMATIONS ================= */

  const revealElements = document.querySelectorAll('.reveal');

  if (revealElements.length > 0) {
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

  /* ================= FOOTER INCLUDE ================= */

  const footerPlaceholder = document.getElementById('footer-placeholder');

  if (footerPlaceholder) {
    fetch('footer.html')
      .then(res => res.text())
      .then(html => {
        footerPlaceholder.innerHTML = html;
      })
      .catch(err => {
        console.warn('Footer failed to load:', err);
      });
  }

  /* ================= CONTACT INTRO AUTO-REMOVE ================= */

  const intro = document.getElementById('contactIntro');

  if (intro) {
    setTimeout(() => {
      intro.remove();
    }, 3000);
  }

});
document.addEventListener('DOMContentLoaded', () => {
  const navToggle = document.querySelector('.nav-toggle');
  const navWrap = document.querySelector('.nav-wrap');

  if (!navToggle || !navWrap) {
    console.warn('Navbar toggle elements not found');
    return;
  }

  navToggle.addEventListener('click', () => {
    navWrap.classList.toggle('open');
  });
});