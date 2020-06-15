    <?= view('app/admin/common/head_top') ?>

    <meta http-equiv="Content-Security-Policy" content="<?= getCsp('Config', true) ?>">

    <title>Users - <?= config('Config\App')->siteName ?></title>

    <!-- meta for search engines -->
    <meta name="robots" content="noindex">

    <?= view('app/admin/common/css') ?>
    <?= view('app/admin/common/head_bottom') ?>
    <?= view('app/admin/common/menu') ?>

    <main id="j-ar" class="container mb-3">
        <div class="row align-items-center">
            <div class="col-md-5">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="admin">Admin</a>
                    </li>
                    <li class="breadcrumb-item active">
                        Users
                    </li>
                </ol>
            </div>
            <div class="col-md-7 mb-2">
                <div class="form-row row-cols-2 row-cols-sm-3 row-cols-lg-4 justify-content-sm-end">
                    <div class="col mb-2">
                        <a href="admin/users/add" class="btn btn-block btn-primary">
                            <span class="fas fa-plus"></span>
                            Add
                        </a>
                    </div>
                    <div class="col mb-2">
                        <button type="button" data-jitem="filter" class="btn btn-block btn-secondary">
                            <span class="fas fa-filter"></span>
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
                        <?php foreach ($filterConfig->filterFields as $k => $v): ?>
                        <option value="<?= $k ?>"><?= $v ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-1">
                    <select class="form-control" data-jitem="filterval">
                        <option value="">Choose</option>
                        <?php foreach ($filterConfig->filterValues as $fk => $fv): ?>
                        <?php foreach ($fv as $k => $v): ?>
                        <option class="d-none" data-item="<?= $fk ?>" value="<?= $k ?>"><?= $v ?></option>
                        <?php endforeach; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-sm-6 col-md-3 mb-2">
                <div class="mb-1">
                    <select class="form-control" data-jitem="searchby">
                        <option value="">Search by</option>
                        <?php foreach ($filterConfig->searchFields as $k => $v): ?>
                        <option value="<?= $k ?>"><?= $v ?></option>
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
                        <?php foreach ($filterConfig->orderFields as $k => $v): ?>
                        <option value="<?= $k ?>"><?= $v ?></option>
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
                            <span class="fas fa-search"></span>
                            Search
                        </button>
                    </div>
                    <div class="col col-sm-12 mb-1">
                        <button data-action="reset" type="button" class="btn btn-block btn-danger">
                            <span class="fas fa-times"></span>
                            Clear
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div data-jitem="confirm" class="alert alert-warning text-center text-sm-left d-none">
            <div class="row align-items-center">
                <div class="col-sm-6 mb-2 mb-sm-0" data-jitem="confirm-msg">Confirm change status</div>
                <div class="col-sm-6 text-sm-right">
                    <button data-action="confirm" type="button" class="btn btn-success">Confirm</button>
                    <button data-action="cancel" type="button" class="btn btn-secondary">Cancel</button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <ul class="list-unstyled j-error d-none" data-show-errors></ul>

                <div class="table-responsive">
                    <div class="g-table table table-bordered text-center">
                        <div class="g-colgroup">
                            <div class="g-col g-w-70"></div>
                            <div class="g-col g-w-200"></div>
                            <div class="g-col g-w-200"></div>
                            <div class="g-col g-w-200"></div>
                            <div class="g-col g-w-100"></div>
                            <div class="g-col g-w-70"></div>
                            <div class="g-col g-w-70"></div>
                        </div>
                        <div class="g-thead bg-light">
                            <div class="g-tr" data-jitem="order">
                                <div class="g-th cursor-pointer" data-id="id">
                                    <span class="fas fa-sort"></span>
                                    ID
                                </div>
                                <div class="g-th text-left">Username</div>
                                <div class="g-th text-left">Email</div>
                                <div class="g-th cursor-pointer" data-id="last_visited">
                                    <span class="fas fa-sort"></span>
                                    Last Visited
                                </div>
                                <div class="g-th cursor-pointer" data-id="status">
                                    <span class="fas fa-sort"></span>
                                    Status
                                </div>
                                <div class="g-th">Edit</div>
                                <div class="g-th">View</div>
                            </div>
                        </div>
                        <div class="g-tbody" data-jitem="items"></div>
                    </div>
                </div>

                <div data-jitem="loading" class="text-center">
                    <div class="spinner-border text-secondary mb-3"></div>
                    <p>Loading</p>
                </div>

                <div data-jitem="noitems" class="text-center d-none">No result found</div>
            </div>
        </div>

        <div data-jitem="footer" class="row d-none">
            <div class="col-md-6">
                <ul data-jitem="pagination" class="pagination flex-wrap flex-md-nowrap"></ul>
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

    <?= view('app/admin/common/foot_top') ?>

    <script nonce="<?= getCspNonce() ?>">
    (() => {
        "use strict";

        function init() {
            const configs = {
                context:     document.querySelector("#j-ar"),
                filterBy:    <?= json_encode($filter['filter']['by'] ?? []) ?>,
                filterVal:   <?= json_encode($filter['filter']['val'] ?? []) ?>,
                searchBy:    <?= json_encode($filter['search']['by'] ?? []) ?>,
                searchVal:   <?= json_encode($filter['search']['val'] ?? []) ?>,
                currentPage: <?= $filter['current_page'] ?>,
                listCount:   <?= $filter['count'] ?>,
                orderBy:     "<?= $filter['order']['order'] ?? '' ?>",
                sortBy:      <?= (int)(($filter['order']['sort'] ?? '') === 'DESC') ?>,
                url:         "admin/users"
            };

            (new GApp()).init(configs);
        }

        (window._jq = window._jq || []).push(init);
    })();
    </script>

    <script type="text/x-async-js" data-src="js/form.js" data-type="module" class="j-ajs"></script>
    <script type="text/x-async-js" data-src="js/app.js" data-type="module" class="j-ajs"></script>

    <?= view('app/admin/common/js') ?>
    <?= view('app/admin/common/foot_bottom') ?>
