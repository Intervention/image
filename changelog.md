## New Features

- DriverInterface::version()
- ColorInterface::create() now accepts functional string color formats as well as single channel values
- ImageManagerInterface::decodeFrom()
- Alignment::class
- DriverInterface::handleImageInput()
- DriverInterface::handleColorInput()
- DataUri::class
- Default resolution is now 72 ppi for new images
- Origin::format()
- Support for ICO-Format
- Improved structure and hiarchy for exceptions

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
- ImageInterface::toJpeg() and ImageInterface::toJpg() are replaced by ImageInterface::encode()
- ImageInterface::toPng() is replaced by ImageInterface::encode()
- ImageInterface::toGif() is replaced by ImageInterface::encode()
- ImageInterface::toJp2() and ImageInterface::toJpeg2000() are replaced by ImageInterface::encode()
- ImageInterface::toWebp() is replaced by ImageInterface::encode()
- ImageInterface::toBitmap() and ImageInterface::toBmp() are replaced by ImageInterface::encode()
- ImageInterface::toAvif() is replaced by ImageInterface::encode()
- ImageInterface::toHeic() is replaced by ImageInterface::encode()
- ImageInterface::toTiff() and ImageInterface::toTif() are replaced by ImageInterface::encode()
- DriverInterface::handleColorInput() has null as default
- Method ImageManagerInterface::read() is now handled by ImageManagerInterface::decode()

## Removed

- Removed topLeftPoint() and bottomRightPoint() from Rectangle::class
