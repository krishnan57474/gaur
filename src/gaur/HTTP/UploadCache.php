<?php

declare(strict_types=1);

namespace Gaur\HTTP;

class UploadCache
{
    /**
     * Page name
     *
     * @var string
     */
    protected $name;

    /**
     * Load initial values
     *
     * @param string $name page name
     *
     * @return void
     */
    public function __construct(string $name)
    {
        $this->name = 'upload-' . $name;
    }

    /**
     * Add upload cache
     *
     * @param int    $index    cache index
     * @param string $filename filename
     *
     * @return void
     */
    public function add(int $index, string $filename): void
    {
        if (!isset($_SESSION[$this->name])) {
            $_SESSION[$this->name] = [];
        }

        $_SESSION[$this->name][$index] = $filename;
    }

    /**
     * Create upload cache
     *
     * @return void
     */
    public function create(): void
    {
        $_SESSION[$this->name] = [];
    }

    /**
     * Get upload cache
     *
     * @return array
     */
    public function get(): array
    {
        return $_SESSION[$this->name] ?? [];
    }

    /**
     * Remove upload cache
     *
     * @return void
     */
    public function remove(): void
    {
        unset($_SESSION[$this->name]);
    }
}
