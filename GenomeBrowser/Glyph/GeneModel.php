<?php
namespace GenomeBrowser\Glyph;

class GeneModel extends Glyph {
  private $name;
  private $transcriptHeight;
  private $transcripts;

  function __construct($track, $start, $end, $name="") {
    parent::__construct($track, $start, $end);
    $this->setColor("blue");
    $this->name = $name;
    $this->transcriptHeight = 32;
    $this->transcripts = array();
  }

  function setLabel($name) {
    $this->name = $name;
  }

  /*
   * In order to position labels at the end of the glyph, we need to extend the
   * coordinate range.
   */
  function getCoordinates() {
    list($start, $end) = parent::getCoordinates();
    if ($this->name) {
      $end += (strlen($this->name) * ($this->getFontSize() / 2));
    }
    return array($start, $end);
  }

  function addTranscript($start, $end, $name="") {
    array_push($this->transcripts, array($start, $end, $name));
  }

  function getRenderSize() {
    return $this->transcriptHeight;
  }

  function render() {
    $drawVertical = $this->drawsVertical();

    $fontSize = $this->getFontSize();
    $color = $this->getColor();
    $geneModel = array('<g class="gene-model" font-family="Verdana" font-size="'.$fontSize.'" stroke="'.$color.'" fill="'.$color.'" stroke-width="2">');

    // draw the line for the gene
    list($start, $end) = $this->getGeneModelCoordinates();
    $trackHeight = $this->getRenderSize();
    $trackCenter = $trackHeight / 2;
    $geneStart = $this->coordToPixel($start);
    $geneEnd = $this->coordToPixel($end);

    $x1 = $geneStart;
    $x2 = $geneEnd;
    $y1 = $trackCenter;
    $y2 = $trackCenter;
    if ($drawVertical) {
      $x1 = $trackCenter;
      $x2 = $trackCenter;
      $y1 = $geneStart;
      $y2 = $geneEnd;
    }
    array_push($geneModel, '<line x1="'.$x1.'" y1="'.$y1.'" x2="'.$x2.'" y2="'.$y2.'"/>');

    // draw the transcripts
    foreach ($this->transcripts as $transcript) {
      list($start, $end, $name) = $transcript;
      $transcriptStart = $this->coordToPixel($start);
      $transcriptEnd = $this->coordToPixel($end);
      $transcriptWidth = $transcriptEnd - $transcriptStart;

      $x = $transcriptStart;
      $y = 0;
      $width = $transcriptWidth;
      $height = $trackHeight;
      if ($drawVertical) {
        $x = 0;
        $y = $transcriptStart;
        $width = $trackHeight;
        $height = $transcriptWidth;
      }

      array_push($geneModel, '<rect class="transcript" x="'.$x.'" y="'.$y.'" width="'.$width.'" height="'.$height.'"/>');
      if ($name) {
        $style = "";
        $transcriptMiddleY = $y + ($height / 2) + ($fontSize / 2);
        $transcriptMiddleX = $x + ($width / 2) - (($fontSize / 2) * strlen($name) / 2);
        if ($drawVertical) {
          $style = 'writing-mode: tb;';
          $transcriptMiddleX = $x + ($width / 2) + ($fontSize / 2);
          $transcritpiMiddleY = $y + ($height / 2) - (($fontSize / 2) * strlen($name) / 2);
        }
        array_push($geneModel, '<text style="'.$style.'" stroke="none" fill="white" class="transcript-name" x="'.$transcriptMiddleX.'" y="'.$transcriptMiddleY.'">'.$name.'</text>');
      }
    }

    // Draw the name of the gene model if there is one
    if ($this->name) {
      $name = $this->name;

      $style = "";
      $nameWidth = strlen($name) * $fontSize;
      $nameX = $x2;
      $nameY = $y1;
      if ($drawVertical) {
        $style = "writing-mode: tb;";
        $nameX = $x2;
        $nameY = $y2;
      }
      array_push($geneModel, '<text style="'.$style.'" stroke="none" fill="black" class="gene-model-name" x="'.$nameX.'" y="'.$nameY.'">'.$name.'</text>');
    }
    array_push($geneModel, '</g>');
    return join("\n", $geneModel);
  }

  /*
   * Preserve the parent method for rendering the correct range since we've
   * hijacked the getCoordinates method to account for text labels
   */
  private function getGeneModelCoordinates() {
    return parent::getCoordinates();
  }

  private function getFontSize() {
    return $this->transcriptHeight / 2;
  }
}
 ?>
