// Fonction pour d�tecter si l'appareil est consid�r� comme mobile
function isMobileDevice() {
    console.log("ismobiledevice");
    console.log("window.innerWidth="+window.innerWidth);

    return window.innerWidth <= 1200; // consid�rer comme mobile si la largeur est inf�rieure ou �gale � 768 pixels
    // Attention cela fonction mais soucis avec la taille �cran pc, au pire on peut le modifier � 1200 par exemple

}

// Ex�cutez cette fonction lors du chargement de la page ou lors d'un �v�nement pertinent
document.addEventListener('DOMContentLoaded', function() {
    //DOMContentLoaded : �v�nement est d�clench� lorsque le document HTML est compl�tement charg� et analys�
    //, sans attendre les feuilles de style, images et ressources externes.
console.log("chargement"); // test
    if (isMobileDevice()) {
        console.log("ismobile");//test
        // Envoie d'une requ�te AJAX pour indiquer que l'appareil est mobile
        fetch('/check-mobile-device', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ isMobile: true }),
        })
            .then(response => {
                // G�rer la r�ponse si n�cessaire
                console.log('l\'appareil est mobile');
            })
            .catch(error => {
                // G�rer les erreurs si n�cessaire
                console.error('Erreur lors de l\'envoi de la requ�te AJAX :', error);
            });
    }
});