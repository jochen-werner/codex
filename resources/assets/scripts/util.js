/// <reference path="types.d.ts" />
define(["require", "exports"], function (require, exports) {
    var kindsOf = {};
    'Number String Boolean Function RegExp Array Date Error'.split(' ').forEach(function (k) {
        kindsOf['[object ' + k + ']'] = k.toLowerCase();
    });
    var nativeTrim = String.prototype.trim;
    function ucfirst(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
    exports.ucfirst = ucfirst;
    function makeString(object) {
        if (object == null)
            return '';
        return '' + object;
    }
    exports.makeString = makeString;
    function escapeRegExp(str) {
        return makeString(str).replace(/([.*+?^=!:${}()|[\]\/\\])/g, '\\$1');
    }
    exports.escapeRegExp = escapeRegExp;
    function defaultToWhiteSpace(characters) {
        if (characters == null)
            return '\\s';
        else if (characters.source)
            return characters.source;
        else
            return '[' + escapeRegExp(characters) + ']';
    }
    exports.defaultToWhiteSpace = defaultToWhiteSpace;
    function trim(str, characters) {
        str = makeString(str);
        if (!characters && nativeTrim)
            return nativeTrim.call(str);
        characters = defaultToWhiteSpace(characters);
        return str.replace(new RegExp('^' + characters + '+|' + characters + '+$', 'g'), '');
    }
    exports.trim = trim;
    function unquote(str, quoteChar) {
        quoteChar = quoteChar || '"';
        if (str[0] === quoteChar && str[str.length - 1] === quoteChar)
            return str.slice(1, str.length - 1);
        else
            return str;
    }
    exports.unquote = unquote;
    function def(val, def) {
        return defined(val) ? val : def;
    }
    exports.def = def;
    function defined(obj) {
        return !_.isUndefined(obj);
    }
    exports.defined = defined;
    function cre(name) {
        if (!defined(name)) {
            name = 'div';
        }
        return $(document.createElement(name));
    }
    exports.cre = cre;
    function getParts(str) {
        return str.replace(/\\\./g, '\uffff').split('.').map(function (s) {
            return s.replace(/\uffff/g, '.');
        });
    }
    exports.getParts = getParts;
    function objectGet(obj, parts, create) {
        if (typeof parts === 'string') {
            parts = getParts(parts);
        }
        var part;
        while (typeof obj === 'object' && obj && parts.length) {
            part = parts.shift();
            if (!(part in obj) && create) {
                obj[part] = {};
            }
            obj = obj[part];
        }
        return obj;
    }
    exports.objectGet = objectGet;
    function objectSet(obj, parts, value) {
        parts = getParts(parts);
        var prop = parts.pop();
        obj = objectGet(obj, parts, true);
        if (obj && typeof obj === 'object') {
            return (obj[prop] = value);
        }
    }
    exports.objectSet = objectSet;
    function objectExists(obj, parts) {
        parts = getParts(parts);
        var prop = parts.pop();
        obj = objectGet(obj, parts);
        return typeof obj === 'object' && obj && prop in obj;
    }
    exports.objectExists = objectExists;
    function kindOf(value) {
        // Null or undefined.
        if (value == null) {
            return String(value);
        }
        // Everything else.
        return kindsOf[kindsOf.toString.call(value)] || 'object';
    }
    exports.kindOf = kindOf;
    function recurse(value, fn, fnContinue) {
        function recurse(value, fn, fnContinue, state) {
            var error;
            if (state.objs.indexOf(value) !== -1) {
                error = new Error('Circular reference detected (' + state.path + ')');
                error.path = state.path;
                throw error;
            }
            var obj, key;
            if (fnContinue && fnContinue(value) === false) {
                // Skip value if necessary.
                return value;
            }
            else if (kindOf(value) === 'array') {
                // If value is an array, recurse.
                return value.map(function (item, index) {
                    return recurse(item, fn, fnContinue, {
                        objs: state.objs.concat([value]),
                        path: state.path + '[' + index + ']',
                    });
                });
            }
            else if (kindOf(value) === 'object') {
                // If value is an object, recurse.
                obj = {};
                for (key in value) {
                    obj[key] = recurse(value[key], fn, fnContinue, {
                        objs: state.objs.concat([value]),
                        path: state.path + (/\W/.test(key) ? '["' + key + '"]' : '.' + key),
                    });
                }
                return obj;
            }
            else {
                // Otherwise pass value into fn and return.
                return fn(value);
            }
        }
        return recurse(value, fn, fnContinue, { objs: [], path: '' });
    }
    exports.recurse = recurse;
    function copyObject(object) {
        var objectCopy = {};
        for (var key in object) {
            if (object.hasOwnProperty(key)) {
                objectCopy[key] = object[key];
            }
        }
        return objectCopy;
    }
    exports.copyObject = copyObject;
    /**
     * Stringify a JSON object, supports functions
     * @param {object} obj - The json object
     * @returns {string}
     */
    function jsonStringify(obj) {
        return JSON.stringify(obj, function (key, value) {
            if (value instanceof Function || typeof value == 'function') {
                return value.toString();
            }
            if (value instanceof RegExp) {
                return '_PxEgEr_' + value;
            }
            return value;
        });
    }
    exports.jsonStringify = jsonStringify;
    ;
    /**
     * Parse a string into json, support functions
     * @param {string} str - The string to parse
     * @param date2obj - I forgot, sorry
     * @returns {object}
     */
    function jsonParse(str, date2obj) {
        var iso8061 = date2obj ? /^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2}(?:\.\d*)?)Z$/ : false;
        return JSON.parse(str, function (key, value) {
            var prefix;
            if (typeof value != 'string') {
                return value;
            }
            if (value.length < 8) {
                return value;
            }
            prefix = value.substring(0, 8);
            if (iso8061 && value.match(iso8061)) {
                return new Date(value);
            }
            if (prefix === 'function') {
                return eval('(' + value + ')');
            }
            if (prefix === '_PxEgEr_') {
                return eval(value.slice(8));
            }
            return value;
        });
    }
    exports.jsonParse = jsonParse;
    ;
    /**
     * Clone an object
     * @param {object} obj
     * @param {boolean} date2obj
     * @returns {Object}
     */
    function jsonClone(obj, date2obj) {
        return jsonParse(jsonStringify(obj), date2obj);
    }
    exports.jsonClone = jsonClone;
    ;
});
