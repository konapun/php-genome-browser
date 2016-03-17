<?php
include_once('GenomeBrowser.php');
use GenomeBrowser\Browser as Browser;

$browser = new Browser(900);

$geneModelTrack = $browser->addTrack();
$geneModel = $geneModelTrack->addGeneModel(1, 200);
$geneModel->addTranscript(20, 30);
$geneModel->addTranscript(50, 90);

$exonTrack = $browser->addTrack();
$exonTrack->addArrow(20, 30);
$exonTrack->addArrow(90, 50);

$testTrack = $browser->addTrack();
$testTrack->addFeature('arrow', 1, 100);
$scale = $browser->addTrack()->addScale();
$scale->labelSteps();
$scale->setStepping(50);

// The genome browser
$svg = $browser->render();
file_put_contents('./test/glyphs.svg', $svg);
?>
