<?php

require_once '../../../init.php';

$pdfFile = __DIR__ . '/assets/nameblock-terms.pdf';

if (file_exists($pdfFile)) {
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="NameBlock-Terms-of-Service.pdf"');
    readfile($pdfFile);
    exit;
} else {
    die('Error: Terms of Service file not found.');
}
