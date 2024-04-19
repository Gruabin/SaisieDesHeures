/*
 * Welcome to your app's body JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';
import { startStimulusApp } from '@symfony/stimulus-bridge';

require('@fortawesome/fontawesome-free/css/fontawesome.min.css');
require('@fortawesome/fontawesome-free/css/solid.min.css');
require('@fortawesome/fontawesome-free/js/fontawesome.min.js');
require('@fortawesome/fontawesome-free/js/solid.min.js');

// export const app = startStimulusApp(require.context(
//     '@symfony/stimulus-bridge/lazy-controller-loader!./controllers',
//     true,
//     /\.[jt]sx?$/
// ));

document.addEventListener('DOMContentLoaded', () => {

    let couleur;
    let couleurTexte;

    function loader() {
        document.querySelector('main').classList.remove('invisible');
        document.querySelector('header').classList.remove('invisible');
        document.querySelector('footer').classList.remove('invisible');
        document.getElementById('boxAlertMessage').classList.remove('invisible');
        const form = document.querySelectorAll('form');
        const loader = document.querySelector('#loader');
        loader.style.display = 'none';

        try {
            form.forEach(element => {
                element.addEventListener('submit', function () {
                    // Afficher le div de chargement
                    loader.style.display = 'block';
                    document.querySelector('main').classList.add('invisible');
                    document.querySelector('header').classList.add('invisible');
                    document.querySelector('footer').classList.add('invisible');
                    document.getElementById('boxAlertMessage').classList.add('invisible');
                });
            });

        } catch (error) {
        }
        let aBalises = document.querySelectorAll('a');

        aBalises.forEach(element => {
            if (!element.classList.contains('supprimer') && element.getAttribute("href") !== '#' && !element.classList.contains('noLoading') && element.getAttribute("href") !== '#') {
                element.addEventListener('click', loading);
            }
        });

        function loading() {
            loader.style.display = 'block';

            document.querySelector('main').classList.add('invisible');
            document.querySelector('header').classList.add('invisible');
            document.querySelector('footer').classList.add('invisible');
            document.getElementById('boxAlertMessage').classList.add('invisible');
        }

        document.addEventListener('keyup', fkey)

        function fkey(e) {

            if (e.keyCode === 116) {
                loader.style.display = 'block';

                document.querySelector('main').classList.add('invisible');
                document.querySelector('header').classList.add('invisible');
                document.querySelector('footer').classList.add('invisible');
                document.getElementById('boxAlertMessage').classList.add('invisible');
            }
        }

    }

    window.addEventListener("load", loader);
    window.addEventListener('pageshow', loader);

    function lettreCouleur(lettre) {
        // Conversion de la lettre en nombre (valeur ASCII)
        let valeurAscii = lettre.charCodeAt(0);
        // Ajout d'une constante pour décaler les couleurs
        valeurAscii += 300;

        // Calcul des composantes RGB en fonction de la valeur ASCII
        const r = (valeurAscii * 420) % 256;
        const g = (valeurAscii * 530) % 256;
        const b = (valeurAscii * 440) % 256;
        // Conversion des composantes RGB en une couleur hexadécimale
        const couleur = "#" + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);

        // Calcul de la luminosité perçue
        const luminosite = Math.round(((r * 299) + (g * 587) + (b * 114)) / 1000);

        // Détermination de la couleur du texte en fonction de la luminosité
        const couleurTexte = (luminosite > 125) ? 'black' : 'white';

        return [couleur, couleurTexte];
    }

    if (document.getElementById('couleurLettre') !== undefined) {
        // Utilisation des fonctions
        const lettre = document.getElementById('couleurLettre');

        if (lettre !== null) {
            const [couleur, couleurTexte] = lettreCouleur(lettre.innerHTML);
            lettre.style.color = couleurTexte;
            lettre.parentElement.style.backgroundColor = couleur;
        }
    }

});
