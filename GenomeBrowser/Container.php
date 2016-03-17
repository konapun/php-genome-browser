<?php
namespace GenomeBrowser;

abstract class Container implements Renderable {
  private $svgClass;
  private $padding;

  abstract function drawsVertical();
  abstract protected function getCollection();

  function __construct($svgClass="") {
    $this->padding = 5;
    $this->svgClass = $svgClass;
  }

  function setPadding($padding) {
    $this->padding = $padding;
  }

  function getPadding() {
    return $this->padding;
  }

  function getCoordinates() {
    $start = -1;
    $end = -1;

    foreach ($this->getCollection() as $feature) {
      list($featureStart, $featureEnd) = $feature->getCoordinates();

      if ($start < 0 || ($featureStart > 0 && $featureStart < $start)) {
        $start = $featureStart;
      }
      if ($end < 0 || ($featureEnd > 0 && $featureEnd > $end)) {
        $end = $featureEnd;
      }
    }

    return array($start, $end);
  }

  function getRenderSize() {
    $size = 0;
    $padding = $this->getPadding();
    foreach ($this->getCollection() as $feature) {
      $size += $feature->getRenderSize() + $padding;
    }
    return $size;
  }

  function render() {
    $svg = array();

    $x = 0;
    $y = 0;
    $padding = $this->getPadding();
    foreach ($this->getCollection() as $feature) {
      $featureWidth = $feature->getWidth();
      $featureHeight = $feature->getHeight();

      array_push($svg, '<svg x="'.$x.'" y="'.$y.'"  width="'.$featureWidth.'" height="'.$featureHeight.'" class="'.$this->svgClass.'">');
      array_push($svg, $feature->render());
      array_push($svg, '</svg>');

      if ($this->drawsVertical()) {
        $y = 0;
        $x += $feature->getRenderSize() + $padding;
      }
      else {
        $x = 0;
        $y += $feature->getRenderSize() + $padding;
      }
    }

    return join("\n", $svg);
  }
}
?>
