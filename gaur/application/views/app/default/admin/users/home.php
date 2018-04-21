<?php
/**
 * Gaur
 *
 * An open source web application
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2017 - 2018, Krishnan
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
 * @copyright  Copyright (c) 2017 - 2018, Krishnan
 * @license    https://opensource.org/licenses/MIT   MIT License
 * @link       https://github.com/krishnan57474
 * @since      Version 1.0.0
 */

defined('BASEPATH') OR exit;

$this->load->view('app/default/common/head_top');

?>
    <title>Users - <?php echo config_item('site_name'); ?></title>

    <!-- meta for search engines -->
    <meta name="robots" content="noindex">

    <?php $this->load->view('app/default/common/css'); ?>

    <style>
        .link {
            cursor: pointer;
        }

        .c-pt-5 {
            padding-top: 5px;
        }

        .thead {
            background: #f5f5f5;
        }

        .sblock {
            width: 400px;
            max-width: 100%;
        }

        .c-mr-1 {
            margin-right: 10px;
        }

        .c-ma {
            margin: 0 auto;
        }
    </style>

    <?php
        $this->load->view('app/default/common/head_bottom');
        $this->load->view('app/default/admin/common/menu');
    ?>

    <main class="container">
        <div class="row">
            <div id="j-ar" class="col-md-12">
                <div class="clearfix">
                    <ol class="breadcrumb pull-left">
                        <li>You are here: </li>
                        <li><a href="">Home</a></li>
                        <li><a href="admin">Admin</a></li>
                        <li class="active">Users</li>
                    </ol>
                    <div class="form-group pull-right">
                        <a href="admin/users/add" class="btn btn-success">
                            <span class="glyphicon glyphicon-pencil"></span>
                            Add
                        </a>
                        <span data-jitem="filter" class="btn btn-primary">
                            <span class="glyphicon glyphicon-search"></span>
                            Filter
                        </span>
                    </div>
                </div>

                <div data-jitem="ufilters" class="form-group form-inline hide">
                    <div class="form-group c-mr-1">
                        <select class="form-control" data-jitem="filterby">
                            <option value="">Filter by</option>
                            <?php foreach ($filter_fields as $k => $v): ?>
                            <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group c-mr-1">
                        <select class="form-control" data-jitem="filterval">
                            <option value="">Choose</option>
                            <?php foreach ($filter_values as $fk => $fv): ?>
                            <?php foreach ($fv as $k => $v): ?>
                            <option class="hide" data-item="<?php echo $fk; ?>" value="<?php echo $k; ?>"><?php echo $v; ?></option>
                            <?php endforeach; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group c-mr-1">
                        <select class="form-control" data-jitem="searchby">
                            <option value="">Search by</option>
                            <?php foreach ($search_fields as $k => $v): ?>
                            <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group c-mr-1">
                        <input class="form-control" data-jitem="searchval" type="text" placeholder="keyword">
                    </div>
                    <div class="form-group c-mr-1">
                        <select class="form-control" data-jitem="orderby">
                            <option value="">Order by</option>
                            <?php foreach ($order_fields as $k => $v): ?>
                            <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group c-mr-1">
                        <select class="form-control" data-jitem="sortby">
                            <option value="">Sort by</option>
                            <option value="0">ASC</option>
                            <option value="1">DESC</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <span class="btn btn-block btn-success">
                            <span class="glyphicon glyphicon-search"></span>
                            Search
                        </span>
                    </div>
                    <div class="form-group">
                        <span class="btn btn-block btn-danger">
                            <span class="glyphicon glyphicon-remove"></span>
                            Clear
                        </span>
                    </div>
                </div>

                <div data-jitem="confirm" class="alert alert-warning clearfix hide">
                    <div class="pull-left c-pt-5">Confirm change status</div>
                    <div class="pull-right">
                        <span class="btn btn-success">Confirm</span>
                        <span class="btn btn-default">Cancel</span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <colgroup>
                            <col style="width: 70px">
                            <col>
                            <col>
                            <col style="width: 150px">
                            <col style="width: 100px">
                            <col style="width: 100px">
                            <col style="width: 50px">
                            <col style="width: 50px">
                        </colgroup>
                        <thead class="thead">
                            <tr data-jitem="order">
                                <th data-id="id" class="link text-center">
                                    <span class="small glyphicon glyphicon-sort"></span>
                                    ID
                                </th>
                                <th data-id="username" class="link">
                                    <span class="small glyphicon glyphicon-sort"></span>
                                    Username
                                </th>
                                <th data-id="email" class="link">
                                    <span class="small glyphicon glyphicon-sort"></span>
                                    Email
                                </th>
                                <th data-id="last_visited" class="link text-center">
                                    <span class="small glyphicon glyphicon-sort"></span>
                                    Last Visited
                                </th>
                                <th data-id="status" class="link text-center">
                                    <span class="small glyphicon glyphicon-sort"></span>
                                    Status
                                </th>
                                <th data-id="activation" class="link text-center">
                                    <span class="small glyphicon glyphicon-sort"></span>
                                    Verified
                                </th>
                                <th class="text-center">Edit</th>
                                <th class="text-center">View</th>
                            </tr>
                        </thead>
                        <tbody data-jitem="items"></tbody>
                    </table>

                    <ul class="list-unstyled j-error hide"></ul>

                    <div data-jitem="loading" class="text-center">
                        <p><img src="images/loader.gif" alt="loading"></p>
                        <p>Loading</p>
                    </div>

                    <div data-jitem="noitems" class="text-center hide">No result found</div>
                </div>

                <div data-jitem="footer" class="clearfix hide">
                    <div class="pull-left form-group">
                        <ul data-jitem="pagination" class="pagination c-ma"></ul>
                    </div>

                    <div class="pull-right form-inline sblock text-right">
                        <div data-jitem="total" class="form-group">
                            <b>Total</b>: <span></span> &nbsp; / &nbsp;
                        </div>
                        <div class="form-group">
                          <label>List count</label>
                          <select data-jitem="listcount" class="form-control">
                              <option value="5">5</option>
                              <option value="10">10</option>
                              <option value="15">15</option>
                              <option value="20">20</option>
                          </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php $this->load->view('app/default/admin/common/foot_top'); ?>

    <script>
    (function () {
        "use strict";

        function init() {
            var configs = {
                filterBy:    "<?php echo $filter['filter'] ? $filter['filter']['by'] : ''; ?>",
                filterVal:   "<?php echo $filter['filter'] ? hentities($filter['filter']['val']) : ''; ?>",
                searchBy:    "<?php echo $filter['search'] ? $filter['search']['by'] : ''; ?>",
                searchVal:   "<?php echo $filter['search'] ? hentities($filter['search']['val']) : ''; ?>",
                currentPage: <?php echo $filter['current_page']; ?>,
                listCount:   <?php echo $filter['list_count']; ?>,
                orderBy:     "<?php echo $filter['order'] ? $filter['order']['order'] : ''; ?>",
                sortBy:      <?php echo ($filter['order'] && $filter['order']['sort'] === 'DESC') ? 1 : 0; ?>,

                handlers:    {
                    search: true,
                    status: true,
                    order:  true
                }
            };

            (new App()).init(configs);
        }

        (window._jq = window._jq || []).push(init);
    }());
    </script>

    <script async type="text/x-js" src="js/form.js" class="j-ljs"></script>
    <script async type="text/x-js" src="js/app.js" class="j-ljs"></script>

    <?php
        $this->load->view('app/default/common/js');
        $this->load->view('app/default/common/foot_bottom');
    ?>