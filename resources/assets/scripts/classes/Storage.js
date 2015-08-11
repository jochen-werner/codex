define(["require", "exports", '../util'], function (require, exports, util_1) {
    /**
     * A wrapper around the localStorage, allows expiration settings and JSON in/output
     * @exports storage
     */
    /**
     * Add a event listener for the 'onstorage' event
     * @param {function} callback
     */
    function on(callback) {
        if (window.addEventListener) {
            window.addEventListener("storage", callback, false);
        }
        else {
            window.attachEvent("onstorage", callback);
        }
    }
    exports.on = on;
    /**
     * @typedef StorageSetOptions
     * @type {object}
     * @property {boolean} [json=false] - Set to true if the value passed is a JSON object
     * @property {number|boolean} [expires=false] - Minutes until expired
     */
    /**
     * Save a value to the storage
     * @param {string|number} key               - The unique id to save the data on
     * @param {*} val                           - The value, can be any datatype. If it's an object, make sure to enable json in the options
     * @param {StorageSetOptions} [options]     - Additional options, check the docs
     */
    function set(key, val, options) {
        var options = _.merge({ json: false, expires: false }, options);
        if (options.json) {
            val = util_1.jsonStringify(val);
        }
        if (options.expires) {
            var now = Math.floor((Date.now() / 1000) / 60);
            window['localStorage'].setItem(key + ':expire', now + options.expires);
        }
        window['localStorage'].setItem(key, val);
    }
    exports.set = set;
    /**
     * @typedef StorageGetOptions
     * @type {object}
     * @property {boolean} [json=false]     - Set to true if the value is a JSON object
     * @property {*} [default=false]        - The default value to return if the requested key does not exist
     */
    /**
     * Get a value from the storage
     * @param key
     * @param {StorageGetOptions} [options] - Optional options, check the docs
     * @returns {*}
     */
    function get(key, options) {
        var options = _.merge({ json: false, def: null }, options);
        if (!util_1.defined(key)) {
            return options.def;
        }
        if (_.isString(window['localStorage'].getItem(key))) {
            if (_.isString(window['localStorage'].getItem(key + ':expire'))) {
                var now = Math.floor((Date.now() / 1000) / 60);
                var expires = parseInt(window['localStorage'].getItem(key + ':expire'));
                if (now > expires) {
                    del(key);
                    del(key + ':expire');
                }
            }
        }
        var val = window['localStorage'].getItem(key);
        if (!util_1.defined(val) || util_1.defined(val) && val == null) {
            return options.def;
        }
        if (options.json) {
            return util_1.jsonParse(val);
        }
        return val;
    }
    exports.get = get;
    /**
     * Delete a value from the storage
     * @param {string|number} key
     */
    function del(key) {
        window['localStorage'].removeItem(key);
    }
    exports.del = del;
    /**
     * Clear the storage, will clean all saved items
     */
    function clear() {
        window.localStorage.clear();
    }
    exports.clear = clear;
    /**
     * Get total localstorage size in MB. If key is provided,
     * it will return size in MB only for the corresponding item.
     * @param [key]
     * @returns {string}
     */
    function getSize(key) {
        key = key || false;
        if (key) {
            return ((localStorage[x].length * 2) / 1024 / 1024).toFixed(2);
        }
        else {
            var total = 0;
            for (var x in localStorage) {
                total += (localStorage[x].length * 2) / 1024 / 1024;
            }
            return total.toFixed(2);
        }
    }
    exports.getSize = getSize;
    exports.storage = {
        on: on,
        get: get,
        set: set,
        del: del,
        clear: clear,
        getSize: getSize,
    };
});
