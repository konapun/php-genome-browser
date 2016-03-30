<?php
namespace GenomeBrowser\Glyph;

class Padding extends Glyph {
  private $padding;

  function __construct($track, $padding) {
    parent::__construct($track, -1, -1);
    $this->padding = $padding;
  }

  function getRenderSize() {
    return $this->padding;
  }

  function render() {
    return '<line class="padding-glyph" stroke-width="'.$this->padding.'" x1="0" y1="0" x2="0" y2="0""/>';
  }
}
?>
