    <?= view('app/default/common/head_top') ?>

    <meta http-equiv="Content-Security-Policy" content="<?= getCsp('Config', true) ?>">

    <title>Email address - <?= config('Config\App')->siteName ?></title>

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
                    <li class="breadcrumb-item">
                        <a href="account">Account</a>
                    </li>
                    <li class="breadcrumb-item active">
                        Email address
                    </li>
                </ol>

                <div class="card">
                    <div class="card-header">Change email address</div>
                    <div class="card-body pb-0" id="j-ar">
                        <ul class="list-unstyled j-error d-none"></ul>
                        <p class="alert alert-success j-success d-none"></p>

                        <form method="post" data-url="account/email" data-timeout="3000">
                            <div class="form-group">
                                <label>New email address <span class="text-danger">*</span></label>
                                <input class="form-control" name="email" type="email" required>
                            </div>
                            <div class="form-group">
                                <input name="<?= $csrf['name'] ?>" type="hidden" value="<?= $csrf['hash'] ?>">
                                <input class="btn btn-primary" type="submit" value="Update">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?= view('app/default/common/foot_top') ?>
    <?= view('app/default/common/js/form') ?>
    <?= view('app/default/common/js') ?>
    <?= view('app/default/common/foot_bottom') ?>
