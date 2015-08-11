var __extends = (this && this.__extends) || function (d, b) {
    for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p];
    function __() { this.constructor = d; }
    __.prototype = b.prototype;
    d.prototype = new __();
};
define(["require", "exports", 'util', 'classes/Storage'], function (require, exports, util_1, Storage_1) {
    var PreferenceControl = (function () {
        function PreferenceControl(pref) {
            this.onChangeHandler = $.noop;
            this.makeFunction = $.noop;
            this.pref = pref;
            this.storageKey = 'preference-' + this.pref.id;
        }
        PreferenceControl.prototype.init = function () {
            this.makeFunction.apply(this, arguments);
        };
        PreferenceControl.prototype.setDefault = function (def) {
            this.def = def;
            return this;
        };
        PreferenceControl.prototype.make = function (onChange) {
            var self = this;
            this.makeFunction = function () {
                var value = Storage_1.storage.get(self.storageKey, {
                    def: self.def
                });
                self.onChangeHandler = onChange;
                self.$el.on('change', function (e) {
                    var $this = this;
                    self.setValue(self.$el.val());
                });
                self.setValue(value);
                self.$el.blur();
            };
        };
        PreferenceControl.prototype.setValue = function (val) {
            this.value = val;
            Storage_1.storage.set(this.storageKey, this.value);
            if (this.onChangeHandler) {
                this.onChangeHandler(this.value, this, this.$el);
            }
            return this;
        };
        return PreferenceControl;
    })();
    exports.PreferenceControl = PreferenceControl;
    var PreferenceSelectControl = (function (_super) {
        __extends(PreferenceSelectControl, _super);
        function PreferenceSelectControl(pref) {
            _super.call(this, pref);
            this.options = {};
            this.$el = util_1.cre('select').addClass('form-control').attr('id', pref.id);
            pref.$controlWrapper.append(this.$el);
        }
        PreferenceSelectControl.prototype.setOptions = function (options) {
            this.options = options;
            return this;
        };
        PreferenceSelectControl.prototype.make = function (onChange) {
            var self = this;
            _super.prototype.make.call(this, function () {
                if (onChange) {
                    onChange.apply(this, arguments);
                }
                self.$el.html('');
                $.each(self.options, function (val, text) {
                    var $opt = util_1.cre('option').val(val).text(text);
                    if (val === self.value) {
                        $opt.attr('selected', 'selected');
                    }
                    self.$el.append($opt);
                });
            });
            return this;
        };
        return PreferenceSelectControl;
    })(PreferenceControl);
    exports.PreferenceSelectControl = PreferenceSelectControl;
    var Preference = (function () {
        function Preference(id, name) {
            this.id = id;
            this.name = name;
            this.$box = util_1.cre().addClass('preference-box clearfix');
            this.$labelWrapper = util_1.cre().addClass('preference-label pull-left').appendTo(this.$box);
            this.$label = util_1.cre().addClass('control-label').attr('for', 'pref-' + id).text(name).appendTo(this.$labelWrapper);
            this.$controlWrapper = util_1.cre().addClass('preference-control pull-right').appendTo(this.$box);
            this.$controlWrapper.html('');
        }
        Preference.prototype.createSelectControl = function () {
            return this.control = new PreferenceSelectControl(this);
        };
        return Preference;
    })();
    exports.Preference = Preference;
    var Preferences = (function () {
        function Preferences(app, $el) {
            this.app = app;
            this.$el = $el;
            this.preferences = {};
        }
        Preferences.prototype.init = function () {
            this.$el.closest('.preferences').find('> .btn').on('click', function (e) {
                e.preventDefault();
                $(this).parent().toggleClass('active');
            });
            $.each(this.preferences, function (id, pref) {
                pref.control.init();
            });
            return this;
        };
        Preferences.prototype.add = function (id, name) {
            this.preferences[id] = new Preference(id, name);
            console.log(this.preferences[id]);
            this.$el.append(this.preferences[id].$box);
            return this.preferences[id];
        };
        Preferences.prototype.getControl = function (id) {
            return this.preferences[id].control;
        };
        return Preferences;
    })();
    exports.Preferences = Preferences;
});
