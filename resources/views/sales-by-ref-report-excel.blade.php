<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$todayDate = date('Y-m-d');
$sheet->setTitle('Sales Reports ' . $todayDate);
$sheet->mergeCells('A1:G1');
$sheet->setCellValue('A1', 'Sales Report'.'-'.$ref_name->first_name." ".$ref_name->last_name. (($start_date || $end_date) ? " - (".$start_date ."-".$end_date.")" : ""));
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
$sheet
    ->getStyle('A1:G1')
    ->getAlignment()
    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

$sheet->setCellValue('A2', 'ID');
$sheet->setCellValue('B2', 'Invoice ID');
$sheet->setCellValue('C2', 'Shop Name');
$sheet->setCellValue('D2', 'Created At');
$sheet->setCellValue('E2', 'Status');
$sheet->setCellValue('F2', 'Status Update At');
$sheet->setCellValue('G2', 'Total Amount');
$sheet->getStyle('A2:G2')->getFont()->setBold(true);

$row = 3;
$totalPaid = 0;
$totalCredit = 0;

foreach ($salesReport->sales as $index => $sale) {

    $creditAmount = $sale->total_price - $sale->received_amount;
    $orderStatus = match ($sale->order_status) {
        '1' => 'Start to Print',
        '2' => 'Print',
        '3' => 'Delivered',
        '4' => 'Completed',
        default => 'Unknown',
    };

    $sheet->setCellValue('A' . $row, $index + 1);
    $sheet->setCellValue('B' . $row, 'MH' . $sale->id);
    $sheet->setCellValue('C' . $row, $sale->shop->name ?? 'N/A');
    $sheet->setCellValue('D' . $row, \Carbon\Carbon::parse($sale->created_at)->format('Y-m-d'));
    $sheet->setCellValue('E'. $row, $orderStatus);
    $sheet->setCellValue('F'. $row, $sale->status_update_at);
    $sheet->setCellValue('G' . $row, $sale->total_price);
    $totalPaid += $sale->total_price;

    $row++;
}

$sheet->mergeCells('A' . $row . ':F' . $row);
$sheet->setCellValue('A' . $row, 'Total');
$sheet
    ->getStyle('A' . $row)
    ->getFont()
    ->setBold(true);
$sheet
    ->getStyle('A' . $row)
    ->getAlignment()
    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

$sheet->setCellValue('G' . $row, $totalPaid);
$sheet
    ->getStyle('E' . $row . ':F' . $row)
    ->getFont()
    ->setBold(true);

foreach (range('A', 'F') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

$writer = new Xlsx($spreadsheet);
$fileName = 'Sales_Report.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=\"$fileName\"");
$writer->save('php://output');
exit();
?>
