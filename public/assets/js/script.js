document.addEventListener("DOMContentLoaded", function () {
    const editButtons = document.querySelectorAll(".editCategoryBtn");
    const modal = document.getElementById("editModal");
    const closeModalBtn = document.getElementById("closeModal");
    const updateCategoryId = document.getElementById("updateCategoryId");
    const updateCategoryName = document.getElementById("updateCategoryName");

    editButtons.forEach(button => {
        button.addEventListener("click", function () {
            const categoryId = this.getAttribute("data-id");
            const categoryName = this.getAttribute("data-name");

            updateCategoryId.value = categoryId;
            updateCategoryName.value = categoryName;
            
            modal.classList.remove("hidden");
        });
    });

    closeModalBtn.addEventListener("click", function () {
        modal.classList.add("hidden");
    });

    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.classList.add("hidden");
        }
    });
});
