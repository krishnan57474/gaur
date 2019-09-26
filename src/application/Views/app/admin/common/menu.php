    <header class="container border-bottom mb-3 mt-sm-2 pb-sm-2">
        <div class="row align-items-center">
            <div class="col-sm-3 d-none d-sm-block">
                <div class="text-center">
                    <a href="" class="h1 mb-0">
                        <?= config('Config\App')->siteName ?>
                    </a>
                </div>
            </div>

            <nav class="col-sm-9 g-menu">
                <input class="g-menu-state" id="menu-state" type="checkbox">

                <div class="g-menu-toggle">
                    <a href="" class="h1 mb-0">
                        <?= config('Config\App')->siteName ?>
                    </a>

                    <label class="g-menu-icon" for="menu-state"></label>
                </div>

                <ul class="g-main-menu">
                    <li>
                        <a href="admin/users">
                            <span class="oi oi-people"></span>
                            Users
                        </a>
                    </li>
                    <li>
                        <a href="account/logout">
                            <span class="oi oi-account-logout"></span>
                            Logout
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>