<?php
session_start();
?>


<header class="bg-white-500 shadow-xl">
        <div class="mx-auto pt-4 pb-1 px-4 flex justify-center items-center flex-col sm:flex-col lg:flex-row">
            <!-- Logo Image centered on mobile -->
            <div class="flex items-center">
                <img src="http://localhost:8081/sairam_lms/admin/images/sabas..png" alt="Logo" class="h-7  mb-4 lg:hidden">
                <img src="http://localhost:8081/sairam_lms/admin/images/sabas..png" alt="Your Logo" class="h-7 mb-4 hidden lg:block">
            </div>
            <nav class="w-full lg:flex  lg:space-x-4 text-purple text-lg lg:justify-end">
                <ul class="lg:flex flex mb-4 justify-center my-2 space-x-4 flex-wrap">
                    <li class="px-4 py-1 hover:bg-gray-200 hover:text-gray-900 bg-white-900 font-bold">
                        <a href="http://localhost:8081/sairam_lms/index.php">Home</a>
                    </li>

                    <?php
                        if (isset($_SESSION['authenticated']) == true) {
                    ?>
                    <li class="px-4 py-1 font-bold hover:bg-gray-200 hover:text-gray-900 bg-white-900 relative group dropdown">
                        <a href="http://localhost:8081/sairam_lms/admin/dashboard.php" class="dropbtn">Dashboard</a>
                        </li>
                    <li class="px-4 py-1 font-bold hover:bg-gray-200 hover:text-gray-900 bg-white-900 relative group dropdown">
                        <a href="#" class="dropbtn" nowrap>Members</a>
                        <div class="dropdown-content absolute hidden bg-white shadow-lg py-1 z-10 text-sm" nowrap style="margin-top: 3px;" >
                            <a href="http://localhost:8081/sairam_lms/admin/members/members2.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" nowrap style=" width: 160px;font-size: 15px;">Manage Members</a>
                            <a href="http://localhost:8081/sairam_lms/testings/generate_report.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;">Manage Reports</a>
                            <a href="http://localhost:8081/sairam_lms/admin/circulation.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;">Settings</a>
                        </div>
                    </li>
                    <li class="px-4 py-1 relative group dropdown hover:bg-gray-200 hover:text-gray-900 bg-white-900 font-bold">
                        <a href="#" class="dropbtn">Books</a>
                        <div class="dropdown-content absolute hidden bg-white shadow-lg mt-2 py-2 z-10 " style="margin-top: 3px;">
                            <a href="http://localhost:8081/sairam_lms/admin/books/add_books.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Add Books</a>
                            <a href="http://localhost:8081/sairam_lms/admin/books/books.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Book List</a>
                            <a href="http://localhost:8081/sairam_lms/admin/books/borrowings.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Borrowings</a>
                        </div>
                    </li>
                    <li class="px-4 py-1 relative group dropdown hover:bg-gray-200 hover:text-gray-900 bg-white-900 text-black font-bold">
                        <a href="http://localhost:8081/sairam_lms/admin/academics/academics.php" class="dropbtn">Academics</a>
                        <div class="dropdown-content absolute hidden bg-white shadow-lg mt-2 py-2 z-10" style="margin-top: 3px;">
                            <a href="http://localhost:8081/sairam_lms/admin/academics/academics.php#department" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Department</a>
                            <a href="http://localhost:8081/sairam_lms/admin/academics/academics.php#class" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Class</a>
                            <a href="http://localhost:8081/sairam_lms/admin/academics/academics.php#year" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Year</a>
                            <a href="http://localhost:8081/sairam_lms/admin/academics/academics.php#course" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Course</a>
                        </div>
                    </li>
                    <li class="px-4 py-1 text-red-600 hover:bg-gray-200 hover:text-red-700 bg-white-900 font-bold">
                        <a href="http://localhost:8081/sairam_lms/admin/logout.php">Logout</a>
                    </li>
                    <?php
                        } else {
                    ?>
                    <li class="px-4 py-1 hover:bg-gray-200 hover:text-red-900 bg-white-900 font-bold">
                        <a href="http://localhost:8081/sairam_lms/admin/login.php">Admin Login</a>
                    </li>
                    <?php
                        }
                    ?>
                </ul>
            </nav>
        </div>
    </header>