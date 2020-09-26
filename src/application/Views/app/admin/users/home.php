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

        <?= view('app/admin/common/app/filter') ?>
        <?= view('app/admin/common/app/content_top') ?>

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
                            Last visited
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

        <?= view('app/admin/common/app/content_bottom') ?>
        <?= view('app/admin/common/app/footer') ?>
    </main>

    <?= view('app/admin/common/foot_top') ?>
    <?= view('app/admin/common/js/app', [ 'pageUrl' => 'admin/users' ]) ?>
    <?= view('app/admin/common/js') ?>
    <?= view('app/admin/common/foot_bottom') ?>
