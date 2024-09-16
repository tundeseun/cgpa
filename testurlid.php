<?php
// Get the current URL
$current_url = "menu.php?id/1"; ///$_SERVER['REQUEST_URI'];

// Split the URL using the '/' character
$parts = explode('?', $current_url);


$newurl= $parts[1];
$part = explode('/', $newurl);
echo $part[0]."=".$part[1];
// Find the position of 'id' in the URL
$id_position = array_search('id', $parts);

if ($id_position !== false && isset($parts[$id_position + 1])) {
    // 'id' is found in the URL, and there's a value after it
    $id_value = $parts[$id_position + 1];
}
    // Now, $id_value contains the value '1' in this example
//     echo "The value of id is: " . $id_value;
// } else {
//     // 'id' is not found in the URL or there's no value after it
//     echo "The 'id' parameter is not present in the URL.";
// }
?>
