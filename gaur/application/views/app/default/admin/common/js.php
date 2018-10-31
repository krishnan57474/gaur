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
 * @since      Version 3.0.0
 */

defined('BASEPATH') OR exit;

?>
    <script>
    (function ($) {
        "use strict";

        function segment(url) {
            return url.replace(/\/$/, "").split("/");
        }

        function compareSegment(base, current) {
            var i = -1,
            l = current.length,
            match = false;

            while (++i < l) {
                if (base[i] === current[i] && (i + 1) >= l) {
                    match = true;
                    break;
                }
            }

            return match;
        }

        function setActiveMenu() {
            var base = segment(location.href.replace(document.baseURI, "")),
            current;

            $(".sm > li > a").each(function (k, elm) {
                current = $(elm).attr("href");

                if (current === undefined) {
                    return;
                }

                if (compareSegment(base, segment(current))) {
                    $(elm).addClass("current");
                    return false;
                }
            });
        }

        function initMenu() {
            $(".sm").smartmenus();
        }

        function init() {
            $ = jQuery;
            setActiveMenu();
            initMenu();
        }

        (window._jq = window._jq || []).push(init);
    }());
    </script>

    <script type="text/x-async-css" data-src="https://cdn.jsdelivr.net/gh/iconic/open-iconic@1.1.1/font/css/open-iconic-bootstrap.min.css" data-integrity="sha384-wWci3BOzr88l+HNsAtr3+e5bk9qh5KfjU6gl/rbzfTYdsAVHBEbxB33veLYmFg/a" class="j-acss"></script>

    <script type="text/x-async-js" data-src="https://cdn.jsdelivr.net/gh/vadikom/smartmenus@1.1.0/dist/jquery.smartmenus.min.js" data-integrity="sha384-kiPl6IJ5XxKLYw0Q2pnwgDh1cclkWG/FxMm94STsu7hO1zRtLFh0nVBnxh5FJTaA" class="j-ajs"></script>

    <script async src="js/script.js"></script>