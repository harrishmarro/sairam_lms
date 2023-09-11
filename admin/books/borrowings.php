<?php
include '../connection/db.php';

// Check if the user is authenticated
session_start();
error_reporting(0);

if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: login.php');
    exit();
}


$fineupdate_query = "SELECT * FROM borrowings";
$fineresult = $conn->query($fineupdate_query);
while($rows = $fineresult->fetch_assoc())
{
    
        if ($rows['status'] == 'Borrowed') {
            $update = 0;
            $current_borrowing_id = $rows['borrowing_id'];
            $current_book_id = $rows['book_id'];
            $current_member_id = $rows['member_id'];
            $current_due_date = $rows['due_date'];
            $returndate = date('Y-m-d');
            $fineRate = $_SESSION['CirculationAmount'];
            $fine_amount = calculateFine($current_due_date, $returndate, $fineRate);
            $updateSql = "UPDATE borrowings SET fine_amount = '$fine_amount' WHERE borrowing_id='$current_borrowing_id'";
            $conn->query($updateSql);
           
 $fine_amount;
             $updateSql1 = "UPDATE fines SET fine_amount = $fine_amount,fine_date='$returndate' WHERE book_id=$current_book_id AND member_id=$current_member_id";

        $conn->query($updateSql1);

            

        }
    
}


//pagination


// Determine the total number of records
$sqlCount = "SELECT COUNT(*) as total FROM borrowings";
$resultCount = $conn->query($sqlCount);
$rowCount = $resultCount->fetch_assoc();
$totalRecords = $rowCount['total'];

// Set the number of records to display per page
$recordsPerPage =10;

// Calculate the total number of pages
$totalPages = ceil($totalRecords / $recordsPerPage);

// Get the current page number
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $currentPage = $_GET['page'];
} else {
    $currentPage = 1;
}

// Calculate the starting offset for retrieving records
$offset = ($currentPage - 1) * $recordsPerPage;

 







$count =0;
// Return a book
if (isset($_GET['return_borrowing'])) {
    echo "success";
    $borrowing_id = $_GET['return_borrowing'];
    $returned_date = date('Y-m-d');
    $sql1 = "SELECT member_id from borrowings where borrowing_id='$borrowing_id'";
    $res3 =  $conn->query($sql1);
    $row_member = $res3->fetch_assoc();
    $member_id = $row_member['member_id'];

    $sql59 = "SELECT bookscount from members where member_id='$member_id'";
    $res39 =  $conn->query($sql59);
    $row_member2 = $res39->fetch_assoc();
    $count = $row_member2['bookscount'];
    

   

    // Update the borrowing status to returned
    $sql = "UPDATE borrowings SET status = 'Returned' , returned_date='$returned_date' WHERE borrowing_id = '$borrowing_id'";
    $conn->query($sql);
    $count--;
    // Update the book availability status to true
    $updateSql = "UPDATE books SET available = available+1 WHERE book_id = (
        SELECT book_id FROM borrowings WHERE borrowing_id = $borrowing_id
    )";
    $conn->query($updateSql);
    $updateSql2 = "UPDATE members SET bookscount = $count WHERE member_id = $member_id";
    $conn->query($updateSql2);
    header('Location: borrowings.php');
    exit();
}
// Calculate Fines
function calculateFine($dueDate, $returnDate, $fineRate)
{
    $dueDateTime = new DateTime($dueDate);
    $returnDateTime = new DateTime($returnDate);
    $diff = $returnDateTime->diff($dueDateTime);
    $daysLate = $diff->days;
if($diff->format('%R%a')< 0)
{
    
     $fineAmount = $daysLate * $fineRate;
    $minimumFineAmount = 5;
    $fineAmount = max($fineAmount, $minimumFineAmount);
    return $fineAmount;
}
else{
    return 0;
}

// Ensure the fine amount is not less than the minimum amount (e.g., Rs. 5)

}


// Add Fine
function addFine($bookId, $fineAmount, $fineDate)
{
    include 'db.php';

    // Insert fine details into the fines table
    $sql = "INSERT INTO fines (book_id, fine_amount, fine_date) VALUES ($bookId, $fineAmount, '$fineDate')";
    $conn->query($sql);
}

// Get Fines
function getFines()
{
    include 'db.php';

    // Retrieve fine details from the fines table
    $sql = "SELECT * from fines";
    $result = $conn->query($sql);

    // Fetch the fines and return as an array
    $fines = [];
    while ($row = $result->fetch_assoc()) {
        $fines[] = $row;
    }

    return $fines;
}

// Handle form submission for calculating and adding fines
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the form is submitted for fine calculation
    if (isset($_POST['calculate_fine'])) {
        $dueDate = $_POST['due_date'];
        $returnDate = $_POST['return_date'];
        $fineRate = 5; // Fine rate per day

        $fineAmount = calculateFine($dueDate, $returnDate, $fineRate);
    }

    // Check if the form is submitted for adding the fine
    if (isset($_POST['add_fine'])) {
        $bookId = $_POST['book_id'];
        $fineAmount = $_POST['fine_amount'];
        $fineDate = date('Y-m-d');

        addFine($bookId, $fineAmount, $fineDate);
    }
}
if (isset($_GET['search'])) 
{
    $search = $_GET['searchInput'];
    $sql = "SELECT borrowings.book_id, borrowings.borrowing_id, borrowings.fine_amount, books.title, members.name, borrowings.borrowed_date, borrowings.due_date, borrowings.status, borrowings.renew
    FROM borrowings
    INNER JOIN books ON borrowings.book_id = books.book_id
    INNER JOIN members ON borrowings.member_id = members.member_id
    WHERE members.member_id LIKE '%$search%' OR borrowings.borrowing_id LIKE '%$search%' OR books.book_id LIKE '%$search%'
    ORDER BY borrowings.borrowed_date DESC LIMIT $offset, $recordsPerPage";
}
 else
 {
// Retrieve all borrowings
$sql = "SELECT borrowings.book_id, borrowings.borrowing_id, borrowings.fine_amount,
books.title, members.name, borrowings.borrowed_date, borrowings.due_date,
borrowings.status, borrowings.renew
FROM borrowings
INNER JOIN books ON borrowings.book_id = books.book_id
INNER JOIN members ON borrowings.member_id = members.member_id
ORDER BY borrowings.borrowed_date DESC
LIMIT $offset, $recordsPerPage;
";
 }
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Borrowings</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
            color: #333;
        }
  

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
    color:#D00000;
}
.current-page {
            color: #B2ABFD; /* Apply the desired color */
            font-weight: bold; /* Optionally, make it bold */
        }

      

      </style>
</head>
<body>

<?php
    include '../includes/header.php';
    ?>
<div class="container mx-auto">
    <h1 class="text-center align-middle my-5 font-bold text-2xl">Manage Borrowings</h1>

<div class="grid grid-rows-1 sm:grid-rows-1 gap-4 m-4">
        <!-- First Row -->
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">

          <div class="bg-gray-200 p-4 text-center">
            
          <form action="borrowings.php" method="GET" class="search-form">
        
        <input type="text" name="searchInput" id="searchInput" Placeholder="Enter Borrow id or book id etc.."  class="w-1/2 px-4 py-2" >
        <input type="submit" name="search" value="search" class="py-2 px-4 bg-purple-500">
        
    </form>
</div>
          <div class="bg-gray-200 p-4 text-center">Column 2</div>

          <div class="bg-gray-200 p-4 text-center hidden lg:block sm:block">Column 3</div>
        </div>

        <div class="bg-gray-200 p-4 text-center grid-cols-1 lg:hidden sm:hidden">Column 3</div>

        <!-- Second Row -->
        <div class="grid grid-row-2 gap-4">
            <div class="bg-gray-100 text-center h-2/5 overflow-scroll">
                <div class="outer h-full">
                     <div class="inner">
                        <table  class="w-full p-10">
                            <tbody>
                            <tr class="bg-purple-500 sticky top-0">
                            <th nowrap class="px-6 py-3  text-center text-xs leading-4 font-bold text-gray-900 uppercase tracking-wider">Book id</th>
                            <th nowrap class="px-6 py-3  text-center text-xs leading-4 font-bold text-gray-900 uppercase tracking-wider">Borrowing ID</th>
                            <th nowrap class="px-6 py-3  text-center text-xs leading-4 font-bold text-gray-900 uppercase tracking-wider">Fine Amount</th>
                            <th nowrap class="px-6 py-3  text-center text-xs leading-4 font-bold text-gray-900 uppercase tracking-wider">book title</th>
                            <th nowrap class="px-6 py-3  text-center text-xs leading-4 font-bold text-gray-900 uppercase tracking-wider">Member Name</th>
                            <th nowrap class="px-6 py-3  text-center text-xs leading-4 font-bold text-gray-900 uppercase tracking-wider">Borrowed Date</th>
                            <th nowrap class="px-6 py-3  text-center text-xs leading-4 font-bold text-gray-900 uppercase tracking-wider">Due Date</th>
                            <th nowrap class="px-6 py-3  text-center text-xs leading-4 font-bold text-gray-900 uppercase tracking-wider">Status</th>
                            <th nowrap class="px-6 py-3  text-center text-xs leading-4 font-bold text-gray-900 uppercase tracking-wider">renew</th>
                            <th nowrap class="px-6 py-3  text-center text-xs leading-4 font-bold text-gray-900 uppercase tracking-wider">Action</th>
                            </tr>
                            <?php while ($row = $result->fetch_array()) 
                            {
                                ?>
                                <tr>
                                   
                                <td nowrap class="px-3 py-2 boder-b-2 text-center whitespace-no-wrap text-sm leading-5 font-medium text-gray-900"><?php echo $row[0]; ?></td>
                                <td nowrap class="px-3 py-2 boder-b-2 text-center whitespace-no-wrap text-sm leading-5 font-medium text-gray-900"><?php echo $row[1]; ?></td>
                                <td nowrap class="px-3 py-2 boder-b-2 text-center whitespace-no-wrap text-sm leading-5 font-medium text-gray-900"><?php echo "Rs.".$row[2].".00"; ?></td>
                                <td nowrap class="px-3 py-2 boder-b-2 text-center whitespace-no-wrap text-sm leading-5 font-medium text-gray-900"><?php echo $row[3]; ?></td>
                                <td nowrap class="px-3 py-2 boder-b-2 text-center whitespace-no-wrap text-sm leading-5 font-medium text-gray-900"><?php echo $row[4]; ?></td>
                                <td <td nowrap class="px-3 py-2 boder-b-2 text-center whitespace-no-wrap text-sm leading-5 font-medium text-gray-900"><?php echo $row[5]; ?></td>
                                <td <td nowrap class="px-3 py-2 boder-b-2 text-center whitespace-no-wrap text-sm leading-5 font-medium text-gray-900"contenteditable="true"><?php echo $row[6]; ?></td>
                                <td nowrap class="px-3 py-2 boder-b-2 text-center whitespace-no-wrap text-sm leading-5 font-medium text-gray-900"><?php echo $row[7]; ?></td>
                                <td nowrap class="px-3 py-2 boder-b-2 text-center whitespace-no-wrap text-sm leading-5 font-medium text-gray-900"><?php echo $row[8]; ?></td>
                                
                                <td <td nowrap class="px-3 py-2 boder-b-2 text-center whitespace-no-wrap text-sm leading-5 font-medium text-gray-900"class="action-buttons">
                                    <?php if($row['status'] === 'Borrowed' && $row['fine_amount'] == 0) {?>
                                        <form action="renew.php" method="post">
                                            <input type="text" name="book_id" id="book_id" style="display:none;" value="<?php echo $row[0]; ?>"
                ">
                                            <button class="renew-button m-2 px-2 py-1 bg-orange-500 text-gray-100" type="submit" class="" id="renew" style="font-weight:700">Renew</button>
                                        </form>
                                        <a class="return-button inline m-2 px-2 py-1 bg-red-900 text-gray-100" href="borrowings.php?return_borrowing=<?php echo $row['borrowing_id']; ?>" id="return" style="font-weight:700">Return</a>
                                    <?php } ?>
                                    <?php if ($row['status'] === 'Borrowed' && $row['fine_amount']> 0) {?>
                                        <a class="return-button m-2 px-2 py-1 bg-red-600 text-gray-100"  href="borrowings.php?return_borrowing=<?php echo $row['borrowing_id']; ?>" id="return" style="font-weight:700">Return</a>
                                    <?php }
                                    ?>
                                </td>
                                </tr>
                                <?php
                             } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="bg-purple-500 text-center h-10 sticky bottom-0">
                                    <?php
                        echo "<div class='borrowings'>";
                        if ($totalPages > 1) {
                        if ($currentPage > 1) {
                            echo "<a href='borrowings.php?page=" . ($currentPage - 1) . "'>Previous</a>";
                        }
        
                        for ($i = 1; $i <= $totalPages; $i++) {
                            if ($i == $currentPage) {
                                echo "<span class='current'>$i </span>";
                            } else {
                                echo "<a href='borrowings.php?page=$i'>$i </a>";
                            }
                        }
        
                        if ($currentPage < $totalPages) {
                            echo "<a href='borrowings.php?page=" . ($currentPage + 1) . "'> Next</a>";
                        }
                        }
        
                        echo "</div>";
                        ?>
                    </div>
                </div>
            </div>
        </div>
      
        <!-- Third Row -->
      </div>
      </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
