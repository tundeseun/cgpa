<?php
include_once('function/script.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$dept_new = isset($_POST['dept_new']) ? $_POST['dept_new'] : '';

// DataTables parameters
$draw = isset($_POST['draw']) ? intval($_POST['draw']) : 1;
$start = isset($_POST['start']) ? intval($_POST['start']) : 0;
$length = isset($_POST['length']) ? intval($_POST['length']) : 10;

// Count total records
$countQuery = $conn->prepare("SELECT COUNT(*) FROM course_new WHERE dept_newids = ? AND (degree_id IS NULL OR degree_id = '' OR degree_id = 0) AND status2 != 3");
$countQuery->bind_param("s", $dept_new);
$countQuery->execute();
$countQuery->bind_result($totalRecords);
$countQuery->fetch();
$countQuery->close();

// Fetch paginated records
$query = $conn->prepare("SELECT id, course_code, course_title, unit, status, specialization FROM course_new WHERE dept_newids = ? AND (degree_id IS NULL OR degree_id = '' OR degree_id = 0) AND status2 != 3 ORDER BY id DESC LIMIT ?, ?");
$query->bind_param("sii", $dept_new, $start, $length);
$query->execute();
$result = $query->get_result();

$data = [];
$sn = $start + 1;
while ($row = $result->fetch_assoc()) {
    // Get specialization title
    $specTitle = '';
    if (!empty($row['specialization'])) {
        $specQ = $conn->prepare("SELECT field_title FROM field_new WHERE id = ?");
        $specQ->bind_param("s", $row['specialization']);
        $specQ->execute();
        $specQ->bind_result($specTitle);
        $specQ->fetch();
        $specQ->close();
    }
    $data[] = [
        'sn' => $sn++,
        'course_code' => htmlspecialchars($row['course_code']),
        'course_title' => htmlspecialchars($row['course_title']),
        'unit' => htmlspecialchars($row['unit']),
        'status' => htmlspecialchars($row['status']),
        'specialization' => htmlspecialchars($specTitle),
        // Placeholder select, to be populated by JS
        'degree_input' => '<select class="form-control degree-select" data-course-id="' . $row['id'] . '"><option value="">Select Degree</option></select>',
        // Archive button
        'archive' => '<button type="button" class="btn btn-warning btn-sm archive-btn" data-course-id="' . $row['id'] . '">Archive</button>'
    ];
}
$query->close();

$response = [
    "draw" => $draw,
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalRecords,
    "data" => $data
];

header('Content-Type: application/json');
echo json_encode($response); 