## New Features

- DriverInterface::version()
- ColorInterface::create() now accepts functional string color formats as well as single channel values
- ImageManagerInterface::decodeFrom()
- Alignment::class
- DataUri::class
- Default resolution is now 72 ppi for new images
- Origin::format()
- Support for ICO-Format
- Improved structure and hiarchy for exceptions
- Improved error and exception messages

## API Changes

- ImageInterface::blendTransparency() was renamed to ImageInterface::background() - Signature changed
- ImageInterface::setBlendingColor() was renamed to ImageInterface::setBackgroundColor()
- ImageInterface::blendingColor() was renamed to ImageInterface::backgroundColor()
- Changed default value for `background` to `null` in ImageInterface::rotate()
- Changed default value for `background` to `null` in ImageInterface::resizeCanvas()
- Changed default value for `background` to `null` in ImageInterface::resizeCanvasRelative()
- Changed default value for `background` to `null` in ImageInterface::contain()
- Changed default value for `background` to `null` in ImageInterface::pad()
- Changed default value for `background` to `null` in ImageInterface::crop()
- Signature of ImageInterface::crop() changed from `offset_x` to `x` and `offset_y` to `y`
- Signature of ImageInterface::place() changed from `offset_x` to `x` and `offset_y` to `y`
- EncodedImageInterface::toDataUri() now returns `DataUriInterface` instead of `string´
- ProfileInterface requires implementation of `::fromPath()`
- DriverInterface requires implementation of `__construct()`
- Replace DriverInterface::specialize() with DriverInterface::specializeModifier(), DriverInterface::specializeAnalyzer(), DriverInterface::specializeDecoder() and DriverInterface::specializeEncoder()
- Attribute `$per_unit` has change to `$unit` with different signature in `Resolution::class`
- ImageInterface::toJpeg() and ImageInterface::toJpg() are replaced by ImageInterface::encodeUsing()
- ImageInterface::toPng() is replaced by ImageInterface::encodeUsing()
- ImageInterface::toGif() is replaced by ImageInterface::encodeUsing()
- ImageInterface::toJp2() and ImageInterface::toJpeg2000() are replaced by ImageInterface::encodeUsing()
- ImageInterface::toWebp() is replaced by ImageInterface::encodeUsing()
- ImageInterface::toBitmap() and ImageInterface::toBmp() are replaced by ImageInterface::encodeUsing()
- ImageInterface::toAvif() is replaced by ImageInterface::encodeUsing()
- ImageInterface::toHeic() is replaced by ImageInterface::encodeUsing()
- ImageInterface::toTiff() and ImageInterface::toTif() are replaced by ImageInterface::encodeUsing()
- DriverInterface::handleColorInput() has null as default
- Method ImageManagerInterface::read() is now handled by ImageManagerInterface::decode() and ImageManagerInterface::decodeFrom()
- Method ImageInterface::save() only processes known image file extensions
- Method FontInterface::filename() is replaced by FontInterface::filepath()
- Method FontInterface::hasFilename() is replaced by FontInterface::hasFile()
- Method FontInterface::setFilename() is replaced by FontInterface::setFilepath()
- Usage of internal font's of GD library is determined by font size and no font file instead of font file
- Method DrawableFactoryInterface::init() is replaced by DrawableFactoryInterface::create()
- Method DrawableFactoryInterface::create() is replaced by DrawableFactoryInterface::drawable()
- Signature of ImageInterface::pickColor() has changed, argument $frame_key is know $frame
- Signature of Frame::__construct() has changed, argument $offset_left is know $offsetLeft and $offset_top is now $offsetTop
- Signature of PixelColorAnalyzer::__construct() has changed, argument $frame_key is know $frame
- DriverInterface::handleInput() is replaced by DriverInterface::handleImageInput(), DriverInterface::handleColorInput()

### Exceptions

```
ImageException [1]
├── LogicException [2]
│   ├── ArgumentException
│   │   ├── MissingArgumentException
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
