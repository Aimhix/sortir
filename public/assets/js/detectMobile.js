// Fonction pour detecter si l'appareil est considere comme mobile
function isMobileDevice() {
    console.log("ismobiledevice");
    console.log("window.innerWidth=" + window.innerWidth);

    return window.innerWidth <= 1200; // considerer comme mobile si la largeur est inferieure ou egale a 768 pixels
    // Attention cela fonction mais soucis avec la taille ecran pc, au pire on peut le modifier a 1200 par exemple

}

// Executez cette fonction lors du chargement de la page ou lors d'un evenement pertinent

document.addEventListener('DOMContentLoaded', function () {
    //DOMContentLoaded : evenement est declenche lorsque le document HTML est completement charge et analyse
    //, sans attendre les feuilles de style, images et ressources externes.
    console.log("chargement"); // test
    if (isMobileDevice()) {
        console.log("ismobile");//test
        // Envoie d'une requete AJAX pour indiquer que l'appareil est mobile
        fetch('/check-mobile-device', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({isMobile: true}),
        })
            .then(response => {
                // Gerer la reponse si necessaire
                console.log('l\'appareil est mobile');
            })
            .catch(error => {
                // Gerer les erreurs si necessaire
                console.error('Erreur lors de l\'envoi de la requete AJAX :', error);
            });
    }
});