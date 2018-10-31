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

    <?php $this->load->view('app/default/admin/common/css'); ?>

    <style>
        .link {
            cursor: pointer;
        }
    </style>

    <?php
        $this->load->view('app/default/common/head_bottom');
        $this->load->view('app/default/admin/common/menu');
    ?>

    <main id="j-ar" class="container">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item"><a href="admin">Admin</a></li>
                    <li class="breadcrumb-item active">Users</li>
                </ol>
            </div>
            <div class="col-sm-6 mb-2">
                <div class="row no-gutters">
                    <div class="col offset-lg-6 mb-2 mr-1">
                        <a href="admin/users/add" class="btn btn-block btn-success">
                            <span class="oi oi-pencil"></span>
                            Add
                        </a>
                    </div>
                    <div class="col mb-2">
                        <button type="button" data-jitem="filter" class="btn btn-block btn-primary">
                            <span class="oi oi-magnifying-glass"></span>
                            Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div data-jitem="ufilters" class="form-row d-none">
            <div class="col-sm-6 col-md-3 mb-2">
                <div class="mb-1">
                    <select class="form-control" data-jitem="filterby">
                        <option value="">Filter by</option>
                        <?php foreach ($filter_fields as $k => $v): ?>
                        <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-1">
                    <select class="form-control" data-jitem="filterval">
                        <option value="">Choose</option>
                        <?php foreach ($filter_values as $fk => $fv): ?>
                        <?php foreach ($fv as $k => $v): ?>
                        <option class="d-none" data-item="<?php echo $fk; ?>" value="<?php echo $k; ?>"><?php echo $v; ?></option>
                        <?php endforeach; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-sm-6 col-md-3 mb-2">
                <div class="mb-1">
                    <select class="form-control" data-jitem="searchby">
                        <option value="">Search by</option>
                        <?php foreach ($search_fields as $k => $v): ?>
                        <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-1">
                    <input class="form-control" data-jitem="searchval" type="text" placeholder="keyword">
                </div>
            </div>
            <div class="col-sm-6 col-md-3 mb-2">
                <div class="mb-1">
                    <select class="form-control" data-jitem="orderby">
                        <option value="">Order by</option>
                        <?php foreach ($order_fields as $k => $v): ?>
                        <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-1">
                    <select class="form-control" data-jitem="sortby">
                        <option value="">Sort by</option>
                        <option value="0">ASC</option>
                        <option value="1">DESC</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-6 col-md-3 mb-2">
                <div class="row no-gutters">
                    <div class="col col-sm-12 mb-1 mr-1">
                        <button data-action="search" type="button" class="btn btn-block btn-success">
                            <span class="oi oi-magnifying-glass"></span>
                            Search
                        </button>
                    </div>
                    <div class="col col-sm-12 mb-1">
                        <button data-action="reset" type="button" class="btn btn-block btn-danger">
                            <span class="oi oi-x"></span>
                            Clear
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div data-jitem="confirm" class="alert alert-warning text-center text-sm-left d-none">
            <div class="row align-items-center">
                <div class="col-sm-6 mb-2 mb-sm-0">Confirm change status</div>
                <div class="col-sm-6 text-sm-right">
                    <button data-action="confirm" type="button" class="btn btn-success">Confirm</button>
                    <button data-action="cancel" type="button" class="btn btn-secondary">Cancel</button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center">
                        <colgroup>
                            <col style="width: 70px">
                            <col>
                            <col>
                            <col style="width: 150px">
                            <col style="width: 100px">
                            <col style="width: 70px">
                            <col style="width: 70px">
                        </colgroup>
                        <thead class="thead-light">
                            <tr data-jitem="order">
                                <th data-id="id" class="link">
                                    <span class="small oi oi-elevator"></span>
                                    ID
                                </th>
                                <th data-id="username" class="link text-left">
                                    <span class="small oi oi-elevator"></span>
                                    Username
                                </th>
                                <th data-id="email" class="link text-left">
                                    <span class="small oi oi-elevator"></span>
                                    Email
                                </th>
                                <th data-id="last_visited" class="link">
                                    <span class="small oi oi-elevator"></span>
                                    Last Visited
                                </th>
                                <th data-id="status" class="link">
                                    <span class="small oi oi-elevator"></span>
                                    Status
                                </th>
                                <th>Edit</th>
                                <th>View</th>
                            </tr>
                        </thead>
                        <tbody data-jitem="items"></tbody>
                    </table>
                </div>

                <ul class="list-unstyled j-error d-none" data-show-errors></ul>

                <div data-jitem="loading" class="text-center">
                    <p><img src="images/loader.gif" alt="loading"></p>
                    <p>Loading</p>
                </div>

                <div data-jitem="noitems" class="text-center d-none">No result found</div>
            </div>
        </div>

        <div data-jitem="footer" class="row d-none">
            <div class="col-md-6">
                <ul data-jitem="pagination" class="pagination flex-wrap flex-sm-nowrap"></ul>
            </div>

            <div class="col-md-6 text-right">
                <b>Total</b>:
                <span data-jitem="total"></span> &nbsp; / &nbsp;
                <b>List count</b>
                <div class="d-inline-block">
                    <select data-jitem="listcount" class="form-control">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                    </select>
                </div>
            </div>
        </div>
    </main>

    <?php $this->load->view('app/default/admin/common/foot_top'); ?>

    <script>
    (function () {
        "use strict";

        function toArray(obj) {
            var aobj = [];

            Object.keys(obj).forEach(function (k) {
                aobj[k] = obj[k];
            });

            return aobj;
        }

        function init() {
            var configs = {
                filterBy:    <?php echo $filter['filter'] ? json_encode($filter['filter']['by']) : '[]'; ?>,
                filterVal:   <?php echo $filter['filter'] ? json_encode($filter['filter']['val']) : '[]'; ?>,
                searchBy:    <?php echo $filter['search'] ? json_encode($filter['search']['by']) : '[]'; ?>,
                searchVal:   <?php echo $filter['search'] ? json_encode($filter['search']['val']) : '[]'; ?>,
                currentPage: <?php echo $filter['current_page']; ?>,
                listCount:   <?php echo $filter['list_count']; ?>,
                orderBy:     "<?php echo $filter['order'] ? $filter['order']['order'] : ''; ?>",
                sortBy:      <?php echo ($filter['order'] && $filter['order']['sort'] === 'DESC') ? 1 : 0; ?>,

                handlers:    {
                    search: true,
                    order:  true,
                    status: true
                }
            };

            ["filterBy", "filterVal", "searchBy", "searchVal"].forEach(function (k) {
                if (!Array.isArray(configs[k])) {
                    configs[k] = toArray(configs[k]);
                }
            });

            (new GApp()).init(configs);
        }

        (window._jq = window._jq || []).push(init);
    }());
    </script>

    <script type="text/x-async-js" data-src="js/form.js" class="j-ajs"></script>
    <script type="text/x-async-js" data-src="js/app.js" class="j-ajs"></script>

    <?php
        $this->load->view('app/default/admin/common/js');
        $this->load->view('app/default/common/foot_bottom');
    ?>