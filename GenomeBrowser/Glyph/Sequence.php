<?php
namespace GenomeBrowser\Glyph;

class Sequence extends Glyph {
  private $sequence;
  private $colorMap;
  private $textHeight;
  private $padding;

  function __construct($track, $start, $sequence) {
    parent::__construct($track, $start, ($start+strlen($sequence)-1));
    $this->sequence = $sequence;
    $this->textHeight = 12;
    $this->colorMap = array(
      'A' => array(
        'bg' => 'blue',
        'fg' => 'white'
      ),
      'C' => array(
        'bg' => 'red',
        'fg' => 'white'
      ),
      'T' => array(
        'bg' => 'yellow',
        'fg' => 'black'
      ),
      'G' => array(
        'bg' => 'orange',
        'fg' => 'black'
      )
    );
  }

  function getRenderSize() {
    return $this->textHeight + ($padding*2);
  }

  function render() {
    $drawVertical = $this->drawsVertical();

    $cellSize = $this->getSize() / strlen($this->sequence);
    $trackCenter = $this->getSize() / 2;
    $strokeWidth = $this->getSize();
    list($start, $end) = $this->getCoordinates();
    $start = $this->coordToPixel($start);
    $end = $this->coordToPixel($end);
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

    $x = 0;
    $y = 0;
    $svg = array('<g class="sequence">');
    foreach (str_split($this->sequence) as $base) {
      array_push($svg, '<rect x="'.$x.'" y="'.$y.'" width="'.$cellSize.'" height="'.$this->getRenderSize().'" fill="'.$this->colorMap[strtoupper($base)]['bg'].'"/>');
      $x += $cellSize;
    }
    array_push($svg, '</g>');
    return join("\n", $svg);
    return '<line class="line-glyph" stroke-width="'.$strokeWidth.'" stroke="'.$this->getColor().'" x1="'.$x1.'" y1="'.$y1.'" x2="'.$x2.'" y2="'.$y2.'"/>';
  }
}
?>
