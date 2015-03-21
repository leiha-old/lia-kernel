$lia.Interface = {
    'store' : {},

    /**
     * @param {string} interfaceName
     * @param {[]} methods
     */
    'register' : function(interfaceName, methods){
        return this.store[interfaceName] = new this.builder(interfaceName, methods);
    },

    /**
     * @param {string} interfaceName
     * @return {boolean}
     */
    'has' : function(interfaceName){
        if(!this.store[interfaceName]) {
            $lia.Exception
                .create('Interface [ {name} ] is not registered', {name: interfaceName})
                .fire()
            ;
        }
        return true;
    },

    /**
     * @param {string} interfaceName
     * @param {$lia.Class} obj
     */
    'check' : function(interfaceName, obj){
        //TODO : Make an Exception if obj is not an object
        if(this.has(interfaceName)) {
            this.store[interfaceName].check(obj);
        }
    },

    /**
     * @constructor
     * @param {string}   interfaceName
     * @param {string[]} methods
     */
    'builder' : function(interfaceName, methods) {
        this.name = interfaceName;
        this.constructor = '_LIAINTERFACE_';
        if (!$.isArray(methods)) {
            $lia.Exception
                .create('Interface must define methods as an Array of Strings')
                .fire()
            ;
        }
        this.methods = methods;

        /**
         * @return {string}
         */
        this.getName = function() {
            return this.name;
        };

        /**
         * @param {object | $lia.Class.builder} objectToCheck
         */
        this.check = function(objectToCheck){
            //TODO : Make an Exception if obj is not an object
            $lia.forEach(this.methods, function(methodName){
                if(!objectToCheck[methodName]){

                    var e = $lia.Exception.create(
                        'Interface [ {name} ]'
                         +' require implementation of method [ {methodName} ]'
                        ,
                        {name:this.name, methodName:methodName}
                    );

                    if($lia.isLiaClass(objectToCheck)) {
                        e.addContext('object'  , objectToCheck.definer.extended)
                         .addContext('Interface Methods Required'  , this.methods)
                         .addContext('instance', objectToCheck)
                         .addContext('definer' , objectToCheck.definer)
                            ;
                    } else {
                        e.addContext('object', objectToCheck)
                    }

                    e.fire();
                }
            }, this);
        };
    }
};