<?php

require_once('lib/fpdf/fpdf.php');
require_once('lib/fpdi/fpdi.php');


class LabelMaker {
  public $perPage      = 30;
  public $currentLabel = 0;
  public $topMargin    = 15;
  public $leftMargin   = 4;
  public $labelWidth   = 69;
  public $labelHeight  = 25.5;
  public $lineHeight   = 5;

  public $cellLeftPad = 5;
  public $cellTopPad  = 3;

  public function __construct($withTemplate=false){
    $this->pdf = new FPDI('P', 'mm', 'Letter');
    $this->pdf->SetFont('Arial','B',11);

    $this->withTemplate = $withTemplate;
    $this->_addPage();
  }

  private function _addPage() {
    $this->currentLabel = 0;
    if ($this->withTemplate) {
      $this->pdf->setSourceFile('template.pdf'); 
      $this->pdf->addPage();
      $tplIdx = $this->pdf->importPage(1);
      $this->pdf->useTemplate($tplIdx, 0,0);

    } else {
      $this->pdf->addPage();
    }
  }

  private function _cornerX(){
    return $this->leftMargin + ($this->currentLabel % 3)  * $this->labelWidth;
  }

  private function _cornerY(){
    return $this->topMargin + floor($this->currentLabel / 3) * $this->labelHeight;
  }

  public function addAddress($line1, $line2, $line3, $line4=null){
    $x = $this->_cornerX() + $this->cellLeftPad;
    $y = $this->_cornerY() + $this->cellTopPad;

    $this->pdf->setXY($x, $y);
    $this->pdf->Cell(0,0,$line1,0,1);

    foreach (array($line2, $line3, $line4) as $line) {
      if ($line) {
        $y += $this->lineHeight; // line height
        $this->pdf->setXY($x, $y);
        $this->pdf->Cell(0,0,$line,0,1);
      }
    }

    $this->currentLabel++;
    if ($this->currentLabel >= $this->perPage) {
      $this->_addPage();
    }
  }

  public function output(){
    $this->pdf->Output();
  }
}
?>
