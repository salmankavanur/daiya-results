<?php
require 'vendor/autoload.php';

$filename = 'MARK LIST OF DAIYA EVEN SEMESTER EXAMINATION - APRIL 26.xlsx';
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filename);

foreach ($spreadsheet->getSheetNames() as $sheetName) {
    echo "Sheet: $sheetName\n";
    $sheet = $spreadsheet->getSheetByName($sheetName);
    $data = array_slice($sheet->toArray(), 0, 5); // first 5 rows
    print_r($data);
    echo "====================================\n";
}
