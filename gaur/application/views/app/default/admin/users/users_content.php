<?php
/**
 * Gaur
 *
 * An open source web application
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2017, Krishnan
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
 * @copyright  Copyright (c) 2017, Krishnan
 * @license    http://opensource.org/licenses/MIT   MIT License
 * @link       https://github.com/krishnan57474
 * @since      Version 1.0.0
 */

defined('BASEPATH') OR exit;

$status = array(
    'glyphicon glyphicon-remove text-danger',
    'glyphicon glyphicon-ok text-success'
);

foreach ($items as $item):
?>
<tr data-id="<?php echo $item['id']; ?>">
    <td class="text-center"><?php echo $item['id']; ?></td>
    <td><?php echo hentities($item['username']); ?></td>
    <td><?php echo hentities($item['email']); ?></td>
    <td class="text-center"><?php echo $item['last_visited'] ? ($item['last_visited'] . ' ago') : '-'; ?></td>
    <td class="text-center"><span data-item="status" class="link <?php echo $status[$item['status']]; ?>"></span></td>
    <td class="text-center"><span class="<?php echo $status[$item['activation']]; ?>"></span></td>
    <td class="text-center"><a href="admin/users/edit/<?php echo $item['id']; ?>"><span class="glyphicon glyphicon-pencil"></span></a></td>
    <td class="text-center"><a href="admin/users/view/<?php echo $item['id']; ?>"><span class="glyphicon glyphicon-link"></span></a></td>
</tr>
<?php endforeach; ?>