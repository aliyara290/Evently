const searchInput = document.getElementById("searchForEvents");
const cityValueSt = document.getElementById("cityValue");
const subSearchBtn = document.getElementById("subSearchBtn");
let cityContainer = document.getElementById("searchForCity");

subSearchBtn.addEventListener("click", function (e) {
  e.preventDefault();
  document.getElementById("overlay").style.display = "none";

  cityContainer.innerHTML = "";
  let query = searchInput.value.trim();
  let city = cityValueSt.value.trim();
  let eventsContainer = document.getElementById("events__lists");
  let userInputValue = document.getElementById("userInputValue");
  let userInputCity = document.getElementById("userInputCity");
  userInputValue.textContent = query;
  userInputCity.textContent = city !== "" ? city : "Morocco";
  // if (query === "") {
  //   location.reload();
  // }
  let searchValue = document.getElementById("searchValue");
  if (query.length > 2 || city.length > 2) {
    searchValue.classList.remove("hidden");
    axios
      .get(
        "/events/search?q=" +
          encodeURIComponent(query) +
          "&city=" +
          encodeURIComponent(city)
      )

      .then((response) => {
        // eventsContainer.innerHTML = "";
        console.log(response.data);

        if (response.data.events.length > 0) {
          document.getElementById("pagination__ev").style.display = "flex";

          eventsContainer.innerHTML = response.data.events
            .map((event) => {
              return `
                          <li>
                              <a href="/event?id=${event.id}">
                                  <article class="overflow-hidden rounded-lg shadow-sm transition hover:shadow-lg h-full">
                                      <img alt="" src="/assets/upload/${
                                        event.image
                                      }" class="h-44 w-full object-cover"/>
                                      <div class="bg-white p-4 sm:p-6 flex flex-col gap-2">
                                          <time datetime="${
                                            event.event_date
                                          }" class="block text-xs text-gray-500">
                                              ${event.event_date}
                                          </time>

                                          <h3 class="text-lg text-gray-900">${
                                            event.title
                                          }</h3>

                                          <p class="line-clamp-3 text-sm/relaxed text-gray-500">
                                              ${event.ville || "Unknown City"}
                                          </p>

                                          <div class="flex items-center gap-2">
                                              ${
                                                event.price == 0
                                                  ? `<span class="text-green-500 font-medium">
                                                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                                                          <path fill-rule="evenodd" d="M5.25 2.25a3 3 0 0 0-3 3v4.318a3 3 0 0 0 .879 2.121l9.58 9.581c.92.92 2.39 1.186 3.548.428a18.849 18.849 0 0 0 5.441-5.44c.758-1.16.492-2.629-.428-3.548l-9.58-9.581a3 3 0 0 0-2.122-.879H5.25ZM6.375 7.5a1.125 1.125 0 1 0 0-2.25 1.125 1.125 0 0 0 0 2.25Z" clip-rule="evenodd"/>
                                                      </svg>
                                                  </span>
                                                  <span class="text-green-500 font-medium">Free</span>`
                                                  : `<span class="text-blue-500 font-medium">
                                                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                                                          <path fill-rule="evenodd" d="M5.25 2.25a3 3 0 0 0-3 3v4.318a3 3 0 0 0 .879 2.121l9.58 9.581c.92.92 2.39 1.186 3.548.428a18.849 18.849 0 0 0 5.441-5.44c.758-1.16.492-2.629-.428-3.548l-9.58-9.581a3 3 0 0 0-2.122-.879H5.25ZM6.375 7.5a1.125 1.125 0 1 0 0-2.25 1.125 1.125 0 0 0 0 2.25Z" clip-rule="evenodd"/>
                                                      </svg>
                                                  </span>
                                                  <span class="text-blue-500 font-medium">${event.price} DH</span>`
                                              }
                                          </div>
                                      </div>
                                  </article>
                              </a>
                          </li>
                      `;
            })
            .join("");
        } else {
          document.getElementById("pagination__ev").style.display = "none";
          eventsContainer.innerHTML = "<p>No events found.</p>";
        }
      })
      .catch((error) => console.error("Error fetching events:", error));
  } else {
    searchValue.classList.add("hidden");
  }
});

const searchCityInput = document.getElementById("cityValue");
searchCityInput.addEventListener("input", () => {
  document.getElementById("overlay").style.display = "block";
  let query = searchCityInput.value.trim();
  if (query === "") {
    cityContainer.innerHTML = "";
  }
  if (query.length >= 1) {
    // searchValue.classList.remove("hidden");
    cityContainer.classList.remove("hidden");
    axios
      .get("/events/city?city=" + encodeURIComponent(query))
      .then((response) => {
        console.log(response.data);

        if (response.data.cities.length > 0) {
          cityContainer.innerHTML = response.data.cities
            .map((city) => {
              return `
                          <li class="hover:bg-gray-200 mx-1 mt-1 bg-[#f5f4f4] p-2 cursor-pointer text-sm text-gray-600 rounded-sm">${city.ville}</li>
                      `;
            })
            .join("");

          const searchForCity = document.querySelectorAll("#searchForCity li");
          searchForCity.forEach((city) => {
            city.addEventListener("click", (e) => {
              document.getElementById("overlay").style.display = "none";

              let target = e.target.textContent;
              let cityValue = document.querySelector("#cityValue");
              cityValue.value = target;
              cityContainer.innerHTML = "";
            });
          });
        } else {
          cityContainer.innerHTML = "<p>No events found.</p>";
        }
      })
      .catch((error) => console.error("Error fetching events:", error));
  } else {
    searchValue.classList.add("hidden");
  }
});

const filterSubmitBtn = document.getElementById("submit-filter-btn");
filterSubmitBtn.addEventListener("click", (e) => {
  e.preventDefault();
  let categoryV = document.querySelector("input[name='category-option']:checked");
  // let priceV = document.querySelector("input[name='price-option']:checked");
  let dateV = document.querySelector("[name='eventDate']");
  let eventsContainer = document.getElementById("events__lists");
  // let checkPrice = priceV === null ? "false" : priceV.value;
  
  if (categoryV || dateV) {
    // searchValue.classList.remove("hidden");
    axios
      .get(
        "/events/filter?category=" +
          encodeURIComponent(categoryV.value) +
          "&date=" +
          encodeURIComponent(dateV.value)
      )
      .then((response) => {
        // eventsContainer.innerHTML = "";
        console.log(response.data);

        if (response.data.eventsFilter.length > 0) {
          document.getElementById("pagination__ev").style.display = "flex";

          eventsContainer.innerHTML = response.data.eventsFilter
            .map((event) => {
              return `
                          <li>
                              <a href="/event?id=${event.id}">
                                  <article class="overflow-hidden rounded-lg shadow-sm transition hover:shadow-lg h-full">
                                      <img alt="" src="/assets/upload/${
                                        event.image
                                      }" class="h-44 w-full object-cover"/>
                                      <div class="bg-white p-4 sm:p-6 flex flex-col gap-2">
                                          <time datetime="${
                                            event.event_date
                                          }" class="block text-xs text-gray-500">
                                              ${event.event_date}
                                          </time>

                                          <h3 class="text-lg text-gray-900">${
                                            event.title
                                          }</h3>

                                          <p class="line-clamp-3 text-sm/relaxed text-gray-500">
                                              ${event.ville || "Unknown City"}
                                          </p>

                                          <div class="flex items-center gap-2">
                                              ${
                                                event.price == 0
                                                  ? `<span class="text-green-500 font-medium">
                                                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                                                          <path fill-rule="evenodd" d="M5.25 2.25a3 3 0 0 0-3 3v4.318a3 3 0 0 0 .879 2.121l9.58 9.581c.92.92 2.39 1.186 3.548.428a18.849 18.849 0 0 0 5.441-5.44c.758-1.16.492-2.629-.428-3.548l-9.58-9.581a3 3 0 0 0-2.122-.879H5.25ZM6.375 7.5a1.125 1.125 0 1 0 0-2.25 1.125 1.125 0 0 0 0 2.25Z" clip-rule="evenodd"/>
                                                      </svg>
                                                  </span>
                                                  <span class="text-green-500 font-medium">Free</span>`
                                                  : `<span class="text-blue-500 font-medium">
                                                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                                                          <path fill-rule="evenodd" d="M5.25 2.25a3 3 0 0 0-3 3v4.318a3 3 0 0 0 .879 2.121l9.58 9.581c.92.92 2.39 1.186 3.548.428a18.849 18.849 0 0 0 5.441-5.44c.758-1.16.492-2.629-.428-3.548l-9.58-9.581a3 3 0 0 0-2.122-.879H5.25ZM6.375 7.5a1.125 1.125 0 1 0 0-2.25 1.125 1.125 0 0 0 0 2.25Z" clip-rule="evenodd"/>
                                                      </svg>
                                                  </span>
                                                  <span class="text-blue-500 font-medium">${event.price} DH</span>`
                                              }
                                          </div>
                                      </div>
                                  </article>
                              </a>
                          </li>
                      `;
            })
            .join("");
        } else {
          document.getElementById("pagination__ev").style.display = "none";
          eventsContainer.innerHTML = "<p>No events found.</p>";
        }
      })
      .catch((error) => console.error("Error fetching events:", error));
  }
});



function togglePassword(id) {
  const eye_slash = document.getElementById("eye-slash");
  const eye = document.getElementById("eye");
  const input = document.getElementById(id);
  if (input.type === "password") {
    input.type = "text";
    eye_slash.classList.add("hidden");
    eye.classList.remove("hidden");
  } else {
    input.type = "password";
    eye_slash.classList.remove("hidden");
    eye.classList.add("hidden");
  }
}
let events = [
  { id: 1, title: "Conférence Tech", date: "2025-02-15", location: "Paris" },
  { id: 2, title: "Workshop Design", date: "2025-02-20", location: "Lyon" },
];

function renderEvents(eventsToRender) {
  const tbody = document.getElementById("eventsTable");
  tbody.innerHTML = eventsToRender
    .map(
      (event) => `
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
    `
    )
    .join("");
}

function handleSort(value) {
  switch (value) {
    case "date-asc":
      events.sort((a, b) => new Date(a.date) - new Date(b.date));
      break;
    case "date-desc":
      events.sort((a, b) => new Date(b.date) - new Date(a.date));
      break;
    case "title-asc":
      events.sort((a, b) => a.title.localeCompare(b.title));
      break;
    case "title-desc":
      events.sort((a, b) => b.title.localeCompare(a.title));
      break;
  }
  renderEvents(events);
}

function showList() {
  document.getElementById("listView").classList.remove("hidden");
  document.getElementById("calendarView").classList.add("hidden");
}

function showCalendar() {
  document.getElementById("listView").classList.add("hidden");
  document.getElementById("calendarView").classList.remove("hidden");
  renderCalendar();
}

function renderCalendar() {
  const calendarDays = document.getElementById("calendarDays");
  const currentDate = new Date();
  const daysInMonth = new Date(
    currentDate.getFullYear(),
    currentDate.getMonth() + 1,
    0
  ).getDate();

  let calendarHTML = "";
  for (let i = 1; i <= daysInMonth; i++) {
    const dayEvents = events.filter(
      (event) => new Date(event.date).getDate() === i
    );
    calendarHTML += `
            <div class="border border-gray-700 p-2 min-h-24 rounded-lg">
                <div class="font-bold">${i}</div>
                ${dayEvents
                  .map(
                    (event) => `
                    <div class="text-sm bg-indigo-900 p-2 mt-1 rounded-lg">
                        ${event.title}
                    </div>
                `
                  )
                  .join("")}
            </div>
        `;
  }
  calendarDays.innerHTML = calendarHTML;
}

function filterEvents() {
  const searchTerm = document.getElementById("searchInput").value.toLowerCase();
  const filtered = events.filter(
    (event) =>
      event.title.toLowerCase().includes(searchTerm) ||
      event.location.toLowerCase().includes(searchTerm)
  );
  renderEvents(filtered);
}

function toggleModal() {
  const modal = document.getElementById("eventModal");
  modal.classList.toggle("hidden");
}

function handleSubmit(event) {
  event.preventDefault();
  const formData = new FormData(event.target);
  const newEvent = {
    id: events.length + 1,
    title: formData.get("title"),
    date: formData.get("date"),
    location: formData.get("location"),
  };
  events.push(newEvent);
  renderEvents(events);
  toggleModal();
  event.target.reset();
}

function deleteEvent(id) {
  events = events.filter((event) => event.id !== id);
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

const fileInputs = document.querySelectorAll(".file-input");
const fileNames = document.querySelectorAll(".file-name");

fileInputs.forEach((fileInput, index) => {
  fileInput.addEventListener("change", function () {
    if (this.files && this.files[0]) {
      fileNames[index].textContent = this.files[0].name;
    } else {
      fileNames[index].textContent = "No file chosen";
    }
  });
});
