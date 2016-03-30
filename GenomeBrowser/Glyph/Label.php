<?php
namespace GenomeBrowser\Glyph;

class Label extends Glyph {
  private $lines;
  private $fontSize;

  function __construct($track, $lines) {
    parent::__construct($track, -1, -1);
    $this->lines = $lines;
    $this->line1 = $line1;
    $this->line2 = $line2;
    $this->fontSize = 12;
  }

  function setFontSize($size) {
    $this->fontSize = $size;
  }

  function getRenderSize() {
    $fontSize = $this->fontSize;
    if ($this->drawsVertical()) {
      return count($this->lines) * $fontSize + 20;
    }
    return $fontSize * 2;
  }

  function render() {
    $drawsVertical = $this->drawsVertical();
    $fontSize = $this->fontSize;

    $x = 0;
    $y = $fontSize;
    $style = "";
    if ($drawsVertical) {
      $x = $fontSize;
      $y = $fontSize;
      $style = 'style="writing-mode: vertical-rl;"';
    }
    $svg = array('<g class="label-glyph" fill="black" font-size="'.$fontSize.'">');
    foreach ($this->lines as $line) {
      array_push($svg, '<text x="'.$x.'" y="'.$y.'"'.$style.'>'.$line.'</text>');
      if ($drawsVertical) {
       $x += $fontSize;
      }
      else {
        $y += $fontSize;
      }
    }
    array_push($svg, '</g>');
    return join("\n", $svg);
  }
}
?>
