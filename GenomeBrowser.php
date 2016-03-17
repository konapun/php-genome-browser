<?php
/*
 * Import everything so they can be used via `use` statements
 *
 * Author: Bremen Braun
 */

$browserBase = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'GenomeBrowser' . DIRECTORY_SEPARATOR;
$glyphBase = $browserBase . 'Glyph' . DIRECTORY_SEPARATOR;

include_once($browserBase . 'Renderable.php');
include_once($browserBase . 'Container.php');
include_once($browserBase . 'Track.php');
include_once($browserBase . 'Browser.php');

include_once($glyphBase . 'Glyph.php');
include_once($glyphBase . 'ArrowGlyph.php');
include_once($glyphBase . 'GeneModelGlyph.php');
include_once($glyphBase . 'LineGlyph.php');
include_once($glyphBase . 'ScaleGlyph.php');
include_once($glyphBase . 'SequenceGlyph.php');
?>
