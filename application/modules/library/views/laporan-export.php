<?php 
$this->load->library('excel');

$alfabet = range('A', 'Z');

$working_row = 1;

if (isset($data[0]->village_name)) {
	$number_last_colom = 4;
}elseif (isset($data[0]->district_name)) {
	$number_last_colom = 3;
}elseif (isset($data[0]->city_name)) {
	$number_last_colom = 2;
}else{
	$number_last_colom = 1;
}
$merge = $number_last_colom;

$number_last_colom += count($data_kolom);


$last_column = $alfabet[$number_last_colom];

$this->excel->setActiveSheetIndex(0);
$active_sheet = $this->excel->getActiveSheet();
$active_sheet->setTitle('Keluarga');

$active_sheet->mergeCells('A'.$working_row.':'.$last_column.$working_row);
$active_sheet->setCellValue('A'.$working_row, get_option('lembaga_name'));
$active_sheet->getStyle('A'.$working_row)->getFont()->setBold(true);
$active_sheet->getStyle('A'.$working_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$working_row++;
$active_sheet->mergeCells('A'.$working_row.':'.$last_column.$working_row);
$active_sheet->setCellValue('A'.$working_row, "Laporan Kependudukan - " . date('d M Y'));
$active_sheet->getStyle('A'.$working_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$working_row++;
$working_row++;

$bordered_table = array();
$bordered_table_start = $working_row;
$bordered_table_end = $working_row;

$col = 0;
$active_sheet->setCellValue($alfabet[$col++].$working_row, "No");
$active_sheet->setCellValue($alfabet[$col++].$working_row, "Provinsi");
if (isset($data[0]->city_name)) {
	$active_sheet->setCellValue($alfabet[$col++].$working_row, "Kota/Kab.");
}
if (isset($data[0]->district_name)) {
	$active_sheet->setCellValue($alfabet[$col++].$working_row, "Kecamatan");
}
if (isset($data[0]->village_name)) {
	$active_sheet->setCellValue($alfabet[$col++].$working_row, "Desa");
}
$data_jumlah = array();
foreach ($data_kolom as $key => $value) {
	$data_jumlah[$key] = 0;
	$active_sheet->setCellValue($alfabet[$col++].$working_row, $value);
}
$working_row++;

foreach ($data as $no => $item) {
    $col = 0;
    $active_sheet->setCellValue($alfabet[$col++].$working_row, $no + 1);
    $active_sheet->setCellValue($alfabet[$col++].$working_row, $item->province_name);
    if (isset($item->city_name)) {
    	$active_sheet->setCellValue($alfabet[$col++].$working_row, $item->city_name);
    }
    if (isset($item->district_name)) {
    	$active_sheet->setCellValue($alfabet[$col++].$working_row, $item->district_name);
    }
    if (isset($item->village_name)) {
    	$active_sheet->setCellValue($alfabet[$col++].$working_row, $item->village_name);
    }
    foreach ($data_kolom as $key => $value) {
    	$data_jumlah[$key] += $item->$key;
    	$active_sheet->setCellValue($alfabet[$col++].$working_row, $item->$key);
    }
    $working_row++;
}
$active_sheet->setCellValue($alfabet[0].$working_row, "Total Data");
$active_sheet->mergeCells('A'.$working_row.':'.$alfabet[$merge++].$working_row);
foreach ($data_kolom as $key => $value) {
	$active_sheet->setCellValue($alfabet[$merge++].$working_row, $data_jumlah[$key]);
}
$bordered_table_end = $working_row;
$working_row++;


$bordered_table[] = 'A'.$bordered_table_start.':'.$last_column.$bordered_table_end;

if($bordered_table){
    foreach ($bordered_table as $cells) {
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => '00000000'),
                ),
            ),
        );
        $active_sheet->getStyle($cells)->applyFromArray($styleArray);
    };
}

for ($i=0; $i < $number_last_colom ; $i++) { 
    $active_sheet->getColumnDimension($alfabet[$i])->setAutoSize(true);
}

$this->excel->setActiveSheetIndex(0);

$filename = 'Laporan Penkependudukan';
$filename .= '.xls'; //save our workbook as this file name
header('Content-Type: application/vnd.ms-excel'); //mime type
header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
header('Cache-Control: max-age=0'); //no cache

$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  

$objWriter->save('php://output');
?>

<script>window.close();</script>