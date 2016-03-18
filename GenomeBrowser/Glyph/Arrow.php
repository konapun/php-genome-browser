<?php
namespace GenomeBrowser\Glyph;

class Arrow extends Glyph {
  private $thickness;
  private $arrowSize;
  private $direction;

  const RIGHT = 'right';
  const LEFT = 'left';

  function __construct($track, $start, $end, $direction=Arrow::RIGHT) {
    if ($end < $start) {
      $tmp = $end;
      $end = $start;
      $start = $tmp;
      $direction = Arrow::LEFT;
    }

    parent::__construct($track, $start, $end);
    $this->thickness = 10;
    $this->arrowSize = 10;
    $this->setDirection($direction);
  }

  function getRenderSize() {
    return $this->thickness;
  }

  function setThickness($thickness) {
    $this->thickness = $thickness;
  }

  function setDirection($direction) {
    switch ($direction) {
      case Arrow::RIGHT:
      case Arrow::LEFT:
        $this->direction = $direction;
        break;
      default:
        throw new \InvalidArgumentException("Direction must be 'right' or 'left'");
    }
  }

  function render() {
    $drawVertical = $this->drawsVertical();

    $strokeWidth = $this->getRenderSize();
    $trackCenter = $strokeWidth / 2;
    $arrowSize = $this->arrowSize;
    list($start, $end) = $this->getCoordinates();
    $start = $this->coordToPixel($start);
    $end = $this->coordToPixel($end);

    $x1 = $start;
    $y1 = $strokeWidth/2;
    $x2 = $end - $arrowSize;
    $y2 = $y1;
    $arrowTop = 0;
    $arrowBottom = $strokeWidth;
    $arrowMiddle = ($arrowBottom - $arrowTop) / 2;
    $arrowPoint = $end;
    $arrowHead = "$x2,$arrowBottom $arrowPoint,$arrowMiddle $x2,$arrowTop";
    if ($this->direction == self::LEFT) {
      $x1 = $start + $arrowSize;
      $x2 = $end;
      $arrowPoint = $start;
      $arrowHead = "$start,$arrowMiddle $x1,$arrowBottom $x1,$arrowTop";
    }

    if ($drawVertical) {
      $x1 = $trackCenter;
      $y1 = $start;
      $x2 = $x1;
      $y2 = $end - $arrowSize;

      $arrowTop = 0;
      $arrowBottom = $arrowTop + $strokeWidth;
      $arrowMiddle = ($arrowBottom - $arrowTop) / 2;
      $arrowPoint = $end;
      $arrowHead = "$arrowTop,$y2 $arrowBottom,$y2 $arrowMiddle,$arrowPoint";
      if ($this->direction == self::LEFT) {
        $y1 = $start + $arrowSize;
        $y2 = $end;
        $arrowPoint = $start;
        $arrowHead = "$arrowTop,$y1 $arrowBottom,$y1 $arrowMiddle,$arrowPoint";
      }
    }

    $arrow = array('<g class="arrow-glyph" stroke="'.$this->getColor().'" fill="'.$this->getColor().'" data-direction="'.$this->direction.'">');
    array_push($arrow, '<line stroke-width="'.$strokeWidth.'" x1="'.$x1.'" y1="'.$y1.'" x2="'.$x2.'" y2="'.$y2.'"/>');
    array_push($arrow, '<polygon points="'.$arrowHead.'"/>');
    array_push($arrow, '</g>');
    return join("\n", $arrow);
  }
}
?>
