<?php
require_once './simple_html_dom.php/simple_html_dom.php'; // Adjust the path if necessary

// Define the URL of the page to scrape
$url = 'https://www.minhngoc.net.vn/ket-qua-xo-so/mien-bac/09-08-2024.html'; // Replace with the actual URL

// Create a DOM object
$html = file_get_html($url);

// Initialize an array to hold the results
$results = [];

// Function to extract results from a single page
function extract_results($html, $date) {
    $data = [];
    $data['date'] = $date;

    // Extract the numbers for each lottery prize
    $prizes = [
        'Giải ĐB' => 'td.giaidb',
        'Giải nhất' => 'td.giai1',
        'Giải nhì' => 'td.giai2',
        'Giải ba' => 'td.giai3',
        'Giải tư' => 'td.giai4',
        'Giải năm' => 'td.giai5',
        'Giải sáu' => 'td.giai6',
        'Giải bảy' => 'td.giai7'
    ];

    foreach ($prizes as $prize => $selector) {
        $elements = $html->find($selector);
        if ($elements === false) {
            echo "Selector '$selector' not found.";
            continue;
        }
        $data[$prize] = [];
        foreach ($elements as $element) {
            $data[$prize][] = trim($element->plaintext);
        }
    }
    return $data;
}

// Find all date blocks
$date_blocks = $html->find('.box_kqxs');

// Database connection
$servername = "127.0.0.1";
$username = "root";
$password = "root";
$dbname = "ketqua_xoso";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare SQL statement
$stmt = $conn->prepare("INSERT INTO ketqua_xoso (date, results) VALUES (?, ?)");

foreach ($date_blocks as $block) {
    // Extract the date
    $date_link = $block->find('.title a', 1);
    if ($date_link) {
        $date = date('Y-m-d', strtotime(str_replace('/', '-', $date_link->plaintext)));
        $result_data = extract_results($block, $date);
        $json_results = json_encode($result_data);

        // Bind parameters and execute
        $stmt->bind_param('ss', $date, $json_results);

        if (!$stmt->execute()) {
            echo "Error: " . $stmt->error;
        }
    }
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
