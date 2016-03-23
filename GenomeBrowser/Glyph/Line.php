<?php
namespace GenomeBrowser\Glyph;

class Line extends Glyph {
  private $thickness;

  function __construct($track, $start, $end) {
    parent::__construct($track, $start, $end);
    $this->thickness = 3;
  }

  function getRenderSize() {
    return $this->thickness;
  }

  function render() {
    $drawVertical = $this->drawsVertical();

    $trackCenter = $this->getSize() / 2;
    $strokeWidth = $this->getSize();
    list($coordStart, $coordEnd) = $this->getCoordinates();
    $start = $this->coordToPixel($coordStart);
    $end = $this->coordToPixel($coordEnd);
    $x1 = $start;
    $y1 = $trackCenter;
    $x2 = $end;
    $y2 = $trackCenter;
    if ($drawVertical) {
      $x1 = $trackCenter;
      $y1 = $start;
      $x2 = $trackCenter;
      $y2 = $end;
    }

    return '<line class="line-glyph" stroke-width="'.$strokeWidth.'" stroke="'.$this->getColor().'" x1="'.$x1.'" y1="'.$y1.'" x2="'.$x2.'" y2="'.$y2.'" data-start="'.$coordStart.'" data-end="'.$coordEnd.'"/>';
  }
}
?>
