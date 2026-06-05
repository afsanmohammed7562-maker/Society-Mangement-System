(() => {
  const body = document.body;
  const themeToggleButton = document.getElementById("themeToggle");
  const themeIcon = document.querySelector(".theme-toggle-icon");

  const counters = document.querySelectorAll(".counter");
  const statsSection = document.getElementById("stats");
  const pageLoader = document.getElementById("pageLoader");

  const navMenu = document.getElementById("navMenu");
  const mobileMenuToggle = document.getElementById("mobileMenuToggle");

  // ---------------- Theme ----------------
  const savedTheme = localStorage.getItem("theme");
  if (savedTheme === "dark") {
    body.classList.add("dark");
  }

  const updateThemeIcon = () => {
    if (!themeIcon) return;
    const isDark = body.classList.contains("dark");
    themeIcon.textContent = isDark ? "☀️" : "🌙";
  };

  updateThemeIcon();

  if (themeToggleButton) {
    themeToggleButton.addEventListener("click", () => {
      body.classList.toggle("dark");
      const activeTheme = body.classList.contains("dark") ? "dark" : "light";
      localStorage.setItem("theme", activeTheme);
      updateThemeIcon();
    });
  }

  // ---------------- Loading spinner ----------------
  window.addEventListener("load", () => {
    if (pageLoader) pageLoader.style.display = "none";
  });

  // ---------------- Scroll reveal ----------------
  const revealEls = document.querySelectorAll("[data-reveal]");
  const prefersReducedMotion = window.matchMedia(
    "(prefers-reduced-motion: reduce)"
  ).matches;

  if (prefersReducedMotion) {
    revealEls.forEach((el) => el.classList.add("is-visible"));
  } else {
    const revealObserver = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("is-visible");
            revealObserver.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.15 }
    );

    revealEls.forEach((el) => revealObserver.observe(el));
  }

  // ---------------- Counters ----------------
  const animateCounter = (counterElement) => {
    const target = Number(counterElement.dataset.target || 0);
    const suffix = counterElement.dataset.suffix ?? "+";

    const duration = 1400;
    const startTime = performance.now();

    const update = (currentTime) => {
      const elapsed = currentTime - startTime;
      const progress = Math.min(elapsed / duration, 1);
      const value = Math.floor(progress * target);

      counterElement.textContent = `${value.toLocaleString()}${suffix}`;

      if (progress < 1) {
        requestAnimationFrame(update);
      } else {
        counterElement.textContent = `${target.toLocaleString()}${suffix}`;
      }
    };

    requestAnimationFrame(update);
  };

  if (counters.length && statsSection) {
    let hasCounted = false;

    const counterObserver = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting && !hasCounted) {
            counters.forEach((counter) => animateCounter(counter));
            hasCounted = true;
          }
        });
      },
      { threshold: 0.35 }
    );

    counterObserver.observe(statsSection);
  }

  // ---------------- Mobile menu ----------------
  const closeNav = () => {
    if (!navMenu || !mobileMenuToggle) return;
    navMenu.classList.remove("nav-menu--open");
    mobileMenuToggle.setAttribute("aria-expanded", "false");
  };

  if (navMenu && mobileMenuToggle) {
    mobileMenuToggle.addEventListener("click", () => {
      const isOpen = navMenu.classList.toggle("nav-menu--open");
      mobileMenuToggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
    });

    navMenu.querySelectorAll("a").forEach((link) => {
      link.addEventListener("click", () => closeNav());
    });

    window.addEventListener("resize", () => {
      if (window.innerWidth > 680) closeNav();
    });
  }

  // ---------------- FAQ accordion ----------------
  document.querySelectorAll(".faq-item").forEach((item) => {
    const button = item.querySelector(".faq-question");
    const panel = item.querySelector(".faq-answer");
    const toggle = item.querySelector(".faq-toggle");

    if (!button || !panel) return;

    // Ensure closed state on load.
    panel.style.maxHeight = "0px";
    button.setAttribute("aria-expanded", "false");
    panel.setAttribute("aria-hidden", "true");

    button.addEventListener("click", () => {
      const isOpen = item.classList.contains("is-open");

      if (isOpen) {
        item.classList.remove("is-open");
        button.setAttribute("aria-expanded", "false");
        panel.setAttribute("aria-hidden", "true");
        panel.style.maxHeight = "0px";
        if (toggle) toggle.textContent = "+";
        return;
      }

      item.classList.add("is-open");
      button.setAttribute("aria-expanded", "true");
      panel.setAttribute("aria-hidden", "false");
      panel.style.maxHeight = `${panel.scrollHeight}px`;
      if (toggle) toggle.textContent = "×";
    });
  });

  

  
})();

