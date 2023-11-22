// Fonction pour détecter si l'appareil est considéré comme mobile
function isMobileDevice() {
    console.log("ismobiledevice");
    console.log("window.innerWidth="+window.innerWidth);

    return window.innerWidth <= 1200; // considérer comme mobile si la largeur est inférieure ou égale à 768 pixels
    // Attention cela fonction mais soucis avec la taille écran pc, au pire on peut le modifier à 1200 par exemple

}

// Exécutez cette fonction lors du chargement de la page ou lors d'un événement pertinent
document.addEventListener('DOMContentLoaded', function() {
    //DOMContentLoaded : événement est déclenché lorsque le document HTML est complètement chargé et analysé
    //, sans attendre les feuilles de style, images et ressources externes.
console.log("chargement"); // test
    if (isMobileDevice()) {
        console.log("ismobile");//test
        // Envoie d'une requête AJAX pour indiquer que l'appareil est mobile
        fetch('/check-mobile-device', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ isMobile: true }),
        })
            .then(response => {
                // Gérer la réponse si nécessaire
                console.log('l\'appareil est mobile');
            })
            .catch(error => {
                // Gérer les erreurs si nécessaire
                console.error('Erreur lors de l\'envoi de la requête AJAX :', error);
            });
    }
});