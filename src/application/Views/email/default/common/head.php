<?php require __DIR__ . '/colors.php'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>
        <?= xhentities($subject) ?>
    </title>
</head>
<body style="background-color:#ebebeb;padding:0;margin:0;width:100%">
    <table cellspacing="0" cellpadding="0" style="background-color:#ebebeb;font-family:sans-serif;width:100%;border:0;table-layout:fixed">
        <tr>
            <td style="padding:30px 10px">
                <table cellspacing="0" cellpadding="0" style="margin:0 auto;width:560px;max-width:100%;border:0;table-layout:fixed">
                    <tr>
                        <td style="background-color:#<?= $colors[mt_rand(0, count($colors) - 1)] ?>;text-align:center;padding:20px;-webkit-border-radius:8px 8px 0 0;-moz-border-radius:8px 8px 0 0;border-radius:8px 8px 0 0">
                            <a href="<?= config('Config\App')->baseURL ?>" style="color:#fff;text-decoration:none;font-size:26px;font-weight:bold">
                                <?= config('Config\App')->siteName ?>
                            </a>
                        </td>
                    </tr>
