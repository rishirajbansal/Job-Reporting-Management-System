<?php

/* Licensed To: ThoughtExecution & 9sistemes
 * Authored By: Rishi Raj Bansal
 * Developed in: Jul-Aug-Sep 2016
 * ===========================================================================
 * This is FULLY owned and COPYRIGHTED by ThoughtExecution
 * This code may NOT be RESOLD or REDISTRUBUTED under any circumstances, and is only to be used with this application
 * Using the code from this application in another application is strictly PROHIBITED and not PERMISSIBLE
 * ===========================================================================
 */

require_once(dirname(__FILE__) . "/../common.php");
require_once(dirname(__FILE__) . "/../inputs/UserOrgInputs.php");
require_once(dirname(__FILE__) . "/../../lib/excelManager/PHPExcel.php");
include_once(dirname(__FILE__) . "/../vo/ReportQFilterRes.php");
include_once(dirname(__FILE__) . "/../vo/ExportData.php");


/**
 * Description of ExcelEngine
 *
 * @author Rishi Raj
 */
class ExportEngine {
    
    private $logger;
    
    private $objPHPExcel;
    private $exportType;
    private $exportedFilename;
    
    
    private $errors;
    private $messages;
    private $successMessage;
    private $completeMsg;
    private $criticalError;
    
    /*
     * 1 for simple success message
     * 2 for Complete success Final
     * 3 for array based messages
     * 4 for array based errors
     * 5 for Critical errors
     */
    private $msgType;
    
    
    function __construct() {
        
        $this->logger = Logger::getRootLogger();
        
        $this->errors = array();
        $this->messages = array();
        
    }
    
    
    function generateTCWReport(ExportData $exportData){
        
        $objPHPExcel = new PHPExcel();
        $userOrgInputs = new UserOrgInputs();
        
        $this->logger->debug('[Query Filter Report] Generating Report for TCW...');
        
        try{
            $userOrgInputs = $exportData->getSearchCriteria();
            $qFilterData = $exportData->getQFilterData();
            $exportType = $userOrgInputs->getEx_type();
            $this->setExportType($exportType);
            
            //Set the exported file name
            $exportedFilename = $this->generateFileName(getLocaleText(EXPORT_FILENAME_REPORT_TCW, TXT_A));
            
            //Generate exported report
            $objPHPExcelProps = $objPHPExcel->getProperties();
            $objPHPExcelProps->setCreator("ThoughtExecution");
            $objPHPExcelProps->setCompany("ThoughtExecution");
            $objPHPExcelProps->setLastModifiedBy("ThoughtExecution");
            $objPHPExcelProps->setTitle(getLocaleText('ExportEngine_generateTCWReport_MSG_1', TXT_U));
            $objPHPExcelProps->setSubject(getLocaleText('ExportEngine_generateTCWReport_MSG_1', TXT_U));

            $objPHPExcel->setActiveSheetIndex(0);
            
            $borderStyle = array(
                              'borders' => array(
                                            'allborders'  => array(
                                                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                            'color' => array('argb' => PHPExcel_Style_Color::COLOR_WHITE)
                                                        )
                                        )
                        );
            $objPHPExcel->getActiveSheet()->getStyle('A1:N150')->applyFromArray($borderStyle);
            
            $objPHPExcel->getActiveSheet()->mergeCells('A1:D3');
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('FFF2F2F2');
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()->getEndColor()->setARGB('FFF2F2F2');
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB('FF4472C4');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', getLocaleText('ExportEngine_generateTCWReport_MSG_1', TXT_U));
            
            $objPHPExcel->getActiveSheet()->mergeCells('A5:C5');
            $objPHPExcel->getActiveSheet()->setCellValue('A5', getLocaleText('ExportEngine_generateTCWReport_MSG_2', TXT_U));
            $objPHPExcel->getActiveSheet()->getStyle('A5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('A5')->getFill()->getStartColor()->setARGB('FF2F75B5');
            $objPHPExcel->getActiveSheet()->getStyle('A5')->getFill()->getEndColor()->setARGB('FF2F75B5');
            $objPHPExcel->getActiveSheet()->getStyle('A5')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('A5')->getFont()->setSize(12);
            $objPHPExcel->getActiveSheet()->getStyle('A5')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
            $objPHPExcel->getActiveSheet()->getStyle('A5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->getRowDimension('5')->setRowHeight(21);
            $borderStyle = array(
                              'borders' => array(
                                            'outline'  => array(
                                                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                            'color' => array('argb' => 'FF2F75B5')
                                                        )
                                        )
                        );
            $objPHPExcel->getActiveSheet()->getStyle('A5:C9')->applyFromArray($borderStyle);
            
            $objPHPExcel->getActiveSheet()->getStyle('A6:A9')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('A6:A9')->getFill()->getStartColor()->setARGB('FF2F75B5');
            $borderStyle = array(
                              'borders' => array(
                                            'bottom'  => array(
                                                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                            'color' => array('argb' => 'FF2F75B5')
                                                        ),
                                            'top'  => array(
                                                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                            'color' => array('argb' => 'FF2F75B5')
                                                        )
                                        )
                        );
            $objPHPExcel->getActiveSheet()->getStyle('A6')->applyFromArray($borderStyle);
            $objPHPExcel->getActiveSheet()->getStyle('A7')->applyFromArray($borderStyle);
            $objPHPExcel->getActiveSheet()->getStyle('A8')->applyFromArray($borderStyle);
            
            $objPHPExcel->getActiveSheet()->getStyle('B6:B9')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('B6:B9')->getFill()->getStartColor()->setARGB('FFD9E1F2');
            $objPHPExcel->getActiveSheet()->setCellValue('B6', getLocaleText('ExportEngine_generateTCWReport_MSG_3', TXT_U));
            $objPHPExcel->getActiveSheet()->setCellValue('B7', getLocaleText('ExportEngine_generateTCWReport_MSG_4', TXT_U));
            $objPHPExcel->getActiveSheet()->setCellValue('B8', getLocaleText('ExportEngine_generateTCWReport_MSG_5', TXT_U));
            $objPHPExcel->getActiveSheet()->setCellValue('B9', getLocaleText('ExportEngine_generateTCWReport_MSG_6', TXT_U));
            $borderStyle = array(
                              'borders' => array(
                                            'bottom'  => array(
                                                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                            'color' => array('argb' => PHPExcel_Style_Color::COLOR_WHITE)
                                                        )
                                        )
                        );
            $objPHPExcel->getActiveSheet()->getStyle('B6')->applyFromArray($borderStyle);
            $objPHPExcel->getActiveSheet()->getStyle('B7')->applyFromArray($borderStyle);
            $objPHPExcel->getActiveSheet()->getStyle('B8')->applyFromArray($borderStyle);
            
            if (!empty($userOrgInputs->getQf_sdate()) && !empty($userOrgInputs->getQf_edate())){
                $formattedDate = $this->reformDate($userOrgInputs->getQf_sdate(), DATEFORMAT_EXPORT_FILE_SEARCH_CRITERIA_DATE);
                $objPHPExcel->getActiveSheet()->setCellValue('C6', $formattedDate);
                $formattedDate = $this->reformDate($userOrgInputs->getQf_edate(), DATEFORMAT_EXPORT_FILE_SEARCH_CRITERIA_DATE);
                $objPHPExcel->getActiveSheet()->setCellValue('C7', $formattedDate);
            }
            else{
                $objPHPExcel->getActiveSheet()->setCellValue('C6', getLocaleText('ExportEngine_generateTCWReport_MSG_11', TXT_U));
                $objPHPExcel->getActiveSheet()->setCellValue('C7', getLocaleText('ExportEngine_generateTCWReport_MSG_11', TXT_U));
                $objPHPExcel->getActiveSheet()->getStyle('C6:C7')->getFont()->getColor()->setARGB('FFE26B0A');
                $objPHPExcel->getActiveSheet()->getStyle('C6:C7')->getFont()->setItalic(true);
            }
            if (!empty($userOrgInputs->getQf_clientname())){
                $objPHPExcel->getActiveSheet()->setCellValue('C8', $userOrgInputs->getQf_clientname());
            }
            else{
                $objPHPExcel->getActiveSheet()->setCellValue('C8', getLocaleText('ExportEngine_generateTCWReport_MSG_11', TXT_U));
                $objPHPExcel->getActiveSheet()->getStyle('C8')->getFont()->getColor()->setARGB('FFE26B0A');
                $objPHPExcel->getActiveSheet()->getStyle('C8')->getFont()->setItalic(true);
            }
            if (!empty($userOrgInputs->getQf_workername())){
                $objPHPExcel->getActiveSheet()->setCellValue('C9', $userOrgInputs->getQf_workername());
            }
            else{
                $objPHPExcel->getActiveSheet()->setCellValue('C9', getLocaleText('ExportEngine_generateTCWReport_MSG_11', TXT_U));
                $objPHPExcel->getActiveSheet()->getStyle('C9')->getFont()->getColor()->setARGB('FFE26B0A');
                $objPHPExcel->getActiveSheet()->getStyle('C9')->getFont()->setItalic(true);
            }
            
            $objPHPExcel->getActiveSheet()->setCellValue('A12', '#');
            $objPHPExcel->getActiveSheet()->setCellValue('B12', getLocaleText('ExportEngine_generateTCWReport_MSG_6', TXT_U));
            $objPHPExcel->getActiveSheet()->setCellValue('C12', getLocaleText('ExportEngine_generateTCWReport_MSG_7', TXT_U));
            $objPHPExcel->getActiveSheet()->setCellValue('D12', getLocaleText('ExportEngine_generateTCWReport_MSG_8', TXT_U));
            $objPHPExcel->getActiveSheet()->getStyle('A12:D12')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('A12:D12')->getFill()->getStartColor()->setARGB('FF5B9BD5');
            $objPHPExcel->getActiveSheet()->getStyle('A12:D12')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('A12:D12')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);

            $objPHPExcel->getActiveSheet()->getStyle('A12')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A12')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(70);
            
            
            $rowsCount = sizeof($qFilterData);
            $objWorksheet = $objPHPExcel->getActiveSheet();
            $row = 13;
            $ctr = 1;
            $reportQFilterRes = new ReportQFilterRes();
            
            foreach ($qFilterData as $reportQFilterRes){
                
                $location = $reportQFilterRes->getLocation(); 
                $location = trim($location);
                $isLocationAvailable = TRUE;
                
                if (strcmp(getLocaleText(LOCATION_MSG_NO_LOCATION_FOUND, TXT_A), $location) === 0 || 
                    strcmp(getLocaleText(LOCATION_MSG_GPS_NOT_ENABLED, TXT_A), $location) === 0 ||
                    strcmp(getLocaleText(LOCATION_MSG_GPS_ENABLED, TXT_A), $location) === 0 || 
                    strcmp(getLocaleText(LOCATION_MSG_GEOCODE_TIMEDOUT, TXT_A), $location) === 0 ||
                    empty($location)){
                    
                    $isLocationAvailable = FALSE;
                }
                
                $objWorksheet->getCellByColumnAndRow(0, $row)->setValue($ctr);
                $objWorksheet->getStyleByColumnAndRow(0, $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $objWorksheet->getStyleByColumnAndRow(0, $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                
                $objWorksheet->getCellByColumnAndRow(1, $row)->setValue($reportQFilterRes->getRptNo());
                $objWorksheet->getStyleByColumnAndRow(1, $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $objWorksheet->getCellByColumnAndRow(2, $row)->setValue($reportQFilterRes->getRptSubmitDate());
                $objWorksheet->getStyleByColumnAndRow(2, $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                
                $objWorksheet->getCellByColumnAndRow(3, $row)->setValue($location);            
                $objWorksheet->getCellByColumnAndRow(3, $row)->getStyle()->getAlignment()->setWrapText(true);
                $objWorksheet->getStyleByColumnAndRow(3, $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $objWorksheet->getStyleByColumnAndRow(3, $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                if (!$isLocationAvailable){
                    $objWorksheet->getStyleByColumnAndRow(3, $row)->getFont()->getColor()->setARGB('C00000');
                }
                
                ++$ctr;
                ++$row;
                $reportQFilterRes = new ReportQFilterRes();
                
            }
            $borderStyle = array(
                              'borders' => array(
                                            'outline'  => array(
                                                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                            'color' => array('argb' => 'FF5B9BD5')
                                                        )
                                        )
                        );
            $borderRowsCols = 'A12:D' . --$row;
            $objPHPExcel->getActiveSheet()->getStyle($borderRowsCols)->applyFromArray($borderStyle);
            $borderStyle = array(
                              'borders' => array(
                                            'inside'  => array(
                                                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                            'color' => array('argb' => 'FFFCE4D6')
                                                        )
                                        )
                        );
            $borderRowsCols = 'A12:D' . $row;
            $objPHPExcel->getActiveSheet()->getStyle($borderRowsCols)->applyFromArray($borderStyle);
            
            
            $objPHPExcel->getActiveSheet()->setTitle(getLocaleText('ExportEngine_generateTCWReport_MSG_10', TXT_U));
            $objPHPExcel->getActiveSheet()->setSelectedCells('E1');

            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);

            
            ob_clean();
            
            //Set the Header
            $this->createHeader($exportType, $exportedFilename);
            
            //Set the Writer
            $this->createWriter($exportType, $objPHPExcel, $exportedFilename);
            
            $this->logger->debug('[Query Filter Report] Exported successfully : ' . $exportedFilename);
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('ExportEngine_generateTCWReport_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
    }
    
    function generateCalWHrsReport(ExportData $exportData){
        
        $objPHPExcel = new PHPExcel();
        $userOrgInputs = new UserOrgInputs();
        
        $this->logger->debug('[Query Filter Report] Generating Report for CalWHrsReport...');
        
        try{
            $userOrgInputs = $exportData->getSearchCriteria();
            $qFilterData = $exportData->getQFilterData();
            $totalHrs = $exportData->getTotalHrs();
            
            $exportType = $userOrgInputs->getEx_type();
            $this->setExportType($exportType);
            
            //Set the exported file name
            $exportedFilename = $this->generateFileName(getLocaleText(EXPORT_FILENAME_REPORT_CALWHRS, TXT_A));
            
            //Generate exported report
            $objPHPExcelProps = $objPHPExcel->getProperties();
            $objPHPExcelProps->setCreator("ThoughtExecution");
            $objPHPExcelProps->setCompany("ThoughtExecution");
            $objPHPExcelProps->setLastModifiedBy("ThoughtExecution");
            $objPHPExcelProps->setTitle(getLocaleText('ExportEngine_generateCalWHrsReport_MSG_1', TXT_U));
            $objPHPExcelProps->setSubject(getLocaleText('ExportEngine_generateCalWHrsReport_MSG_1', TXT_U));

            $objPHPExcel->setActiveSheetIndex(0);
            
            $borderStyle = array(
                              'borders' => array(
                                            'allborders'  => array(
                                                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                            'color' => array('argb' => PHPExcel_Style_Color::COLOR_WHITE)
                                                        )
                                        )
                        );
            $objPHPExcel->getActiveSheet()->getStyle('A1:Q150')->applyFromArray($borderStyle);
            
            $objPHPExcel->getActiveSheet()->mergeCells('A1:D3');
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('FFF2F2F2');
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()->getEndColor()->setARGB('FFF2F2F2');
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB('FF4472C4');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', getLocaleText('ExportEngine_generateCalWHrsReport_MSG_1', TXT_U));
            
            $objPHPExcel->getActiveSheet()->mergeCells('A5:C5');
            $objPHPExcel->getActiveSheet()->setCellValue('A5', getLocaleText('ExportEngine_generateCalWHrsReport_MSG_2', TXT_U));
            $objPHPExcel->getActiveSheet()->getStyle('A5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('A5')->getFill()->getStartColor()->setARGB('FF2F75B5');
            $objPHPExcel->getActiveSheet()->getStyle('A5')->getFill()->getEndColor()->setARGB('FF2F75B5');
            $objPHPExcel->getActiveSheet()->getStyle('A5')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('A5')->getFont()->setSize(12);
            $objPHPExcel->getActiveSheet()->getStyle('A5')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
            $objPHPExcel->getActiveSheet()->getStyle('A5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->getRowDimension('5')->setRowHeight(21);
            $borderStyle = array(
                              'borders' => array(
                                            'outline'  => array(
                                                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                            'color' => array('argb' => 'FF2F75B5')
                                                        )
                                        )
                        );
            $objPHPExcel->getActiveSheet()->getStyle('A5:C9')->applyFromArray($borderStyle);
            
            $objPHPExcel->getActiveSheet()->getStyle('A6:A9')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('A6:A9')->getFill()->getStartColor()->setARGB('FF2F75B5');
            $borderStyle = array(
                              'borders' => array(
                                            'bottom'  => array(
                                                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                            'color' => array('argb' => 'FF2F75B5')
                                                        ),
                                            'top'  => array(
                                                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                            'color' => array('argb' => 'FF2F75B5')
                                                        )
                                        )
                        );
            $objPHPExcel->getActiveSheet()->getStyle('A6')->applyFromArray($borderStyle);
            $objPHPExcel->getActiveSheet()->getStyle('A7')->applyFromArray($borderStyle);
            $objPHPExcel->getActiveSheet()->getStyle('A8')->applyFromArray($borderStyle);
            
            $objPHPExcel->getActiveSheet()->getStyle('B6:B9')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('B6:B9')->getFill()->getStartColor()->setARGB('FFD9E1F2');
            $objPHPExcel->getActiveSheet()->setCellValue('B6', getLocaleText('ExportEngine_generateCalWHrsReport_MSG_3', TXT_U));
            $objPHPExcel->getActiveSheet()->setCellValue('B7', getLocaleText('ExportEngine_generateCalWHrsReport_MSG_4', TXT_U));
            $objPHPExcel->getActiveSheet()->setCellValue('B8', getLocaleText('ExportEngine_generateCalWHrsReport_MSG_5', TXT_U));
            $objPHPExcel->getActiveSheet()->setCellValue('B9', getLocaleText('ExportEngine_generateCalWHrsReport_MSG_6', TXT_U));
            $borderStyle = array(
                              'borders' => array(
                                            'bottom'  => array(
                                                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                            'color' => array('argb' => PHPExcel_Style_Color::COLOR_WHITE)
                                                        )
                                        )
                        );
            $objPHPExcel->getActiveSheet()->getStyle('B6')->applyFromArray($borderStyle);
            $objPHPExcel->getActiveSheet()->getStyle('B7')->applyFromArray($borderStyle);
            $objPHPExcel->getActiveSheet()->getStyle('B8')->applyFromArray($borderStyle);
            
            if (!empty($userOrgInputs->getQf_sdate()) && !empty($userOrgInputs->getQf_edate())){
                $formattedDate = $this->reformDate($userOrgInputs->getQf_sdate(), DATEFORMAT_EXPORT_FILE_SEARCH_CRITERIA_DATE);
                $objPHPExcel->getActiveSheet()->setCellValue('C6', $formattedDate);
                $formattedDate = $this->reformDate($userOrgInputs->getQf_edate(), DATEFORMAT_EXPORT_FILE_SEARCH_CRITERIA_DATE);
                $objPHPExcel->getActiveSheet()->setCellValue('C7', $formattedDate);
            }
            else{
                $objPHPExcel->getActiveSheet()->setCellValue('C6', getLocaleText('ExportEngine_generateCalWHrsReport_MSG_12', TXT_U));
                $objPHPExcel->getActiveSheet()->setCellValue('C7', getLocaleText('ExportEngine_generateCalWHrsReport_MSG_12', TXT_U));
                $objPHPExcel->getActiveSheet()->getStyle('C6:C7')->getFont()->getColor()->setARGB('FFE26B0A');
                $objPHPExcel->getActiveSheet()->getStyle('C6:C7')->getFont()->setItalic(true);
            }
            if (!empty($userOrgInputs->getQf_clientname())){
                $objPHPExcel->getActiveSheet()->setCellValue('C8', $userOrgInputs->getQf_clientname());
            }
            else{
                $objPHPExcel->getActiveSheet()->setCellValue('C8', getLocaleText('ExportEngine_generateCalWHrsReport_MSG_12', TXT_U));
                $objPHPExcel->getActiveSheet()->getStyle('C8')->getFont()->getColor()->setARGB('FFE26B0A');
                $objPHPExcel->getActiveSheet()->getStyle('C8')->getFont()->setItalic(true);
            }
            if (!empty($userOrgInputs->getQf_workername())){
                $objPHPExcel->getActiveSheet()->setCellValue('C9', $userOrgInputs->getQf_workername());
            }
            else{
                $objPHPExcel->getActiveSheet()->setCellValue('C9', getLocaleText('ExportEngine_generateCalWHrsReport_MSG_12', TXT_U));
                $objPHPExcel->getActiveSheet()->getStyle('C9')->getFont()->getColor()->setARGB('FFE26B0A');
                $objPHPExcel->getActiveSheet()->getStyle('C9')->getFont()->setItalic(true);
            }
            
            $objPHPExcel->getActiveSheet()->setCellValue('A12', '#');
            $objPHPExcel->getActiveSheet()->setCellValue('B12', getLocaleText('ExportEngine_generateCalWHrsReport_MSG_7', TXT_U));
            $objPHPExcel->getActiveSheet()->setCellValue('C12', getLocaleText('ExportEngine_generateCalWHrsReport_MSG_8', TXT_U));
            $objPHPExcel->getActiveSheet()->setCellValue('D12', getLocaleText('ExportEngine_generateCalWHrsReport_MSG_9', TXT_U));
            $objPHPExcel->getActiveSheet()->getStyle('A12:D12')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('A12:D12')->getFill()->getStartColor()->setARGB('FF5B9BD5');
            $objPHPExcel->getActiveSheet()->getStyle('A12:D12')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('A12:D12')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);

            $objPHPExcel->getActiveSheet()->getStyle('A12')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A12')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
            
            $rowsCount = sizeof($qFilterData);
            $objWorksheet = $objPHPExcel->getActiveSheet();
            $row = 13;
            $ctr = 1;
            $reportQFilterRes = new ReportQFilterRes();
            
            foreach ($qFilterData as $reportQFilterRes){
                
                $objWorksheet->getCellByColumnAndRow(0, $row)->setValue($ctr);
                $objWorksheet->getStyleByColumnAndRow(0, $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $objWorksheet->getStyleByColumnAndRow(0, $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                
                $objWorksheet->getCellByColumnAndRow(1, $row)->setValue($reportQFilterRes->getRptNo());
                $objWorksheet->getStyleByColumnAndRow(1, $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $objWorksheet->getCellByColumnAndRow(2, $row)->setValue($reportQFilterRes->getRptSubmitDate());
                $objWorksheet->getStyleByColumnAndRow(2, $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                
                $objWorksheet->getCellByColumnAndRow(3, $row)->setValue($reportQFilterRes->getServiceHrs());            
                $objWorksheet->getCellByColumnAndRow(3, $row)->getStyle()->getAlignment()->setWrapText(true);
                $objWorksheet->getStyleByColumnAndRow(3, $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $objWorksheet->getStyleByColumnAndRow(3, $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                
                ++$ctr;
                ++$row;
                $reportQFilterRes = new ReportQFilterRes();
                
            }
            --$row;
            $borderStyle = array(
                              'borders' => array(
                                            'outline'  => array(
                                                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                            'color' => array('argb' => 'FF5B9BD5')
                                                        )
                                        )
                        );
            $borderRowsCols = 'A12:D' . $row;
            $objPHPExcel->getActiveSheet()->getStyle($borderRowsCols)->applyFromArray($borderStyle);
            $borderStyle = array(
                              'borders' => array(
                                            'inside'  => array(
                                                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                            'color' => array('argb' => 'FFFCE4D6')
                                                        )
                                        )
                        );
            $borderRowsCols = 'A12:D' . $row;
            $objPHPExcel->getActiveSheet()->getStyle($borderRowsCols)->applyFromArray($borderStyle);
            
            ++$row;
            $objPHPExcel->getActiveSheet()->mergeCells('A'.$row.':C'.$row);
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, getLocaleText('ExportEngine_generateCalWHrsReport_MSG_10', TXT_U));
            $objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFill()->getStartColor()->setARGB('FFFFF2CC');
            $objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFill()->getEndColor()->setARGB('FFFFF2CC');
            $objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->getColor()->setARGB('FF5B9BD5');
            $objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(23);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $totalHrs);
            $objPHPExcel->getActiveSheet()->getStyle('D'.$row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('D'.$row)->getFill()->getStartColor()->setARGB('FFFFF2CC');
            $objPHPExcel->getActiveSheet()->getStyle('D'.$row)->getFill()->getEndColor()->setARGB('FFFFF2CC');
            $objPHPExcel->getActiveSheet()->getStyle('D'.$row)->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('D'.$row)->getFont()->getColor()->setARGB('FF5B9BD5');
            $objPHPExcel->getActiveSheet()->getStyle('D'.$row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('D'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $borderStyle = array(
                              'borders' => array(
                                            'inside'  => array(
                                                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                            'color' => array('argb' => 'FF5B9BD5')
                                                        ),
                                            'outline'  => array(
                                                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                            'color' => array('argb' => 'FF5B9BD5')
                                                        )
                                        )
                        );
            $borderRowsCols = 'A12:D' . $row;
            $objPHPExcel->getActiveSheet()->getStyle('A'.$row.':D'.$row)->applyFromArray($borderStyle);
            
            
            $objPHPExcel->getActiveSheet()->setTitle(getLocaleText('ExportEngine_generateCalWHrsReport_MSG_11', TXT_U));
            $objPHPExcel->getActiveSheet()->setSelectedCells('E1');

            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            
            
            ob_clean();
            
            //Set the Header
            $this->createHeader($exportType, $exportedFilename);

            //Set the Writer
            $this->createWriter($exportType, $objPHPExcel, $exportedFilename);
            
            $this->logger->debug('[Query Filter Report] Exported successfully : ' . $exportedFilename);
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('ExportEngine_generateCalWHrsReport_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
    }
    
    function generateTtlPrdQtyReport(ExportData $exportData){
        
        $objPHPExcel = new PHPExcel();
        $userOrgInputs = new UserOrgInputs();
        
        $this->logger->debug('[Query Filter Report] Generating Report for TtlPrdQtyReport...');
        
        try{
            $userOrgInputs = $exportData->getSearchCriteria();
            $qFilterData = $exportData->getQFilterData();
            
            $exportType = $userOrgInputs->getEx_type();
            $this->setExportType($exportType);
            
            //Set the exported file name
            $exportedFilename = $this->generateFileName(getLocaleText(EXPORT_FILENAME_REPORT_TTLPRDQTY, TXT_A));
            
            //Generate exported report
            $objPHPExcelProps = $objPHPExcel->getProperties();
            $objPHPExcelProps->setCreator("ThoughtExecution");
            $objPHPExcelProps->setCompany("ThoughtExecution");
            $objPHPExcelProps->setLastModifiedBy("ThoughtExecution");
            $objPHPExcelProps->setTitle(getLocaleText('ExportEngine_generateTtlPrdQtyReport_1', TXT_U));
            $objPHPExcelProps->setSubject(getLocaleText('ExportEngine_generateTtlPrdQtyReport_1', TXT_U));

            $objPHPExcel->setActiveSheetIndex(0);
            
            $borderStyle = array(
                              'borders' => array(
                                            'allborders'  => array(
                                                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                            'color' => array('argb' => PHPExcel_Style_Color::COLOR_WHITE)
                                                        )
                                        )
                        );
            $objPHPExcel->getActiveSheet()->getStyle('A1:O150')->applyFromArray($borderStyle);
            
            $objPHPExcel->getActiveSheet()->mergeCells('A1:E3');
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('FFF2F2F2');
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()->getEndColor()->setARGB('FFF2F2F2');
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB('FF4472C4');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', getLocaleText('ExportEngine_generateTtlPrdQtyReport_1', TXT_U));
            
            $objPHPExcel->getActiveSheet()->mergeCells('A5:C5');
            $objPHPExcel->getActiveSheet()->setCellValue('A5', getLocaleText('ExportEngine_generateTtlPrdQtyReport_2', TXT_U));
            $objPHPExcel->getActiveSheet()->getStyle('A5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('A5')->getFill()->getStartColor()->setARGB('FF2F75B5');
            $objPHPExcel->getActiveSheet()->getStyle('A5')->getFill()->getEndColor()->setARGB('FF2F75B5');
            $objPHPExcel->getActiveSheet()->getStyle('A5')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('A5')->getFont()->setSize(12);
            $objPHPExcel->getActiveSheet()->getStyle('A5')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
            $objPHPExcel->getActiveSheet()->getStyle('A5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->getRowDimension('5')->setRowHeight(21);
            $borderStyle = array(
                              'borders' => array(
                                            'outline'  => array(
                                                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                            'color' => array('argb' => 'FF2F75B5')
                                                        )
                                        )
                        );
            $objPHPExcel->getActiveSheet()->getStyle('A5:C9')->applyFromArray($borderStyle);
            
            $objPHPExcel->getActiveSheet()->getStyle('A6:A9')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('A6:A9')->getFill()->getStartColor()->setARGB('FF2F75B5');
            $borderStyle = array(
                              'borders' => array(
                                            'bottom'  => array(
                                                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                            'color' => array('argb' => 'FF2F75B5')
                                                        ),
                                            'top'  => array(
                                                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                            'color' => array('argb' => 'FF2F75B5')
                                                        )
                                        )
                        );
            $objPHPExcel->getActiveSheet()->getStyle('A6')->applyFromArray($borderStyle);
            $objPHPExcel->getActiveSheet()->getStyle('A7')->applyFromArray($borderStyle);
            $objPHPExcel->getActiveSheet()->getStyle('A8')->applyFromArray($borderStyle);
            
            $objPHPExcel->getActiveSheet()->getStyle('B6:B9')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('B6:B9')->getFill()->getStartColor()->setARGB('FFD9E1F2');
            $objPHPExcel->getActiveSheet()->setCellValue('B6', getLocaleText('ExportEngine_generateTtlPrdQtyReport_3', TXT_U));
            $objPHPExcel->getActiveSheet()->setCellValue('B7', getLocaleText('ExportEngine_generateTtlPrdQtyReport_4', TXT_U));
            $objPHPExcel->getActiveSheet()->setCellValue('B8', getLocaleText('ExportEngine_generateTtlPrdQtyReport_5', TXT_U));
            $objPHPExcel->getActiveSheet()->setCellValue('B9', getLocaleText('ExportEngine_generateTtlPrdQtyReport_6', TXT_U));
            $borderStyle = array(
                              'borders' => array(
                                            'bottom'  => array(
                                                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                            'color' => array('argb' => PHPExcel_Style_Color::COLOR_WHITE)
                                                        )
                                        )
                        );
            $objPHPExcel->getActiveSheet()->getStyle('B6')->applyFromArray($borderStyle);
            $objPHPExcel->getActiveSheet()->getStyle('B7')->applyFromArray($borderStyle);
            $objPHPExcel->getActiveSheet()->getStyle('B8')->applyFromArray($borderStyle);
            
            if (!empty($userOrgInputs->getQf_sdate()) && !empty($userOrgInputs->getQf_edate())){
                $formattedDate = $this->reformDate($userOrgInputs->getQf_sdate(), DATEFORMAT_EXPORT_FILE_SEARCH_CRITERIA_DATE);
                $objPHPExcel->getActiveSheet()->setCellValue('C6', $formattedDate);
                $formattedDate = $this->reformDate($userOrgInputs->getQf_edate(), DATEFORMAT_EXPORT_FILE_SEARCH_CRITERIA_DATE);
                $objPHPExcel->getActiveSheet()->setCellValue('C7', $formattedDate);
            }
            else{
                $objPHPExcel->getActiveSheet()->setCellValue('C6', getLocaleText('ExportEngine_generateTtlPrdQtyReport_13', TXT_U));
                $objPHPExcel->getActiveSheet()->setCellValue('C7', getLocaleText('ExportEngine_generateTtlPrdQtyReport_13', TXT_U));
                $objPHPExcel->getActiveSheet()->getStyle('C6:C7')->getFont()->getColor()->setARGB('FFE26B0A');
                $objPHPExcel->getActiveSheet()->getStyle('C6:C7')->getFont()->setItalic(true);
            }
            if (!empty($userOrgInputs->getQf_clientname())){
                $objPHPExcel->getActiveSheet()->setCellValue('C8', $userOrgInputs->getQf_clientname());
            }
            else{
                $objPHPExcel->getActiveSheet()->setCellValue('C8', getLocaleText('ExportEngine_generateTtlPrdQtyReport_13', TXT_U));
                $objPHPExcel->getActiveSheet()->getStyle('C8')->getFont()->getColor()->setARGB('FFE26B0A');
                $objPHPExcel->getActiveSheet()->getStyle('C8')->getFont()->setItalic(true);
            }
            if (!empty($userOrgInputs->getQf_workername())){
                $objPHPExcel->getActiveSheet()->setCellValue('C9', $userOrgInputs->getQf_workername());
            }
            else{
                $objPHPExcel->getActiveSheet()->setCellValue('C9', getLocaleText('ExportEngine_generateTtlPrdQtyReport_13', TXT_U));
                $objPHPExcel->getActiveSheet()->getStyle('C9')->getFont()->getColor()->setARGB('FFE26B0A');
                $objPHPExcel->getActiveSheet()->getStyle('C9')->getFont()->setItalic(true);
            }
            
            $objPHPExcel->getActiveSheet()->mergeCells('D12:E12');
            $objPHPExcel->getActiveSheet()->setCellValue('A12', '#');
            $objPHPExcel->getActiveSheet()->setCellValue('B12', getLocaleText('ExportEngine_generateTtlPrdQtyReport_7', TXT_U));
            $objPHPExcel->getActiveSheet()->setCellValue('C12', getLocaleText('ExportEngine_generateTtlPrdQtyReport_8', TXT_U));
            $objPHPExcel->getActiveSheet()->setCellValue('D12', getLocaleText('ExportEngine_generateTtlPrdQtyReport_9', TXT_U));
            $objPHPExcel->getActiveSheet()->getStyle('A12:D12')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('A12:D12')->getFill()->getStartColor()->setARGB('FF5B9BD5');
            $objPHPExcel->getActiveSheet()->getStyle('A12:D12')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('A12:D12')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);

            $objPHPExcel->getActiveSheet()->getStyle('A12')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A12')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(35);
            
            $rowsCount = sizeof($qFilterData);
            $objWorksheet = $objPHPExcel->getActiveSheet();
            $row = 13;
            $ctr = 1;
            $reportQFilterRes = new ReportQFilterRes();
            
            foreach ($qFilterData as $reportQFilterRes){
                
                $objWorksheet->getCellByColumnAndRow(3, $row)->setValue(getLocaleText('ExportEngine_generateTtlPrdQtyReport_10', TXT_U));
                $objWorksheet->getCellByColumnAndRow(4, $row)->setValue(getLocaleText('ExportEngine_generateTtlPrdQtyReport_11', TXT_U));
                $objWorksheet->getStyleByColumnAndRow(3, $row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                $objWorksheet->getStyleByColumnAndRow(3, $row)->getFill()->getStartColor()->setARGB('FFF2F2F2');
                $objWorksheet->getStyleByColumnAndRow(3, $row)->getFont()->setBold(true);
                $objWorksheet->getStyleByColumnAndRow(4, $row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                $objWorksheet->getStyleByColumnAndRow(4, $row)->getFill()->getStartColor()->setARGB('FFF2F2F2');
                $objWorksheet->getStyleByColumnAndRow(4, $row)->getFont()->setBold(true);
                
                $innerRow = $row + 1;
                
                $prdListArr = $reportQFilterRes->getPrdQtyList();
                if (!empty($prdListArr)){
                    foreach ($prdListArr as $prdList){
                        $objWorksheet->getCellByColumnAndRow(3, $innerRow)->setValue($prdList[0]);
                        $objWorksheet->getCellByColumnAndRow(4, $innerRow)->setValue($prdList[1]);
                        $objWorksheet->getStyleByColumnAndRow(3, $innerRow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                        $objWorksheet->getStyleByColumnAndRow(4, $innerRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                        
                        ++$innerRow;
                    }
                }
                --$innerRow;
                
                $merge = 'A'.$row.':'.'A'.$innerRow;
                $objPHPExcel->getActiveSheet()->mergeCells($merge);
                $merge = 'B'.$row.':'.'B'.$innerRow;
                $objPHPExcel->getActiveSheet()->mergeCells($merge);
                $merge = 'C'.$row.':'.'C'.$innerRow;
                $objPHPExcel->getActiveSheet()->mergeCells($merge);
                
                $objWorksheet->getCellByColumnAndRow(0, $row)->setValue($ctr);
                $objWorksheet->getStyleByColumnAndRow(0, $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $objWorksheet->getStyleByColumnAndRow(0, $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                
                $objWorksheet->getCellByColumnAndRow(1, $row)->setValue($reportQFilterRes->getRptNo());
                $objWorksheet->getStyleByColumnAndRow(1, $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $objWorksheet->getCellByColumnAndRow(2, $row)->setValue($reportQFilterRes->getRptSubmitDate());
                $objWorksheet->getStyleByColumnAndRow(2, $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

                ++$ctr;
                $row = $innerRow;
                ++$row;
                $reportQFilterRes = new ReportQFilterRes();
            }
            --$row;
            $borderStyle = array(
                              'borders' => array(
                                            'outline'  => array(
                                                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                            'color' => array('argb' => 'FF5B9BD5')
                                                        )
                                        )
                        );
            $borderRowsCols = 'A12:E' . $row;
            $objPHPExcel->getActiveSheet()->getStyle($borderRowsCols)->applyFromArray($borderStyle);
            $borderStyle = array(
                              'borders' => array(
                                            'inside'  => array(
                                                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                            'color' => array('argb' => 'FFFCE4D6')
                                                        )
                                        )
                        );
            $borderRowsCols = 'A12:E' . $row;
            $objPHPExcel->getActiveSheet()->getStyle($borderRowsCols)->applyFromArray($borderStyle);
            
            
            $objPHPExcel->getActiveSheet()->setTitle(getLocaleText('ExportEngine_generateTtlPrdQtyReport_12', TXT_U));
            $objPHPExcel->getActiveSheet()->setSelectedCells('F1');

            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            
            
            ob_clean();
            
            //Set the Header
            $this->createHeader($exportType, $exportedFilename);

            //Set the Writer
            $this->createWriter($exportType, $objPHPExcel, $exportedFilename);
            
            $this->logger->debug('[Query Filter Report] Exported successfully : ' . $exportedFilename);
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('ExportEngine_generateTtlPrdQtyReport_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        
    }
    
    
    function createHeader($exportType, $filename) {
    
        header("Pragma: public");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Cache-Control: max-age=0");
        header("Content-Description: Job Reporting File Export");
        header('Content-Transfer-Encoding: binary');

        if ($exportType == "CSV"){
            header("Content-Type: application/csv");
            header("Content-Disposition: attachment;filename=\"" . $filename . ".csv");
        }
        else if ($exportType == "XLS"){
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment;filename=\"" . $filename . ".xls");
        }
        else if ($exportType == "XLSX"){
            header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
            header("Content-Disposition: attachment;filename=\"" . $filename . ".xlsx");
        }
        else{
            echo getLocaleText('ExportEngine_createHeader_MSG_1-1', TXT_U) . ' <a href="home.php"> ' . getLocaleText('ExportEngine_createHeader_MSG_1-2', TXT_U) . '</a>';
        }

    }

    function createWriter($exportType, $objPHPExcel, $filename){

        if ($exportType == "CSV"){
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
            $objWriter->setDelimiter(',');
            $objWriter->setEnclosure('"');
            $objWriter->setLineEnding("\r\n");
            $objWriter->setSheetIndex(0);
            $objWriter->save('php://output');
        }
        else if ($exportType == "XLS"){
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
        }
        else if ($exportType == "XLSX"){
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            //$objWriter->save('php://output');
            $this->saveViaTempFile($objWriter, $filename);
        }
        else{
            echo getLocaleText('ExportEngine_createHeader_MSG_1-1', TXT_U) . ' <a href="home.php"> ' . getLocaleText('ExportEngine_createHeader_MSG_1-2', TXT_U) . '</a>';
        }

    }
    
    function generateFileName($basename){
        
        $filename = $basename. " " . date(DATEFORMAT_EXPORT_FILE);
        
        return $filename;
        
    }
    
    static function saveViaTempFile($objWriter, $filename){
        $filePath = $filename . ".xlsx";
        $objWriter->save($filePath);
        readfile($filePath);
        unlink($filePath);
    }
    
    function reformDate($date, $format) {
        return date_format( DateTime::createFromFormat('d/m/Y', $date), $format);
    }
    
    
    public function getErrors() {
        return $this->errors;
    }

    public function getMessages() {
        return $this->messages;
    }

    public function getSuccessMessage() {
        return $this->successMessage;
    }

    public function getCompleteMsg() {
        return $this->completeMsg;
    }

    public function getCriticalError() {
        return $this->criticalError;
    }

    public function getMsgType() {
        return $this->msgType;
    }

    public function setErrors($errors) {
        $this->errors = $errors;
    }

    public function setMessages($messages) {
        $this->messages = $messages;
    }

    public function setSuccessMessage($successMessage) {
        $this->successMessage = $successMessage;
    }

    public function setCompleteMsg($completeMsg) {
        $this->completeMsg = $completeMsg;
    }

    public function setCriticalError($criticalError) {
        $this->criticalError = $criticalError;
    }

    public function setMsgType($msgType) {
        $this->msgType = $msgType;
    }
    
    public function getObjPHPExcel() {
        return $this->objPHPExcel;
    }

    public function getExportType() {
        return $this->exportType;
    }

    public function setObjPHPExcel($objPHPExcel) {
        $this->objPHPExcel = $objPHPExcel;
    }

    public function setExportType($exportType) {
        $this->exportType = $exportType;
    }

    public function getExportedFilename() {
        return $this->exportedFilename;
    }

    public function setExportedFilename($exportedFilename) {
        $this->exportedFilename = $exportedFilename;
    }


    
}
