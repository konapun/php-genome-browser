<?php
namespace GenomeBrowser;

use GenomeBrowser\Glyph\ArrowGlyph as ArrowGlyph;
use GenomeBrowser\Glyph\GeneModelGlyph as GeneModelGlyph;
use GenomeBrowser\Glyph\LineGlyph as LineGlyph;
use GenomeBrowser\Glyph\ScaleGlyph as ScaleGlyph;

class Track extends Container {
  private $browser;
  private $features;

  function __construct($browser) {
    parent::__construct('glyph');
    $this->browser = $browser;
    $this->features = array();
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

  function getGenomeBrowser() {
    return $this->browser;
  }

  function drawsVertical() {
    return $this->browser->drawsVertical();
  }

  function addFeature($feature, $start=null, $end=null) {
    if (!is_null($start)) { // Adding a feature based on type by naming convention rather than an instance of GenomeBrowserGlyph
      $className = 'GenomeBrowser\\Glyph\\'.$this->getGlyphName($feature);
      if (class_exists($className)) {
        $feature = new $className($this, $start, $end);
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