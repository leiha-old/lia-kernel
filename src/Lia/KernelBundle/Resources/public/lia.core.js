/**
 * This object contains all libraries of lia Framework
 * @type {{}}
 */
$lia = {
    'bridge' : {}
};

$lia.length = function(o) {
    var l = 0;
    for (var i in o) l++;
    return l;
};

$lia.typeOf = function(o){
    var t = jQuery.type(o);
    if('object' == t) {
        if(o.htmlElement) {
            t = 'node';
        } else if(o.nodeName){
            switch(o.nodeType){
                case 1: t = 'node';break;
                case 3: t = (/\S/).test(o.nodeValue) ? 'text-node' : 'white-space';break;
            }
        }
    }
    return t;

};

$lia.isTypeOf = function(o, is){
    var t = $lia.typeOf(o);
    if(t == is){
        return true;
    } else {
        return o.constructor == is;
    }
};

$lia.isLiaDefinitionClass = function(o){
    return $lia.isTypeOf(o, '_LIADEFINITIONCLASS_');
};

$lia.isLiaClass = function(o){
    return $lia.isTypeOf(o, '_LIACLASS_');
};

$lia.isLiaInterface = function(o){
    return $lia.isTypeOf(o, '_LIAINTERFACE_');
};

/**
 *
 * @param {object}   obj
 * @param {function} fn
 * @param {object}   context
 */
$lia.forEach = function(obj, fn, context){
    if($.isArray(obj)){
        for (var i = 0, len = obj.length; i < len; i++) {
            fn.apply(context, [obj[i], i]);
        }
    } else if($.isPlainObject(obj)){
        for (var i in obj) {
            fn.apply(context, [obj[i], i]);
        }
    }
};

$lia.apply = function(context, func, arg){
    return func[Array.isArray(arg) ? 'apply' : 'call'](context, arg);
};

/**
 *
 * @param {object} context
 * @param {object} mapping
 * @param {object} config
 */
$lia.applyIterator = function(context, mapping, config){
    if(config) {
        $lia.forEach(mapping, function(value, key){
            if (config[key]) {
                context[mapping[key]].call(context, config[key]);
            }
        }, context);
    }
};

