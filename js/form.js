(function ($) {
    "use strict";

    var configs, jar, xlock,

    // self overriding fnc
    progress, showErrors;

    function poverride(key, fnc) {
        Form.prototype[key] = fnc;
    }

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
            prg.css({
                width: (display ? 0 : 100) + "%",
                height: (display ? 3 : 0) + "px",
                borderWidth: (display ? 1 : 0) + "px"
            });

            if (display) {
                width = 5;
                clearInterval(timer);

                timer = setInterval(function () {
                    width += 5;

                    if (width < 100) {
                        prg.css({ width: width + "%" });
                    } else {
                        clearInterval(timer);
                    }
                }, 500);
            } else {
                clearInterval(timer);

                setTimeout(function () {
                    prg.css({ width: 0 });
                }, 1000);
            }
        };

        poverride("progress", progress);
        progress.apply(undefined, arguments);
    };

    showErrors = function () {
        var elm = $(".j-error", jar),
        frg = $(document.createDocumentFragment()),
        errorFrg = $($.parseHTML("<li class='alert alert-danger'><span class='glyphicon glyphicon-remove-sign'></span> </li>")),
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
                    frg.append(errorFrg.clone().append(v));
                });
            } else {
                frg.append(errorFrg.clone().append(defaultError));
            }

            elm.children().remove();
            elm.append(frg).removeClass("hide");
            $("html, body").animate({ scrollTop: elm.offset().top });
        };

        poverride("showErrors", showErrors);
        showErrors.apply(undefined, arguments);
    };

    function isValidFile(file, atypes, limit) {
        var rx = new RegExp("^" + atypes.join("$|^") + "$", "i");

        if (!rx.test(file.name.split(".").pop())) {
            showErrors(["The filetype you are attempting to upload is not allowed."]);
            return false;
        }

        if (!file.size || file.size > limit) {
            showErrors(["The uploaded file exceeds the maximum upload file size limit."]);
            return false;
        }

        return true;
    }

    function submit(xconfigs) {
        if (xlock) {
            return;
        }

        var data = {
            method: "POST",
            url: xconfigs.url || location.href,
            error: function () {
                if (xconfigs.load) {
                    xconfigs.load();
                }

                showErrors();
                progress(false);
                xlock = false;
            },
            data: xconfigs.data
        };

        if (xconfigs.upload) {
            data.processData = false;
            data.contentType = false;
        }

        xlock = true;
        $(".j-error", jar).addClass("hide");
        progress(true);

        $.ajax(data).done(function (response, status) {
            if (xconfigs.load) {
                xconfigs.load();
            }

            progress(false);

            if (status !== "success" || !response.status || response.errors) {
                showErrors(response.errors);
                xlock = false;
                return;
            }

            xconfigs.success(response.data);
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
        isValidFile: isValidFile,
        showErrors: showErrors,
        progress: progress,

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