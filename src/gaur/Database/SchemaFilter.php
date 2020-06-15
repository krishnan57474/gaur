<?php

declare(strict_types=1);

namespace Gaur\Database;

use Exception;
use Gaur\Database\SchemaTypeGroup;

class SchemaFilter
{
    /**
     * Filter schema
     *
     * @param array<string, mixed> $rdata data list
     *
     * @return array<string, mixed>
     */
    public function filter(array $rdata): array
    {
        $data = [];

        foreach ($rdata as $k => $v) {
            if (!property_exists($this, $k)) {
                continue;
            }

            $rule = $this->$k;

            if ($rule['null']
                && ($v === '' || is_null($v))
            ) {
                $data[$k] = null;
                continue;
            }

            switch ($rule['type']) {
                case SchemaTypeGroup::INTEGER: {
                    if (is_int($v)) {
                        $data[$k] = $v;
                    } else {
                        $data[$k] = (int)$v;
                    }

                    break;
                }

                case SchemaTypeGroup::FLOAT: {
                    if (is_float($v)) {
                        $data[$k] = $v;
                    } else {
                        $data[$k] = (float)$v;
                    }

                    break;
                }

                case SchemaTypeGroup::DATE: {
                    if (preg_match('/^\d{4}\-\d{2}\-\d{2}$/', $v)) {
                        $data[$k] = $v;
                    } else {
                        $data[$k] = '0000-00-00';
                    }

                    break;
                }

                case SchemaTypeGroup::DATETIME: {
                    if (preg_match('/^\d{4}\-\d{2}\-\d{2} \d{2}:\d{2}:\d{2}$/', $v)) {
                        $data[$k] = $v;
                    } else {
                        $data[$k] = '0000-00-00 00:00:00';
                    }

                    break;
                }

                case SchemaTypeGroup::TIME: {
                    if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $v)) {
                        $data[$k] = $v;
                    } else {
                        $data[$k] = '00:00:00';
                    }

                    break;
                }

                case SchemaTypeGroup::STRING: {
                    if (is_string($v)) {
                        $data[$k] = $v;
                    } else {
                        $data[$k] = (string)$v;
                    }

                    break;
                }

                case SchemaTypeGroup::JSON: {
                    if (is_array($v) || is_object($v)) {
                        $data[$k] = json_encode($v);
                    } elseif (is_string($v)) {
                        $data[$k] = json_decode($v, true);
                    }

                    break;
                }
            }
        }

        return $data;
    }

    /**
     * Filter schema batch
     *
     * @param array<array<string, mixed>> $rdata data list
     *
     * @return array<array<string, mixed>>
     */
    public function filterBatch(array $rdata): array
    {
        $data = [];

        foreach ($rdata as $v) {
            $data[] = $this->filter($v);
        }

        return $data;
    }
}
