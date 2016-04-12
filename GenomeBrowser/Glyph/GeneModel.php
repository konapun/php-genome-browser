<?php
namespace GenomeBrowser\Glyph;

class GeneModel extends Glyph {
  private $name;
  private $transcriptHeight;
  private $direction;
  private $transcripts;

  const RIGHT = 'right';
  const LEFT = 'left';

  function __construct($track, $start, $end, $name="") {
    parent::__construct($track, $start, $end);

    $this->setColor("blue");
    $this->name = $name;
    $this->transcriptHeight = 32;
    $this->direction = ($start < $end) ? self::RIGHT : self::LEFT;
    $this->transcripts = array();
  }

  function setDirection($direction) {
    $this->direction = $direction;
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
    $arrowSize = 10;
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
    array_push($geneModel, '<line x1="'.$x1.'" y1="'.$y1.'" x2="'.$x2.'" y2="'.$y2.'" data-start="'.$start.'" data-end="'.$end.'"/>');

    // draw the transcripts
    $index = 0;
    foreach ($this->transcripts as $transcript) {
      $first = false;
      $last = false;
      if ($index == 0) { // directionalities are applied to the first or last transcript
        $first = true;
        $last = true;
      }
      //elseif ($index == count($this->transcripts)-1) { // meh, having directions at the start is probably better
      //  $last = true;
      //}

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

      if ($first && $this->direction == self::RIGHT) { // draw at the end pointing right
        $arrowHead = "";
        if ($drawVertical) {
          $height = $height - $arrowSize;
          $arrowStart = $y + $height;
          $arrowEnd = $arrowStart + $arrowSize;
          $arrowTop = $x;
          $arrowBottom = $x + $width;
          $arrowMiddle = ($arrowBottom - $arrowTop) / 2;
          $arrowHead = "$arrowTop,$arrowStart $arrowBottom,$arrowStart $arrowMiddle,$arrowEnd";
        }
        else {
          $width = $width - $arrowSize;
          $arrowStart = $x + $width;
          $arrowEnd = $arrowStart + $arrowSize;
          $arrowTop = $y;
          $arrowBottom = $y + $height;
          $arrowMiddle = ($arrowBottom - $arrowTop) / 2;
          $arrowHead = "$arrowStart,$arrowTop $arrowStart,$arrowBottom $arrowEnd,$arrowMiddle"; // top, bottom, point
        }

        array_push($geneModel, '<g class="transcript" data-start="'.$start.'" data-end="'.$end.'">');
        array_push($geneModel, '<rect x="'.$x.'" y="'.$y.'" width="'.$width.'" height="'.$height.'"/>');
        array_push($geneModel, '<polygon points="'.$arrowHead.'"/>');
        array_push($geneModel, '</g>');
      }
      elseif ($last && $this->direction == self::LEFT) {
        $arrowHead = "";
        if ($drawVertical) {
          $height = $height - $arrowSize;
          $arrowStart = $y;
          $arrowEnd = $y + $arrowSize;
          $arrowTop = $x;
          $arrowBottom = $x + $width;
          $arrowMiddle = ($arrowBottom - $arrowTop) / 2;
          $arrowHead = "$arrowTop,$arrowEnd $arrowBottom,$arrowEnd $arrowMiddle,$arrowStart";
          $y = $arrowEnd;
        }
        else {
          $width = $width - $arrowSize;
          $arrowStart = $x;
          $arrowEnd = $x + $arrowSize;
          $arrowTop = $y;
          $arrowBottom = $y + $height;
          $arrowMiddle = ($arrowBottom - $arrowTop) / 2;
          $arrowHead = "$arrowEnd,$arrowTop $arrowEnd,$arrowBottom $arrowStart,$arrowMiddle"; // top, bottom, point
          $x = $arrowEnd;
        }

        array_push($geneModel, '<g class="transcript" data-start="'.$start.'" data-end="'.$end.'">');
        array_push($geneModel, '<rect x="'.$x.'" y="'.$y.'" width="'.$width.'" height="'.$height.'"/>');
        array_push($geneModel, '<polygon points="'.$arrowHead.'"/>');
        array_push($geneModel, '</g>');
      }
      else {
        array_push($geneModel, '<rect class="transcript" x="'.$x.'" y="'.$y.'" width="'.$width.'" height="'.$height.'" data-start="'.$start.'" data-end="'.$end.'"/>');
      }
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

      $index++;
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
