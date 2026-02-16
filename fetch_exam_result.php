<?php
// Add this at the very beginning to catch all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

// Start output buffering to prevent any unwanted output
ob_start();

session_start();

// Set proper JSON header
header('Content-Type: application/json');

try {
    // Check if script.php exists
    $script_path = 'function/script.php';
    if (! file_exists($script_path)) {
        throw new Exception("Script file not found at: " . $script_path);
    }

    // Include the database connection
    include_once $script_path;

    // Check if connection variable exists
    if (! isset($conn)) {
        throw new Exception("Database connection variable (\$conn) not defined in script.php");
    }

    // Check connection status
    if (! $conn) {
        throw new Exception("Database connection failed: " . mysqli_connect_error());
    }

    // Get request parameters
    $level      = isset($_GET['level']) ? mysqli_real_escape_string($conn, $_GET['level']) : 'faculties';
    $faculty_id = isset($_GET['faculty_id']) ? mysqli_real_escape_string($conn, $_GET['faculty_id']) : null;
    $dept_id    = isset($_GET['dept_id']) ? mysqli_real_escape_string($conn, $_GET['dept_id']) : null;
    $field_id   = isset($_GET['field_id']) ? mysqli_real_escape_string($conn, $_GET['field_id']) : null;

    $response = [];

    switch ($level) {
        case 'faculties':
            // Get all faculties with counts
            $queryFaculties = "
                SELECT DISTINCT
                    fac_new.id,
                    fac_new.faculty,
                    COUNT(DISTINCT testscore.dept) as dept_count,
                    COUNT(testscore.id) as total_results
                FROM testscore
                INNER JOIN fac_new ON fac_new.id = testscore.fac
                WHERE (testscore.session_of_grad IS NOT NULL AND testscore.session_of_grad != '')
                AND testscore.stage >= 3
                GROUP BY fac_new.id, fac_new.faculty
                ORDER BY fac_new.faculty
            ";

            $result = mysqli_query($conn, $queryFaculties);
            if (! $result) {
                throw new Exception("Faculties query failed: " . mysqli_error($conn));
            }

            $faculties = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $faculties[] = [
                    'id'            => $row['id'],
                    'name'          => $row['faculty'],
                    'dept_count'    => (int) $row['dept_count'],
                    'total_results' => (int) $row['total_results'],
                ];
            }

            $response = [
                'data'    => $faculties,
                'level'   => 'faculties',
                'success' => true,
                'count'   => count($faculties),
            ];
            break;

        case 'departments':
            if (! $faculty_id) {
                throw new Exception("Faculty ID is required for departments level");
            }

            $queryDepartments = "
                SELECT DISTINCT
                    dept_new.id,
                    dept_new.department,
                    COUNT(DISTINCT testscore.field) as specialization_count,
                    COUNT(testscore.id) as total_results
                FROM testscore
                INNER JOIN dept_new ON dept_new.id = testscore.dept
                WHERE testscore.fac = ?
                AND (testscore.session_of_grad IS NOT NULL AND testscore.session_of_grad != '')
                AND testscore.stage >= 3
                GROUP BY dept_new.id, dept_new.department
                ORDER BY dept_new.department
            ";

            $stmt = mysqli_prepare($conn, $queryDepartments);
            mysqli_stmt_bind_param($stmt, 's', $faculty_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (! $result) {
                throw new Exception("Departments query failed: " . mysqli_error($conn));
            }

            $departments = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $departments[] = [
                    'id'                   => $row['id'],
                    'name'                 => $row['department'],
                    'specialization_count' => (int) $row['specialization_count'],
                    'total_results'        => (int) $row['total_results'],
                ];
            }

            mysqli_stmt_close($stmt);

            $response = [
                'data'       => $departments,
                'level'      => 'departments',
                'faculty_id' => $faculty_id,
                'success'    => true,
                'count'      => count($departments),
            ];
            break;

        case 'specializations':
            if (! $faculty_id || ! $dept_id) {
                throw new Exception("Faculty ID and Department ID are required for specializations level");
            }

            $querySpecializations = "
                SELECT DISTINCT
                    field_new.id,
                    field_new.field_title,
                    COUNT(testscore.id) as result_count
                FROM testscore
                INNER JOIN field_new ON field_new.id = testscore.field
                WHERE testscore.fac = ? AND testscore.dept = ?
                AND (testscore.session_of_grad IS NOT NULL AND testscore.session_of_grad != '')
                AND testscore.stage >= 3
                GROUP BY field_new.id, field_new.field_title
                ORDER BY field_new.field_title
            ";

            $stmt = mysqli_prepare($conn, $querySpecializations);
            mysqli_stmt_bind_param($stmt, 'ss', $faculty_id, $dept_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (! $result) {
                throw new Exception("Specializations query failed: " . mysqli_error($conn));
            }

            $specializations = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $specializations[] = [
                    'id'           => $row['id'],
                    'name'         => $row['field_title'],
                    'result_count' => (int) $row['result_count'],
                ];
            }

            mysqli_stmt_close($stmt);

            $response = [
                'data'       => $specializations,
                'level'      => 'specializations',
                'faculty_id' => $faculty_id,
                'dept_id'    => $dept_id,
                'success'    => true,
                'count'      => count($specializations),
            ];
            break;

        case 'results':
            if (! $faculty_id || ! $dept_id || ! $field_id) {
                throw new Exception("Faculty ID, Department ID, and Field ID are required for results level");
            }

            $queryResults = "
                SELECT DISTINCT
                    testscore.resulttype,
                    testscore.stage,
                    testscore.mode AS smode,
                    testscore.effectivedate,
                    testscore.session_of_grad AS sec,
                    testscore.external,
                    external_cgpa.fname AS external_fname,
  external_cgpa.lname AS external_lname,
                    testscore.degree
                FROM testscore
                INNER JOIN external_cgpa ON external_cgpa.id = testscore.external

                WHERE testscore.fac = ? AND testscore.dept = ? AND testscore.field = ?
                AND (testscore.session_of_grad IS NOT NULL AND testscore.session_of_grad != '')
                AND testscore.stage >= 3
                ORDER BY testscore.effectivedate DESC, testscore.session_of_grad DESC
                LIMIT 50
            ";

            $stmt = mysqli_prepare($conn, $queryResults);
            mysqli_stmt_bind_param($stmt, 'sss', $faculty_id, $dept_id, $field_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (! $result) {
                throw new Exception("Results query failed: " . mysqli_error($conn));
            }

            $results = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $resultType   = ($row['resulttype'] == 0) ? "Main Result" : "Supplementary Result";
                $mode         = ($row['smode'] == 0) ? "Part-Time" : "Full-Time";
                $externalName = $row['external_fname'] . ' ' . $row['external_lname'];

                $results[] = [
                    'type'          => $resultType,
                    'mode'          => $mode,
                    'stage'         => (int) $row['stage'],
                    'degree_id'     => $row['degree'],
                    'effectivedate' => $row['effectivedate'],
                    'external'      => $externalName,
                    'externalId'    => $row['external'],
                    'resulttype'    => (int) $row['resulttype'],
                    'sec'           => $row['sec'],
                    'smode'         => (int) $row['smode'],
                ];
            }

            mysqli_stmt_close($stmt);

            $response = [
                'data'       => $results,
                'level'      => 'results',
                'faculty_id' => $faculty_id,
                'dept_id'    => $dept_id,
                'field_id'   => $field_id,
                'success'    => true,
                'count'      => count($results),
            ];
            break;

        default:
            throw new Exception("Invalid level specified: " . $level);
    }

    // Clean the output buffer and send response
    ob_end_clean();
    echo json_encode($response);
    mysqli_close($conn);

} catch (Exception $e) {
    // Clean any output buffer
    ob_end_clean();

    // Set error status
    http_response_code(500);

    echo json_encode([
        'error'      => $e->getMessage(),
        'success'    => false,
        'debug_info' => [
            'file'  => __FILE__,
            'line'  => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ],
    ]);
}
