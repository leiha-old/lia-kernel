String.prototype.format = function(v, f){
    return $.isFunction(f)
        ? this.format.callback.call(this,v, f)
        : this.format.default.call(this, v)
        ;
};
String.prototype.format.default = function(v){
    var s = this;
    for(var i in v){
        s = s.replace(new RegExp("{" + i + "}",'g'),
            !$.isFunction(v[i])
                ? v[i]
                : function(){return v[i](i);}
        );
    }
    return s;
};
String.prototype.format.callback = function(v, f){
    var s = this;
    for(var i in v){
        s = s.replace(new RegExp("{" + v[i] + "}",'g'), function(){
            return f(v[i]);
        });
    }
    return s;
};