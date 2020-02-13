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
                            <span class="fas fa-home"></span>
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="contact">
                            <span class="fas fa-envelope"></span>
                            Contact
                        </a>
                    </li>
                    <li>
                        <a href="enquiry">
                            <span class="fas fa-envelope"></span>
                            Enquiry
                        </a>
                    </li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <li>
                        <a href="account">
                            <span class="fas fa-user"></span>
                            Account
                        </a>
                    </li>
                    <li>
                        <a href="account/logout">
                            <span class="fas fa-sign-out-alt"></span>
                            Logout
                        </a>
                    </li>
                    <?php else: ?>
                    <li>
                        <a href="account/login">
                            <span class="fas fa-sign-in-alt"></span>
                            Login
                        </a>
                    </li>
                    <li>
                        <a href="account/register">
                            <span class="fas fa-user"></span>
                            Register
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
