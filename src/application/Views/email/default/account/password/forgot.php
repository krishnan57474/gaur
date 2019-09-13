    <?= view('email/default/common/head') ?>

    <tr>
        <td style="background-color:#f6f6f7;padding:20px 20px 10px">
            <p style="color:#465059;font-size:24px;font-weight:bold;text-align:center;margin:0">
                Password Reset
            </p>
        </td>
    </tr>

    <tr>
        <td style="background-color:#f6f6f7;padding:0 20px 20px">
            <p style="font-size:14px;color:#a1a2a5;text-align:center;margin:0">
                <?= xhentities($subject) ?>
            </p>
        </td>
    </tr>

    <tr>
        <td style="background-color:#fff;padding:20px">
            <p style="font-size:14px;color:#555;line-height: 22px;margin:0">
                Hi <?= xhentities($username) ?>,<br />
                <?= config('Config\App')->siteName ?> recently received a request for a password reset.
                To change your account password, you'll need to reset your password.
            </p>
        </td>
    </tr>

    <tr>
        <td style="background-color:#fff;padding:0 20px 20px">
            <table cellspacing="0" cellpadding="0" style="margin:0 auto;border:0;table-layout:fixed">
                <tr>
                    <td style="background-color:#7fbe56;width:250px;text-align:center;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px">
                        <a href="<?= config('Config\App')->baseURL ?>account/password/reset/<?= $token ?>" style="color:#fff;text-decoration:none;font-weight:bold;font-size:18px;padding:12px 15px;display:block">
                            Reset password
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td style="background-color:#fff;padding:0 20px">
            <p style="font-size:14px;color:#555;line-height: 22px;margin:0">
                If you did not request this change, you do not need to do anything. This link will expire in one hour.
            </p>
        </td>
    </tr>

    <?= view('email/default/common/foot') ?>
