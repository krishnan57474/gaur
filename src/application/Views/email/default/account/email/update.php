    <?= view('email/default/common/head') ?>

    <tr>
        <td style="background-color:#f6f6f7;padding:20px 20px 10px">
            <p style="color:#465059;font-size:24px;font-weight:bold;text-align:center;margin:0">
                Email Update
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
                Hi,<br />
                <?= config('Config\App')->siteName ?> recently received a request for an email update.
                To change your account email, please use code below.
            </p>
        </td>
    </tr>

    <tr>
        <td style="background-color:#fff;padding:0 20px 20px">
            <table cellspacing="0" cellpadding="0" style="margin:0 auto;border:0;table-layout:fixed">
                <tr>
                    <td style="width:250px;text-align:center;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px;font-weight:bold;font-size:26px">
                        <?= $token ?>
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
