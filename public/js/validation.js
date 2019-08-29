let userId = 0;

/**
 * Demande à l'utilisateur si il veut réélement supprimer l'utilisateur séléctioné
 * @param id
 */
function validateUserDeletion(id) {
    userId = id;

    // Affichage de la model de validation
    $('.ui.modal').modal('show');
}

/**
 * Supprime l'utilisateur
 */
function deleteUser() {
    window.location.replace(`/user/${userId}/delete`)
}