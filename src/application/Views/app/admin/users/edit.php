    <?= view('app/admin/common/head_top') ?>

    <meta http-equiv="Content-Security-Policy" content="<?= getCsp('Config', true) ?>">

    <title>Edit a user - <?= config('Config\App')->siteName ?></title>

    <!-- meta for search engines -->
    <meta name="robots" content="noindex">

    <?= view('app/admin/common/css') ?>
    <?= view('app/admin/common/head_bottom') ?>
    <?= view('app/admin/common/menu') ?>

    <main class="container" id="j-ar">
        <div class="row">
            <div class="col-md-5">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="admin">Admin</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="admin/users">Users</a>
                    </li>
                    <li class="breadcrumb-item active">
                        Edit
                    </li>
                </ol>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-sm-9 col-md-7 col-lg-5">
                <ul class="list-unstyled j-error d-none"></ul>
                <p class="alert alert-success j-success d-none"></p>

                <form method="post" data-method="put" data-url="admin/users/<?= $user['id'] ?>" data-timeout="3000">
                    <div class="form-group">
                        <label>Username <span class="text-danger">*</span></label>
                        <input class="form-control" name="username" type="text" required value="<?= hentities($user['username']) ?>">
                        <small class="form-text text-muted">Username can contain letters (a-z) and numbers (0-9).</small>
                    </div>

                    <div class="form-group">
                        <label>Email address <span class="text-danger">*</span></label>
                        <input class="form-control" name="email" type="email" required value="<?= hentities($user['email']) ?>">
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input class="form-control" name="password" type="password">
                    </div>

                    <div class="form-group">
                        <label>Password confirmation</label>
                        <input class="form-control" name="password-confirm" type="password">
                    </div>

                    <div class="form-group">
                        <label>Admin <span class="text-danger">*</span></label>
                        <select class="form-control" name="admin" required>
                            <option value="">Choose</option>
                            <option value="0"<?php if (!$user['admin']): ?> selected<?php endif; ?>>Disable</option>
                            <option value="1"<?php if ($user['admin']): ?> selected<?php endif; ?>>Enable</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Status <span class="text-danger">*</span></label>
                        <select class="form-control" name="status" required>
                            <option value="">Choose</option>
                            <option value="0"<?php if (!$user['status']): ?> selected<?php endif; ?>>Disable</option>
                            <option value="1"<?php if ($user['status']): ?> selected<?php endif; ?>>Enable</option>
                        </select>
                    </div>

                    <div class="form-row form-group">
                        <div class="col">
                            <input name="<?= $csrf['name'] ?>" type="hidden" value="<?= $csrf['hash'] ?>">
                            <input class="btn btn-block btn-primary" type="submit" value="Update">
                        </div>
                        <div class="col">
                            <a class="btn btn-block btn-secondary" href="admin/users">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <?= view('app/admin/common/foot_top') ?>
    <?= view('app/admin/common/js/form') ?>
    <?= view('app/admin/common/js') ?>
    <?= view('app/admin/common/foot_bottom') ?>
