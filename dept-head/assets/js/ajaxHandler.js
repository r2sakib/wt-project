document.addEventListener("DOMContentLoaded", function () {
    
  const sidebarContainer = document.getElementById("sidebar-container");
  const headerContainer = document.getElementById("header-container");
  const mainContentContainer = document.getElementById(
    "main-content-container",
  );
  const footerContainer = document.getElementById("footer-container");


  function loadView(url, container, callback = null) {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", url, true);

    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
        if (xhr.status === 200) {
          container.innerHTML = xhr.responseText;

          if (callback) callback();
        } else {
          container.innerHTML = `<div style="padding: 3.2rem; color: var(--danger);">Error loading view: ${url}</div>`;
        }
      }
    };
    xhr.send();
  }

  loadView("views/layouts/sidebar.php", sidebarContainer, setupNavigation);
  loadView("views/layouts/header.php", headerContainer);

  const urlParams = new URLSearchParams(window.location.search);
  const pageParam = urlParams.get('page');

  if (pageParam === 'programs') {
    loadView("views/programs/index.php", mainContentContainer, setupProgramListActions);
  } else {
    loadView("views/dashboard/dashboard.php", mainContentContainer);
  }

  if (footerContainer) loadView("views/layouts/footer.php", footerContainer);


  function setupNavigation() {
    const navLinks = document.querySelectorAll(".sidebar-nav .nav-link");
    const urlParams = new URLSearchParams(window.location.search);
    const pageParam = urlParams.get('page');

    if (pageParam === 'programs') {
      navLinks.forEach((l) => l.classList.remove("active"));
      navLinks.forEach((link) => {
        const text = link.querySelector(".nav-text").innerText.trim();
        if (text === "Degree Programmes") {
          link.classList.add("active");
        }
      });
    }

    navLinks.forEach((link) => {
      link.addEventListener("click", function (e) {
        e.preventDefault(); 
        
        navLinks.forEach((l) => l.classList.remove("active"));
        this.classList.add("active");

        const pageText = this.querySelector(".nav-text").innerText.trim();

        switch (pageText) {
          case "Dashboard":
            loadView("views/dashboard/dashboard.php", mainContentContainer);
            break;
          case "Degree Programmes":
            loadView("views/programs/index.php", mainContentContainer, setupProgramListActions);
            break;
          case "Courses & Faculty":
            loadView("views/courses/index.php", mainContentContainer, setupCourseFilters);
            break;
          case "Students Directory":
            loadView("views/students/index.php", mainContentContainer);
            break;
          case "Grade Appeals":
            loadView("views/appeals/index.php", mainContentContainer);
            break;
          case "Performance Reports":
            loadView("views/reports/performence.php", mainContentContainer);
            break;
          case "Department Calendar":
            loadView("views/calender/index.php", mainContentContainer);
            break;
          case "Announcements":
            loadView("views/announcement/index.php", mainContentContainer);
            break;
          default:
            mainContentContainer.innerHTML =
              "<h2 style='padding: 3.2rem;'>View Not Found</h2>";
        }
      });
    });
  }

  function setupCourseFilters() {
    const filterForm = document.getElementById("course-filter-form");

    if (filterForm) {
      filterForm.addEventListener("submit", function (e) {
        e.preventDefault();

        const program = document.getElementById("filter-program").value;
        const status = document.getElementById("filter-status").value;

        const xhr = new XMLHttpRequest();

        xhr.open("POST", "api/filter_courses.php", true);

        xhr.setRequestHeader(
          "Content-type",
          "application/x-www-form-urlencoded",
        );

        xhr.onreadystatechange = function () {
          if (xhr.readyState === 4 && xhr.status === 200) {
            try {
              const coursesData = JSON.parse(xhr.responseText);

              let htmlOutput = "";

              if (coursesData.length === 0) {
                htmlOutput = `<tr><td colspan="6" style="text-align:center; padding: 3.2rem;">No courses found matching these filters.</td></tr>`;
              } else {
                coursesData.forEach((course) => {
                  let badgeClass =
                    course.status === "Open" ? "badge-open" : "badge-closed";

                  htmlOutput += `
                                        <tr>
                                            <td class="font-medium">${course.code}</td>
                                            <td>${course.title}</td>
                                            <td>${course.program}</td>
                                            <td>${course.faculty}</td>
                                            <td><span class="badge ${badgeClass}">${course.status}</span></td>
                                            <td class="text-right">
                                                <button class="btn-icon">👁️</button>
                                            </td>
                                        </tr>
                                    `;
                });
              }

              document.querySelector(".data-table tbody").innerHTML =
                htmlOutput;
            } catch (e) {
              console.error("Error parsing JSON from API:", e);
            }
          }
        };

        const dataString = `program=${encodeURIComponent(program)}&status=${encodeURIComponent(status)}`;
        xhr.send(dataString);
      });
    }
  }

  function setupProgramListActions() {
    const addProgramBtn = document.getElementById("btn-add-program");
    
    if (addProgramBtn) {
      addProgramBtn.addEventListener("click", function (e) {
        e.preventDefault();
        loadView("views/programs/form.php", mainContentContainer, setupProgramFormActions);
      });
    }

    const editBtns = document.querySelectorAll(".btn-edit");
    editBtns.forEach(btn => {
      btn.addEventListener("click", function(e) {
        e.preventDefault();
        const id = this.getAttribute("data-id");
        loadView("views/programs/edit.php?id=" + id, mainContentContainer, setupProgramFormActions);
      });
    });
  }

  function setupProgramFormActions() {
    const backBtn = document.getElementById("btn-back-programs");
    const cancelBtn = document.getElementById("btn-cancel-programs");

    const returnToList = function (e) {
      e.preventDefault();
      loadView("views/programs/index.php", mainContentContainer, setupProgramListActions);
    };

    if (backBtn) backBtn.addEventListener("click", returnToList);
    if (cancelBtn) cancelBtn.addEventListener("click", returnToList);
  }
});
