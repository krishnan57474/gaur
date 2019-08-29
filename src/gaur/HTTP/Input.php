<?php

declare(strict_types=1);

namespace Gaur\HTTP;

class Input
{
    /**
     * Get array value from given array
     *
     * @param array  $data array to use
     * @param string $key  field name
     *
     * @return array
     */
    protected function filterArray(array $data, string $key): array
    {
        $values  = is_array($data[$key] ?? null) ? $data[$key] : [];
        $fvalues = [];

        foreach ($values as $v) {
            if (is_string($v)) {
                $fvalues[] = trim($v);
            }
        }

        return $fvalues;
    }

    /**
     * Get string value from given array
     *
     * @param array  $data array to use
     * @param string $key  field name
     *
     * @return string
     */
    protected function filterString(array $data, string $key): string
    {
        return is_string($data[$key] ?? null) ? trim($data[$key]) : '';
    }

    /**
     * Get values from $_FILES array
     *
     * @param string $key field name
     *
     * @return array
     */
    public function files(string $key): array
    {
        $files = [];

        foreach ($_FILES[$key] ?? [] as $k => $finfo) {
            foreach (is_array($finfo) ? $finfo : [$finfo] as $i => $v) {
                if (!is_int($i) || is_array($v)) {
                    continue;
                }

                if (!key_exists($i, $files)) {
                    $files[$i] = [];
                }

                $files[$i][$k] = $v;
            }
        }

        return $files;
    }

    /**
     * Get string value from $_GET array
     *
     * @param string $key field name
     *
     * @return string
     */
    public function get(string $key): string
    {
        return $this->filterString($_GET, $key);
    }

    /**
     * Get array value from $_GET array
     *
     * @param string $key field name
     *
     * @return array
     */
    public function getArray(string $key): array
    {
        return $this->filterArray($_GET, $key);
    }

    /**
     * Get string value from $_POST array
     *
     * @param string $key field name
     *
     * @return string
     */
    public function post(string $key): string
    {
        return $this->filterString($_POST, $key);
    }

    /**
     * Get array value from $_POST array
     *
     * @param string $key field name
     *
     * @return array
     */
    public function postArray(string $key): array
    {
        return $this->filterArray($_POST, $key);
    }
}
