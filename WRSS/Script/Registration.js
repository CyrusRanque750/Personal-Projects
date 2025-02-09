var modal = document.getElementById("myModal");
var registerButton = document.getElementById("registerButton");
var closeButton = document.getElementsByClassName("close-button")[0];

registerButton.onclick = function() {
    var username = document.getElementById("username").value.trim();
    var email = document.getElementById("email").value.trim();
    var password = document.getElementById("password").value.trim();
    var confirmPassword = document.getElementsByName("confirm-password")[0].value.trim();

    if (username === "" || email === "" || password === "" || confirmPassword === "") {
        alert("Please fill in all required fields.");
    } else if (password !== confirmPassword) {
        alert("Passwords do not match. Please try again.");
    }
}

closeButton.onclick = function() {
    modal.style.display = "none";
}

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

document.getElementById("userButton").onclick = function() {
    alert("You selected User.");
    modal.style.display = "none";
}

document.getElementById("buyerButton").onclick = function() {
    alert("You selected Buyer.");
    modal.style.display = "none";
}   