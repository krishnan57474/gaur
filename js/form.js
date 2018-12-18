(function ($) {
    "use strict";

    var jar,

    // self overriding fnc
    progress, showErrors, isValidFile, submit;

    function poverride(key, fnc, args) {
        Form.prototype[key] = fnc;
        return fnc.apply(undefined, args);
    }

    function getTransitionDuration(elm) {
        var duration = elm.css("transition-duration").split(",");

        return parseFloat(duration[0]) * 1000;
    }

    progress = function () {
        var progressElm,
        progressBarElm,
        transitionDuration,
        timer;

        function getProgressFrg() {
            var progressFrg = $(document.createElement("div"));
            progressFrg.attr("class", "progress fixed-top");
            progressFrg.css({ height: "3px" });

            return progressFrg;
        }

        function getProgressBarFrg() {
            var progressBarFrg = $(document.createElement("div"));
            progressBarFrg.attr("class", "progress-bar bg-warning");

            return progressBarFrg;
        }

        function showProgress() {
            var width = 5;

            clearInterval(timer);
            progressElm.css({ width: "100%" });
            progressBarElm.css({ width: 0 });

            timer = setInterval(function () {
                width += 5;

                if (width < 100) {
                    progressBarElm.css({ width: width + "%" });
                } else {
                    clearInterval(timer);
                }
            }, 500);
        }

        function hideProgress() {
            clearInterval(timer);
            progressBarElm.css({ width: "100%" });

            setTimeout(function () {
                progressBarElm.css({ width: 0 });

                setTimeout(function () {
                    progressElm.css({ width: 0 });
                }, transitionDuration);
            }, 1000);
        }

        function init() {
            progressElm = getProgressFrg();
            progressBarElm = getProgressBarFrg();
            transitionDuration = getTransitionDuration(progressBarElm);

            progressElm.append(progressBarElm);
            $(document.body).append(progressElm);
        }

        progress = function (show) {
            if (show) {
                showProgress();
            } else {
                hideProgress();
            }
        };

        init();
        return poverride("progress", progress, arguments);
    };

    showErrors = function () {
        var listElm,
        btnElm,
        defaultError,
        errorElm,
        errorElmPosition,
        transitionDuration,
        isAutoHideErrors,
        timer;

        function getListFrg() {
            var listFrg = $(document.createElement("li"));
            listFrg.attr("class", "alert alert-danger alert-dismissible fade show");

            return listFrg;
        }

        function getBtnFrg() {
            var btnFrg = $(document.createElement("button"));
            btnFrg.attr("type", "button");
            btnFrg.attr("class", "close");
            btnFrg.text("×");

            return btnFrg;
        }

        function getErrorsFrg(errors) {
            var errorsFrg = $(document.createDocumentFragment()),
            frgClone;

            ($.isArray(errors) ? errors : defaultError).forEach(function (errorMsg) {
                frgClone = listElm.clone();
                frgClone.text(errorMsg);

                if (isAutoHideErrors) {
                    frgClone.append(btnElm.clone());
                } else {
                    frgClone.removeClass("alert-dismissible");
                }

                errorsFrg.append(frgClone);
            });

            return errorsFrg;
        }

        function hideErrors() {
            errorElm.children().removeClass("show");

            setTimeout(function () {
                errorElm.addClass("d-none");
            }, transitionDuration);
        }

        function autoHideErrors() {
            clearTimeout(timer);

            timer = setTimeout(function () {
                if (!errorElm.hasClass("d-none")) {
                    hideErrors();
                }
            }, 10000);
        }

        function errorsHandler() {
            errorElm.on("click", function (e) {
                if (e.target.tagName === "BUTTON") {
                    hideErrors();
                }
            });
        }

        function init() {
            listElm = getListFrg();
            btnElm = getBtnFrg();
            defaultError = ["Invalid request. Please refresh the page or try again later!"];
            errorElm = $(".j-error", jar);
            transitionDuration = getTransitionDuration(listElm);
            isAutoHideErrors = (errorElm.attr("data-show-errors") === undefined);

            errorsHandler();
        }

        showErrors = function (errors) {
            if (isAutoHideErrors) {
                autoHideErrors();
            }

            errorElm.addClass("d-none").children().remove();
            errorElm.append(getErrorsFrg(errors)).removeClass("d-none");

            if (errorElmPosition === undefined) {
                errorElmPosition = errorElm.offset() ? errorElm.offset().top : 0;
            }

            $("html, body").animate({ scrollTop: errorElmPosition });
        };

        init();
        return poverride("showErrors", showErrors, arguments);
    };

    isValidFile = function () {
        var invalidFileError = ["The filetype you are attempting to upload is not allowed."],
        filesizeError = ["The uploaded file exceeds the maximum upload file size limit."];

        function toBytes(val) {
            var units = "bkmgtpezy",
            size = val.match(/[\d.]+/g),
            unit = val.replace(size, "");

            size = parseFloat(size);
            unit = unit[0].toLowerCase();

            return size * Math.pow(1024, units.indexOf(unit));
        }

        isValidFile = function (file, atypes, limit, error) {
            var rx = new RegExp("^" + atypes.join("$|^") + "$", "i");

            if (!rx.test(file.name.split(".").pop()) || !file.size) {
                (error || showErrors)(invalidFileError);
                return false;
            }

            if (file.size > toBytes(limit)) {
                (error || showErrors)(filesizeError);
                return false;
            }

            return true;
        };

        return poverride("isValidFile", isValidFile, arguments);
    };

    submit = function () {
        var errorElm = $(".j-error", jar),
        lock;

        function getConfigs(uconfigs) {
            var configs = {
                method: "POST",
                url: uconfigs.url || location.href,
                error: function () {
                    if (uconfigs.load) {
                        uconfigs.load();
                    }

                    if (!uconfigs.ignoreErrors) {
                        (uconfigs.error || showErrors)();
                    }

                    (uconfigs.progress || progress)(false);
                    lock = false;
                },
                data: uconfigs.data
            };

            if (uconfigs.upload) {
                configs.processData = false;
                configs.contentType = false;
            }

            return configs;
        }

        function responseHandler(uconfigs, response, status) {
            if (uconfigs.load) {
                uconfigs.load();
            }

            (uconfigs.progress || progress)(false);

            if (status !== "success" || !response.status || response.errors) {
                if (!uconfigs.ignoreErrors) {
                    (uconfigs.error || showErrors)(response.errors);
                }

                lock = false;
                return;
            }

            uconfigs.success(response.data);
            lock = false;
        }

        submit = function (uconfigs, ignoreLock) {
            if (!ignoreLock && lock) {
                return;
            }

            lock = true;
            errorElm.addClass("d-none");
            (uconfigs.progress || progress)(true);

            $.ajax(getConfigs(uconfigs)).done(function (response, status) {
                responseHandler(uconfigs, response, status);
            });
        };

        return poverride("submit", submit, arguments);
    };

    function init() {
        $ = jQuery;
        jar = $("#j-ar");
    }

    function Form() {}

    Form.prototype = {
        progress: progress,
        showErrors: showErrors,
        isValidFile: isValidFile,
        submit: submit
    };

    function GForm() {
        if (this instanceof GForm) {
            return new Form();
        }

        return new GForm();
    }

    init();
    window.GForm = GForm;
}());