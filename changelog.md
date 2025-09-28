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
- Signature of ImageInterface::crop() changed `offset_x` is no `x` and `offset_y` is now `y`
- Signature of ImageInterface::place() changed `offset_x` is no `x` and `offset_y` is now `y`
- EncodedImageInterface::toDataUri() now returns `DataUriInterface` instead of `string´
- ProfileInterface requires implementation of `::fromPath()`
- DriverInterface requires implementation of `__construct()`
- Replace DriverInterface::specialize() with DriverInterface::specializeModifier(), DriverInterface::specializeAnalyzer(), DriverInterface::specializeDecoder() and DriverInterface::specializeEncoder()
