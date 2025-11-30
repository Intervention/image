## New Features

- ImageInterface::version()
- ColorInterface::create() now accepts functional string color formats as well as single channel values
- ImageManagerInterface::createFrom()
- ImageManagerInterface::createFromPath()
- ImageManagerInterface::createFromBinary()
- ImageManagerInterface::createFromBase64()
- ImageManagerInterface::createFromSplFileInfo()
- ImageManagerInterface::createFromDataUri()
- Alignment::class
- DriverInterface::handleImageInput()
- DriverInterface::handleColorInput()
- DataUri::class
- Default resolution is now 72 ppi for new images
- Origin::format()

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
- Removed topLeftPoint() and bottomRightPoint() from Rectangle::class
- Attribute `$per_unit` has change to `$unit` with different signature in `Resolution::class`
- ImageInterface::toJpeg() and ImageInterface::toJpg() are replaced by ImageInterface::encodeByFormat()
- ImageInterface::toPng() is replaced by ImageInterface::encodeByFormat()
- ImageInterface::toGif() is replaced by ImageInterface::encodeByFormat()
- ImageInterface::toJp2() and ImageInterface::toJpeg2000() are replaced by ImageInterface::encodeByFormat()
- ImageInterface::toWebp() is replaced by ImageInterface::encodeByFormat()
- ImageInterface::toBitmap() and ImageInterface::toBmp() are replaced by ImageInterface::encodeByFormat()
- ImageInterface::toAvif() is replaced by ImageInterface::encodeByFormat()
- ImageInterface::toHeic() is replaced by ImageInterface::encodeByFormat()
- ImageInterface::toTiff() and ImageInterface::toTif() are replaced by ImageInterface::encodeByFormat()
