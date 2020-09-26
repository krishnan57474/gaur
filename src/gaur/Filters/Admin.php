<?php

declare(strict_types=1);

namespace Gaur\Filters;

use Gaur\Filters\Config;
use Gaur\HTTP\Input;

class Admin
{
    /**
     * Page name
     *
     * @var string
     */
    protected string $name;

    /**
     * Get fields
     *
     * @param string[]              $keys    keys list
     * @param string[]              $vals    values list
     * @param array<string, string> $afields allowed fields
     *
     * @return array<string, string[]>|null
     */
    protected function getFields(
        array $keys,
        array $vals,
        array $afields
    ): ?array
    {
        $data = [
            'by'  => [],
            'val' => []
        ];

        foreach ($keys as $k => $v) {
            if (key_exists($v, $afields)) {
                $data['by'][$k]  = $v;
                $data['val'][$k] = $vals[$k] ?? '';
            }
        }

        if (!$data['by']) {
            $data = null;
        }

        return $data;
    }

    /**
     * Load initial values
     *
     * @param string $name page name
     *
     * @return void
     */
    public function __construct(string $name)
    {
        $this->name = 'admin-filter-' . $name;
    }

    /**
     * Filter user inputs
     *
     * @param Config $config filter configuration
     *
     * @return mixed[]
     */
    public function filter(Config $config): array
    {
        $filter      = null;
        $search      = null;
        $currentPage = 1;
        $listCount   = 5;
        $offset      = 0;
        $order       = null;

        $filter = $this->getFields(
            Input::urlArray('filterby'),
            Input::urlArray('filterval'),
            $config->filterFields
        );

        $search = $this->getFields(
            Input::urlArray('searchby'),
            Input::urlArray('searchval'),
            $config->searchFields
        );

        if (key_exists(Input::url('orderby'), $config->orderFields)) {
            $order = [
                'order' => Input::url('orderby'),
                'sort'  => (Input::url('sortby') ? 'DESC' : 'ASC')
            ];
        }

        if (ctype_digit(Input::url('page'))) {
            $currentPage = (int)Input::url('page');
        }

        if (ctype_digit(Input::url('count'))) {
            $listCount = (int)Input::url('count');
        }

        if ($currentPage < 1) {
            $currentPage = 1;
        }

        if ($listCount < 5) {
            $listCount = 5;
        }

        if ($listCount > 20) {
            $listCount = 20;
        }

        if ($currentPage > 1) {
            $offset = ($currentPage - 1) * $listCount;
        }

        $_SESSION[$this->name] = [
            'count'  => $listCount,
            'filter' => $filter,
            'offset' => $offset,
            'order'  => $order,
            'search' => $search
        ];

        return $_SESSION[$this->name];
    }

    /**
     * Get filtered inputs
     *
     * @return mixed[]
     */
    public function get(): array
    {
        if (!isset($_SESSION[$this->name])) {
            $_SESSION[$this->name] = [
                'count'  => 5,
                'filter' => null,
                'offset' => 0,
                'order'  => null,
                'search' => null
            ];
        }

        return $_SESSION[$this->name];
    }
}
