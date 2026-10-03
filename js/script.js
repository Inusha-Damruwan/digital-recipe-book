const searchForm = document.getElementById("searchForm");
const searchInput = document.getElementById("searchInput");
const recipeItems = Array.from(document.querySelectorAll(".recipe-item"));
const categoryButtons = Array.from(document.querySelectorAll(".category-chip"));
const suggestions = document.querySelectorAll(".suggestion");
const emptyMessage = document.getElementById("emptyMessage");
const slides = Array.from(document.querySelectorAll(".hero-slide"));
const slideCount = document.getElementById("slideCount");
const slider = document.getElementById("foodSlider");
const sliderDelay = 5000;

let selectedCategory = "All";
let activeSlide = 0;
let autoplayTimer;

// Match typed searches against recipe names only.
function filterRecipes() {
  if (!searchInput || !emptyMessage) return;
  const searchText = searchInput.value.trim().toLowerCase();
  let visibleCount = 0;

  recipeItems.forEach(function(item) {
    const matchesSearch = item.dataset.name.toLowerCase().includes(searchText);
    const matchesCategory =
      selectedCategory === "All" || item.dataset.category === selectedCategory;

    item.hidden = !(matchesSearch && matchesCategory);
    if (!item.hidden) visibleCount++;
  });

  emptyMessage.hidden = visibleCount > 0;
}

function selectCategory(category) {
  selectedCategory = category;

  categoryButtons.forEach(function(button) {
    const isSelected = button.dataset.category === category;
    button.classList.toggle("active", isSelected);
    button.setAttribute("aria-pressed", String(isSelected));
  });

  filterRecipes();
}

if (searchForm && searchInput) {
  searchForm.addEventListener("submit", function(event) {
    event.preventDefault();
    filterRecipes();
    document.getElementById("recipes").scrollIntoView({ behavior: "smooth" });
  });

  searchInput.addEventListener("input", filterRecipes);
}

categoryButtons.forEach(function(button) {
  button.addEventListener("click", function() {
    selectCategory(button.dataset.category);
  });
});

suggestions.forEach(function(button) {
  button.addEventListener("click", function() {
    const suggestion = button.dataset.search;
    const matchingCategory = categoryButtons.find(function(categoryButton) {
      return categoryButton.dataset.category.toLowerCase() === suggestion.toLowerCase();
    });

    if (matchingCategory) {
      searchInput.value = "";
      selectCategory(matchingCategory.dataset.category);
    } else {
      searchInput.value = suggestion;
      selectCategory("All");
    }

    document.getElementById("recipes").scrollIntoView({ behavior: "smooth" });
  });
});

document.querySelectorAll(".save-btn").forEach(function(button) {
  button.addEventListener("click", function() {
    const isSaved = button.getAttribute("aria-pressed") !== "true";
    button.classList.toggle("saved", isSaved);
    button.setAttribute("aria-pressed", String(isSaved));
    button.textContent = isSaved ? "♥" : "♡";
    button.setAttribute(
      "aria-label",
      (isSaved ? "Remove saved recipe " : "Save ") +
        button.closest(".recipe-card").querySelector("h3").textContent
    );
  });
});

function showSlide(index) {
  if (!slides.length || !slideCount) return;
  activeSlide = (index + slides.length) % slides.length;
  slides.forEach(function(slide, slideIndex) {
    const isActive = slideIndex === activeSlide;
    slide.classList.toggle("active", isActive);
    slide.setAttribute("aria-hidden", String(!isActive));
  });
  slideCount.textContent =
    String(activeSlide + 1).padStart(2, "0") +
    " / " +
    String(slides.length).padStart(2, "0");
}

function startAutoplay() {
  if (!slider || slides.length < 2) return;
  window.clearInterval(autoplayTimer);
  autoplayTimer = window.setInterval(function() {
    showSlide(activeSlide + 1);
  }, sliderDelay);
}

const previousSlideButton = document.getElementById("prevSlide");
const nextSlideButton = document.getElementById("nextSlide");

if (slider && slides.length) {
  previousSlideButton?.addEventListener("click", function() {
    showSlide(activeSlide - 1);
    startAutoplay();
  });

  nextSlideButton?.addEventListener("click", function() {
    showSlide(activeSlide + 1);
    startAutoplay();
  });

  slider.addEventListener("mouseenter", function() {
    window.clearInterval(autoplayTimer);
  });
  slider.addEventListener("mouseleave", startAutoplay);
  slider.addEventListener("focusin", function() {
    window.clearInterval(autoplayTimer);
  });
  slider.addEventListener("focusout", function(event) {
    if (!slider.contains(event.relatedTarget)) startAutoplay();
  });
  document.addEventListener("visibilitychange", function() {
    if (document.hidden) {
      window.clearInterval(autoplayTimer);
    } else if (!slider.matches(":hover") && !slider.contains(document.activeElement)) {
      startAutoplay();
    }
  });

  showSlide(0);
  startAutoplay();
}

// Keep the current section highlighted in the navigation.
const navLinks = Array.from(document.querySelectorAll(".site-nav .nav-link"));
const navSections = navLinks
  .map(function(link) {
    const target = link.getAttribute("href");
    return target && target.startsWith("#") ? document.querySelector(target) : null;
  })
  .filter(Boolean);

if ("IntersectionObserver" in window) {
  const sectionObserver = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
      if (!entry.isIntersecting) return;
      navLinks.forEach(function(link) {
        const isActive = link.getAttribute("href") === "#" + entry.target.id;
        link.classList.toggle("active", isActive);
        if (isActive) link.setAttribute("aria-current", "page");
        else link.removeAttribute("aria-current");
      });
    });
  }, { rootMargin: "-25% 0px -65% 0px" });

  navSections.forEach(function(section) {
    sectionObserver.observe(section);
  });
}

// Close the mobile navigation after choosing a section.
navLinks.forEach(function(link) {
  link.addEventListener("click", function() {
    const menu = document.getElementById("mainNav");
    const toggleButton = document.querySelector(".navbar-toggler");
    if (!menu || !toggleButton || !window.bootstrap || window.innerWidth >= 992) return;

    const collapse = window.bootstrap.Collapse.getOrCreateInstance(menu);
    if (menu.classList.contains("show")) {
      collapse.hide();
    } else if (toggleButton.getAttribute("aria-expanded") === "true") {
      // A link can be clicked before the opening animation adds the "show" class.
      menu.addEventListener("shown.bs.collapse", function() {
        collapse.hide();
      }, { once: true });
    }
  });
});

async function postToApi(action, values) {
  const apiUrl = document.body.dataset.apiUrl || "api.php";
  const response = await fetch(apiUrl + "?action=" + encodeURIComponent(action), {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    credentials: "same-origin",
    body: JSON.stringify(values)
  });
  const result = await response.json();

  if (!response.ok || !result.success) {
    throw new Error(result.message || "The request could not be completed.");
  }

  return result;
}

function validateForm(form, feedback) {
  const fields = Array.from(form.querySelectorAll("input, select, textarea"));
  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  feedback.textContent = "";

  for (const field of fields) {
    const value = field.value.trim();
    const label = field.labels && field.labels.length
      ? field.labels[0].textContent.trim()
      : "This field";
    let message = "";

    if (field.required && !value) {
      message = label + " is required.";
    } else if (value && field.type === "email" && !emailPattern.test(value)) {
      message = "Enter a valid email address.";
    } else if (value && field.minLength > 0 && value.length < field.minLength) {
      message = label + " must be at least " + field.minLength + " characters.";
    } else if (value && field.maxLength > 0 && value.length > field.maxLength) {
      message = label + " must be " + field.maxLength + " characters or fewer.";
    } else if (field.type === "number" && value && Number(value) <= 0) {
      message = label + " must be a positive number.";
    } else if (value && field.type === "url" && field.validity.typeMismatch) {
      message = "Enter a valid image URL.";
    }

    if (message) {
      feedback.textContent = message;
      field.focus();
      return false;
    }
  }

  return true;
}

function connectAuthForm(formId, feedbackId, action) {
  const form = document.getElementById(formId);
  const feedback = document.getElementById(feedbackId);
  if (!form || !feedback) return;

  form.addEventListener("submit", async function(event) {
    event.preventDefault();
    if (!validateForm(form, feedback)) return;
    feedback.textContent = "Please wait...";
    const submitButton = form.querySelector('button[type="submit"]');
    submitButton.disabled = true;

    try {
      const formData = new FormData(form);
      const result = await postToApi(action, Object.fromEntries(formData.entries()));
      const username = result.user && result.user.username;
      if (username) updateNavigationForUser(username);
      const modal = form.closest(".modal");
      if (modal && window.bootstrap) {
        window.bootstrap.Modal.getOrCreateInstance(modal).hide();
      } else {
        window.location.href = document.body.dataset.successUrl || "../dashboard.php";
      }
      form.reset();
      feedback.textContent = "";
    } catch (error) {
      feedback.textContent = error.message;
    } finally {
      submitButton.disabled = false;
    }
  });
}

function updateNavigationForUser(username) {
  const loginAction = document.getElementById("loginAction");
  const joinAction = document.getElementById("joinAction");
  if (!loginAction || !joinAction) return;
  let logoutInProgress = false;
  loginAction.textContent = "Hi, " + username;
  loginAction.removeAttribute("data-bs-toggle");
  loginAction.removeAttribute("data-bs-target");
  loginAction.href = "#home";
  joinAction.textContent = "Log out";
  joinAction.href = "#logout";
  joinAction.removeAttribute("data-bs-toggle");
  joinAction.removeAttribute("data-bs-target");
  joinAction.addEventListener("click", async function(event) {
    event.preventDefault();
    if (logoutInProgress) return;
    logoutInProgress = true;
    joinAction.setAttribute("aria-disabled", "true");

    try {
      await postToApi("logout", {});
      window.location.reload();
    } catch (error) {
      joinAction.setAttribute("aria-disabled", "false");
      window.alert(error.message);
    } finally {
      logoutInProgress = false;
    }
  });
}

connectAuthForm("loginForm", "loginFeedback", "login");
connectAuthForm("registerForm", "registerFeedback", "register");

const addRecipeForm = document.getElementById("addRecipeForm");
const recipeFeedback = document.getElementById("recipeFeedback");

if (addRecipeForm && recipeFeedback) {
  addRecipeForm.addEventListener("submit", async function(event) {
    event.preventDefault();
    if (!validateForm(addRecipeForm, recipeFeedback)) return;
    recipeFeedback.textContent = "Publishing your recipe...";
    const submitButton = addRecipeForm.querySelector('button[type="submit"]');
    submitButton.disabled = true;

    try {
      const formData = new FormData(addRecipeForm);
      await postToApi("add_recipe", Object.fromEntries(formData.entries()));
      recipeFeedback.textContent = "Your recipe is published. Thank you for sharing!";
      addRecipeForm.reset();
    } catch (error) {
      recipeFeedback.textContent = error.message;
    } finally {
      submitButton.disabled = false;
    }
  });
}

const contactForm = document.getElementById("contactForm");
const formFeedback = document.getElementById("formFeedback");

if (contactForm && formFeedback) {
  contactForm.addEventListener("submit", async function(event) {
    event.preventDefault();
    if (!validateForm(contactForm, formFeedback)) return;
    formFeedback.textContent = "Sending your message...";
    const submitButton = contactForm.querySelector('button[type="submit"]');
    submitButton.disabled = true;

    try {
      const formData = new FormData(contactForm);
      await postToApi("contact", Object.fromEntries(formData.entries()));
      formFeedback.textContent = "Thanks for your note. We'll be in touch!";
      contactForm.reset();
    } catch (error) {
      formFeedback.textContent = error.message;
    } finally {
      submitButton.disabled = false;
    }
  });
}

const logoutForm = document.getElementById("logoutForm");
const logoutFeedback = document.getElementById("logoutFeedback");

if (logoutForm && logoutFeedback) {
  logoutForm.addEventListener("submit", async function(event) {
    event.preventDefault();
    logoutFeedback.textContent = "Signing you out...";

    try {
      await postToApi("logout", {});
      window.location.href = "../index.php";
    } catch (error) {
      logoutFeedback.textContent = error.message;
    }
  });
}
