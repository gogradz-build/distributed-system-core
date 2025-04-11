<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$todayDate = date('Y-m-d');
$sheet->setTitle('Collection Reports ' . $todayDate);
$sheet->mergeCells('A1:F1');
$sheet->setCellValue('A1', 'Collection Report'." - ".$refName->first_name ." ".$refName->last_name ." ". ($start_date && $end_date ? " - ({$start_date} - {$end_date})" : ""));
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
$sheet
    ->getStyle('A1:F1')
    ->getAlignment()
    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

$sheet->setCellValue('A2', 'ID');
$sheet->setCellValue('B2', 'Invoice ID');
$sheet->setCellValue('C2', 'Shop Name');
// $sheet->setCellValue('D2', 'Sale Created');
$sheet->setCellValue('d2', 'Status');
$sheet->setCellValue('e2', 'Status Update Date');
$sheet->setCellValue('f2', 'Date Amount To Current Date');
$sheet->setCellValue('g2', 'Paid Amount');
$sheet->setCellValue('h2', 'Credit Amount');
$sheet->getStyle('A2:F2')->getFont()->setBold(true);

// Sort sales by month and day
$salesSorted = $salesReport->sales->sort(function ($a, $b) {
    $dateA = Carbon::parse($a->status_update_at);
    $dateB = Carbon::parse($b->status_update_at);

    return ($dateA->month === $dateB->month) 
        ? $dateA->day - $dateB->day 
        : $dateA->month - $dateB->month; 
});

$row = 3;
$totalPaid = 0;
$totalCredit = 0;

// foreach ($salesReport->sales as $index => $sale) {
foreach ($salesSorted as $index => $sale) {
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
    // $sheet->setCellValue('D' . $row, \Carbon\Carbon::parse($sale->created_at)->format('Y-F-d'));
    $sheet->setCellValue('d' . $row, $orderStatus);
    $sheet->setCellValue('e' . $row, \Carbon\Carbon::parse($sale->status_update_at)->format('Y-F-d'));
    $sheet->setCellValue('f' . $row, \Carbon\Carbon::parse($sale->status_update_at)->diffInDays(\Carbon\Carbon::now()));
    $sheet->setCellValue('g' . $row, $sale->received_amount);
    $sheet->setCellValue('h' . $row, $creditAmount);

    $totalPaid += $sale->received_amount;
    $totalCredit += $creditAmount;

    $row++;
}

$sheet->mergeCells('A' . $row . ':f' . $row);
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
$sheet->setCellValue('H' . $row, $totalCredit);
$sheet
    ->getStyle('G' . $row . ':G' . $row)
    ->getFont()
    ->setBold(true);

foreach (range('A', 'F') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

$writer = new Xlsx($spreadsheet);
$fileName = "Collection_Report_{$refName->first_name}_{$refName->last_name}_{$todayDate}.xlsx";

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=\"$fileName\"");
$writer->save('php://output');
exit();
?>
<?php /**PATH F:\real world\Web Project\maharaja_hardware\mahraja_hardware\resources\views/collection-by-ref-report-excel.blade.php ENDPATH**/ ?>