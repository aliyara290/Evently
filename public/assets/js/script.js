document.addEventListener("DOMContentLoaded", function () {
    const getTicketButton = document.querySelector(".bg-orange");
    const reservationForm = document.querySelector("main.hidden");
    const mainPage = document.querySelector("main:not(.hidden)");

    if (getTicketButton) {
        getTicketButton.addEventListener("click", function (event) {
            event.preventDefault(); 

            mainPage.classList.add("hidden"); 
            reservationForm.classList.remove("hidden"); 
        });
    }
});

function calculateTotal() {
    const ticketPrice = 20; 
    const ticketInput = document.getElementById("tickets").value;
    const totalDisplay = document.getElementById("total");

    const total = ticketPrice * (ticketInput ? parseInt(ticketInput) : 0);
    totalDisplay.textContent = total + "€";
}


