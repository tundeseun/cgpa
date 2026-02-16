<?php
session_start();
$dept=$_SESSION["dept_new"];

include('function/connect.php');
// Get DataTables parameters
$draw = isset($_POST['draw']) ? intval($_POST['draw']) : 1;
$start = isset($_POST['start']) ? intval($_POST['start']) : 0;
$length = isset($_POST['length']) ? intval($_POST['length']) : 10;
$search = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';
$orderColumnIndex = isset($_POST['order'][0]['column']) ? intval($_POST['order'][0]['column']) : 0;
$orderDir = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'asc';

// Map DataTables column index to database column
$columns = [
    0 => 'course_new.id', // S/N (generated in PHP)
    1 => 'course_code',
    2 => 'course_title',
    3 => 'unit',
    4 => 'status',
    5 => 'field_new.field_title',
    6 => 'status2' // For Enable/Disable actions
];

// Determine the column for ordering
$orderColumn = $columns[$orderColumnIndex] ?? 'course_new.id';

// Base query
$query = "SELECT course_new.id AS id, course_code, course_title, unit, status, status2, 
                 field_new.field_title AS specialization, degree_new.degree AS degree
          FROM course_new 
          INNER JOIN field_new ON field_new.id = course_new.specialization 
          INNER JOIN degree_new ON degree_new.id = course_new.degree_id
          WHERE course_new.dept_newids = '$dept' AND course_new.status2 != 3";

// Search functionality
if (!empty($search)) {
    $query .= " AND (course_code LIKE '%$search%' OR 
                     course_title LIKE '%$search%' OR 
                     field_new.field_title LIKE '%$search%')";
}

// Total records count (without filtering)
$totalRecordsQuery = "SELECT COUNT(*) AS total FROM course_new WHERE dept_newids = '$dept'";
$totalRecordsResult = $conn->query($totalRecordsQuery);
$totalRecords = $totalRecordsResult->fetch_assoc()['total'];

// Total filtered records
$filteredRecordsQuery = "SELECT COUNT(*) AS total FROM ($query) AS subquery";
$filteredRecordsResult = $conn->query($filteredRecordsQuery);
$totalFilteredRecords = $filteredRecordsResult->fetch_assoc()['total'];

// Add ordering, limit, and offset
$query .= " ORDER BY $orderColumn $orderDir LIMIT $start, $length";
$result = $conn->query($query);

// Prepare the data for DataTables
$data = [];
$sn = $start + 1;
while ($row = $result->fetch_assoc()) {
    $cosid = $row['id'];
    $status = $row['status2'];
    // Human-readable status
    $statusText = 'Unknown';
    if ($status == 0) $statusText = 'Enabled';
    elseif ($status == 1) $statusText = 'Disabled';
    elseif ($status == 3) $statusText = 'Archived';
    // Render all three buttons, disabling the one matching current status
    $enableBtn = "<button type='button' class='btn btn-success action-btn enable-btn' data-id='$cosid'" . ($status == 0 ? " disabled" : "") . "><i class='fas fa-check'></i> Enable</button> ";
    $disableBtn = "<button type='button' class='btn btn-warning action-btn disable-btn' data-id='$cosid'" . ($status == 1 ? " disabled" : "") . "><i class='fas fa-ban'></i> Disable</button> ";
    $archiveBtn = "<button type='button' class='btn btn-secondary action-btn archive-btn' data-id='$cosid'" . ($status == 3 ? " disabled" : "") . "><i class='fas fa-archive'></i> Archive</button>";
    $btnGroup = '<div class="action-btn-group">' . $enableBtn . $disableBtn . $archiveBtn . '</div>';
    $action = $btnGroup;

    $data[] = [
        'sn' => $sn++,
        'course_code' => $row['course_code'] ?? 'No record',
        'course_title' => $row['course_title'] ?? 'No record',
        'unit' => $row['unit'] ?? 'No record',
        'status' => $row['status'] ?? 'No record',
        'current_status' => $statusText,
        'degree' => $row['degree'] ?? 'No record',
        'specialization' => $row['specialization'] ?? 'No record',
        'action' => $action
    ];
}

// Prepare JSON response
$response = [
    "draw" => intval($draw),
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($totalFilteredRecords),
    "data" => $data
];
// Debugging the SQL query and output
error_log("Query executed: $query");
error_log(print_r($data, true)); // Logs each row

// Return JSON response
echo json_encode($response);
?>
