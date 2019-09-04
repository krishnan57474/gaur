<?php

declare(strict_types=1);

namespace Gaur\Filters;

use Gaur\HTTP\Input;

class Admin
{
    /**
     * Filter user inputs
     *
     * $config fields
     *
     * array  filter_fields allowed filter fields
     * array  order_fields  allowed order fields
     * array  search_fields allowed search fields
     *
     * @param string $name   page name
     * @param array  $config filter configuration
     *
     * @return array
     */
    public function filter(string $name, array $config): array
    {
        $input = new Input();
        $name  = 'admin-filter-' . $name;

        $filter      = null;
        $search      = null;
        $currentPage = 1;
        $listCount   = 5;
        $offset      = 0;
        $order       = null;

        $filter = $this->getFields(
            $input->postArray('filterby'),
            $input->postArray('filterval'),
            $config['filter_fields']
        );

        $search = $this->getFields(
            $input->postArray('searchby'),
            $input->postArray('searchval'),
            $config['search_fields']
        );

        if (in_array($input->post('orderby'), $config['order_fields'], true)) {
            $order = [
                'order' => $input->post('orderby'),
                'sort'  => ($input->post('sortby') ? 'DESC' : 'ASC')
            ];
        }

        if (ctype_digit($input->post('page'))) {
            $currentPage = (int)$input->post('page');
        }

        if (ctype_digit($input->post('count'))) {
            $listCount = (int)$input->post('count');
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

        $_SESSION[$name] = [
            'count'  => $listCount,
            'filter' => $filter,
            'offset' => $offset,
            'order'  => $order,
            'search' => $search
        ];

        return $_SESSION[$name];
    }

    /**
     * Get filtered inputs
     *
     * @param string $name page name
     *
     * @return array
     */
    public function get(string $name): array
    {
        $name = 'admin-filter-' . $name;

        if (!isset($_SESSION[$name])) {
            $_SESSION[$name] = [
                'count'  => 5,
                'filter' => null,
                'offset' => 0,
                'order'  => null,
                'search' => null
            ];
        }

        return $_SESSION[$name];
    }

    /**
     * Get fields
     *
     * @param array $keys    keys list
     * @param array $vals    values list
     * @param array $afields allowed fields
     *
     * @return array|null
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
            if (in_array($v, $afields, true)) {
                $data['by'][$k]  = $v;
                $data['val'][$k] = $vals[$k] ?? '';
            }
        }

        if (!$data['by']) {
            $data = null;
        }

        return $data;
    }
}
