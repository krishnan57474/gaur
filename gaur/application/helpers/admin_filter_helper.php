<?php
/**
 * Gaur
 *
 * An open source web application
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2017, Krishnan
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package    Gaur
 * @author     Krishnan <krishnan57474@gmail.com>
 * @copyright  Copyright (c) 2017, Krishnan
 * @license    http://opensource.org/licenses/MIT   MIT License
 * @link       https://github.com/krishnan57474
 * @since      Version 2.0.0
 */

defined('BASEPATH') OR exit;

/**
 * Admin filter
 *
 * @param   array  filter vars
 *
 * @return  array
 */
function admin_filter($configs)
{
    /*
        used vars

        page            - string
        filter          - bool
        filter_fields   - array
        search_fields   - array
        order_fields    - array
    */
    extract($configs);

    if (!isset($_SESSION['admin']['filter']))
    {
        $_SESSION['admin'] = array(
            'filter' => array()
        );
    }

    if (!isset($_SESSION['admin']['filter'][$page]))
    {
        $_SESSION['admin']['filter'][$page] = array(
            'filter'        => NULL,
            'search'        => NULL,
            'list_count'    => 5,
            'offset'        => 0,
            'order'         => NULL
        );
    }

    if (!$filter)
    {
        session_write_close();
        return $_SESSION['admin']['filter'][$page];
    }

    $filter       = NULL;
    $search       = NULL;
    $current_page = (int)form_input('page');
    $list_count   = (int)form_input('count');
    $order        = NULL;

    if (isset($filter_fields)
        && in_array(form_input('filterby'), $filter_fields))
    {
        $filter = array(
            'by'    => form_input('filterby'),
            'val'   => form_input('filterval')
        );
    }

    if (isset($search_fields)
        && form_input('searchval') !== ''
        && in_array(form_input('searchby'), $search_fields))
    {
        $search = array(
            'by'    => form_input('searchby'),
            'val'   => form_input('searchval')
        );
    }

    if (isset($order_fields)
        && in_array(form_input('orderby'), $order_fields))
    {
        $order = array(
            'order' => form_input('orderby'),
            'sort'  => (bool)form_input('sortby') ? 'DESC' : 'ASC'
        );
    }

    if ($current_page < 1)
    {
        $current_page = 1;
    }

    if ($list_count < 5 || $list_count > 20)
    {
        $list_count = ($list_count < 5) ? 5 : 20;
    }

    $offset = 0;

    if ($current_page > 1)
    {
        $offset = ($current_page - 1) * $list_count;
    }

    $_SESSION['admin']['filter'][$page] = array(
        'filter'        => $filter,
        'search'        => $search,
        'list_count'    => $list_count,
        'offset'        => $offset,
        'order'         => $order
    );

    session_write_close();
    return $_SESSION['admin']['filter'][$page];
}