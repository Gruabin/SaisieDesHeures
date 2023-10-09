

document.getElementById("type").addEventListener("change", function () {
    console.log(this.value);
    switch (parseInt(this.value)) {
        case 0:
            document.getElementById("divOrdre").classList.remove("hidden");
            document.getElementById("divTache").classList.add("hidden");
            document.getElementById("divOperation").classList.remove("hidden");
            document.getElementById("divActivite").classList.add("hidden");
            break;
        case 1:
            document.getElementById("divOrdre").classList.add("hidden");
            document.getElementById("divTache").classList.remove("hidden");
            document.getElementById("divOperation").classList.add("hidden");
            document.getElementById("divActivite").classList.add("hidden");
            break;
        case 2:
            document.getElementById("divOrdre").classList.remove("hidden");
            document.getElementById("divTache").classList.add("hidden");
            document.getElementById("divOperation").classList.remove("hidden");
            document.getElementById("divActivite").classList.add("hidden");
            break;
        case 3:
            document.getElementById("divOrdre").classList.remove("hidden");
            document.getElementById("divTache").classList.remove("hidden");
            document.getElementById("divOperation").classList.add("hidden");
            document.getElementById("divActivite").classList.remove("hidden");
            break;
        default:
            document.getElementById("divOrdre").classList.add("hidden");
            document.getElementById("divTache").classList.add("hidden");
            document.getElementById("divOperation").classList.add("hidden");
            document.getElementById("divActivite").classList.add("hidden");
            break;
    }
});

//     document.getElementById("info").innerText = "";
//     document.getElementById("info").classList.add("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");

// document.getElementById("info").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
//             document.getElementById("info").innerText = "test";
