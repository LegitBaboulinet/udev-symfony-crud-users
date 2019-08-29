// Définition des animations des messages
$(document).ready(() => {
    $('.ui.message').hide().fadeIn(5000).delay(5000).fadeOut(2000);
});

/**
 * Ferme le message passé en paramètre
 * @param {Element} message
 */
function closeMessage(message) {
    $(message.parentElement).hide();
}