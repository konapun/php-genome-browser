<?php
namespace GenomeBrowser\Glyph;

use GenomeBrowser\Renderable;

abstract class Glyph implements Renderable {
  private $track;
  private $start;
  private $end;
  private $color;

  function __construct($track, $start=-1, $end=-1) {
    $this->track = $track;
    $this->start = $start;
    $this->end = $end;
    $this->color = 'black';
  }

  function setColor($color) {
    $this->color = $color;
  }

  function getColor() {
    return $this->color;
  }

  function getSize() {
    return $this->getTrack()->getSize();
  }

  function getWidth() {
    if ($this->drawsVertical()) {
      return $this->getRenderSize();
    }
    return $this->getSize();
  }

  function getHeight() {
    if ($this->drawsVertical()) {
      return $this->getSize();
    }
    return $this->getRenderSize();
  }

  function getCoordinates() {
    return array($this->start, $this->end);
  }

  protected function getTrack() {
    return $this->track;
  }

  protected function getGenomeBrowser() {
    return $this->getTrack()->getGenomeBrowser();
  }

  protected function drawsVertical() {
    return $this->getGenomeBrowser()->drawsVertical();
  }

  protected function coordToPixel($coord) {
    $size = $this->getSize(); // use static size
    list($start, $end) = $this->getTrack()->getGenomeBrowser()->getCoordinates();

    $adjustedCoord = $coord - $start;
    $coordsInModel = abs($end - $start);
    $scaling = $size / $coordsInModel;
    return $adjustedCoord * $scaling;
  }
}
?>
