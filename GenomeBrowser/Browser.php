<?php
namespace GenomeBrowser;

class Browser extends Container {
  private $size;
  private $padding;
  private $start;
  private $end;
  private $vertical;
  private $tracks;

  /*
   * The size of the genome browser is its static size.
   *
   * In a horizontal (default) genome browser, the static size is its width
   * while a vertical genome browser uses the height as its static size, since
   * the other property is based on its tracks
   */
  function __construct($size=500) {
    parent::__construct('track');
    $this->size = $size;
    $this->start = 0;
    $this->end = 0;
    $this->vertical = false;
    $this->tracks = array();
  }

  function setSize($size) {
    $this->size = $size;
  }

  function getSize() {
    return $this->size;
  }

  function getWidth() {
    if ($this->vertical) {
      return $this->getRenderSize(); // width grows with tracks
    }
    return $this->getSize(); // horizontal width is static
  }

  function getHeight() {
    if ($this->vertical) {
      return $this->getSize();
    }
    return $this->getRenderSize();
  }

  function addTrack() {
    $track = new Track($this);
    array_push($this->tracks, $track);
    return $track;
  }

  function drawsVertical() {
    return $this->vertical;
  }

  function drawVertical($bool=true) {
    $this->vertical = $bool;
  }

  function render() {
    $svg = array('<svg xmlns="http://www.w3.org/2000/svg" class="genome-browser" width="'.$this->getWidth().'" height="'.$this->getHeight().'" version="1.1">');
    array_push($svg, parent::render());
    array_push($svg, '</svg>');

    return join("\n", $svg);
  }

  protected function getCollection() {
    return $this->tracks;
  }
}
?>
