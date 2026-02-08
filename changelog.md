## New Features

- DriverInterface::version()
- ColorInterface::create() now accepts functional string color formats as well as single channel values
- Alignment::class
- DataUri::class
- Default resolution is now 72 ppi for new images
- Origin::format()
- Color::class
- Support for ICO-Format
- Support for OkLab color space
- Improved structure and hiarchy for exceptions
- Improved error and exception messages
- Function color format now supports syntax without comma
- All colors now have full alpha channel support

## API Changes

- ImageManagerInterface::animate() is replaced by universal ImageManagerInterface::createImage()
- ImageManagerInterface::read() is now handled by Image::decode*()
- ImageManagerInterface::withDriver() is now handled by Image::usingDriver()
- ImageInterface::blendTransparency() was renamed to ImageInterface::fillTransparentAreas() - Signature changed & allowed (semi) transparent colors
- ImageInterface::setBlendingColor() was renamed to ImageInterface::setBackgroundColor()
- ImageInterface::blendingColor() was renamed to ImageInterface::backgroundColor()
- Config::class option blendingColor was renamed to backgroundColor
- BlendTransparencyModifer::class was renamed to FillTransparentAreasModifier::class
- Changed default value for `background` to `null` in ImageInterface::rotate()
- Changed default value for `background` to `null` in ImageInterface::resizeCanvas()
- Changed default value for `background` to `null` in ImageInterface::resizeCanvasRelative()
- Changed default value for `background` to `null` in ImageInterface::contain()
- Changed default value for `background` to `null` in ImageInterface::pad()
- Changed default value for `background` to `null` in ImageInterface::crop()
- Signature of ImageInterface::crop() changed from `offset_x` to `x` and `offset_y` to `y`
- EncodedImageInterface::toDataUri() now returns `DataUriInterface` instead of `string´
- ProfileInterface requires implementation of `::fromPath()`
- DriverInterface requires implementation of `__construct()`
- DriverInterface requires implementation of `createCore()`
- Replace DriverInterface::specialize() with DriverInterface::specializeModifier(), DriverInterface::specializeAnalyzer(), DriverInterface::specializeDecoder() and DriverInterface::specializeEncoder()
- Attribute `$per_unit` has change to `$unit` with different signature in `Resolution::class`
- ImageInterface::toJpeg() and ImageInterface::toJpg() are replaced by ImageInterface::encodeUsingFormat()
- ImageInterface::toPng() is replaced by ImageInterface::encodeUsingFormat()
- ImageInterface::toGif() is replaced by ImageInterface::encodeUsingFormat()
- ImageInterface::toJp2() and ImageInterface::toJpeg2000() are replaced by ImageInterface::encodeUsingFormat()
- ImageInterface::toWebp() is replaced by ImageInterface::encodeUsingFormat()
- ImageInterface::toBitmap() and ImageInterface::toBmp() are replaced by ImageInterface::encodeUsingFormat()
- ImageInterface::toAvif() is replaced by ImageInterface::encodeUsingFormat()
- ImageInterface::toHeic() is replaced by ImageInterface::encodeUsingFormat()
- ImageInterface::toTiff() and ImageInterface::toTif() are replaced by ImageInterface::encodeUsingFormat()
- DriverInterface::handleColorInput() has null as default
- Method ImageInterface::save() only processes known image file extensions
- Method FontInterface::filename() is replaced by FontInterface::filepath()
- Method FontInterface::hasFilename() is replaced by FontInterface::hasFile()
- Method FontInterface::setFilename() is replaced by FontInterface::setFilepath()
- Usage of internal font's of GD library is determined by font size and no font file instead of font file
- Method DrawableFactoryInterface::init() is replaced by DrawableFactoryInterface::create()
- Method DrawableFactoryInterface::create() is replaced by DrawableFactoryInterface::drawable()
- Signature of Frame::__construct() has changed, argument $offset_left is know $offsetLeft and $offset_top is now $offsetTop
- Signature of PixelColorAnalyzer::__construct() has changed, argument $frame_key is know $frame
- DriverInterface::handleInput() is replaced by DriverInterface::handleImageInput(), DriverInterface::handleColorInput()
- ColorChannelInterface::max() and ColorChannelInterface::min() are now static
- Method ColorInterface::convertTo() was renamed to ColorInterface::toColorspace()
- Method ColorChannelInterface::toInt() was removed use ColorChannelInterface::value() instead
- Method ColorChannelInterface::colorFromNormalized() requires now a static implementation
- Method ColorChannelInterface::normalize() was renamed to ColorChannelInterface::normalizedValue()
- CoreInterface::class now requires implementation of CoreInterface::meta()
- RectangleResizer::class was renamed to Resize::class
- ImageInterface::pickColor() was renamed to ImageInterface::colorAt() and signature has changed, argument $frame_key is know $frame
- ImageInterface::pickColors() was renamed to ImageInterface::colorsAt()
- CollectionInterface::empty() was renamed to CollectionInterface::clear()
- FontFactory::valign() was replaced with FontInterface::align()
- FontInterface::valignment() was renamed to FontInterface::verticalAlignment()
- FontInterface::setValignment() was renamed to FontInterface::setVerticalAlignment()
- FontInterface::alignment() was renamed to FontInterface::horizontalAlignment()
- FontInterface::setAlignment() was renamed to FontInterface::setHorizontalAlignment()
- GreyscaleModifier::class was renamed to GrayscaleModifier::class
- ImageInterface::greyscale() was renamed to ImageInterface::grayscale()
- ColorInterface::isGreyscale() was renamed to ColorInterface::isGrayscale()
- Alpha channel values in __construct() or create() methods of colors are now defined as float values (0-1)
- ImageInterface::place() was renamed to ImageInterface::insert, signature changed from `offset_x` to `x` and `offset_y` to `y` and updated argument order
- DrawableFactoryInterface::__invoke() was removed, use DrawableFactoryInterface::build or DrawableFactoryInterface::drawable()
- FontFactory::__invoke() was removed, use FontFactory::build or FontFactory::font()
- AnimationFactory::__invoke() was removed, use AnimationFactory::build or AnimationFactory::animation()
- Signatures of ImageInterface::drawRectangle(), ImageInterface::drawLine(), ImageInterface::drawEllipse(), ImageInterface::drawCircle() ImageInterface::drawPolygon() and ImageInterface::drawBezier() have changed
- FrameInterface::dispose() was rename to FrameInterface::disposalMethod()
- FrameInterface::setDispose() was renamed to FrameInterface::setDisposalMethod()
- InputHandler::withDecoders() was renamed to InputHandler::usingDecoders()
- ImageInterface::drawCircle() has a different signature: coordinate arguments removed
- ImageInterface::drawEllipse() has a different signature: coordinate arguments removed
- ImageInterface::drawRectangle() has a different signature: coordinate arguments removed
- Added ColorInterface::withTransparency()

### Exceptions

```
ImageException [1]
├── LogicException [2]
│   ├── ArgumentException
│   │   └── InvalidArgumentException
│   ├── NotSupportedException
│   └── StateException
│
└── RuntimeException [3]
    ├── MissingDependencyException
    ├── FilesystemException
    │   ├── DirectoryNotFoundException
    │   ├── FilePointerException
    │   ├── FileNotFoundException
    │   ├── FileNotReadableException
    │   └── FileNotWritableException
    └── DriverException
        ├── AnalyzerException
        ├── ModifierException
        ├── DecoderException
        │   ├── ImageDecoderException
        │   └── ColorDecoderException
        └── EncoderException

```

[1] Library container exception 
[2] LogicException: API violation, end-user misuse aka end user's CODE IS INCORRECT and can be
corrected, the code is wrong, Deterministic, Fully checkable, No external
dependencies, Violates documented input format
[3] RuntimeException: operational failure, can happen even if end-user's: CODE
IS CORRENT, the operation failed, even though the code was correct,
Data-dependent, External / environment-dependent, Cannot be guaranteed by
caller, Operation fails after valid input

## Removed

- Removed topLeftPoint() and bottomRightPoint() from Rectangle::class
- Removed ColorChannelInterface::__construct() from interface
- Removed ColorInterface::toArray() use ColorInterface::channels() and map to desired format
- Removed ColorInterface::normalize() use ColorInterface::channels() and map to desired format
- Method DriverInterface::createAnimation() was removed
