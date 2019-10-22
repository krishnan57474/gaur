    <?= view('app/default/common/head_top') ?>

    <title><?= config('Config\App')->siteName ?></title>

    <!-- meta for search engines -->
    <link rel="canonical" href="<?= config('Config\App')->baseURL ?>">
    <meta name="robots" content="follow, index">

    <?= view('app/default/common/css') ?>
    <?= view('app/default/common/head_bottom') ?>
    <?= view('app/default/common/menu') ?>

    <main class="container">
        <div class="row">
            <div class="col-md-12">
                <p>Welcome to <?= config('Config\App')->siteName ?></p>
            </div>
        </div>
    </main>

    <?= view('app/default/common/foot_top') ?>
    <?= view('app/default/common/js') ?>
    <?= view('app/default/common/foot_bottom') ?>
