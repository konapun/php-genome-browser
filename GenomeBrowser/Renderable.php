<?php
namespace GenomeBrowser;

/*
 * Interface for something that may be rendered
 */
interface Renderable {

  /*
   * Returns the size of the static dimension
   */
  function getSize();

  /*
   * Returns the size of the dynamic dimension
   */
  function getRenderSize();

  /*
   * Returns the height by calling either getSize or getRenderSize as
   * appropriate
   */
  function getHeight();

  /*
   * Returns the width by calling either getSize or getRenderSize as
   * appropriate
   */
  function getWidth();

  /*
   * Return the start and end genomic coordinates represented by this renderable
   */
  function getCoordinates();

  /*
   * Returns a rendered representation of the element
   */
  function render();
}
?>
