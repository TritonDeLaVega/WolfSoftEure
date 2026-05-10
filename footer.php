<footer class="site-footer">
    <button id="contact-btn" aria-haspopup="dialog">Contact</button>

    <a href="<?php echo esc_url(get_permalink(get_page_by_path('politique-de-confidentialite'))); ?>" target="_blank">
        Politique de confidentialité
    </a>

    <span class="footer-rights">Tous droits réservés.</span>
</footer>

<!-- Modale de contact -->
<div id="contact-modal" class="modal" aria-hidden="true">
    <div class="modal-content" role="dialog" aria-modal="true">
        <span class="close-modal" id="close-modal">&times;</span>
        <h2>Contact</h2>

        <form id="contact-form">
            <label for="contact-name">Nom</label>
            <input type="text" id="contact-name" name="name" required>

            <label for="contact-email">Email</label>
            <input type="email" id="contact-email" name="email" required>

            <label for="contact-message">Message</label>
            <textarea id="contact-message" name="message" required></textarea>

            <button type="submit" id="send-contact">Envoyer</button>
        </form>
    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>
