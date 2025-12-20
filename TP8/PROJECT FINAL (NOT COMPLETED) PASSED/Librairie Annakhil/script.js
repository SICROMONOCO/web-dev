
document.addEventListener("DOMContentLoaded", function() {
    var form = document.getElementById("contactForm");

    if (form) {
        form.addEventListener("submit", function(event) {
            var nom = document.getElementById("client_nom").value;
            var tel = document.getElementById("client_telephone").value;
            var msg = document.getElementById("message_details").value;
            if (nom === "" || tel === "" || msg === "") {
                alert("Veuillez remplir tous les champs (Nom, Téléphone et Message) !");
                event.preventDefault(); 
            }
        });
    }
});