(function ($) {
    "use strict";

    var configs, jar, xlock,

    // self overriding fnc
    progress, showErrors;

    function forEach(obj, callback) {
        Object.keys(obj).forEach(function (k) {
            callback(obj[k], k, obj);
        });
    }

    progress = function () {
        var prg = $("<div id='prg'></div>"),
        timer,
        width;

        $(document.body).append(prg);

        progress = function (display) {
            $(prg).css({
                width: (display ? 0 : 100) + "%",
                height: (display ? 3 : 0) + "px",
                borderWidth: (display ? 1 : 0) + "px"
            });

            if (display) {
                width = 5;

                timer = setInterval(function () {
                    width += 5;

                    if (width < 100) {
                        $(prg).css({ width: width + "%" });
                    } else {
                        clearInterval(timer);
                    }
                }, 500);
            } else {
                clearInterval(timer);

                setTimeout(function () {
                    $(prg).css({ width: 0 });
                }, 1000);
            }
        };

        progress.apply(undefined, arguments);
    };

    showErrors = function () {
        var elm = $(".j-error", jar),
        frg = document.createDocumentFragment(),
        errorFrg = $.parseHTML("<li class='alert alert-danger'><span class='glyphicon glyphicon-remove-sign'></span> </li>"),
        defaultError = "Invalid request. Please refresh the page or try again later.",
        timer;

        showErrors = function (errors) {
            if (configs.hideErrors) {
                clearTimeout(timer);

                timer = setTimeout(function () {
                    elm.addClass("hide");
                }, 10000);
            }

            if ($.isArray(errors)) {
                errors.forEach(function (v) {
                    $(frg).append($(errorFrg).clone().append(v));
                });
            } else {
                $(frg).append($(errorFrg).clone().append(defaultError));
            }

            elm.children().remove();
            elm.append(frg).removeClass("hide");
            scrollTo(0, elm[0].getBoundingClientRect().top - document.body.getBoundingClientRect().top);
            xlock = false;
        };

        showErrors.apply(undefined, arguments);
    };

    function submit(uinputs, onsuccess, onload) {
        if (xlock) {
            return;
        }

        xlock = true;
        $(".j-error", jar).addClass("hide");
        progress(true);

        $.ajax({
            method: "POST",
            url: location.href,
            error: function () {
                if (onload) {
                    onload();
                }

                showErrors();
                progress(false);
            },
            data: uinputs
        }).done(function (response, status) {
            if (onload) {
                onload();
            }

            progress(false);

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