<?php

namespace PhpExcelHelperFormat {
    require_once "PHPExcel.php";
    class PhpExcelHelperFormat
    {
        private $phpExcel = null;
        private $sheet = null;

        private $currentRow = 1;

        private $title = null;
        private $header = null;
        public function __construct(Title $title = null,Header $header = null)
        {
            $this->phpExcel = new \PHPExcel();
            try {
                $this->sheet = $this->phpExcel->getActiveSheet();
            } catch (PHPExcel_Exception $e) {
            }
            if ($title != null && $title instanceof Title) {
                $this->title = $title;
                $this->setTitle();
            }
            if ($header != null && $header instanceof Header) {
                $this->header = $header;
                $this->setHeader();
            }
        }
        public function insertRow(Row $row) {
            if ($row instanceof Row) {
                foreach ($row->getCells() as $cell) {
                    $coordinate = ''.$cell->getCol().$this->currentRow;
                    $this->sheet->setCellValue($coordinate, $cell->getValue());
                }
                $this->currentRow++;
            }
        }
        private function setTitle()
        {
            $titleStyle = array(
                'font' => array(
                    'name' => 'Arial',
                    'bold' => true,
                    'size' => 12,
                    'color' => array('rgb' => 'FFFFFF')
                ),
                'fill' => array(
                    'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => '000000')
                ),
                'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER
                )
            );

            $bootCols = $this->title->getFrom().$this->currentRow.':'.$this->title->getTo().$this->currentRow;
            $firtsCell = ''.$this->title->getFrom().$this->currentRow;
            $this->sheet->mergeCells($bootCols);
            $this->sheet->getStyle($firtsCell)->applyFromArray($titleStyle);
            $this->sheet->setCellValue($firtsCell, $this->title->getTitle());
            $this->currentRow++;
        }
        public function addStyle($cordinate, $style=array()){
            if ($style !== null) {
                $this->sheet->getStyle($cordinate)->applyFromArray($style);
            }
        }
        private function setHeader() {
            $headerStyle = array(
                'font' => array(
                    'name'      => 'Arial',
                    'bold'      => true,
                    'size' 		=> 10,
                    'color'     => array('rgb' => '000000')
                ),
                'fill' => array(
                    'type'  => \PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'ACB9CA')
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => \PHPExcel_Style_Border::BORDER_THIN
                    )
                ),
                'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER,
                    'wrap' => true
                )
            );
            foreach ($this->header->getData() as $cellHeader) {
                $coordinate = ''.$cellHeader->getCol().$this->currentRow;
                $col = ''.$cellHeader->getCol();
                $style = $cellHeader->getStyle();
                $this->sheet->setCellValue($coordinate, $cellHeader->getValue());
                $this->sheet->getStyle($coordinate)->applyFromArray($headerStyle);
                if ($style !== null) {
                    $this->sheet->getStyle($coordinate)->applyFromArray($style);
                }
                $this->sheet->getColumnDimension($col)->setWidth($cellHeader->getWidth());
            }
            $this->currentRow++;
        }

        public function downloadAsXLSX($name)
        {
            $name = $name . "_" . date('YmdHis') . ".xlsx";
            $writer = new \PHPExcel_Writer_Excel2007($this->phpExcel);
            try {
                header("Content-Description: File Transfer");
                header('Content-Type: application/vnd.ms-excel');
                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Content-Type: application/force-download");
                header("Content-Type: application/download");
                header('Content-Disposition: attachment; filename="' . $name . '"');
                header("Content-Transfer-Encoding: chunked");
                $writer->save("php://output");
            } catch (Exception $e) {
                print_r('Error: ' . $e->getMessage());
            }
            exit;
        }
    }

    class Row {
        private $cells = [];

        /**
         * Row constructor.
         * @param array $cells
         */
        public function __construct(array $cells)
        {
            $this->cells = $cells;
        }

        /**
         * @return array
         */
        public function getCells()
        {
            return $this->cells;
        }

        /**
         * @param array $cells
         */
        public function setCells($cells)
        {
            $this->cells = $cells;
        }


    }
    class Title {
        private $title = '';
        private $from = null;
        private $to = null;

        /**
         * Title constructor.
         * @param string $title
         * @param null $from
         * @param null $to
         */
        public function __construct($title, $from, $to)
        {
            $this->title = $title;
            $this->from = $from;
            $this->to = $to;
        }

        /**
         * @return string
         */
        public function getTitle()
        {
            return $this->title;
        }

        /**
         * @param string $title
         */
        public function setTitle($title)
        {
            $this->title = $title;
        }

        /**
         * @return null
         */
        public function getFrom()
        {
            return $this->from;
        }

        /**
         * @param null $from
         */
        public function setFrom($from)
        {
            $this->from = $from;
        }

        /**
         * @return null
         */
        public function getTo()
        {
            return $this->to;
        }

        /**
         * @param null $to
         */
        public function setTo($to)
        {
            $this->to = $to;
        }


    }
    class Header {
        private $data = [];

        /**
         * Header constructor.
         * @param array $data
         */
        public function __construct(array $data)
        {
            $this->data = $data;
        }


        /**
         * @return array
         */
        public function getData()
        {
            return $this->data;
        }

        /**
         * @param array $data
         */
        public function setData($data)
        {
            $this->data = $data;
        }

    }
    class CellHeader extends Cell {
        private $width = 10;

        public function __construct($value, $col, $width = 10, $style = null)
        {
            parent::__construct($value, $col, $style);
            $this->width = $width;
        }

        /**
         * @return int
         */
        public function getWidth()
        {
            return $this->width;
        }

        /**
         * @param int $width
         */
        public function setWidth($width)
        {
            $this->width = $width;
        }

    }
    class Cell {
        private $value = '';
        private $col = null;
        private $style = null;

        /**
         * Cell constructor.
         * @param string $value
         * @param null $col
         */
        public function __construct($value, $col, $style = null)
        {
            $this->value = $value;
            $this->col = $col;
            $this->style = $style;
        }


        /**
         * @return string
         */
        public function getValue()
        {
            return $this->value;
        }

        /**
         * @param string $value
         */
        public function setValue($value)
        {
            $this->value = $value;
        }

        /**
         * @return null
         */
        public function getCol()
        {
            return $this->col;
        }

        /**
         * @param null $col
         */
        public function setCol($col)
        {
            $this->col = $col;
        }

        /**
         * @return null
         */
        public function getStyle()
        {
            return $this->style;
        }

        /**
         * @param null $style
         */
        public function setStyle($style)
        {
            $this->style = $style;
        }


    }
}