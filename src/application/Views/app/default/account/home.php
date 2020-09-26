    <?= view('app/default/common/head_top') ?>

    <meta http-equiv="Content-Security-Policy" content="<?= getCsp('Config', true) ?>">

    <title>Account - <?= config('Config\App')->siteName ?></title>

    <!-- meta for search engines -->
    <meta name="robots" content="noindex">

    <?= view('app/default/common/css') ?>
    <?= view('app/default/common/head_bottom') ?>
    <?= view('app/default/common/menu') ?>

    <main class="container mb-3">
        <div class="row">
            <div class="col-md-3">
                <?= view('app/default/common/account_menu') ?>
            </div>
            <div class="col-md-9">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="">Home</a>
                    </li>
                    <li class="breadcrumb-item active">
                        Account
                    </li>
                </ol>

                <div class="card">
                    <div class="card-header">Account details</div>
                    <div class="card-body pb-0">
                        <div class="row mb-3">
                            <div class="col-5">Username</div>
                            <div class="col-7"><?= hentities($user['username']) ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5">Email address</div>
                            <div class="col-7"><?= hentities($user['email']) ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5">Member since</div>
                            <div class="col-7"><?= date('F, Y', strtotime($user['date_added'])) ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?= view('app/default/common/foot_top') ?>
    <?= view('app/default/common/js') ?>
    <?= view('app/default/common/foot_bottom') ?>
