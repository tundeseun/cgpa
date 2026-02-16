<?php
include_once('function/connect.php');
function displayScoresVerticallyHorizontally($conn) {
    // Assuming you have a database connection established.

    // Query to select data from your tables
    $query = "SELECT t.matric, t.score, t.exam_sec, c.course_code 
              FROM testscore t 
              JOIN course_new c ON t.cozid = c.cgpa_id
              ORDER BY t.matric";

    // Execute the query and fetch the data
    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));

    // Initialize variables to track current matric
    $currentMatric = null;

    // Create an array to store the scores
    $scores = array();

    echo "Matric Number\tYear Of Entry\tMode of Study";

    while ($row = mysqli_fetch_assoc($result)) {
        $matric = $row['matric'];
        $courseCode = $row['course_code'];
        $score = $row['score'];

        // Check if the matric has changed
        if ($matric != $currentMatric) {
            // Print the previous matric's scores and start a new row
            if ($currentMatric !== null) {
                // Add the calculations for HEE 701, HEE 702, etc.
                $hee701 = 0;
                $hee702 = 0;
                $yearOfEntry="";
                $modeOfStudy="";
                $tut="";
                $tup="";
                $cgpa="";
                $result="";
                $remark="";
                // ... You should calculate the values for all other columns similarly.

                // Print the values for HEE 701, HEE 702, etc.
                echo "\t$hee701\t$hee702";
                
                // Add more calculations for TUT, TUP, TGP, CGPA, etc.
                $tut = 0;
                $tup = 0;
                // ... You should calculate the values for all other columns similarly.

                // Print the values for TUT, TUP, TGP, CGPA, etc.
                echo "\t$tut\t$tup\t$cgpa\t$result\t$remark";

                echo "\n"; // Start a new line for the next student
            }

            // Output matric, year of entry, and mode of study
            echo "$matric\t$yearOfEntry\t$modeOfStudy";

            // Initialize the array for the current matric
            $scores = array();
            
            $currentMatric = $matric;
        }

        // Store the score in the array
        $scores[$courseCode] = $score;
    }

    // Print the last matric's scores
    if ($currentMatric !== null) {
        // Add the calculations for HEE 701, HEE 702, etc.
        $hee701 = 0;
        $hee702 = 0;
        // ... You should calculate the values for all other columns similarly.

        // Print the values for HEE 701, HEE 702, etc.
        echo "\t$hee701\t$hee702";
        
        // Add more calculations for TUT, TUP, TGP, CGPA, etc.
        $tut = 0;
        $tup = 0;
        // ... You should calculate the values for all other columns similarly.

        // Print the values for TUT, TUP, TGP, CGPA, etc.
        echo "\t$tut\t$tup\t$cgpa\t$result\t$remark";

        echo "\n"; // Start a new line for the next student
    }

    // Free the result set
    mysqli_free_result($result);

    // Close the database connection
    mysqli_close($your_db_connection);
}

// Call the function to display the scores
displayScoresVerticallyHorizontally($conn);

?>
