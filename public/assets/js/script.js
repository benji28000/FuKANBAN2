const statusColors = {
    "backlog": "#FFD700",
    "running": "red",
    "evaluating": "blue",
    "in-progress": "orange",
    "live": "green",
};


function updateTagColors(background) {
    const cardTags = document.querySelectorAll(".card__tag");

    cardTags.forEach(tag => {
        const statusLabel = tag.dataset.statut;
        const color = statusColors[statusLabel];
        if (color) {
            tag.style.backgroundColor = color;
        }
        else {
            tag.style.backgroundColor = background;
        }
    });
}

document.addEventListener("DOMContentLoaded", function () {
    const columns = document.querySelectorAll(".column__item");
    columns.forEach(column => {
        const statusLabel = column.classList[1];
        const color = statusColors[statusLabel];
        if (color) {
            column.style.backgroundColor = color;
            updateTagColors();

        }
        else {
            let color = `rgb(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)})`
            column.setAttribute("data-color", color);
            column.style.backgroundColor = `${color}`;
            updateTagColors(color);

        }
    });

    // Call the function to update tag colors when the DOM is initially loaded
});

function updateTaskCount(statut, increment) {
    const span = document.querySelector(`[data-statut-number="${statut}"]`);
    if (span) {
        const currentCount = parseInt(span.textContent);
        span.textContent = (currentCount + (increment ? 1 : -1)).toString();
    }
}

document.addEventListener("DOMContentLoaded", function () {
    let draggedElement = null;
    let sourceStatut = null; // Store the source column's status

    // Add a dragstart event listener to the card__item elements
    const cardItems = document.querySelectorAll(".card__item");
    cardItems.forEach(cardItem => {
        cardItem.addEventListener("dragstart", function (event) {
            // Set the data to be transferred during the drag operation
            event.dataTransfer.setData("text/plain", this.dataset.taskId);
            draggedElement = this;
            sourceStatut = draggedElement.dataset.statut;
        });
    });

    // Add a dragover event listener to the column__item elements
    const columnItems = document.querySelectorAll(".column__item");
    columnItems.forEach(columnItem => {
        columnItem.addEventListener("dragover", function (event) {
            event.preventDefault(); // Allow the element to be dropped
        });

        // Add a drop event listener to handle dropping a card into a column
        columnItem.addEventListener("drop", function (event) {
            event.preventDefault();
            if (draggedElement) {
                // Move the card to the new column
                this.querySelector(".card__list").appendChild(draggedElement);

                // Update the data-statut attribute to reflect the new status
                const newStatus = this.classList[1];
                draggedElement.dataset.statut = newStatus;

                // Update the data-statut attribute of tags within the card
                const tagsInCard = draggedElement.querySelectorAll(".card__tag");
                tagsInCard.forEach(tag => {
                    tag.dataset.statut = newStatus;
                });

                // Call the function to update tag colors after the drop
                updateTagColors();
                const taskId = draggedElement.dataset.taskId;
                console.log(taskId);

                updateTaskStatus(taskId, newStatus);

                // Update task count for source and destination columns
                console.log("Source Statut:", sourceStatut);
                console.log("New Statut:", newStatus);
                updateTaskCount(sourceStatut, false); // Decrement source column count
                updateTaskCount(newStatus, true); // Increment destination column count

                draggedElement = null;
                sourceStatut = null;
            }
        });
    });
});



function updateTaskStatus(taskId, newStatus) {
    fetch(`http://localhost:8000/tasks/update-task-status/${taskId}/${newStatus}`)
}

