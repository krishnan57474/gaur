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
 * @since      Version 1.0.0
 */

defined('BASEPATH') OR exit;

/**
 * Manage users
 *
 * @package     Gaur
 * @subpackage  Controller
 * @author      Krishnan <krishnan57474@gmail.com>
 */
class Home extends CI_Controller
{
    /**
     * Default page for this controller
     *
     * @return  void
     */
    public function _remap()
    {
        // prevent non logged users
        if (!isset($_SESSION['user_id']))
        {
            // login redirect
            $_SESSION['redirect'] = 'admin/users';
            $this->session->mark_as_flash('redirect');
            session_write_close();

            $this->load->helper('url');
            redirect('account/login', 'location');
        }

        if (!isset($_SESSION['is_admin']))
        {
            $this->load->model('admin/users/user', NULL, TRUE);
            $_SESSION['is_admin'] = $this->user->is_admin($_SESSION['user_id']);
        }

        // prevent non admin users
        if (!$_SESSION['is_admin'])
        {
            show_404(NULL, FALSE);
        }

        $this->load->helper('form_input');

        if ($this->input->method() === 'post'
            && $this->input->is_ajax_request()
            && form_input('j-af') === 'r')
        {
            return $this->_aaction();
        }

        $this->load->helper('html');

        $filter = $this->_filter();
        $data = array();
        $data['filter'] = $filter;
        $data['filter']['current_page'] = 1;

        if ($filter['offset'])
        {
            $data['filter']['current_page'] = ($filter['offset'] / $filter['list_count']) + 1;
        }

        $filter_fields = array(
            'id'       => 'ID',
            'username' => 'Username',
            'email'    => 'Email'
        );

        $order_fields = array(
            'id'           => 'ID',
            'username'     => 'Username',
            'email'        => 'Email',
            'last_visited' => 'Last Visited',
            'status'       => 'Status',
            'activation'   => 'Verified'
        );

        $data['filter_fields'] = $filter_fields;
        $data['order_fields'] = $order_fields;

        $this->load->view('app/default/admin/users/home', $data);
    }

    /**
     * Filter user inputs
     *
     * @return  array
     */
    private function _filter()
    {
        if (!isset($_SESSION['admin']['filter']))
        {
            $_SESSION['admin'] = array(
                'filter' => array()
            );
        }

        if (!isset($_SESSION['admin']['filter']['user']))
        {
            $_SESSION['admin']['filter']['user'] = array(
                'filter'        => NULL,
                'list_count'    => 5,
                'offset'        => 0,
                'order'         => NULL
            );
        }

        if (!form_input('filter'))
        {
            session_write_close();
            return $_SESSION['admin']['filter']['user'];
        }

        $filter       = NULL;
        $current_page = (int)form_input('page');
        $list_count   = (int)form_input('count');
        $order        = NULL;

        $filter_fields = array(
            'id',
            'username',
            'email'
        );

        $order_fields = array(
            'id',
            'username',
            'email',
            'last_visited',
            'status',
            'activation'
        );

        if (form_input('keyword')
            && in_array(form_input('filterby'), $filter_fields))
        {
            $filter = array(
                'by'      => form_input('filterby'),
                'keyword' => form_input('keyword')
            );
        }

        if (in_array(form_input('orderby'), $order_fields))
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

        $_SESSION['admin']['filter']['user'] = array(
            'filter'        => $filter,
            'list_count'    => $list_count,
            'offset'        => $offset,
            'order'         => $order
        );

        session_write_close();
        return $_SESSION['admin']['filter']['user'];
    }

    /**
     * Get users list
     *
     * @param   array   form data
     *
     * @return  void
     */
    private function _action_getitems(&$fdata)
    {
        $this->load->model('admin/users/users', NULL, TRUE);

        $filter = $this->_filter();

        $items = $this->users->get(
            $filter['filter'],
            $filter['list_count'],
            $filter['offset'],
            $filter['order']
        );

        if (!$items)
        {
            $fdata['data'] = '';
            return;
        }

        $this->load->helper(array('html', 'date'));
        $now = date('Y-m-d H:i:s');

        foreach ($items as $k => $v)
        {
            if (preg_match('#[1-9]#', $v['last_visited']))
            {
                $items[$k]['last_visited'] = datetime_diff($v['last_visited'], $now);
            }
            else
            {
                $items[$k]['last_visited'] = '';
            }
        }

        $fdata['data'] = $this->load->view(
            'app/default/admin/users/users_content',
            array('items' => $items),
            TRUE
        );
    }

    /**
     * Get users count
     *
     * @param   array   form data
     *
     * @return  void
     */
    private function _action_gettotal(&$fdata)
    {
        $this->load->model('admin/users/users', NULL, TRUE);

        $filter = $this->_filter();

        $fdata['data'] = (int)$this->users->total($filter['filter']);
    }

    /**
     * Toggle user status
     *
     * @param   array   form data
     *
     * @return  void
     */
    private function _action_changestatus(&$fdata)
    {
        if ($_SESSION['user_id'] === form_input('id'))
        {
            return;
        }

        $this->load->model('admin/users/user', NULL, TRUE);

        $this->user->change_status((int)form_input('id'));

        $fdata['data'] = 1;
    }

    /**
     * Ajax form action
     *
     * @return  void
     */
    private function _aaction()
    {
        $fdata = array();
        $fdata['status'] = 0;

        if (!preg_match('#[^a-z]#', form_input('action'))
            && method_exists($this, '_action_' . form_input('action')))
        {
            $fdata['status'] = 1;
            $this->{'_action_' . form_input('action')}($fdata);
        }

        $this->output->set_content_type('json')->set_output(json_encode($fdata));
    }
}