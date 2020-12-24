<?php

declare(strict_types=1);

namespace Gaur\HTTP\FileUpload;

class Errors
{
    public const FILE_COUNT_EXCEED = 'Maximum number of files exceeded.';
    public const FILE_SIZE_EXCEED  = 'The uploaded file exceeds the maximum upload file size limit.';
    public const INVALID_FILE_TYPE = 'The file type you are attempting to upload is not allowed.';
    public const MOVE_FAILED       = 'Please contact the system administrator of this site.';
}
