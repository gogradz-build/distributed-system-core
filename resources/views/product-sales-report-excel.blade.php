<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$todayDate = date('Y-m-d');
$sheet->setTitle('Product Sales Report ' . $todayDate);
$sheet->mergeCells('A1:D1');
$sheet->setCellValue('A1', 'Product Sales Report');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
$sheet
    ->getStyle('A1:D1')
    ->getAlignment()
    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

$sheet->setCellValue('A2', 'ID');
$sheet->setCellValue('B2', 'Product Name');
$sheet->setCellValue('C2', 'Product Name');
$sheet->setCellValue('D2', 'Quantity Sold');
$sheet->getStyle('A2:D2')->getFont()->setBold(true);

$row = 3;
$totalQuantity = 0;

foreach ($productReport as $index => $product) {
    $sheet->setCellValue('A' . $row, $index + 1);
    $sheet->setCellValue('B' . $row, $product['code']);
    $sheet->setCellValue('C' . $row, $product['name']);
    $sheet->setCellValue('D' . $row, $product['quantity_sold']);

    $totalQuantity += $product['quantity_sold'];

    $row++;
}

$sheet->mergeCells('A' . $row . ':C' . $row);
$sheet->setCellValue('A' . $row, 'Total');
$sheet
    ->getStyle('A' . $row)
    ->getFont()
    ->setBold(true);
$sheet
    ->getStyle('A' . $row)
    ->getAlignment()
    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

$sheet->setCellValue('D' . $row, $totalQuantity);
$sheet
    ->getStyle('C' . $row . ':D' . $row)
    ->getFont()
    ->setBold(true);

foreach (range('A', 'D') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

$writer = new Xlsx($spreadsheet);
$fileName = 'Product_Sales_Report_' . $todayDate . '.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=\"$fileName\"");
header('Cache-Control: max-age=0');
$writer->save('php://output');
exit();

?>
