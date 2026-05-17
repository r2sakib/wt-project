<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/performence.css">

    <title>Reports</title>
</head>

<body>
    <div class="reports-container">

        <div class="page-header">
            <div class="header-info">
                <h2>Performance Reports</h2>
                <p class="text-muted">Analyze academic trends, pass rates, and faculty evaluations.</p>
            </div>
            <div class="header-actions">
                <button class="btn-primary">
                    <span class="icon">📥</span> Download PDF
                </button>
            </div>
        </div>

        <div class="filter-card">
            <form class="filter-form" id="reports-filter-form">

                <div class="filter-group">
                    <label for="report-type">Report Type</label>
                    <select id="report-type" name="report_type">
                        <option value="course_pass_rates" selected>Course Pass Rates</option>
                        <option value="faculty_evals">Faculty Evaluations</option>
                        <option value="student_cgpa">Student CGPA Trends</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="filter-semester">Semester</label>
                    <select id="filter-semester" name="semester">
                        <option value="Spring2026">Spring 2026 (Current)</option>
                        <option value="Fall2025">Fall 2025</option>
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn-primary btn-sm">Generate Report</button>
                </div>
            </form>
        </div>

        <div class="report-summary-grid">
            <div class="report-metric">
                <span class="metric-value">84%</span>
                <span class="metric-label">Dept. Average Pass Rate</span>
            </div>
            <div class="report-metric">
                <span class="metric-value">3.12</span>
                <span class="metric-label">Dept. Average CGPA</span>
            </div>
            <div class="report-metric">
                <span class="metric-value text-warning">2</span>
                <span class="metric-label">Courses Underperforming</span>
            </div>
        </div>

        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Course Code</th>
                        <th>Course Title</th>
                        <th>Enrolled</th>
                        <th>Pass Rate</th>
                        <th>Avg Grade</th>
                        <th class="text-right">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="font-medium">CSC4105</td>
                        <td>Software Engineering</td>
                        <td>145</td>
                        <td>
                            <span class="rate-text">92%</span>
                            <div class="progress-wrapper">
                                <div class="progress-fill fill-success" style="width: 92%;"></div>
                            </div>
                        </td>
                        <td class="font-medium">B+</td>
                        <td class="text-right"><span class="badge badge-active">Excellent</span></td>
                    </tr>

                    <tr>
                        <td class="font-medium">CSC3201</td>
                        <td>Web Technology</td>
                        <td>120</td>
                        <td>
                            <span class="rate-text">78%</span>
                            <div class="progress-wrapper">
                                <div class="progress-fill fill-warning" style="width: 78%;"></div>
                            </div>
                        </td>
                        <td class="font-medium">B-</td>
                        <td class="text-right"><span class="badge badge-inactive">Normal</span></td>
                    </tr>

                    <tr class="row-attention">
                        <td class="font-medium">PHY1001</td>
                        <td>Physics 1</td>
                        <td>160</td>
                        <td>
                            <span class="rate-text text-danger">54%</span>
                            <div class="progress-wrapper">
                                <div class="progress-fill fill-danger" style="width: 54%;"></div>
                            </div>
                        </td>
                        <td class="font-medium text-warning">C</td>
                        <td class="text-right"><span class="badge badge-danger">Review Needed</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</body>

</html>