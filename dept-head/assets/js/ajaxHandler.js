document.addEventListener("DOMContentLoaded", function () {
  const sidebarContainer = document.getElementById("sidebar-container");
  const headerContainer = document.getElementById("header-container");
  const mainContentContainer = document.getElementById("main-content-container");
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
  } else if (pageParam === 'courses') {
    loadView("views/courses/index.php", mainContentContainer, setupCourseListActions);
  } else if (pageParam === 'students') {
    loadView("views/students/index.php", mainContentContainer, setupStudentListActions);
  } else if (pageParam === 'appeals') {
    loadView("views/appeals/index.php", mainContentContainer);
  } else if (pageParam === 'performance') {
    loadView("views/reports/performance.php", mainContentContainer, setupReportModuleActions);
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
    } else if (pageParam === 'courses') {
      navLinks.forEach((l) => l.classList.remove("active"));
      navLinks.forEach((link) => {
        const text = link.querySelector(".nav-text").innerText.trim();
        if (text === "Courses & Faculty") {
          link.classList.add("active");
        }
      });
    } else if (pageParam === 'performance') {
      navLinks.forEach((l) => l.classList.remove("active"));
      navLinks.forEach((link) => {
        const text = link.querySelector(".nav-text").innerText.trim();
        if (text === "Performance Reports") {
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
            loadView("views/courses/index.php", mainContentContainer, setupCourseListActions);
            break;
          case "Students Directory":
            loadView("views/students/index.php", mainContentContainer, setupStudentListActions);
            break;
          case "Grade Appeals":
            loadView("views/appeals/index.php", mainContentContainer);
            break;
          case "Performance Reports":
            loadView("views/reports/performance.php", mainContentContainer, setupReportModuleActions);
            break;
          case "Department Calendar":
            loadView("views/calendar/index.php", mainContentContainer);
            break;
          case "Announcements":
            loadView("views/announcement/index.php", mainContentContainer);
            break;
          default:
            mainContentContainer.innerHTML = "<h2 style='padding: 3.2rem;'>View Not Found</h2>";
        }
      });
    });
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

  function setupCourseListActions() {
    setupCourseFilters();

    const addCourseBtn = document.getElementById("btn-add-course");
    if (addCourseBtn) {
      addCourseBtn.addEventListener("click", function (e) {
        e.preventDefault();
        loadView("views/courses/form.php", mainContentContainer, setupCourseFormActions);
      });
    }

    bindCourseRowButtons();
  }

  function bindCourseRowButtons() {
    const editBtns = document.querySelectorAll(".btn-edit-course");
    editBtns.forEach(btn => {
      btn.addEventListener("click", function(e) {
        e.preventDefault();
        const id = this.getAttribute("data-id");
        loadView("views/courses/edit.php?id=" + id, mainContentContainer, setupCourseFormActions);
      });
    });

    const assignBtns = document.querySelectorAll(".btn-assign-faculty");
    assignBtns.forEach(btn => {
      btn.addEventListener("click", function(e) {
        e.preventDefault();
        const id = this.getAttribute("data-id");
        loadView("views/courses/assignFaculty.php?id=" + id, mainContentContainer, setupCourseFormActions);
      });
    });
  }

  function setupCourseFormActions() {
    const backBtn = document.getElementById("btn-back-courses");
    const cancelBtn = document.getElementById("btn-cancel-courses");

    const returnToList = function (e) {
      e.preventDefault();
      loadView("views/courses/index.php", mainContentContainer, setupCourseListActions);
    };

    if (backBtn) backBtn.addEventListener("click", returnToList);
    if (cancelBtn) cancelBtn.addEventListener("click", returnToList);
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
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
          if (xhr.readyState === 4 && xhr.status === 200) {
            try {
              const coursesData = JSON.parse(xhr.responseText);
              let htmlOutput = "";

              if (coursesData.length === 0) {
                htmlOutput = `<tr><td colspan="6" style="text-align:center; padding: 3.2rem;">No courses found matching these filters.</td></tr>`;
              } else {
                coursesData.forEach((course) => {
                  let badgeClass = course.status === "open" ? "badge-active" : "badge-inactive";
                  let displayStatus = course.status.charAt(0).toUpperCase() + course.status.slice(1);
                  let facultyName = course.faculty_name ? course.faculty_name : "Unassigned";

                  htmlOutput += `
                    <tr>
                        <td class="font-medium">${course.code}</td>
                        <td>${course.title}</td>
                        <td>${course.program_name}</td>
                        <td>${facultyName}</td>
                        <td><span class="badge ${badgeClass}">${displayStatus}</span></td>
                        <td class="text-right" style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                            <button class="btn-icon btn-assign-faculty" title="Assign Faculty" data-id="${course.id}">👤</button>
                            <button class="btn-icon btn-edit-course" title="Edit" data-id="${course.id}">✏️</button>
                            <form action="controllers/CourseController.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this course?');" style="margin: 0;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="course_id" value="${course.id}">
                                <button type="submit" class="btn-icon btn-danger" style="border: none; background: none;">🛑</button>
                            </form>
                        </td>
                    </tr>
                  `;
                });
              }

              document.querySelector(".data-table tbody").innerHTML = htmlOutput;
              bindCourseRowButtons();
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

  function setupStudentListActions() {
    const viewBtns = document.querySelectorAll(".btn-view-student");
    viewBtns.forEach(btn => {
      btn.addEventListener("click", function(e) {
        e.preventDefault();
        const id = this.getAttribute("data-id");
        loadView("views/students/view.php?id=" + id, mainContentContainer, setupStudentViewActions);
      });
    });
  }

  function setupStudentViewActions() {
    const backBtn = document.getElementById("btn-back-students");
    if (backBtn) {
      backBtn.addEventListener("click", function (e) {
        e.preventDefault();
        loadView("views/students/index.php", mainContentContainer, setupStudentListActions);
      });
    }
  }

  function setupReportModuleActions() {
    const reportNavBtns = document.querySelectorAll(".report-nav-btn");
    reportNavBtns.forEach(btn => {
      btn.addEventListener("click", function(e) {
        e.preventDefault();
        const targetViewUrl = this.getAttribute("data-target");
        loadView(targetViewUrl, mainContentContainer, setupReportModuleActions);
      });
    });
  }
});