# PHP Genome Browser
An implementation of a genome browser in pure PHP which renders as SVG.

## Usage
```php

$browser = new GenomeBrowser(900); // Render a dotplot with 900 px in its static direction

$geneModelTrack = $browser->addTrack();
$geneModel = $geneModelTrack->addGeneModel(1, 200); // Gene model glyphs support showing transcripts
$geneModel->addTranscript(20, 30);
$geneModel->addTranscript(50, 90);

$exonTrack = $browser->addTrack();
$exonTrack->addArrowGlyph(20, 30); // Arrow glyphs are directional. This one goes 5' to 3'
$exonTrack->addArrowGlyph(90, 50); // Since start > end in this case, this feature points 3' to 5'

$scale = $browser->addTrack()->addScale();
$scale->labelSteps();
$scale->setStepping(50);

// The genome browser
$svg = $browser->render();
```

## Browser
The browser is a collection of tracks

### Methods
  * `__construct($size=500)`: Create a new genome browser with $size as the static size
    * If the browser is being rendered horizontally (default), its vertical size is dynamic to grow with the tracks and glyphs added. If the browser is rendered vertically then its horizontal size is dynamic.
    * Returns: Genome browser
  * `setSize($px)`: Sets the size for the browser's static dimension (in pixels)
  * `getSize()`: Get the static size for the browser (in pixels)
    * Returns: Size in pixels
  * `getWidth()`: Get the browser's width (in pixels).
    * If the browser is being rendered horizontally (default) then its width is its static dimension. If it's being rendered vertically then its width is its dynamic dimension.
    * Returns: Width in pixels
  * `getHeight()`: Get the browser's height (in pixels).
    * If the browser is being rendered horizontally (default) then its height is its dynamic dimension. If it's being rendered vertically then its height is its static dimension.
    * Returns: Height in pixels
  * `addTrack()`: Adds a new track to the genome browser
    * Returns: Track
  * `drawsVertical()`: Whether or not the browser is set to draw vertically (horizontal is default)
    * Returns: Boolean
  * `drawVertical($bool=true)`: Set the browser's render direction
  * Render: Render the genome browser as SVG
    * Returns: SVG markup representing the browser

## Tracks
Tracks are used for grouping glyphs into cohesive features.

### Methods
  * `$track->setPadding(int)`: Set the padding between tracks
  * `$track->addFeature($feature, $start=null, $end=null)`: Add a feature to the track
    * If $start and $end are null, $feature is a proper subclass of `GenomeBrowser\Glyph\Glyph`. Else, $feature is a string which is used to look up the proper glyph
    * Returns: Glyph that was added
  * `$track->addScale()`: Adds a scale glyph to the track
    * Returns: Scale glyph
  * `$track->addLine($start, $end)`: Adds a line glyph to the track
    * $start and $end are the start and end coordinates for the line glyph
    * Returns: Line glyph
  * `$track->addArrow($start, $end, $direction='right')`: Adds an arrow glyph to the track
    * If $end > $start, $direction is automatically set to `left`
    * Returns: Arrow glyph
  * `$track->addGeneModel($start, $end)`: Adds a gene model glyph to the track
    * Returns: GeneModel glyph

## Glyphs
All glyphs extend `GenomeBrowser\Glyph\Glyph`. Available glyphs are:
  * **GeneModelGlyph**
  * **LineGlyph**
  * **ScaleGlyph**
  * **ArrowGlyph**
