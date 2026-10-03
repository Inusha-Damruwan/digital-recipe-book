<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/page-layout.php';

crumb_page_header('Contact');
?>
<section class="contact-section standalone-contact">
  <div class="container contact-inner">
    <div class="contact-info">
      <p class="section-kicker">LET'S TALK FOOD</p>
      <h1 id="contactHeading">Have a recipe<br><em>to share?</em></h1>
      <p>Send us a note or tell us what you'd love to cook next.</p>
      <address class="contact-details">
        <a href="tel:+94702936583"><span aria-hidden="true">☎</span><span><small>PHONE</small>0702936583</span></a>
        <a href="mailto:ipdamruwan2831@gmail.com"><span aria-hidden="true">✉</span><span><small>EMAIL</small>ipdamruwan2831@gmail.com</span></a>
        <a href="https://maps.google.com/?q=22%2C+Punchi+Akurugoda%2C+Tissamaharama%2C+Sri+Lanka" target="_blank" rel="noopener noreferrer"><span aria-hidden="true">⌖</span><span><small>ADDRESS</small>22, Punchi Akurugoda,<br>Tissamaharama, Sri Lanka</span></a>
        <a href="https://crumbherbfoods.lk/" target="_blank" rel="noopener noreferrer"><span aria-hidden="true">↗</span><span><small>WEBSITE</small>crumbherbfoods.lk</span></a>
      </address>
    </div>
    <form class="contact-form" id="contactForm" novalidate>
      <label for="contactName">Your name</label>
      <input type="text" id="contactName" name="name" maxlength="100" autocomplete="name" required>
      <label for="contactEmail">Email address</label>
      <input type="email" id="contactEmail" name="email" maxlength="190" autocomplete="email" required>
      <label for="contactMessage">Your message</label>
      <textarea id="contactMessage" name="message" rows="4" minlength="10" maxlength="3000" required></textarea>
      <button type="submit" class="btn btn-orange">Send message <span aria-hidden="true">↗</span></button>
      <p class="form-feedback" id="formFeedback" aria-live="polite"></p>
    </form>
  </div>
</section>
<?php crumb_page_footer(); ?>
