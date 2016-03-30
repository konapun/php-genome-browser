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
include_once($glyphBase . 'Arrow.php');
include_once($glyphBase . 'GeneModel.php');
include_once($glyphBase . 'Label.php');
include_once($glyphBase . 'Line.php');
include_once($glyphBase . 'Padding.php');
include_once($glyphBase . 'Scale.php');
include_once($glyphBase . 'Sequence.php');
?>
