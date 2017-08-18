(function ($) {
    "use strict";

    var configs, jar, xlock, derror, progress;

    function forEach(obj, callback) {
        Object.keys(obj).forEach(function (k) {
            callback(obj[k], k, obj);
        });
    }

    progress = function () {
        $(document.body).append("<div id='prg'></div>");
        var prg = $("#prg");

        progress = function (val, display) {
            $(prg).css({
                width: val + "%",
                height: (display ? 3 : 0) + "px",
                borderWidth: (display ? 1 : 0) + "px"
            });

            if (!display) {
                setTimeout(function () {
                    $(prg).css({ width: 0 });
                }, 1000);
            }
        };

        progress.apply(undefined, arguments);
    };

    function getBCR(elm, key) {
        elm = elm.getBoundingClientRect();
        return key ? elm[key] : elm;
    }

    function scrollViewTo(elm) {
        scrollTo(0, getBCR(elm, "top") - getBCR(document.body, "top"));
    }

    function showErrors(errors) {
        var ifrg = "",
        elm = $(".j-error", jar),
        e = Number(elm.data("e")) || 0;
        xlock = false;

        // error counter
        elm.data("e", ++e);

        if (configs.hideErrors) {
            setTimeout(function () {
                if (e === Number(elm.data("e"))) {
                    elm.addClass("hide");
                }
            }, 10000);
        }

        (($.isArray(errors) && errors) || derror).forEach(function (v) {
            ifrg += "<li class='alert alert-danger'><span class='glyphicon glyphicon-remove-sign'></span> " + v + "</li>";
        });

        elm.html(ifrg).removeClass("hide");
        scrollViewTo(elm[0]);
    }

    function submit(uinputs, onsuccess, onload) {
        if (xlock) {
            return;
        }

        xlock = true;
        $(".j-error", jar).addClass("hide");
        progress(40, true);

        $.ajax({
            method: "POST",
            url: location.href,
            error: function () {
                if (onload) {
                    onload();
                }

                showErrors();
                progress(100, false);
            },
            data: uinputs
        }).done(function (response, status) {
            if (onload) {
                onload();
            }

            progress(100, false);

            if (status !== "success" || !response.status || response.errors) {
                showErrors(response.errors);
                return;
            }

            onsuccess(response.data);
            xlock = false;
        });
    }

    function init(uconfigs) {
        $ = jQuery;
        configs = {
            hideErrors: true
        };

        if (uconfigs) {
            forEach(uconfigs, function (v, k) {
                if (configs.hasOwnProperty(k)) {
                    configs[k] = v;
                }
            });
        }

        jar = $("#j-ar");
        derror = ["Invalid request. Please refresh the page or try again later."];

        if (configs.hideErrors) {
            $(".j-error", jar).on("click", function () {
                $(this).addClass("hide");
            });
        }
    }

    function Form() {}

    Form.prototype = {
        init: init,
        submit: submit,

        get lock() { return xlock; },
        set lock(status) { xlock = status; }
    };

    function GForm() {
        if (this instanceof GForm) {
            return new Form();
        }

        return new GForm();
    }

    window.GForm = GForm;
}());