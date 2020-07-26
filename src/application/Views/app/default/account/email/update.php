    <?= view('app/default/common/head_top') ?>

    <meta http-equiv="Content-Security-Policy" content="<?= getCsp('Config', true) ?>">

    <title>Update Email - <?= config('Config\App')->siteName ?></title>

    <!-- meta for search engines -->
    <meta name="robots" content="noindex">

    <?= view('app/default/common/css') ?>
    <?= view('app/default/common/head_bottom') ?>
    <?= view('app/default/common/menu') ?>

    <main class="container" id="j-ar">
        <div class="row justify-content-center">
            <div class="col-sm-9 col-md-7 col-lg-5">
                <h1 class="text-center">Update Email</h1>

                <ul class="list-unstyled j-error d-none"></ul>
                <p class="alert alert-success j-success d-none"></p>

                <form method="post" data-url="account/email/update" data-timeout="5000">
                    <div class="form-group">
                        <label>Code <span class="text-danger">*</span></label>
                        <input class="form-control" name="code" type="text" required>
                    </div>

                    <div class="form-group">
                        <input name="<?= $csrf['name'] ?>" type="hidden" value="<?= $csrf['hash'] ?>">
                        <input class="btn btn-primary" type="submit" value="Update">
                    </div>
                </form>
            </div>
        </div>
    </main>

    <?= view('app/default/common/foot_top') ?>
    <?= view('app/default/common/js/form') ?>
    <?= view('app/default/common/js') ?>
    <?= view('app/default/common/foot_bottom') ?>
