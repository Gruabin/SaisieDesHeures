// 
// * Change les boutons de couleur lors de leur survol
// 
check = document.querySelectorAll('.check');
xmark = document.querySelectorAll('.xmark');
pen = document.querySelectorAll('.pen');
check.forEach(element => {
    element.addEventListener('mouseover', function () {
        element.classList.add('text-success');
    });
    element.addEventListener('mouseout', function () {
        element.classList.remove('text-success');
    });
});
xmark.forEach(element => {
    element.addEventListener('mouseover', function () {
        element.classList.add('text-accent');
    });
    element.addEventListener('mouseout', function () {
        element.classList.remove('text-accent');
    });
});
pen.forEach(element => {
    element.addEventListener('mouseover', function () {
        element.classList.add('text-secondary');
    });
    element.addEventListener('mouseout', function () {
        element.classList.remove('text-secondary');
    });
});

// 
// * Sélectionne tout les éléments
// 
let checkbox = document.getElementById("select_all");
if (!!checkbox) {
    checkbox.addEventListener("click", (event) => {
        let checkboxDiv = document.getElementById("select_all_checkboxes");

        if (event.target.checked) {
            checkboxDiv.querySelectorAll("input[type=checkbox]").forEach((item) => {
                if (item.checked === false) {
                    item.click();
                }
            });
        } else {
            checkboxDiv.querySelectorAll("input[type=checkbox]").forEach((item) => {
                if (item.checked === true) {
                    item.click();
                }
            });
        }
    });
}