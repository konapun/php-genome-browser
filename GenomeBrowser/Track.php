<?php
namespace GenomeBrowser;

use GenomeBrowser\Glyph\ArrowGlyph as ArrowGlyph;
use GenomeBrowser\Glyph\GeneModelGlyph as GeneModelGlyph;
use GenomeBrowser\Glyph\LineGlyph as LineGlyph;
use GenomeBrowser\Glyph\ScaleGlyph as ScaleGlyph;

class Track extends Container {
  private $browser;
  private $enabled;
  private $features;

  function __construct($browser) {
    parent::__construct('glyph');
    $this->browser = $browser;
    $this->enabled = true;
    $this->features = array();
  }

  function disable($bool=true) {
    $this->enabled = !$bool;
  }

  function setPadding($padding) {
    $this->padding = $padding;
  }

  function getWidth() {
    if ($this->browser->drawsVertical()) { // width is dynamic
      return $this->getRenderSize();
    }
    return $this->getSize();
  }

  function getHeight() {
    if ($this->browser->drawsVertical()) { // height is static
      return $this->getSize();
    }
    return $this->getRenderSize();
  }

  function getSize() {
    return $this->browser->getSize();
  }

  function getRenderSize() {
    if (!$this->enabled) return 0;
    return parent::getRenderSize();
  }

  function getGenomeBrowser() {
    return $this->browser;
  }

  function drawsVertical() {
    return $this->browser->drawsVertical();
  }

  function addFeature($feature) {
    $start = null;
    $end = null;
    if (func_num_args() > 1) { // Adding a feature based on type by naming convention rather than an instance of GenomeBrowser\Glyph\Glyph
      $args = array_slice(func_get_args(), 1); // remove $feature from args since we already have it
      $className = 'GenomeBrowser\\Glyph\\'.$this->getGlyphName($feature);
      if (class_exists($className)) {
        array_unshift($args, $this); // all glyphs are instantiated with the track as the first argument
        $reflection = new \ReflectionClass($className);
        $feature = $reflection->newInstanceArgs($args);
      }
      else {
        throw new \InvalidArgumentException("Unable to locate glyph for feature type $feature (tried $className)");
      }
    }

    array_push($this->features, $feature);
    return $feature;
  }

  function addScale() {
    $scale = new ScaleGlyph($this);
    $this->addFeature($scale);
    return $scale;
  }

  function addLine($start, $end) {
    $line = new LineGlyph($this, $start, $end);
    $this->addFeature($line);
    return $line;
  }

  function addArrow($start, $end, $direction=ArrowGlyph::RIGHT) {
    $arrow = new ArrowGlyph($this, $start, $end, $direction);
    $this->addFeature($arrow);
    return $arrow;
  }

  function addGeneModel($start, $end, $name="") {
    $geneModel = new GeneModelGlyph($this, $start, $end, $name);
    $this->addFeature($geneModel);
    return $geneModel;
  }

  function render() {
    if (!$this->enabled) return "";
    return parent::render();
  }

  protected function getCollection() {
    return $this->features;
  }

  /*
   * Returns the glyph name for a type by converting it to lower-case, replacing
   * spaces and hyphens with camelCase, and applying the naming convention
   * "GenomeBrowser[type]Glyph"
   */
  private function getGlyphName($type) {
    return ucfirst(join('', array_map(function($e) { return ucfirst($e); }, preg_split('/-|\s+/', strtolower($type))))) . 'Glyph';
  }
}
?>
