    <header class="container border-bottom mb-3 mt-md-2 pb-md-2">
        <div class="row align-items-center">
            <div class="col-md-3 d-none d-md-block">
                <div class="text-center">
                    <a href="" class="h1 mb-0">
                        <?= config('Config\App')->siteName ?>
                    </a>
                </div>
            </div>

            <nav class="col-md-9 g-menu">
                <input class="g-menu-state" id="menu-state" type="checkbox">

                <div class="g-menu-toggle">
                    <a href="" class="h1 mb-0">
                        <?= config('Config\App')->siteName ?>
                    </a>

                    <label class="g-menu-icon" for="menu-state"></label>
                </div>

                <ul class="g-main-menu">
                    <li>
                        <a href="">
                            <span class="oi oi-home"></span>
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="contact">
                            <span class="oi oi-envelope-closed"></span>
                            Contact
                        </a>
                    </li>
                    <li>
                        <a href="enquiry">
                            <span class="oi oi-envelope-closed"></span>
                            Enquiry
                        </a>
                    </li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <li>
                        <a href="account">
                            <span class="oi oi-person"></span>
                            Account
                        </a>
                    </li>
                    <li>
                        <a href="account/logout">
                            <span class="oi oi-account-logout"></span>
                            Logout
                        </a>
                    </li>
                    <?php else: ?>
                    <li>
                        <a href="account/login">
                            <span class="oi oi-account-login"></span>
                            Login
                        </a>
                    </li>
                    <li>
                        <a href="account/register">
                            <span class="oi oi-person"></span>
                            Register
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
