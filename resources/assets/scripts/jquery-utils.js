/// <reference path="types.d.ts" />
define(["require", "exports", 'jquery'], function (require, exports, $) {
    $.fn.prefixedData = function (prefix) {
        var origData = $(this).first().data();
        var data = {};
        for (var p in origData) {
            var pattern = new RegExp("^" + prefix + "[A-Z]+");
            if (origData.hasOwnProperty(p) && pattern.test(p)) {
                var shortName = p[prefix.length].toLowerCase() + p.substr(prefix.length + 1);
                data[shortName] = origData[p];
            }
        }
        return data;
    };
    $.fn.removeAttributes = function () {
        return this.each(function () {
            var attributes = $.map(this.attributes, function (item) {
                return item.name;
            });
            var img = $(this);
            $.each(attributes, function (i, item) {
                img.removeAttr(item);
            });
        });
    };
    $.fn.ensureClass = function (clas, has) {
        if (has === void 0) { has = true; }
        var $this = $(this);
        if (has === true && $this.hasClass(clas) === false) {
            $this.addClass(clas);
        }
        else if (has === false && $this.hasClass(clas) === true) {
            $this.removeClass(clas);
        }
        return this;
    };
});
