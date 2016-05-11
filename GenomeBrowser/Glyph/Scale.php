<?php
namespace GenomeBrowser\Glyph;

class Scale extends Glyph {
  private $stepping;
  private $labelSteps;
  private $stepHeight;
  private $padding;
  private $textHeight;

  function __construct($track) {
    parent::__construct($track, -1, -1);
    $this->stepping = 10;
    $this->stepHeight = 10;
    $this->padding = 4;
    $this->textHeight = 12;
    $this->labelSteps = false;
  }

  function labelSteps($bool=true) {
    $this->labelSteps = $bool;
  }

  function setStepping($stepping) {
    $this->stepping = $stepping;
  }

  function getRenderSize() {
    $height = $this->stepHeight;
    if ($this->labelSteps) {
      if ($this->drawsVertical()) { // need to account for length of the longest label
        list($start, $end) = $this->getGenomeBrowser()->getCoordinates(); // need global coords since this sets them to (-1 to -1) since it doesn't know them ahead of time

        $nchars = strlen($end) + 1; // 1 extra for label
        $height += $this->padding + ($this->textHeight * $nchars);
      }
      else {
        $height += $this->padding + $this->textHeight;
      }
    }

    return $height;
  }

  function render() {
    $drawsVertical = $this->drawsVertical();
    $shouldLabel = $this->labelSteps;
    $this->labelSteps(false); // temporarily set so everything draws correctly

    $scale = array('<g class="scale" font-family="Verdana" font-size="'.($this->textHeight).'" stroke="'.$this->getColor().'" line-color="'.$this->getColor().'" stroke-width="2">');

    $renderSize = $this->getRenderSize();
    $lineCenter = $renderSize / 2;
    $x1 = 0;
    $y1 = $lineCenter;
    $x2 = $this->getSize();
    $y2 = $lineCenter;
    if ($drawsVertical) {
      $x1 = $lineCenter;
      $y1 = 0;
      $x2 = $lineCenter;
      $y2 = $this->getSize();
    }

    // The main scale  line
    array_push($scale, '<line class="scale-x" x1="'.$x1.'" y1="'.$y1.'" x2="'.$x2.'" y2="'.$y2.'"/>');

    list($startCoords, $endCoords) = $this->getGenomeBrowser()->getCoordinates();
    $range = $endCoords - $startCoords;
    $steppingDistance = $this->stepping * ($this->getSize() / $range);

    $stepStart = $startCoords - 1;
    for ($x = 0; $x < $this->getSize(); $x+= $steppingDistance) { // scale steps
      $x1 = $x;
      $y1 = 0;
      $x2 = $x;
      $y2 = $renderSize;
      if ($drawsVertical) {
        $x1 = 0;
        $y1 = $x;
        $x2 = $renderSize;
        $y2 = $x;
      }

      array_push($scale, '<line stroke-width="1" class="scale-step" x1="'.$x1.'" y1="'.$y1.'" x2="'.$x2.'" y2="'.$y2.'"/>');

      if ($shouldLabel) {
        $label = $this->coordToHumanReadable($stepStart);

        $xpos = $x1 - ($this->textHeight / 2);
        $ypos = $y2 + $this->padding + $this->textHeight;
        if ($xpos < 0) $xpos = 0;
        if ($drawsVertical) {
          $ypos = $y1 + ($this->textHeight / 2);
          $xpos = $x2 + $this->padding;
          if ($ypos < $this->textHeight) $ypos = $this->textHeight;
        }

        array_push($scale, '<text stroke-width="1" x="'.$xpos.'" y="'.$ypos.'">'.$label.'</text>');
        $stepStart += $this->stepping;
      }
    }

    array_push($scale, '</g>');

    $this->labelSteps($shouldLabel);
    return join("\n", $scale);
  }

  /*
   * Converts a large number to human readable form
   */
  private function coordToHumanReadable($coord) {
    $units = array( // must be sorted from high to low
      'tera' => array(
        'factor' => 1000000000000,
        'symbol' => 'T'
      ),
      'giga' => array(
        'factor' => 1000000000,
        'symbol' => 'G'
      ),
      'mega' => array(
        'factor' => 1000000,
        'symbol' => 'M'
      ),
      'kilo' => array(
        'factor' => 1000,
        'symbol' => 'K'
      )
    );

    foreach ($units as $unit) {
      $factor = $unit['factor'];

      if ($coord > $factor) {
        return ($coord / $factor) . $unit['symbol'];
      }
    }
    return $coord;
  }
}
?>
