    <?= view('email/default/common/head') ?>

    <tr>
        <td style="background-color:#f6f6f7;padding:20px 20px 10px">
            <p style="color:#465059;font-size:24px;font-weight:bold;text-align:center;margin:0">
                Enquiry email
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
        <td style="background-color:#fff;padding:20px 20px 0">
            <table cellspacing="0" cellpadding="0" style="margin:0 auto;border:0;width:100%;table-layout:fixed">
                <colgroup>
                    <col style="width:120px" />
                    <col />
                </colgroup>
                <?php foreach ($inputs as $k => $v): ?>
                <tr>
                    <td style="font-size:14px;color:#a1a2a5;padding:5px 10px;vertical-align:top">
                        <?= $k ?>
                    </td>
                    <td style="font-size:14px;color:#555;padding:5px 10px">
                        <?= xhentities($v) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </td>
    </tr>

    <?php if (isset($attachments)): ?>
    <tr>
        <td style="background-color:#fff;padding:10px 20px 0">
            <p style="font-size:14px;color:#555;line-height: 22px;margin:0;font-weight:bold">
                Attachments:
            </p>
        </td>
    </tr>

    <tr>
        <td style="background-color:#fff;padding:0 20px">
            <table cellspacing="0" cellpadding="0" style="margin:0 auto;border:0;width:100%;table-layout:fixed">
                <colgroup>
                    <col />
                    <col style="width:120px" />
                </colgroup>
                <?php foreach ($attachments as $k => $v): ?>
                <tr>
                    <td style="font-size:14px;color:#a1a2a5;padding:5px 10px">
                        <?= $k ?>
                    </td>
                    <td style="padding:5px 10px">
                        <a href="<?= config('Config\App')->baseURL . $v ?>" style="font-size:14px;color:#666">
                            Download
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </td>
    </tr>
    <?php endif; ?>

    <?= view('email/default/common/foot') ?>
