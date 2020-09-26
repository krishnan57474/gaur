    <?= view('app/default/common/head_top') ?>

    <meta http-equiv="Content-Security-Policy" content="<?= getCsp('Config', true) ?>">

    <title>Reset your password - <?= config('Config\App')->siteName ?></title>

    <!-- meta for search engines -->
    <meta name="robots" content="noindex">

    <?= view('app/default/common/css') ?>
    <?= view('app/default/common/head_bottom') ?>
    <?= view('app/default/common/menu') ?>

    <main class="container" id="j-ar" data-method="post" data-url="account/password/reset/<?= $token ?>">
        <div class="row justify-content-center">
            <div class="col-sm-9 col-md-7 col-lg-5">
                <h1 class="text-center">Reset password</h1>

                <ul class="list-unstyled j-error d-none" data-show-errors></ul>
                <p class="alert alert-success j-success d-none"></p>

                <div class="text-center j-loading mt-3">
                    <div class="spinner-border text-secondary mb-3"></div>
                    <p>Please wait while we verify your request.</p>
                </div>
            </div>
        </div>
    </main>

    <?= view('app/default/common/foot_top') ?>
    <?= view('app/default/common/js/form_token') ?>
    <?= view('app/default/common/js') ?>
    <?= view('app/default/common/foot_bottom') ?>
