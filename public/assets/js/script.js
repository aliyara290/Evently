// pop upe de update categry
document.addEventListener("DOMContentLoaded", function () {
    const editButtons = document.querySelectorAll(".edit-btn");
    const modal = document.getElementById("editModal");
    const closeModalBtn = document.getElementById("closeModalBtn");
    const modalCategoryId = document.getElementById("modal_category_id");
    const modalCategoryName = document.getElementById("modal_category_name");

    // Ouvrir la modal avec les valeurs de la catégorie
    editButtons.forEach(button => {
        button.addEventListener("click", function () {
            const categoryId = this.getAttribute("data-id");
            const categoryName = this.getAttribute("data-name");

            modalCategoryId.value = categoryId;
            modalCategoryName.value = categoryName;
            modal.classList.remove("hidden");
        });
    });

    // Fermer la modal
    closeModalBtn.addEventListener("click", function () {
        modal.classList.add("hidden");
    });

    // Fermer la modal si on clique en dehors
    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.classList.add("hidden");
        }
    });
});
