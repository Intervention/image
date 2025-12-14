<?php

declare(strict_types=1);

namespace Intervention\Image\Exceptions;

use Intervention\Image\Interfaces\ExceptionInterface;
use Exception;

abstract class ImageException extends Exception implements ExceptionInterface
{
    // LogicException
    // ========================================================================
    // - the code is wrong
    // - Deterministic
    // - Fully checkable
    // - No external dependencies
    // - Violates documented input format
    //
    // RuntimeException
    // ========================================================================
    // - the operation failed, even though the code was correct
    // - Data-dependent
    // - External / environment-dependent
    // - Cannot be guaranteed by caller
    // - Operation fails after valid input



    // ImageException
    // └─── LogicException (end-user misuse aka end user's CODE IS INCORRECT and can be corrected)
    //     ├── ArgumentException
    //     │   ├── MissingArgumentException
    //     │   └── InvalidArgumentException
    //     └── NotSupportedException
    //         └── NotSupportedDriverFeatureException
    //
    // ├── RuntimeException (operational failure, can happen even if end-user's: CODE IS CORRENT)
    //     ├── FilesystemException
    //     │   ├── DirectoryNotFoundException
    //     │   ├── FilePointerException
    //     │   ├── FileNotFoundException
    //     │   ├── FileNotReadableException
    //     │   └── FileNotWritableException
    //     ├── DecoderException
    //     │   ├── ImageDecoderException [MAYBE]
    //     │   └── ColorDecoderException [MAYBE]
    //     ├── EncoderException
    //     │   ├── ImageEncoderException [MAYBE]
    //     │   └── ColorEncoderDecoderException [MAYBE]
    //     ├── DriverException
    //     │   └── DriverMissingDependencyException
    //     ├── AnalyzerException
    //     ├── ModifierException
    //     └── FontException
    //
}
