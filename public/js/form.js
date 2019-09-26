(function ($) {
    "use strict";
    function getTransitionDuration(elm) {
        var duration = elm.css("transition-duration").split(",");
        return parseFloat(duration[0]) * 1000;
    }
    var Progress = (function () {
        function Progress() {
        }
        Progress.getProgressBarFrg = function () {
            var progressBarFrg = $(document.createElement("div"));
            progressBarFrg.attr("class", "progress-bar progress-bar-striped progress-bar-animated");
            return progressBarFrg;
        };
        Progress.getProgressFrg = function () {
            var progressFrg = $(document.createElement("div"));
            progressFrg.attr("class", "progress fixed-top");
            progressFrg.css({
                height: "6px",
                width: 0
            });
            return progressFrg;
        };
        Progress.hide = function () {
            var _this = this;
            clearInterval(this.timer);
            this.progressBarElm.css({ width: "100%" });
            setTimeout(function () {
                _this.progressBarElm.css({ width: 0 });
                setTimeout(function () { return _this.progressElm.css({ width: 0 }); }, _this.transitionDuration);
            }, 1000);
        };
        Progress.init = function () {
            this.progressBarElm = this.getProgressBarFrg();
            this.progressElm = this.getProgressFrg();
            this.timer = 0;
            this.transitionDuration = getTransitionDuration(this.progressBarElm);
            this.progressElm.append(this.progressBarElm);
            $(document.body).append(this.progressElm);
        };
        Progress.show = function () {
            var _this = this;
            var width = 5;
            clearInterval(this.timer);
            this.progressElm.css({ width: "100%" });
            this.progressBarElm.css({ width: 0 });
            this.timer = setInterval(function () {
                width += 5;
                if (width < 100) {
                    _this.progressBarElm.css({
                        width: width + "%"
                    });
                }
                else {
                    clearInterval(_this.timer);
                }
            }, 500);
        };
        return Progress;
    }());
    var ErrorsFrg = (function () {
        function ErrorsFrg() {
        }
        ErrorsFrg.getBtnFrg = function () {
            var btnFrg = $(document.createElement("button"));
            btnFrg.attr("type", "button");
            btnFrg.attr("class", "close");
            btnFrg.attr("data-ebtn", "");
            btnFrg.text("×");
            return btnFrg;
        };
        ErrorsFrg.getListFrg = function () {
            var listFrg = $(document.createElement("li"));
            listFrg.attr("class", "alert alert-danger alert-dismissible fade show");
            return listFrg;
        };
        ErrorsFrg.get = function (errors, isAutoHide) {
            var _this = this;
            var errorsFrg = $(document.createDocumentFragment()), errorsList = Array.isArray(errors) ? errors : [this.defaultError];
            var frgClone;
            errorsList.forEach(function (e) {
                frgClone = _this.listElm.clone();
                frgClone.text(e);
                if (isAutoHide) {
                    frgClone.append(_this.btnElm.clone());
                }
                else {
                    frgClone.removeClass("alert-dismissible");
                }
                errorsFrg.append(frgClone);
            });
            return errorsFrg;
        };
        ErrorsFrg.getTransitionDuration = function () {
            return this.transitionDuration;
        };
        ErrorsFrg.init = function () {
            this.btnElm = this.getBtnFrg();
            this.listElm = this.getListFrg();
            this.transitionDuration = getTransitionDuration(this.listElm);
            this.defaultError = "Invalid request. Please refresh the page or try again later!";
        };
        return ErrorsFrg;
    }());
    var Errors = (function () {
        function Errors() {
        }
        Errors.handler = function (e) {
            var elm = e.target;
            if (elm.tagName !== "BUTTON" || !elm.hasAttribute("data-ebtn")) {
                return;
            }
            this.hide($(elm).closest(".j-error"));
        };
        Errors.hide = function (errorElm) {
            errorElm.children().removeClass("show");
            setTimeout(function () { return errorElm.addClass("d-none"); }, ErrorsFrg.getTransitionDuration());
        };
        Errors.setAutoHide = function (errorElm) {
            var _this = this;
            var timer = setTimeout(function () {
                if (!errorElm.hasClass("d-none")) {
                    _this.hide(errorElm);
                }
            }, 10000);
            this.errorElms.push(errorElm);
            this.timers.push(timer);
        };
        Errors.clear = function () {
            this.timers.splice(0).forEach(clearTimeout);
            this.errorElms.splice(0).forEach(function (e) { return e.addClass("d-none"); });
        };
        Errors.init = function () {
            var _this = this;
            ErrorsFrg.init();
            this.errorElms = [];
            this.timers = [];
            $(window).on("click", function (e) { return _this.handler(e); });
        };
        Errors.show = function (errors, context) {
            var errorElm = $(".j-error", context), isAutoHide = errorElm.attr("data-show-errors") === undefined;
            this.clear();
            errorElm
                .addClass("d-none")
                .children()
                .remove();
            errorElm.append(ErrorsFrg.get(errors, isAutoHide)).removeClass("d-none");
            var errorElmPosition = errorElm.offset();
            if (isAutoHide) {
                this.setAutoHide(errorElm);
            }
            if (errorElmPosition) {
                $("html, body").animate({
                    scrollTop: errorElmPosition.top
                });
            }
        };
        return Errors;
    }());
    var ValidateFile = (function () {
        function ValidateFile() {
        }
        ValidateFile.showError = function (args, error) {
            if (args.error) {
                args.error([error]);
            }
            else {
                Errors.show([error], args.context || $());
            }
        };
        ValidateFile.toBytes = function (unit) {
            var units = "bkmgtpezy", size = parseFloat(unit), uprefix = unit.substr(-2, 1).toLowerCase();
            var uindex = units.indexOf(uprefix);
            if (uindex < 0) {
                uindex = 0;
            }
            return size * Math.pow(1024, uindex);
        };
        ValidateFile.init = function () {
            this.filesizeError = "The uploaded file exceeds the maximum upload file size limit.";
            this.invalidFileError = "The filetype you are attempting to upload is not allowed.";
        };
        ValidateFile.isValid = function (args) {
            var file = args.file, rx = new RegExp("^" + args.types.join("$|^") + "$", "i"), ext = file.name.split(".").pop();
            if (!rx.test(ext || "") || !file.size) {
                this.showError(args, this.invalidFileError);
                return false;
            }
            if (file.size > this.toBytes(args.size)) {
                this.showError(args, this.filesizeError);
                return false;
            }
            return true;
        };
        return ValidateFile;
    }());
    var Ajax = (function () {
        function Ajax() {
        }
        Ajax.getConfigs = function (uconfigs, handlers) {
            var _this = this;
            var configs = {
                method: "POST",
                url: uconfigs.url || location.href,
                error: function () { return _this.onError(uconfigs, handlers); },
                data: uconfigs.data
            };
            if (uconfigs.upload) {
                configs.processData = false;
                configs.contentType = false;
            }
            return configs;
        };
        Ajax.getHandlers = function (uconfigs) {
            var handlers = {
                error: function (errors) {
                    if (uconfigs.error) {
                        uconfigs.error(errors);
                    }
                    else {
                        Errors.show(errors, uconfigs.context || $());
                    }
                },
                progress: function (status) {
                    if (uconfigs.progress) {
                        uconfigs.progress(status);
                    }
                    else if (status) {
                        Progress.show();
                    }
                    else {
                        Progress.hide();
                    }
                }
            };
            return handlers;
        };
        Ajax.onError = function (uconfigs, handlers) {
            if (uconfigs.load) {
                uconfigs.load();
            }
            handlers.progress(false);
            handlers.error("");
            this.lock = false;
        };
        Ajax.onSuccess = function (uconfigs, handlers, response, status) {
            if (uconfigs.load) {
                uconfigs.load();
            }
            handlers.progress(false);
            if (status === "success" && response.status && !response.errors) {
                uconfigs.success(response.data || "");
            }
            else {
                handlers.error(response.errors || "");
            }
            this.lock = false;
        };
        Ajax.submit = function (uconfigs, ignoreLock) {
            var _this = this;
            if (this.lock && !ignoreLock) {
                return;
            }
            var handlers = this.getHandlers(uconfigs), configs = this.getConfigs(uconfigs, handlers);
            this.lock = true;
            Errors.clear();
            handlers.progress(true);
            $.ajax(configs).done(function (response, status) {
                return _this.onSuccess(uconfigs, handlers, response, status);
            });
        };
        return Ajax;
    }());
    var Form = (function () {
        function Form() {
        }
        Form.prototype.error = function (errors, context) {
            Errors.show(errors, context || $());
        };
        Form.prototype.isValidFile = function (args) {
            return ValidateFile.isValid(args);
        };
        Form.prototype.progress = function (status) {
            if (status) {
                Progress.show();
            }
            else {
                Progress.hide();
            }
        };
        Form.prototype.submit = function (uconfigs, ignoreLock) {
            Ajax.submit(uconfigs, ignoreLock || false);
        };
        return Form;
    }());
    function gform() {
        return new Form();
    }
    function init() {
        $ = jQuery;
        Progress.init();
        Errors.init();
        ValidateFile.init();
        window.GForm = gform;
    }
    init();
})();
