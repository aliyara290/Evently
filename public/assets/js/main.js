let Evently;
let events = [
    { id: 1, title: 'Conférence Tech', date: '2025-02-15', location: 'Paris' },
    { id: 2, title: 'Workshop Design', date: '2025-02-20', location: 'Lyon' }
];

function renderEvents(eventsToRender) {
    const tbody = document.getElementById('eventsTable');
    tbody.innerHTML = eventsToRender.map(event => `
        <tr class="hover:bg-gray-700 transition-colors">
            <td class="px-6 py-4">${event.title}</td>
            <td class="px-6 py-4">${event.date}</td>
            <td class="px-6 py-4">${event.location}</td>
            <td class="px-6 py-4">
                <button onclick="deleteEvent(${event.id})" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    Supprimer
                </button>
            </td>
        </tr>
    `).join('');
}

function handleSort(value) {
    switch(value) {
        case 'date-asc':
            events.sort((a, b) => new Date(a.date) - new Date(b.date));
            break;
        case 'date-desc':
            events.sort((a, b) => new Date(b.date) - new Date(a.date));
            break;
        case 'title-asc':
            events.sort((a, b) => a.title.localeCompare(b.title));
            break;
        case 'title-desc':
            events.sort((a, b) => b.title.localeCompare(a.title));
            break;
    }
    renderEvents(events);
}

function showList() {
    document.getElementById('listView').classList.remove('hidden');
    document.getElementById('calendarView').classList.add('hidden');
}

function showCalendar() {
    document.getElementById('listView').classList.add('hidden');
    document.getElementById('calendarView').classList.remove('hidden');
    renderCalendar();
}

function renderCalendar() {
    const calendarDays = document.getElementById('calendarDays');
    const currentDate = new Date();
    const daysInMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).getDate();
    
    let calendarHTML = '';
    for (let i = 1; i <= daysInMonth; i++) {
        const dayEvents = events.filter(event => new Date(event.date).getDate() === i);
        calendarHTML += `
            <div class="border border-gray-700 p-2 min-h-24 rounded-lg">
                <div class="font-bold">${i}</div>
                ${dayEvents.map(event => `
                    <div class="text-sm bg-indigo-900 p-2 mt-1 rounded-lg">
                        ${event.title}
                    </div>
                `).join('')}
            </div>
        `;
    }
    calendarDays.innerHTML = calendarHTML;
}

function filterEvents() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const filtered = events.filter(event => 
        event.title.toLowerCase().includes(searchTerm) ||
        event.location.toLowerCase().includes(searchTerm)
    );
    renderEvents(filtered);
}

function toggleModal() {
    const modal = document.getElementById('eventModal');
    modal.classList.toggle('hidden');
}

function handleSubmit(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    const newEvent = {
        id: events.length + 1,
        title: formData.get('title'),
        date: formData.get('date'),
        location: formData.get('location')
    };
    events.push(newEvent);
    renderEvents(events);
    toggleModal();
    event.target.reset();
}

function deleteEvent(id) {
    events = events.filter(event => event.id !== id);
    renderEvents(events);
}

// Initial render
renderEvents(events);


// event de sidebar
  document.addEventListener("DOMContentLoaded", () => {
    const sidebar = document.getElementById("sidebar");
    const toggleBtn = document.getElementById("toggleSidebar");

    toggleBtn.addEventListener("click", () => {
        if (sidebar.classList.contains("-translate-x-full")) {
            sidebar.classList.remove("-translate-x-full");
            toggleBtn.classList.add("left-64");
            toggleBtn.classList.remove("left-2");
            toggleBtn.innerHTML = "◀"; 
        } else {
            sidebar.classList.add("-translate-x-full");
            toggleBtn.classList.remove("left-64");
            toggleBtn.classList.add("left-2");
            toggleBtn.innerHTML = "▶"; 
        }
    });
});
