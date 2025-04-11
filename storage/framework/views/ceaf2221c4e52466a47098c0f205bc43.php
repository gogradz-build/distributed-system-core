<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setTitle('Stock Report');
$sheet->mergeCells('A1:F1');
$sheet->setCellValue('A1', 'Stock Report');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
$sheet
    ->getStyle('A1:F1')
    ->getAlignment()
    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

$sheet->setCellValue('A2', 'Product Name');
$sheet->setCellValue('B2', 'Product Code');
$sheet->setCellValue('C2', 'Warehouse Name');
$sheet->setCellValue('D2', 'Quantity');
$sheet->setCellValue('E2', 'Price');
$sheet->setCellValue('F2', 'Total Prise');
$sheet->getStyle('A2:F2')->getFont()->setBold(true);

$row = 3;
$totalPriceSum = 0;

foreach ($stock as $item) {
    $sheet->setCellValue('A' . $row, $item->product->name);
    $sheet->setCellValue('B' . $row, $item->product->code);
    $sheet->setCellValue('C' . $row, $item->warehouse->name);
    $sheet->setCellValue('D' . $row, $item->quantity);

    $formattedPrice = 'Rs. ' . number_format($item->product->price, 2);
    $sheet->setCellValue('E' . $row, $formattedPrice);

    $totalPrice = $item->product->price * $item->quantity;
    $formattedTotalPrice = 'Rs. ' . number_format($totalPrice, 2);
    $sheet->setCellValue('F' . $row, $formattedTotalPrice);

    $totalPriceSum += $totalPrice;
    $row++;
}

$sheet->mergeCells('A' . $row . ':E' . $row);
$sheet->setCellValue('A' . $row, 'Total');
$sheet
    ->getStyle('A' . $row)
    ->getFont()
    ->setBold(true);
$sheet
    ->getStyle('A' . $row)
    ->getAlignment()
    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

    $totalPriceSum = 'Rs. ' . number_format($totalPriceSum, 2);

$sheet->setCellValue('F' . $row, $totalPriceSum);
$sheet
    ->getStyle('F' . $row)
    ->getFont()
    ->setBold(true);

foreach (range('A', 'F') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Generate Excel File
$writer = new Xlsx($spreadsheet);
$fileName = 'Stock_Report.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=\"$fileName\"");
$writer->save('php://output');
exit();
?>
<?php /**PATH F:\real world\Web Project\maharaja_hardware\mahraja_hardware\resources\views/stock-report-excel.blade.php ENDPATH**/ ?>