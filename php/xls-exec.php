<?php
	session_start();
	require_once('../inc/inc.autoload.php');
   
	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		$act = $_REQUEST['act'];

		switch($act){

			case 'gerar':
                  
                $mesano = !empty($_REQUEST['mesano']) ? $_REQUEST['mesano']: $_SESSION['mesano'];
                $dao = new NotasEn1TxtDAO();
                $vet = $dao->ListaQtdCabeçaParaExcel($_SESSION['cnpj'],$mesano);
                $num = count($vet);

                $atvexcel = sha1($_SESSION['id_emp'].''.$_SESSION['cnpj'].''.$_SESSION['inscemp']);
                require_once('../PHPExcel/Classes/PHPExcel.php');
                $objPHPExcel = new PHPExcel();

                $objPHPExcel->getProperties()->setCreator("Agregar")
							 ->setLastModifiedBy("Agregar")
							 ->setTitle("Quantidade de cabeças Agregar")
							 ->setSubject("Quantidade de cabeças Agregar")
							 ->setDescription("Esse arquivo destinado para informar quantidade de cabeças.")
							 ->setKeywords("Agregar prodasiq")
                             ->setCategory("file");
                             
                /*$objPHPExcel->getSecurity()->setLockWindows(true);
                $objPHPExcel->getSecurity()->setLockStructure(true);
                $objPHPExcel->getSecurity()->setWorkbookPassword('secret');*/
                $objPHPExcel->setActiveSheetIndex(0)
                             ->setCellValue('A1', 'N° Validação Empresa:')
                             ->setCellValue('B1', ''.$atvexcel.'')
                             ->setCellValue('C1', '')
                             ->setCellValue('D1', '')
                             ->setCellValue('E1', '');

                $objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true); 
                $objPHPExcel->getDefaultStyle()->getFont()->setSize(14);

                $objPHPExcel->getActiveSheet()->getComment('B1')
                ->getText()->createTextRun('Numero de validação da empresa, gerado pelo sistema Agregar');
                $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFill()->getStartColor()->setARGB('9e9e9e');

                $styleArray = array(
                    'borders' => array(
                        'outline' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('argb' => '0000000'),
                        ),
                    ),
                );
                $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray($styleArray);
               // $objPHPExcel->getActiveSheet()->setAutoFilter('A1:D1');
                 // Miscellaneous glyphs, UTF-8
                 $objPHPExcel->setActiveSheetIndex(0)
                             ->setCellValue('A2', 'Número Nota')
                             ->setCellValue('B2', 'Série')
                             ->setCellValue('C2', 'Código Produto')
                             ->setCellValue('D2', 'Item')
                             ->setCellValue('E2', 'N° Cabeças');

                $objPHPExcel->setActiveSheetIndex(0)->protectCells('A1:E1', 'PHP');
                $objPHPExcel->getActiveSheet()->getStyle("A2:E2")->getFont()->setBold(true);

                $objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                $objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getFill()->getStartColor()->setARGB('9e9e9e');

                $styleArray2 = array(
                    'borders' => array(
                        'outline' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('argb' => '0000000'),
                        ),
                    ),
                );
                
                $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
                $objPHPExcel->getActiveSheet()->getStyle('A2:E2')->applyFromArray($styleArray2);
                $objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
               

                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

                $rowCount = 3; 
                for ($i=0; $i < $num; $i++) { 
                    
                    $notasen1       = $vet[$i];

                    $numero_nota    = $notasen1->getNumeroNota();
                    $serie          = $notasen1->getSerie();
                    $codigo_produto = $notasen1->getCodigoProduto();
                    $qtd_cabeca     = $notasen1->getQtdCabeca();
                    $item           = $notasen1->getNumeroItemNota();

                    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount,$numero_nota);
                    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount,$serie);
                    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount,$codigo_produto);
                    $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount,$item);
                    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount,$qtd_cabeca);

                    $objPHPExcel->getActiveSheet()->getStyle('A'.$rowCount)->applyFromArray($styleArray2);
                    $objPHPExcel->getActiveSheet()->getStyle('B'.$rowCount)->applyFromArray($styleArray2);
                    $objPHPExcel->getActiveSheet()->getStyle('C'.$rowCount)->applyFromArray($styleArray2);
                    $objPHPExcel->getActiveSheet()->getStyle('D'.$rowCount)->applyFromArray($styleArray2);
                    $objPHPExcel->getActiveSheet()->getStyle('E'.$rowCount)->applyFromArray($styleArray2);

                    $objPHPExcel->getActiveSheet()->protectCells('A'.$rowCount, 'PHP');
                    $objPHPExcel->getActiveSheet()->protectCells('B'.$rowCount, 'PHP');
                    $objPHPExcel->getActiveSheet()->protectCells('C'.$rowCount, 'PHP');
                    $objPHPExcel->getActiveSheet()->protectCells('D'.$rowCount, 'PHP');
//                    $objPHPExcel->getActiveSheet()->unprotectCells('D'.$rowCount, 'cacio');
                    //$objPHPExcel->setActiveSheetIndex(0)->protectCells('A1:B1', 'PHP');
                    $objPHPExcel->getActiveSheet()->getStyle('E'.$rowCount)->getProtection()
                    ->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);

                    $rowCount++;     
                } 

                 $objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(-1);
                 $objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setWrapText(true);
                 
                 
                 $objPHPExcel->getActiveSheet()->setTitle('Simple');
                 $objPHPExcel->setActiveSheetIndex(0);

                 $callStartTime = microtime(true);

                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $file = "../arquivos/{$_SESSION['cnpj']}/arquivos.xlsx";
                if(file_exists($file)){
                    unlink($file);
                }
                
                $objWriter->save($file);

                echo $file;

			break;	
										
		}
	}

?>