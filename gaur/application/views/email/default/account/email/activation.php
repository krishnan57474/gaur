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
<td style="background-color:#f6f6f7;padding:20px">
<p style="color:#465059;font-size:24px;font-weight:bold;text-align:center;margin:0">
Welcome to <?php echo config_item('site_name'); ?>
</p>
</td>
</tr>
<tr>
<td style="background-color:#fff;padding:20px">
<p style="font-size:14px;color:#555;text-align:center;line-height: 22px;margin:0">
Hi <?php echo xhentities($username); ?>,<br />
You are ready to setup your new account.<br />
To setup your account, you'll need to activate your account.
</p>
</td>
</tr>
<tr>
<td style="background-color:#fff;padding:0 20px">
<table cellspacing="0" cellpadding="0" style="margin:0 auto;border:0">
<tr>
<td style="background-color:#7fbe56;width:250px;text-align:center;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px">
<a href="<?php echo config_item('base_url'); ?>account/email/activation/<?php echo $token; ?>" style="color:#fff;text-decoration:none;font-weight:bold;font-size:18px;padding:12px 15px;display:block">Activate Account</a>
</td>
</tr>
</table>
</td>
</tr>
<?php $this->load->view('email/default/common/foot'); ?>