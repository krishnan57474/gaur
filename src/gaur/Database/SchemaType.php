<?php

declare(strict_types=1);

namespace Gaur\Database;

use Gaur\Database\SchemaTypeGroup;

class SchemaType
{
    public const TINYINT    = SchemaTypeGroup::INTEGER;
    public const SMALLINT   = SchemaTypeGroup::INTEGER;
    public const MEDIUMINT  = SchemaTypeGroup::INTEGER;
    public const INT        = SchemaTypeGroup::INTEGER;
    public const BIGINT     = SchemaTypeGroup::INTEGER;
    public const DECIMAL    = SchemaTypeGroup::FLOAT;
    public const FLOAT      = SchemaTypeGroup::FLOAT;
    public const DOUBLE     = SchemaTypeGroup::FLOAT;
    public const REAL       = SchemaTypeGroup::FLOAT;
    public const DATE       = SchemaTypeGroup::DATE;
    public const DATETIME   = SchemaTypeGroup::DATETIME;
    public const TIMESTAMP  = SchemaTypeGroup::DATETIME;
    public const TIME       = SchemaTypeGroup::TIME;
    public const YEAR       = SchemaTypeGroup::INTEGER;
    public const CHAR       = SchemaTypeGroup::STRING;
    public const VARCHAR    = SchemaTypeGroup::STRING;
    public const TINYTEXT   = SchemaTypeGroup::STRING;
    public const TEXT       = SchemaTypeGroup::STRING;
    public const MEDIUMTEXT = SchemaTypeGroup::STRING;
    public const LONGTEXT   = SchemaTypeGroup::STRING;
    public const JSON       = SchemaTypeGroup::JSON;
}
