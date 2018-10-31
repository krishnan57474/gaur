<?php
/**
 * Gaur
 *
 * An open source web application
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2017 - 2018, Krishnan
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package    Gaur
 * @author     Krishnan <krishnan57474@gmail.com>
 * @copyright  Copyright (c) 2017 - 2018, Krishnan
 * @license    https://opensource.org/licenses/MIT   MIT License
 * @link       https://github.com/krishnan57474
 * @since      Version 1.0.0
 */

defined('BASEPATH') OR exit;

$this->load->view('email/default/common/head');

?>
<tr>
<td style="background-color:#f6f6f7;padding:20px 20px 10px">
<p style="color:#465059;font-size:24px;font-weight:bold;text-align:center;margin:0">
Enquiry email
</p>
</td>
</tr>
<tr>
<td style="background-color:#f6f6f7;padding:0 20px 20px">
<p style="font-size:14px;color:#A1A2A5;text-align:center;margin:0">
<?php echo $subject; ?>
</p>
</td>
</tr>
<tr>
<td style="background-color:#fff;padding:20px 20px 0">
<table cellspacing="0" cellpadding="0" style="margin:0 auto;border:0;width:100%">
<?php foreach ($inputs as $k => $v): ?>
<tr>
<td style="font-size:14px;color:#A1A2A5;padding:5px 10px"><?php echo $k; ?></td>
<td style="font-size:14px;color:#555;padding:5px 10px"><?php echo xhentities($v); ?></td>
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
<table cellspacing="0" cellpadding="0" style="margin:0 auto;border:0;width:100%">
<?php foreach ($attachments as $k => $v): ?>
<tr>
<td style="font-size:14px;color:#A1A2A5;padding:5px 10px"><?php echo $k; ?></td>
<td style="padding:5px 10px">
<a href="<?php echo config_item('base_url'); ?><?php echo $v; ?>" style="font-size:14px;color:#666">Download</a>
</td>
</tr>
<?php endforeach; ?>
</table>
</td>
</tr>
<?php endif; ?>
<?php $this->load->view('email/default/common/foot'); ?>