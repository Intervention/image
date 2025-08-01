## New Features

- ImageInterface::version()
- ColorInterface::create() now accepts functional string color formats as well as single channel values
- ImageManagerInterface::readPath()
- ImageManagerInterface::readBinary()
- ImageManagerInterface::readBase64()
- ImageManagerInterface::readSplFileInfo()
- ImageManagerInterface::readDataUri()
- Alignment::class
- DriverInterface::handleImageInput()
- DriverInterface::handleColorInput()

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
