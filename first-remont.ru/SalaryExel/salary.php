<?php
    if (!defined('_MACHINE_ON')) {
        define( '_MACHINE_ON', 1 );
    }

    $uploadDir = 'tmpXML/';

    if(count($_FILES) != 2){
		exit;
	}
	foreach( $_FILES as $file ){
	    move_uploaded_file( $file['tmp_name'], $uploadDir . basename($file['name']) );
	}
    
    include('framework.php');

    $PHPExcel = new PHPExcel();
	
	$xmlFormat = 'Excel5';
	$masterExcel = 'master.xls';
	$baseExcel = 'base.xls';
	$objReader = PHPExcel_IOFactory::createReader($xmlFormat);
	$filesToZip = array('baseChecked.xlsx', 'masterChecked.xlsx');
	$zipName = date('U');
	
	$objMasterExcel = $objReader->load($uploadDir.$masterExcel);
	$objBaseExcel = $objReader->load($uploadDir.$baseExcel);

	$masterArray = array();
	$baseArray = array();
	$intersection = array();
	$numColumsMaster = 0;
	$numColumsBase = 0;
	$masterSpreadsheet = array('receipt'=>'','costPart'=>'','costRepair'=>'');
	$baseSpreadsheet = array('receipt'=>'','costPart'=>'','costRepair'=>'');
	$patterns = array('receipt'=>'/^(?:к|К)витанция[\s\.]{0,2}/','costPart'=>'/^(?:с|С)тоим[\s\.]{0,2}запчастей[\s\.]{0,2}/','costRepair'=>'/^(?:С|с)умма[\s\.]{0,2}/');

	$objMasterExcel->setActiveSheetIndex(0);
	$sheetMaster = $objMasterExcel->getActiveSheet();
	
	$objBaseExcel->setActiveSheetIndex(0);
	$sheetBase = $objBaseExcel->getActiveSheet();

	//get number collum of master spreadsheet
	while(true){
		$cell = $sheetMaster->getCell(PHPExcel_Cell::stringFromColumnIndex($numColumsMaster).'1');
		$val = $cell->getValue();
		if(empty($val)){
			break;
		}
		$numColumsMaster++;
	}
	//get number collum of base spreadsheet
	while(true){
		$cell = $sheetBase->getCell(PHPExcel_Cell::stringFromColumnIndex($numColumsBase).'1');
		$val = $cell->getValue();
		if(empty($val)){
			break;
		}
		$numColumsBase++;
	}
	
	//find collums' names in masterSpreadsheet
	for ($i=0; $i < $numColumsMaster; $i++) {
		$cellLetter = PHPExcel_Cell::stringFromColumnIndex($i);
		$masterSpreadsheet['receipt'] = ( empty($masterSpreadsheet['receipt']) && preg_match($patterns['receipt'], $sheetMaster->getCell($cellLetter.'1')))? $cellLetter : $masterSpreadsheet['receipt'];
		$masterSpreadsheet['costPart'] = ( empty($masterSpreadsheet['costPart']) && preg_match($patterns['costPart'], $sheetMaster->getCell($cellLetter.'1')))? $cellLetter: $masterSpreadsheet['costPart'];
		$masterSpreadsheet['costRepair'] = ( empty($masterSpreadsheet['costRepair']) && preg_match($patterns['costRepair'], $sheetMaster->getCell($cellLetter.'1')))? $cellLetter: $masterSpreadsheet['costRepair'];
	}
	//find collums' names in baseSpreadsheet
	for ($i=0; $i < $numColumsBase; $i++) { 
		$cellLetter = PHPExcel_Cell::stringFromColumnIndex($i);
		$baseSpreadsheet['receipt'] = ( empty($baseSpreadsheet['receipt']) && preg_match($patterns['receipt'], $sheetBase->getCell($cellLetter.'1')))? $cellLetter : $baseSpreadsheet['receipt'];
		$baseSpreadsheet['costPart'] = ( empty($baseSpreadsheet['costPart']) && preg_match($patterns['costPart'], $sheetBase->getCell($cellLetter.'1')))? $cellLetter: $baseSpreadsheet['costPart'];
		$baseSpreadsheet['costRepair'] = ( empty($baseSpreadsheet['costRepair']) && preg_match($patterns['costRepair'], $sheetBase->getCell($cellLetter.'1')))? $cellLetter: $baseSpreadsheet['costRepair'];
	}	

	//get Master Array
	$i = 2;
	while(true){
		$cell = $sheetMaster->getCell($masterSpreadsheet['receipt'].$i);
		$val = $cell->getValue();
		if(empty($val)){
			break;
		}
		$masterArray[$cell->getValue()] = $i++;
	}
	//get Base Array
	$i = 2;
	while(true){
		$cell = $sheetBase->getCell($baseSpreadsheet['receipt'].$i);
		$val = $cell->getValue();
		if(empty($val)){
			break;
		}
		$baseArray[$cell->getValue()] = $i++;
	}

	$intersection = array_intersect_key($masterArray,$baseArray);

	// Check cells in spreadsheets
	
	foreach ($intersection as $key => $value){
		$rgb = ($sheetMaster->getCell($masterSpreadsheet['costPart'].$masterArray[$key])->getValue() == $sheetBase->getCell($baseSpreadsheet['costPart'].$baseArray[$key])->getValue() && $sheetMaster->getCell($masterSpreadsheet['costRepair'].$masterArray[$key])->getValue() == $sheetBase->getCell($baseSpreadsheet['costRepair'].$baseArray[$key])->getValue())?'00FF00':'F01C1C';
		$sheetMaster->getStyle('A'.$masterArray[$key].':'.PHPExcel_Cell::stringFromColumnIndex($numColumsMaster-1).$masterArray[$key])
		    ->getFill()
		    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
		    ->getStartColor()
		    ->setARGB($rgb);
		$sheetBase->getStyle('A'.$baseArray[$key].':'.PHPExcel_Cell::stringFromColumnIndex($numColumsBase-1).$baseArray[$key])
		    ->getFill()
		    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
		    ->getStartColor()
		    ->setARGB($rgb);
	}
	// Save the <files>.xlsx
	$objWriter = PHPExcel_IOFactory::createWriter($objMasterExcel, 'Excel2007');
		$objWriter->save('masterChecked.xlsx');
	$objWriter = PHPExcel_IOFactory::createWriter($objBaseExcel, 'Excel2007');
		$objWriter->save('baseChecked.xlsx');

	// New zip file
	$zip = new ZipArchive;
	$zip->open($zipName.'.zip', ZipArchive::CREATE);
	foreach ($filesToZip as $file) {
	  $zip->addFile($file);
	}
	$zip->close();

	// Remove work file and uploaded— changing XMLs
	foreach (array('masterChecked.xlsx', 'baseChecked.xlsx', $uploadDir.$baseExcel, $uploadDir.$masterExcel) as $file) {
	 	unlink($file);
	 };

	// Create temporary License
    
    rename( $zipName.'.zip', 'zip/'.$zipName.'.zip' );

	$db = new MachineDbSqlMysqli( new MachineConfig );
    
    $data = new stdClass();
        $data->url = 'zip/'.$zipName.'.zip';
        $data->uses = 0;
        $data->temp = md5('salary'.$zipName);

    $db->insertObject('#__salary_url', $data);
    $answer = 'http://machine.shliambur.ru/salary.download?code='.$data->temp;
    echo $answer;