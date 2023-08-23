<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

// Adjust the path to your tested_websites.txt file
$testedWebsitesFilePath = 'path/to/tested_websites.txt';

// Function to read the tested websites and send them as SSE events
function sendTestedWebsites($filePath) {
    $lastFileSize = 0;

    while (true) {
        clearstatcache();
        $fileSize = filesize($filePath);

        if ($fileSize > $lastFileSize) {
            $handle = fopen($filePath, 'r');
            fseek($handle, $lastFileSize);
            while (($line = fgets($handle)) !== false) {
                echo "data: $line\n\n";
                flush();
            }
            fclose($handle);

            $lastFileSize = $fileSize;
        }

        sleep(1); // Delay to check for updates
    }
}

// Start sending tested websites as SSE events
sendTestedWebsites($testedWebsitesFilePath);
?>
