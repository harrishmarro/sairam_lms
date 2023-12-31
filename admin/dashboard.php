<?php

include 'connection/db.php';

error_reporting(0);
// Check if the user is authenticated
session_start();
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: login.php');
    exit();
}
// Functions to get the required data
function getNumberOfBooks()
{
    include 'connection/db.php';
    // Implement your logic to get the books from the "books" table
    // For example:
    $query = "SELECT COUNT(*) AS count FROM books";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['count'];
}

function getNumberOfBooksTaken()
{
    include 'connection/db.php';
    // Implement your logic to get the books taken from the "borrowings" table
    // For example:
    $query = "SELECT COUNT(*) AS count FROM borrowings";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['count'];
}

function getNumberOfStudents()
{
    include 'connection/db.php';
    // Implement your logic to get the students from the "members" table
    // For example:
    $query = "SELECT COUNT(*) AS count FROM members WHERE member_type = 'student'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['count'];
}

function getNumberOfStaffs()
{
    include 'connection/db.php';
    // Implement your logic to get the staffs from the "members" table
    // For example:
    $query = "SELECT COUNT(*) AS count FROM members WHERE member_type = 'staff'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['count'];
}

function getTotalFineAmount()
{
    include 'connection/db.php';
    // Implement your logic to get the total fine amount from the "fines" table
    // For example:
    $query = "SELECT SUM(fine_amount) AS total_amount FROM members";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['total_amount'];
}

function getNumberOfStudentMembersWithFine()
{
    include 'connection/db.php';
    // Implement your logic to get the student members with fine from the "members" table
    // For example:
    $query = "SELECT COUNT(*) AS count FROM members WHERE member_type = 'student' AND fine_amount > 0";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['count'];
}

function getNumberOfStaffMembersWithFine()
{
    include 'connection/db.php';
    // Implement your logic to get the student members with fine from the "members" table
    // For example:
    $query = "SELECT COUNT(*) AS count FROM members WHERE member_type = 'staff' AND fine_amount > 0";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['count'];
}
// ...
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Analytics Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.15/dist/tailwind.min.css" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>

.dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
            color: #333;
        }
        .pur{
    color: #5500A9;
}
.rr{
    color: #D00000;
}
    .dashboard-1{
      outline:none;
      position: relative;
      justify-content: space-evenly;
      margin:auto;
      display: flex;
      flex-direction: row;
      height:30vh;
      width: 100vw;
    }
    .text{
        text-align: center;
        font-weight:600;
    }
    .text1{
        text-align: center;
        font-weight:bolder;
        font-size:1.5vw;
    }

    .content{
        padding: 15px;
        margin-left: 8px;
        height: auto;
        width: inherit;
        background-image: url(./images/dash-back.png);
        border-radius: 8px;
        width: 11vw;
        background-repeat: no-repeat;
        background-size: cover;

    }
    .dashboard-card{
        padding: 10px 10px 10px 10px;
        margin: 15px;
        background-color: #b1b1ff;
        display: flex;
        justify-content:center;
        align-items:center;
        max-width:fit-content;
        flex-direction: row;
        border-radius: 7px;
        flex-grow: 2;
        flex-shrink: 2;
        height: 21vh;
    }

    .dashboard {
      display: flex;
      max-width: auto;
      margin: 20px;
      overflow: hidden;
      outline:none;
    }

    .filter-options {
      flex: 1;
      padding: 20px;
      max-width:20vw;
    }
  

    .dashboard-card-graph {
      width:100%;
      flex: 2;
      padding: 20px;
      border-radius: 30px;
      background: #EEEFFF;
    }
    /* .nav-link{
      color:black;
    } */
    .nav-link.active {
    color: purple; /* Set the desired color for visited links */
    font-weight: bold; /* Make the link bold, for example */
    }

    label {
      display: inline-block;
      margin-bottom: 10px;
      font-weight:bold;
    }
   

   select{
    padding: 1vw;
    width: 100%;
    background: #eeefff;
   }

    h2 {
      margin-bottom: 20px;
     
    }
    /* h1{
      color: #303030;
      font-family: Signika Negative;
      font-size: 24px;
      font-style: normal;
      font-weight: 400;
      line-height: normal;
      max-width: 1000px;
      margin: 20px auto;
    } */

    .graph-container {
      height: 300px;
    }
    #todaychart{
      max-width:20vw;
    }
    .tabletoday{
      margin-left:10px;
      width:55vw;
      height:60vh;
      background: #eeefff;
      border-radius:10px;
    }
  </style>
</head>
<body>

<?php
include 'includes/header.php';
?>
  
<div class="dashboard-1">
  <div class="dashboard-card">
    <img  class="dashboard-images" src="./images/1.png" alt="img">
    <div style="display:flex;flex-direction:column;"> 
      <div class="content">
        <div class="text" >
    Total Books 
        </div>
      <div class="text1">
    <?php echo getNumberOfBooks(); ?>
      </div>
      </div>
  </div>
</div> 
<div class="dashboard-card">
    <img  class="dashboard-images" src="./images/2.png" alt="img">
    <div style="display:flex;flex-direction:column;"> 
      <div class="content">
        <div class="text">
    Books Taken
        </div>
      <div class="text1">
    <?php echo getNumberOfBooksTaken(); ?>
      </div>
      </div>
  </div>
</div> 
<div class="dashboard-card">
    <img  class="dashboard-images" src="./images/3.png" alt="img">
    <div style="display:flex;flex-direction:column;"> 
      <div class="content">
        <div class="text">
    Total Students 
        </div>
      <div class="text1">
    <?php echo getNumberOfStudents(); ?>
      </div>
      </div>
  </div>
</div> 
<div class="dashboard-card">
    <img  class="dashboard-images" src="./images/4.png" alt="img">
    <div style="display:flex;flex-direction:column;"> 
      <div class="content">
        <div class="text">
    Total staffs 
        </div>
      <div class="text1">
    <?php echo getNumberOfStaffs(); ?>
      </div>
      </div>
  </div>
</div> 
     
</div>
<center><h1 class="font-bold text-3xl">Today's Report</h1></center>
<div class="dashboard">
  <div class="filter-options">
    <label for="chart-type-today">Select Chart Type:</label>
    <select class="px-6 py-2" id="chart-type-today" onchange="updateChartTypeToday()">
        <option value="bar">Bar Chart</option>
        <option value="pie">Pie Chart</option>
    </select>
  
    <label for="department-today">Select Department:</label>
    <select class="px-6 py-2"id="department-today" onchange="fetchTodayAnalyticsData()">
        <option value="">All Departments</option>
        <?php
    // Include your database connection here
    include 'connection/db.php';

    // Query to fetch distinct departments
    $query = "SELECT DISTINCT department FROM members WHERE department Is NOT NULL";
    $result = $conn->query($query);

    while ($row = $result->fetch_assoc()) {
        $department = $row['department'];
        echo '<option value="' . $department . '">' . $department . '</option>';
    }

    $conn->close();
    ?>
    </select>
  
    <label for="year-today">Select Year:</label>
    <select class="px-6 py-2"id="year-today" onchange="fetchTodayAnalyticsData()">
        <option value="">All Years</option>
        <option value="1">First Year</option>
        <option value="2">Second Year</option>
    </select>
  
    <label for="member-type-today">Select Member Type:</label>
    <select class="px-6 py-2"id="member-type-today" onchange="fetchTodayAnalyticsData()">
        <option value="">All Member Types</option>
        <option value="student">Student</option>
        <option value="staff">Staff</option>
    </select>
  </div>
  <div class="dashboard-card-graph" id="todaychart">
    <h2 class="font-bold text-center">Today's Analytics</h2>
    <div class="graph-container">
        <canvas id="today-analytics-chart"></canvas>
    </div>
  </div>
  <div class="tabletoday" style="overflow-y: scroll;">
    <table border="1" id="todaytbl">
        <style>
            table {
                border-collapse: collapse;
                width: 100%;
            }

            th, td {
                padding: 8px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }
            td{
                margin-bottom:1px;
            }

        

            .pagination {
                display: flex;
                justify-content: center;
                margin-top: 20px;
            }

            .pagination a {
                padding: 5px 10px;
                text-decoration: none;
                background-color: #4F5AFF;
                color: #fff;
                border: 1px solid #ddd;
                margin: 0 2px;
                border-radius:5px;
                border: none;
            }

            .pagination a:hover {
              background-color: #4F5AFF;
                color: #fff;/* Change to your preferred hover text color */
            }
            .pagination a:active {
              background-color: blue;
                color: #fff;/* Change to your preferred hover text color */
            }
        </style>
        <tr class="shadow-2xl" style="position: sticky; top: 0px; background-image: linear-gradient(to right, rgb(177, 177, 255), rgb(177, 177, 255))">
            <th>Book Title</th>
            <th>Member Name</th>
            <th>Borrowed Date</th>
            <th>Due Date</th>
            <th>Returned Date</th>
            <th>Status</th>
        </tr>
        <?php
        // Items per page
        $itemsPerPage = 10;

        // Get the current page number from the URL or set it to 1 if not provided
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

        // Calculate the offset
        $offset = ($currentPage - 1) * $itemsPerPage;

        // Include your database connection here
        include 'connection/db.php';

        // SQL query to retrieve today's borrowing details with Borrowed Date and Returned Date for today
        $sql = "SELECT
                    bo.title AS BookTitle,
                    m.name AS MemberName,
                    b.borrowed_date AS BorrowedDate,
                    b.due_date AS DueDate,
                    b.returned_date AS ReturnedDate,
                    b.status AS Status
                FROM
                    borrowings b
                JOIN
                    books bo ON b.book_id = bo.book_id
                JOIN
                    members m ON b.member_id = m.member_id
                WHERE
                    DATE(b.borrowed_date) = CURDATE() OR DATE(b.returned_date) = CURDATE()
                LIMIT $itemsPerPage OFFSET $offset";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['BookTitle'] . '</td>';
                echo '<td>' . $row['MemberName'] . '</td>';
                echo '<td>' . $row['BorrowedDate'] . '</td>';
                echo '<td>' . $row['DueDate'] . '</td>';
                echo '<td>' . $row['ReturnedDate'] . '</td>';
                if ($row['Status'] == 'Borrowed') {
                  echo '<td style="background-color: #4BC0C0;
                  color: #fff;
                  font-weight: bold;
                  border: none;">' . $row['Status'] . '</td>';
              } elseif ($row['Status'] == 'Returned') {
                  echo '<td style="background-color: #FF6384;
                  color: #fff;
                  font-weight: bold;
                  border: none;">' . $row['Status'] . '</td>';
              } else {
              
                  echo '<td>' . $row['Status'] . '</td>';
              }
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="6">No borrowings for today.</td></tr>';
        }

        $conn->close();
        ?>
    </table>

    <?php
    // Calculate the total number of pages
    $totalPages = ceil($result->num_rows / $itemsPerPage) + 1;

    // Generate pagination links
    echo '<div  style="position: sticky; bottom: 0px; background-image: linear-gradient(to right, rgb(177, 177, 255), rgb(177, 177, 255));padding: 5px;" class="pagination">';
    if ($currentPage > 1) {
        echo '<a href="?page=1" style=" background-color: #4F5AFF;
        color: #fff;">First</a>';
        echo '<a href="?page=' . ($currentPage - 1) . '" style=" background-color: #4F5AFF;
        color: #fff;">Previous</a>';
    }
    for ($i = max(1, $currentPage - 2); $i <= min($currentPage + 2, $totalPages); $i++) {
        echo '<a href="?page=' . $i . '" ' . ($i == $currentPage ? 'class="active"' : '') . ' style=" background-color: #4F5AFF;
        color: #fff;">' . $i . '</a>';
    }
    if ($currentPage < $totalPages) {
        echo '<a href="?page=' . ($currentPage + 1) . '#" style=" background-color: #4F5AFF;
        color: #fff;">Next</a>';
        echo '<a href="?page=' . $totalPages . '" style=" background-color: #4F5AFF;
        color: #fff;">Last</a>';
    }
    echo '</div>';
    ?>
</div>


  
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
  const navLinks = document.querySelectorAll('.nav-link'); // Select all navigation links

  // Get the current page's URL
  const currentUrl = window.location.href;

  // Loop through each navigation link
  navLinks.forEach(link => {
    // Check if the link's href matches the current page's URL
    if (link.href === currentUrl) {
      link.classList.add('active'); // Add the active class to the link
    }
  });
});

//   document.addEventListener('DOMContentLoaded', function () {
//   const navLinks = document.querySelectorAll('.nav-link'); // Select all navigation links
  
//   // Loop through each navigation link
//   navLinks.forEach(link => {
//     link.addEventListener('click', function () {
//       // Add a class to mark the link as visited
//       this.classList.add('visited');
      
//       // Store visited status in local storage or a cookie if needed
//       localStorage.setItem('visited_' + this.href, true);
//     });
    
//     // Check if the link was previously visited and apply the class
//     if (localStorage.getItem('visited_' + link.href)) {
//       link.classList.add('visited');
//     }
//   });
// });

  // document.addEventListener('DOMContentLoaded', function () {
  //   const navLinks = document.querySelectorAll('.nav-link');

  //   navLinks.forEach(link => {
  //     link.addEventListener('click', function () {
  //       navLinks.forEach(navLink => navLink.classList.remove('active'));
  //       this.classList.add('active');
  //     });
  //   });
  // });
   let todayAnalyticsData = {
        labels: [],
        datasets: [{
            label: "Borrowed",
            data: [],
            backgroundColor: 'rgb(75, 192, 192)'
        },
        {
            label: "Returned",
            data: [],
            backgroundColor: 'rgb(255, 99, 132)'
        }]
    };
    let selectedChartTypeToday = 'line';
    let chartToday = null;

    // Update chart type for today's analytics
    function updateChartTypeToday() {
        selectedChartTypeToday = document.getElementById('chart-type-today').value;
        destroyChartToday();
        updateChartToday();
    }

    // Destroy the current chart instance
    function destroyChartToday() {
        if (chartToday !== null) {
            chartToday.destroy();
            chartToday = null;
        }
    }

    // Update the chart for today's analytics
    function updateChartToday() {
        const ctx = document.getElementById('today-analytics-chart').getContext('2d');

        if (selectedChartTypeToday === 'line') {
            chartToday = new Chart(ctx, {
                type: 'line',
                data: todayAnalyticsData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                    // Additional options as needed
                }
            });
        } else if (selectedChartTypeToday === 'bar') {
            chartToday = new Chart(ctx, {
                type: 'bar',
                data: todayAnalyticsData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                    // Additional options as needed
                }
            });
        } else if (selectedChartTypeToday === 'pie') {
            chartToday = new Chart(ctx, {
                type: 'pie',
                data: todayAnalyticsData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                    // Additional options as needed
                }
            });
        }
    }

    function fetchTodayAnalyticsData() {
        const departmentFilter = document.getElementById('department-today').value;
        const yearFilter = document.getElementById('year-today').value;
        const memberTypeFilter = document.getElementById('member-type-today').value;

        const fetchURL = `fetch_today_analytics_data.php?department=${departmentFilter}&year=${yearFilter}&memberType=${memberTypeFilter}`;
console.log(fetchURL)
        fetch(fetchURL)
            .then(response => response.json())
            .then(data => {
                todayAnalyticsData.labels = ['Today'];
                todayAnalyticsData.datasets[0].data = [data.borrowed[0]];
                todayAnalyticsData.datasets[1].data = [data.returned[0]];

                updateChartTypeToday();
            })
            .catch(error => {
                console.error("Error fetching today's analytics data:", error);
            });
    }
    // Fetch and display today's analytics data on page load
    fetchTodayAnalyticsData();
</script>


<div class="dashboard">
<div class="dashboard-card-graph">
      <h2>Books Returned</h2>
      <div class="graph-container">
        <canvas id="books-returned-chart"></canvas>
      </div>
    </div>
    <div class="filter-options">
      <label for="chart-type">Select Chart Type:</label>
      <select class="px-6 py-2"id="chart-type" onchange="updateChartType()">
        <option value="line">Line Chart</option>
        <option value="bar">Bar Chart</option>
        <option value="pie">Pie Chart</option>
      </select>

      <label for="department">Select Department:</label>
      <select class="px-6 py-2"id="department" onchange="fetchBooksReturnedData()">
        <option value="">All Departments</option>
        <option value="CSE">CSE</option>
        <option value="CSBS">CSBS</option>
        <!-- Add other departments here -->
      </select>

      <label for="year">Select Year:</label>
      <select class="px-6 py-2"id="year" onchange="fetchBooksReturnedData()">
        <option value="">All Years</option>
        <option value="1">First Year</option>
        <option value="2">Second Year</option>
        <option value="3">Third Year</option>
        <option value="4">Fourth Year</option>
        <!-- Add other years here -->
      </select>

      <label for="member-type">Select Member Type:</label>
      <select class="px-6 py-2"id="member-type" onchange="fetchBooksReturnedData()">
        <option value="">All Member Types</option>
        <option value="student">Student</option>
        <option value="staff">Staff</option>
      </select>


      <label for="time-filter">Select Time Filter:</label>
        <select class="px-6 py-2"id="time-filter" onchange="updateTimeFilter()">
            <option value="">Daily</option>
            <option value="weekly">Weekly</option>
            <option value="monthly">Monthly</option>
            <option value="yearly">Yearly</option>
        </select>

        <!-- Weekly and Monthly filter options -->
        <div id="weekly-filter" style="display: none;">
            <label for="start-date">Start Date:</label>
            <input type="date" id="start-date">

            <label for="end-date">End Date:</label>
            <input type="date" id="end-date" onchange="updateTimeFilter()">
        </div>

        <div id="monthly-filter" style="display: none;">
            <label for="selected-month">Select Month:</label>
            <input type="month" id="selected-month" onchange="updateTimeFilter()">
        </div>
        <div id="yearly-filter" style="display: none;">
            <label for="selected-year">Select Year:</label>
            <input type="year" id="selected-year" onchange="updateTimeFilter()">
        </div>
    </div>
  </div>

  <script>
    let selectedChartType = 'line';
    let booksReturnedData = {};
    let chart = null; // Track the current chart instance

    function updateChartType() {
      selectedChartType = document.getElementById('chart-type').value;
      destroyChart(); // Destroy the previous chart before updating
      updateChart();
    }

    function destroyChart() {
      if (chart !== null) {
        chart.destroy();
        chart = null;
      }
    }

    let chartWidth = 800;
    let chartHeight = 400;

     function updateChart() {
      const ctx = document.getElementById('books-returned-chart').getContext('2d');
      

      if (selectedChartType === 'line') {
        chart = new Chart(ctx, {
          type: 'line',
          data: booksReturnedData,
          options: {
            responsive: true,
            maintainAspectRatio: false,
            width: chartWidth,
            height: chartHeight
          }
        });
      } else if (selectedChartType === 'bar') {
        chart = new Chart(ctx, {
          type: 'bar',
          data: booksReturnedData,
          options: {
            responsive: true,
            maintainAspectRatio: false,
            width: chartWidth,
            height: chartHeight
          }
        });
      } else if (selectedChartType === 'pie') {
        chart = new Chart(ctx, {
          type: 'pie',
          data: booksReturnedData,
          options: {
            responsive: true,
            maintainAspectRatio: false,
            width: chartWidth,
            height: chartHeight
          }
        });
      }
      
    }

    function fetchBooksReturnedData() {
        const departmentFilter = document.getElementById('department').value;
        const yearFilter = document.getElementById('year').value;
        const memberTypeFilter = document.getElementById('member-type').value;
        const timeFilter = document.getElementById('time-filter').value;

        let fetchURL = `fetch_books_returned_data.php?department=${departmentFilter}&year=${yearFilter}&memberType=${memberTypeFilter}&timeFilter=${timeFilter}`;

        if (timeFilter === 'weekly') {
            const startDate = document.getElementById('start-date').value;
            const endDate = document.getElementById('end-date').value;
            fetchURL += `&startDate=${startDate}&endDate=${endDate}`;
        } else if (timeFilter === 'monthly') {
            const selectedMonth = document.getElementById('selected-month').value;
            fetchURL += `&selectedMonth=${selectedMonth}`;
        } else if (timeFilter === 'yearly') {
            const selectedYear = document.getElementById('selected-year').value;
            fetchURL += `&selectedYear=${selectedYear}`;
        }
console.log(fetchURL)
        fetch(fetchURL)
            .then(response => response.json())
            .then(data => {
                booksReturnedData = {
                    labels: data.labels,
                    datasets: [{
                        label: 'Books Returned',
                        data: data.values,
                        backgroundColor: ['rgb(75, 192, 192)', 'rgb(255, 99, 132)', 'rgb(54, 162, 235)', 'rgb(255, 205, 86)', 'rgb(153, 102, 255)', 'rgb(201, 203, 207)']
                    }]
                };

                updateChartType();
            })
            .catch(error => {
                console.error('Error fetching books returned data:', error);
            });
    }
   

    function updateTimeFilter() {
        const selectedTimeFilter = document.getElementById('time-filter').value;
        const weeklyFilter = document.getElementById('weekly-filter');
        const monthlyFilter = document.getElementById('monthly-filter');
        const yearlyFilter = document.getElementById('yearly-filter');

        if (selectedTimeFilter === 'weekly') {
            weeklyFilter.style.display = 'block';
            monthlyFilter.style.display = 'none';
            yearlyFilter.style.display = 'none';
        } else if (selectedTimeFilter === 'monthly') {
            weeklyFilter.style.display = 'none';
            monthlyFilter.style.display = 'block';
            yearlyFilter.style.display = 'none';
        } else if (selectedTimeFilter === 'yearly') {
            yearlyFilter.style.display = 'block';
            weeklyFilter.style.display = 'none';
            monthlyFilter.style.display = 'none';
        } else {
            weeklyFilter.style.display = 'none';
            monthlyFilter.style.display = 'none';
            yearlyFilter.style.display = 'none';
        }

        fetchBooksReturnedData();
    }
    fetchBooksReturnedData();



  </script>





<div class="dashboard">
    <div class="filter-options">
      <label for="chart-type-borrowed">Select Chart Type:</label>
      <select class="px-6 py-2"id="chart-type-borrowed" onchange="updateChartTypeBorrowed()">
        <option value="line">Line Chart</option>
        <option value="bar">Bar Chart</option>
        <option value="pie">Pie Chart</option>
      </select>

      <label for="department-borrowed">Select Department:</label>
      <select class="px-6 py-2"id="department-borrowed" onchange="fetchBooksBorrowedData()">
        <option value="">All Departments</option>
        <option value="CSE">CSE</option>
        <option value="CSBS">CSBS</option>
        <!-- Add other departments here -->
      </select>

      <label for="year-borrowed">Select Year:</label>
      <select class="px-6 py-2"id="year-borrowed" onchange="fetchBooksBorrowedData()">
        <option value="">All Years</option>
        <option value="1">First Year</option>
        <option value="2">Second Year</option>
        <!-- Add other years here -->
      </select>

      <label for="member-type-borrowed">Select Member Type:</label>
      <select class="px-6 py-2"id="member-type-borrowed" onchange="fetchBooksBorrowedData()">
        <option value="">All Member Types</option>
        <option value="student">Student</option>
        <option value="staff">Staff</option>
      </select>

      
        <!-- Books Borrowed Time Filter -->
        <label for="time-filter-borrowed">Select Time Filter:</label>
        <select class="px-6 py-2"id="time-filter-borrowed" onchange="updateTimeFilterBorrowed()">
            <option value="">Daily</option>
            <option value="weekly">Weekly</option>
            <option value="monthly">Monthly</option>
            <option value="yearly">Yearly</option>
        </select>

        <!-- Weekly and Monthly filter options for Books Borrowed -->
        <div id="weekly-filter-borrowed" style="display: none;">
            <label for="start-date-borrowed">Start Date:</label>
            <input type="date" id="start-date-borrowed">

            <label for="end-date-borrowed">End Date:</label>
            <input type="date" id="end-date-borrowed" onchange="updateTimeFilterBorrowed()">
        </div>

        <div id="monthly-filter-borrowed" style="display: none;">
            <label for="selected-month-borrowed">Select Month:</label>
            <input type="month" id="selected-month-borrowed" onchange="updateTimeFilterBorrowed()">
        </div>

        <div id="yearly-filter-borrowed" style="display: none;">
            <label for="selected-year-borrowed">Select Year:</label>
            <input type="year" id="selected-year-borrowed" onchange="updateTimeFilterBorrowed()">
        </div>
    </div>
    <div class="dashboard-card-graph">
        <h2>Books Borrowed</h2>
        <div class="graph-container">
            <canvas id="books-borrowed-chart"></canvas>
        </div>
    </div>
  </div>

<script>
    let selectedChartTypeBorrowed = 'line';
    let booksBorrowedData = {};
    let chartBorrowed = null;

    function updateChartTypeBorrowed() {
        selectedChartTypeBorrowed = document.getElementById('chart-type-borrowed').value;
        destroyChartBorrowed();
        updateChartBorrowed();
    }

    function destroyChartBorrowed() {
        if (chartBorrowed !== null) {
            chartBorrowed.destroy();
            chartBorrowed = null;
        }
    }

    function updateChartBorrowed() {
        const ctx = document.getElementById('books-borrowed-chart').getContext('2d');

        if (selectedChartTypeBorrowed === 'line') {
            chartBorrowed = new Chart(ctx, {
                type: 'line',
                data: booksBorrowedData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    width: chartWidth,
                    height: chartHeight
                }
            });
        } else if (selectedChartTypeBorrowed === 'bar') {
            chartBorrowed = new Chart(ctx, {
                type: 'bar',
                data: booksBorrowedData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    width: chartWidth,
                    height: chartHeight
                }
            });
        } else if (selectedChartTypeBorrowed === 'pie') {
            chartBorrowed = new Chart(ctx, {
                type: 'pie',
                data: booksBorrowedData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    width: chartWidth,
                    height: chartHeight
                }
            });
        }
    }
    function fetchBooksBorrowedData() {
        const departmentFilter = document.getElementById('department-borrowed').value;
        const yearFilter = document.getElementById('year-borrowed').value;
        const memberTypeFilter = document.getElementById('member-type-borrowed').value;
        const timeFilter = document.getElementById('time-filter-borrowed').value;

        let fetchURL = `fetch_books_borrowed_data.php?department=${departmentFilter}&year=${yearFilter}&memberType=${memberTypeFilter}&timeFilter=${timeFilter}`;

        if (timeFilter === 'weekly') {
            const startDate = document.getElementById('start-date-borrowed').value;
            const endDate = document.getElementById('end-date-borrowed').value;
            fetchURL += `&startDate=${startDate}&endDate=${endDate}`;
        } else if (timeFilter === 'monthly') {
            const selectedMonth = document.getElementById('selected-month-borrowed').value;
            fetchURL += `&selectedMonth=${selectedMonth}`;
        } else if (timeFilter === 'yearly') {
            const selectedYear = document.getElementById('selected-year-borrowed').value;
            fetchURL += `&selectedYear=${selectedYear}`;
        }

        fetch(fetchURL)
            .then(response => response.json())
            .then(data => {
                booksBorrowedData = {
                    labels: data.labels,
                    datasets: [{
                        label: 'Books Borrowed',
                        data: data.values,
                        backgroundColor: ['rgb(75, 192, 192)', 'rgb(255, 99, 132)', 'rgb(54, 162, 235)', 'rgb(255, 205, 86)', 'rgb(153, 102, 255)', 'rgb(201, 203, 207)']
                    }]
                };

                updateChartTypeBorrowed();
            })
            .catch(error => {
                console.error('Error fetching books borrowed data:', error);
            });
    }

    // Update Time Filter for Books Borrowed
    function updateTimeFilterBorrowed() {
        const selectedTimeFilter = document.getElementById('time-filter-borrowed').value;
        const weeklyFilter = document.getElementById('weekly-filter-borrowed');
        const monthlyFilter = document.getElementById('monthly-filter-borrowed');
        const yearlyFilter = document.getElementById('yearly-filter-borrowed');

        if (selectedTimeFilter === 'weekly') {
            weeklyFilter.style.display = 'block';
            monthlyFilter.style.display = 'none';
            yearlyFilter.style.display = 'none';
        } else if (selectedTimeFilter === 'monthly') {
            weeklyFilter.style.display = 'none';
            monthlyFilter.style.display = 'block';
            yearlyFilter.style.display = 'none';
        } else if (selectedTimeFilter === 'yearly') {
            yearlyFilter.style.display = 'block';
            weeklyFilter.style.display = 'none';
            monthlyFilter.style.display = 'none';
        } else {
            weeklyFilter.style.display = 'none';
            monthlyFilter.style.display = 'none';
            yearlyFilter.style.display = 'none';
        }

        fetchBooksBorrowedData();
    }

    fetchBooksBorrowedData();
</script>
<?php
include 'includes/footer.php';
?>
</body>
</html>
