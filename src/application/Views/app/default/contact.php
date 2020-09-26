    <?= view('app/default/common/head_top') ?>

    <meta http-equiv="Content-Security-Policy" content="<?= getCsp('Config', true) ?>">

    <title>Contact - <?= config('Config\App')->siteName ?></title>

    <!-- meta for search engines -->
    <link rel="canonical" href="<?= config('Config\App')->baseURL ?>contact">
    <meta name="robots" content="follow, index">

    <?= view('app/default/common/css') ?>
    <?= view('app/default/common/head_bottom') ?>
    <?= view('app/default/common/menu') ?>

    <main class="container" id="j-ar">
        <div class="row justify-content-center">
            <div class="col-sm-9 col-md-7 col-lg-5">
                <h1 class="text-center">Contact</h1>

                <ul class="list-unstyled j-error d-none"></ul>
                <p class="alert alert-success j-success d-none"></p>

                <form method="post" data-url="contact">
                    <div class="form-group">
                        <label>Name <span class="text-danger">*</span></label>
                        <input class="form-control" name="name" type="text" required>
                    </div>

                    <div class="form-group">
                        <label>Email address <span class="text-danger">*</span></label>
                        <input class="form-control" name="email" type="email" required>
                    </div>

                    <div class="form-group">
                        <label>Phone number <span class="text-danger">*</span></label>
                        <input class="form-control" name="phone" type="text" required>
                    </div>

                    <div class="form-group">
                        <label>Message <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="message" required></textarea>
                    </div>

                    <div class="form-group">
                        <input type="hidden" name="<?= $csrf['name'] ?>" value="<?= $csrf['hash'] ?>">
                        <input class="btn btn-primary" type="submit" value="Submit">
                    </div>
                </form>
            </div>
        </div>
    </main>

    <?= view('app/default/common/foot_top') ?>
    <?= view('app/default/common/js/form') ?>
    <?= view('app/default/common/js') ?>
    <?= view('app/default/common/foot_bottom') ?>
