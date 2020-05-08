    <?= view('email/default/common/head') ?>

    <tr>
        <td style="background-color:#f6f6f7;padding:20px">
            <p style="color:#465059;font-size:24px;font-weight:bold;text-align:center;margin:0">
                Welcome to <?= config('Config\App')->siteName ?>
            </p>
        </td>
    </tr>

    <tr>
        <td style="background-color:#fff;padding:20px">
            <p style="font-size:14px;color:#555;text-align:center;line-height: 22px;margin:0">
                Hi <b><?= xhentities($username) ?></b>,<br />
                You are ready to setup your new account.<br />
                To setup your account, you'll need to activate your account.
            </p>
        </td>
    </tr>

    <tr>
        <td style="background-color:#fff;padding:0 20px">
            <table cellspacing="0" cellpadding="0" style="margin:0 auto;border:0;table-layout:fixed">
                <tr>
                    <td style="background-color:#7fbe56;width:250px;text-align:center;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px">
                        <a href="<?= config('Config\App')->baseURL ?>account/email/activate/<?= $token ?>" style="color:#fff;text-decoration:none;font-weight:bold;font-size:18px;padding:12px 15px;display:block">
                            Activate Account
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <?= view('email/default/common/foot') ?>
