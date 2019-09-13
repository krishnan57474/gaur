    <?= view('app/admin/common/head_top') ?>

    <title>Admin - <?= config('Config\App')->siteName ?></title>

    <!-- meta for search engines -->
    <meta name="robots" content="noindex">

    <?= view('app/admin/common/css') ?>
    <?= view('app/admin/common/head_bottom') ?>
    <?= view('app/admin/common/menu') ?>

    <main class="container">
        <div class="row">
            <div class="col-sm-12">
                <p>Welcome to admin</p>
            </div>
        </div>
    </main>

    <?= view('app/admin/common/foot_top') ?>
    <?= view('app/admin/common/js') ?>
    <?= view('app/admin/common/foot_bottom') ?>
