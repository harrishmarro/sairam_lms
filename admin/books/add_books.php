<?php
include '../connection/db.php';
error_reporting(3);
// Check if the user is authenticated
session_start();
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: login.php');
    exit();
}
// Import books from Excel file
if (isset($_POST['import'])) {
    // Check if a file is selected
    if ($_FILES['file']['name']) {
        $filename = $_FILES['file']['tmp_name'];
        require_once '../../Classes/PHPExcel/IOFactory.php';

        try {
            $objPHPExcel = PHPExcel_IOFactory::load($filename);
            $worksheet = $objPHPExcel->getActiveSheet();
            $highestRow = $worksheet->getHighestRow();

            // Start from the second row to skip the header row
            for ($row = 2; $row <= $highestRow; ++$row) {
                $isbn_no = $worksheet->getCell('A' . $row)->getValue();
                $code = $worksheet->getCell('B' . $row)->getValue();
                $start = 1;
                $end = 1;
              
                $title = $worksheet->getCell('E' . $row)->getValue();
                $sub_title = $worksheet->getCell('F' . $row)->getValue();
                $category = $worksheet->getCell('G' . $row)->getValue();
                $author = $worksheet->getCell('H' . $row)->getValue();
                $price = $worksheet->getCell('I' . $row)->getValue();
                $edition = $worksheet->getCell('J' . $row)->getValue();
                $edition_year = $worksheet->getCell('K' . $row)->getValue();
                $language = $worksheet->getCell('L' . $row)->getValue();
                $publisher = $worksheet->getCell('M' . $row)->getValue();
                $publication_year = $worksheet->getCell('N' . $row)->getValue();
                $publisher_year = $worksheet->getCell('N' . $row)->getValue();
                $series = $worksheet->getCell('O' . $row)->getValue();
                $rack_location = $worksheet->getCell('P' . $row)->getValue();
                $total_page = $worksheet->getCell('Q' . $row)->getValue();
                $source = $worksheet->getCell('R' . $row)->getValue();
                $uploaded_date = $worksheet->getCell('S' . $row)->getValue();
                $updated_date = $worksheet->getCell('T' . $row)->getValue();
                $subject = $worksheet->getCell('U' . $row)->getValue();
                $department = $worksheet->getCell('V' . $row)->getValue();
                $available = $end - $start + 1;
                $available = $available/$available;
                // Perform validation on the book data
                $errors = array();

                // Validate required fields
                if (empty($isbn_no)) {
                    $errors[] = 'ISBN number is required.';
                }
               
                if (empty($title)) {
                    $errors[] = 'Title is required.';
                }
                if (empty($author)) {
                    $errors[] = 'Author is required.';
                }
                  $code = generateUniqueCode($isbn, $i);
                // Insert the book if there are no validation errors
                if (empty($errors)) {
                    
                    // Generate and insert multiple books
                    $successCount = 0; // Counter for successful insertions
                    $errorMessages = []; // Array to store error messages
                
                    // Generate and insert multiple books
                    for ($i = $start; $i <= $end; $i++) {
                        // Generate a unique code
                
                         $code = generateUniqueCode($isbn_no, $i);
                        
                        // Insert the book into the database with the generated code
                        $sql = "INSERT INTO books (isbn_no, code, title, sub_title, category, author, price, edition, edition_year, language, publisher, publisher_year, series, rack_location, total_page, source,available,subject,department) VALUES ('$isbn_no', '$code', '$title', '$sub_title', '$category', '$author', '$price', '$edition', '$edition_year', '$language', '$publisher', '$publisher_year', '$series', '$rack_location', '$total_page', '$source','$available','$subject','$department')";
                         $result = $conn->query($sql);
                
                        if ($result) {
                             $successCount++; // Increment the success count
                        } else {
                            // Error occurred while adding the book
                          
                            echo "error is : ".mysqli_error($conn);
                            $errorMessages[] = "Error adding book with number $i";
                
                        }
                    }
                     // Check if all books were successfully inserted
                    if ($successCount == 1) {
                        // All books were inserted successfully
                        // Redirect or display a success message
                        echo "All books were added successfully!";
                        
                    } else {
                        // Some books could not be inserted
                        // Display the error messages
                        echo "Errors occurred while adding the books:<br>";
                        foreach ($errorMessages as $errorMessage) {
                            echo "- $errorMessage<br>";
                        }
                    }
                } else {
                    // Handle validation errors
                    foreach ($errors as $error) {
                        echo '<p>' . $error . '</p>';
                    }
                }
            }
            
            // header('Location: books.php');
            exit();
        } catch (Exception $e) {
            $importError = 'Failed to import the Excel file.';
        }
    } else {
        $importError = 'Please select an Excel file to import.';
    }
    header("Location:add_books.php");
}


function validateBookInput($title, $author, $publication_year)
{
    $errors = [];

    if (empty($title)) {
        $errors[] = 'Please enter the book title.';
    }

    if (empty($author)) {
        $errors[] = 'Please enter the author name.';
    }

    if (empty($publication_year)) {
        $errors[] = 'Please enter the publication year.';
    } elseif (!is_numeric($publication_year)) {
        $errors[] = 'Publication year should be a number.';
    }

    return $errors;
}

$currenttotalbooks = "SELECT COUNT(*) from books";
$resultbooks = $conn->query($currenttotalbooks);

// if ($resultbooks) {
//     $row = $resultbooks->fetch_assoc();
//     $count = $row['COUNT(*)'];
//     echo "Total books: " . $count;
// } else {
//     echo "Error executing query: " . $conn->error;
// }

// Add Book Form
if(isset($_POST['submit'])) {
    // Retrieve the form data
     $isbn_no = $_POST['isbn_no'];
     $title = $_POST['title'];
    
     $sub_title = $_POST['sub_title'];
     $category = $_POST['category'];
     $author = $_POST['author'];
    $price = $_POST['price'];
     $edition = $_POST['edition'];
     $edition_year = $_POST['edition_year'];
     $language = $_POST['language'];
    $publisher = $_POST['publisher'];
     $publisher_year = $_POST['publisher_year'];
     $series = $_POST['series'];
     $rack_location = $_POST['rack_location'];
     $total_page = $_POST['total_page'];
     $source = $_POST['source'];
     $subject = $_POST['subject'];
     $department = $_POST['department'];

     $startNumber = $_POST['start'];
     $copies = $_POST['end'];
     $endNumber = $startNumber+$copies;
     $available = 1;

    // Generate and insert multiple books
    $successCount = 0; // Counter for successful insertions
    $errorMessages = []; // Array to store error messages

    // Generate and insert multiple books
    for ($i = $startNumber; $i <= $endNumber; $i++) {
        // Generate a unique code

         $code =  generateUniqueCode($isbn_no, $i); 
        // Insert the book into the database with the generated code
        $sql = "INSERT INTO books (isbn_no, code, title, sub_title, category, author, price, edition, edition_year, language, publisher, publisher_year, series, rack_location, total_page, source,subject,department,available) VALUES ('$isbn_no', '$code', '$title', '$sub_title', '$category', '$author', '$price', '$edition', '$edition_year', '$language', '$publisher', '$publisher_year', '$series', '$rack_location', '$total_page', '$source','$subject','$department','$available')";
         $result = $conn->query($sql);

        if ($result) {
             $successCount++; // Increment the success count
        } else {
            // Error occurred while adding the book
            echo "false";
            echo "error is : ".mysqli_error($conn);
            $errorMessages[] = "Error adding book with number $i";

        }
    }
     // Check if all books were successfully inserted
     if ($successCount == ($endNumber - $startNumber + 1)) {
        // All books were inserted successfully
        // Redirect or display a success message
        $_SESSION['success_badd'] = "All books were added successfully!";
    } else {
        // Some books could not be inserted
        // Display the error messages
        echo "Errors occurred while adding the books:<br>";
        foreach ($errorMessages as $errorMessage) {
            echo "- $errorMessage<br>";
        }
    }

    // Redirect or display a success message
    // header('Location: add_books.php');
}

// Function to generate a unique code based on book title and number
function generateUniqueCode($isbn_no, $number) {
    // Generate a unique code using the book title and number
    
    $digits = array();

    while (count($digits) < 6) {
        $digit = mt_rand(0, 9);
        if (!in_array($digit, $digits)) {
            $digits[] = $digit;
        }
    }

    $code = implode('', $digits);
    return $code;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
    </style>
    <style>
       .cont{
        max-width: 92vw;
       }
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        h1 {
            text-align: center;
            margin-top: 50px;
        }

        form {
            margin-bottom: 20px;
        }

        form label {
            display: block;
            margin-bottom: 10px;
        }

        form input[type="text"],
        form input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }

        form input[type="submit"] {
            padding: 10px 20px;
            font-size: 16px;
            background-image: linear-gradient(to right,rgba(101, 146, 255, 1) , rgba(180, 162, 252, 1),  rgba(127, 95, 255, 1), rgba(157, 185, 255, 1),  rgba(207, 194, 255, 1));
            color: #ffffff;
            border: none;
            cursor: pointer;
            border-radius: 10px;
        }

        .error {
            color: #dc3545;
            margin-bottom: 20px;
        }



        .actions {
            display: flex;
            gap: 5px;
        }

        .actions a {
            display: inline-block;
            padding: 5px 10px;
            text-decoration: none;
            background-color: #007bff;
            color: #ffffff;
        }

        a.logout {
            display: block;
            text-align: right;
            margin-top: 20px;
        }
         /* CSS styles for the header */
         body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .navbar {
            background-color: #333;
            color: #fff;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar h1 {
            margin: 0;
            padding: 0;
            color: #fff;
            font-size: 24px;
            font-weight: bold;
        }

        .navbar ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .navbar li {
            margin-right: 10px;
        }

        .navbar a {
            color: #fff;
            text-decoration: none;
            padding: 10px;
        }

        .navbar a:hover {
            background-color: #555;
        }

        .active {
            background-color: #555;
        }
        .import-excel{
            padding: 1vw;
    font-size: 18px;
    /* : 33px; */
    margin-bottom: 19px;
    font-weight: 700;
    color: black;
        }
        .import-excel-form{
            display: flex;
            width: 100%;
        border: none;
        background-color: #EEEFFF;
        border-radius: 10px;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
    color: black;
    font-size: 19px;
    padding: 21px;
    font-weight: 700; /* This centers elements from top and bottom */
  align-items: center;
        }
        /* dropdown feature for academics */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }
        .form-column {
    display: flex;
    justify-content: space-evenly;
    width: 100%;
        border: none;
        background-color: #EEEFFF;
        border-radius: 10px;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
  }

  .form-column {
    flex: 1;
    padding: 10px;
    box-sizing: border-box;
  }

  .form-column label,
  .form-column input {
    display: block;
    margin-bottom: 10px;
  }
  #column1,#column2,#column3{
    width: 100%;
    margin: 5px;
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
    </style>
</head>
<body >

<?php
    include '../includes/header.php';
    ?>


<div class="cont mx-auto"style="margin:auto; width:100%;">
<div class="msg">
    <?php if(isset($_SESSION['success_badd'])) {echo $_SESSION['success_badd'];}?>
</div>
    <!-- Import Books Form -->
    <h2 style="font-size:30px;font-weight:bold">Import Books</h2>
    <form method="POST" enctype="multipart/form-data" class="import-excel-form">
        <label style="margin-right:50px">Select Excel File:</label>
        <input type="file" name="file" required class="import-excel" style="background-color: #B2ABFD;border-radius: 10px;margin-right:50px">
        
        <input type="submit" name="import" value="Import" style="width:25%">
    </form>

    <!-- Import Error Handling -->
    <?php if (isset($importError)) : ?>
        <div class="error">
            <?php echo $importError; ?>
        </div>
    <?php endif; ?>
    <h2 style="font-size:30px;font-weight:bold;">Add Book</h2>

    
    <form method="POST" action="add_books.php">
    <div class ="form-column">
        <div id="column1" >
    <label>ISBN:</label>
        <input type="text" name="isbn_no" required>
     
        <label>Start:</label>
        <input type="text" name="start" value="<?php if ($resultbooks) {
    $row = $resultbooks->fetch_assoc();
    $count = $row['COUNT(*)'];
    echo $count+1;
} ?>">
        <label>No.Of Copies:</label>
        <input type="text" name="end" required>
        <label>Title:</label>
        <input type="text" name="title" required>
        <label>Subtitle:</label>
        <input type="text" name="sub_title">
        <label>Category:</label>
        <input type="text" name="category" required>
        </div>
<div id="column2">
        <label>Author:</label>
        <input type="text" name="author" required>
        <label>Price:</label>
        <input type="text" name="price" required>

        <label>Edition:</label>
        <input type="text" name="edition" required>
        <label>Edition Year:</label>
        <input type="text" name="edition_year" required>
        <label>Language:</label>
        <input type="text" name="language" required>
        <label>Publisher:</label>
        <input type="text" name="publisher" required>
        <!-- <label>Publisher Year:</label>
        <input type="text" name="publisher_year" required> -->
        <br>
        <center>    <input type="submit" name="submit" value="add_Book" style="width:100%" ></center>
        </div>
<div id="column3">
        <label>Series:</label>
        <input type="text" name="series" required>

        <label>Rack Location:</label>
        <input type="text" name="rack_location" required>
        <label>Total Pages:</label>
        <input type="text" name="total_page" required>
        <label>Source:</label>
        <input type="text" name="source" required>
        <label>Subject:</label>
        <input type="text" name="subject" required>
        <label>Department:</label>
        <input type="text" name="department" required>

</div>

        </div>

    </form>


    <!-- Error Handling -->
    <?php if (!empty($errors)) : ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $error) : ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>
<?php
include '../includes/footer.php';
?>
</body>
</html>