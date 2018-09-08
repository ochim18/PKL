<?php 
$this->load->library('excel');

$alfabet = range('A', 'Z');

// Keluarga
$working_row = 1;
$number_last_colom = 17;

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
$active_sheet->setCellValue('A'.$working_row, "Data Kependudukan - " . date('d M Y'));
$active_sheet->getStyle('A'.$working_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$working_row++;
$working_row++;

$bordered_table = array();
$bordered_table_start = $working_row;
$bordered_table_end = $working_row;

$col = 0;
$active_sheet->setCellValue($alfabet[$col++].$working_row, "No");
$active_sheet->setCellValue($alfabet[$col++].$working_row, "Kode Provinsi");
$active_sheet->setCellValue($alfabet[$col++].$working_row, "Kode Kota/Kab.");
$active_sheet->setCellValue($alfabet[$col++].$working_row, "Kode Kecamatan");
$active_sheet->setCellValue($alfabet[$col++].$working_row, "Kode Desa");
$active_sheet->setCellValue($alfabet[$col++].$working_row, "RW");
$active_sheet->setCellValue($alfabet[$col++].$working_row, "RT");
$active_sheet->setCellValue($alfabet[$col++].$working_row, "No Rumah");
$active_sheet->setCellValue($alfabet[$col++].$working_row, "No KK");
$active_sheet->setCellValue($alfabet[$col++].$working_row, "Jumlah PUS");
$active_sheet->setCellValue($alfabet[$col++].$working_row, "Peserta KB");
$active_sheet->setCellValue($alfabet[$col++].$working_row, "UKP Suamin");
$active_sheet->setCellValue($alfabet[$col++].$working_row, "UKP Istri");
$active_sheet->setCellValue($alfabet[$col++].$working_row, "JALH Laki-laki");
$active_sheet->setCellValue($alfabet[$col++].$working_row, "JALH Perempuan");
$active_sheet->setCellValue($alfabet[$col++].$working_row, "JAMH Laki-laki");
$active_sheet->setCellValue($alfabet[$col++].$working_row, "JAMH Perempuan");
$active_sheet->setCellValue($alfabet[$col++].$working_row, "Kesertaan Ber-KB");
$working_row++;

foreach ($data as $no => $item) {
    $col = 0;
    $active_sheet->setCellValue($alfabet[$col++].$working_row, $no + 1);
    $active_sheet->setCellValue($alfabet[$col++].$working_row, $item->province_code);
    $active_sheet->setCellValue($alfabet[$col++].$working_row, $item->city_code);
    $active_sheet->setCellValue($alfabet[$col++].$working_row, $item->district_code);
    $active_sheet->setCellValue($alfabet[$col++].$working_row, $item->village_code);
    $active_sheet->setCellValue($alfabet[$col++].$working_row, $item->rw);
    $active_sheet->setCellValue($alfabet[$col++].$working_row, $item->rt);
    $active_sheet->setCellValue($alfabet[$col++].$working_row, $item->house_number);
    $active_sheet->setCellValue($alfabet[$col++].$working_row, $item->family_number);
    $active_sheet->setCellValue($alfabet[$col++].$working_row, $item->number_of_pus);
    $active_sheet->setCellValue($alfabet[$col++].$working_row, $item->number_of_kb_members);
    $active_sheet->setCellValue($alfabet[$col++].$working_row, $item->married_age_of_husband);
    $active_sheet->setCellValue($alfabet[$col++].$working_row, $item->married_wife_age);
    $active_sheet->setCellValue($alfabet[$col++].$working_row, $item->number_of_children_born_lk);
    $active_sheet->setCellValue($alfabet[$col++].$working_row, $item->number_of_children_born_pr);
    $active_sheet->setCellValue($alfabet[$col++].$working_row, $item->number_of_children_lk);
    $active_sheet->setCellValue($alfabet[$col++].$working_row, $item->number_of_children_born_pr);
    $active_sheet->setCellValue($alfabet[$col++].$working_row, $item->participation_kb_value);
    $bordered_table_end = $working_row;
    $working_row++;
}

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

// Jiwa

$working_row = 1;
$number_last_colom = 11;

$last_column = $alfabet[$number_last_colom];

$this->excel->createSheet(NULL, 1);
$this->excel->setActiveSheetIndex(1);
$active_sheet = $this->excel->getActiveSheet();
$active_sheet->setTitle('Jiwa');

$active_sheet->mergeCells('A'.$working_row.':'.$last_column.$working_row);
$active_sheet->setCellValue('A'.$working_row, "Data Kependudukan - " . get_option('app_name'));
$active_sheet->getStyle('A'.$working_row)->getFont()->setBold(true);
$active_sheet->getStyle('A'.$working_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$working_row++;
$active_sheet->mergeCells('A'.$working_row.':'.$last_column.$working_row);
$active_sheet->setCellValue('A'.$working_row, date('d M Y'));
$active_sheet->getStyle('A'.$working_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$working_row++;
$working_row++;

$bordered_table = array();
$bordered_table_start = $working_row;
$bordered_table_end = $working_row;

$col = 0;
$active_sheet->setCellValue($alfabet[$col++].$working_row, "No");
$active_sheet->setCellValue($alfabet[$col++].$working_row, "No KK");
$active_sheet->setCellValue($alfabet[$col++].$working_row, "No NIK");
$active_sheet->setCellValue($alfabet[$col++].$working_row, "Nama");
$active_sheet->setCellValue($alfabet[$col++].$working_row, "Tanggal Lahir");
$active_sheet->setCellValue($alfabet[$col++].$working_row, "Jenis Kelamin");
$active_sheet->setCellValue($alfabet[$col++].$working_row, "Agama");
$active_sheet->setCellValue($alfabet[$col++].$working_row, "Hubungan KK");
$active_sheet->setCellValue($alfabet[$col++].$working_row, "Pendidikan");
$active_sheet->setCellValue($alfabet[$col++].$working_row, "Pekerjaan");
$active_sheet->setCellValue($alfabet[$col++].$working_row, "Status Perkawinan");
$active_sheet->setCellValue($alfabet[$col++].$working_row, "JKN");
$working_row++;

foreach ($data_anggota as $no => $item) {
    $col = 0;
    $active_sheet->setCellValue($alfabet[$col++].$working_row,  $no + 1);
    $active_sheet->setCellValue($alfabet[$col++].$working_row, $item->family_number);
    $active_sheet->setCellValue($alfabet[$col++].$working_row, $item->nik);
    $active_sheet->setCellValue($alfabet[$col++].$working_row, $item->name);
    $active_sheet->setCellValue($alfabet[$col++].$working_row, $item->dob);
    $active_sheet->setCellValue($alfabet[$col++].$working_row, $item->sex_value);
    $active_sheet->setCellValue($alfabet[$col++].$working_row, $item->religion_value);
    $active_sheet->setCellValue($alfabet[$col++].$working_row, $item->relationship_value);
    $active_sheet->setCellValue($alfabet[$col++].$working_row, $item->education_value);
    $active_sheet->setCellValue($alfabet[$col++].$working_row, $item->work_value);
    $active_sheet->setCellValue($alfabet[$col++].$working_row, $item->status_value);
    $active_sheet->setCellValue($alfabet[$col++].$working_row, $item->jkn_value);
    $bordered_table_end = $working_row;
    $working_row++;
}

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

$filename = 'Data Penkependudukan';
$filename .= '.xls'; //save our workbook as this file name
header('Content-Type: application/vnd.ms-excel'); //mime type
header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
header('Cache-Control: max-age=0'); //no cache

$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  

$objWriter->save('php://output');
?>

<script>window.close();</script>