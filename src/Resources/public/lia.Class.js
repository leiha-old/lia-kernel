$lia.Class = {
    'store' : {},

    /**
     *
     * @param {string} className
     * @return {$lia.Class.definition}
     */
    'register' : function(className){
        return this.store[className] = new this.builder(className);
    },

    /**
     *
     * @param name
     * @return {$lia.Class.definition}
     */
    'get' : function(name){
        //TODO : Make an Exception if this.store[name] is unknown
        return this.store[name];
    },

    /**
     *
     * @constructor
     * @type {$lia.Class.definition}
     */
    'builder' : function(className) {
        $.extend(this, $lia.Class.definition);
        this.name = className;

    },

    'definition' : {
        constructor  : '_LIADEFINITIONCLASS_',

        name : '',

        /**
         * @private
         */
        primary      : {},

        /**
         * @private
         */
        interfaces   : [],

        /**
         * @private
         */
        inheritances : [],

        /**
         * @private
         */
        extended     : {},

        /**
         * @private
         */
        alreadyBuilt : false,

        /**
         * @private
         */
        globalVars   : {},

        /**
         * @private
         */
        store        : {},

        /**
         * Create and register a new instance of defined object
         * @param {string} instanceName
         * @param {[]}     [args]
         * @return {object}
         */
        createAndRegister : function(instanceName, args){
            if(args && !$.isArray((args))) {
                $lia.Exception
                    .create('The second argument must be an array')
                    .fire()
                ;
            }

            if(this.store[instanceName]) {
                $lia.Exception
                    .create('Instance [ {name} ] is already registered'
                    + ' in definition [ {definitionName} ]'
                    , {name: instanceName, definitionName:this.getName()})
                    .addContext('stored', this.store)
                    .addContext('definition', this)
                    .fire()
                ;
            }

            this.store[instanceName] = this.create.apply(this, args);
            this.store[instanceName].__getInstanceName = function(){
                return instanceName;
            };

            return this.store[instanceName];
        },

        /**
         * Create a new instance of defined object
         */
        create : function () {
            this._isAlreadyBuilt(true);

            var object = $.extend({
                'constructor'   : '_LIACLASS_',
                'definer'       : this,
                '__addGlobalVar': function () {
                    return this.definer.addGlobalVar.apply(this.definer, arguments);
                },
                '__getGlobalVar': function () {
                    return this.definer.getGlobalVar.apply(this.definer, arguments);
                },
                '__deleteGlobalVar': function () {
                    return this.definer.deleteGlobalVar.apply(this.definer, arguments);
                },
                '__checkForInterfaces': function () {
                    return this.definer.checkForInterfaces.apply(this.definer, arguments);
                }
            }, this.extended);

            this._checkForInterfacesMethods(object);

            // If the method __constructor is present in the final object
            // then we call her and we pass the arguments of this function
            if (object['__constructor']) {
                object['__constructor'].apply(object, arguments);
            }
            return object;
        },

        /**
         * @param {string} instanceName
         * @return {boolean}
         */
        has : function(instanceName){
            if(!this.store[instanceName]) {
                $lia.Exception
                    .create('Instance [ {name} ] is not registered'
                    + ' in definition [ {definitionName} ]'
                    , {name: instanceName, definitionName:this.getName()})
                    .addContext('stored', this.store)
                    .addContext('definition', this)
                    .fire()
                ;
            }
            return true;
        },

        /**
         * @param {string} instanceName
         * @return {$lia.Class.definition}
         */
        get : function(instanceName){
            this.has(instanceName);
            return this.store[instanceName];
        },

        getName : function(){
            return this.name;
        },

        checkForLiaClass : function(objectToCheck){
            // If object is not a lia.class instance it's wrong
            if (!$lia.isLiaClass(objectToCheck)) {
                $lia.Exception
                    .create('Object must be defined by the lia.Class.register'
                    +' and instantiated by lia.Class.create or lia.Class.createAndRegister'
                )
                    .addContext('instance', objectToCheck)
                    .fire()
                ;
            }
        },

        /**
         * @param {$lia.Class} objectToCheck
         * @param {string[]}   interfaceNames
         */
        checkForInterfaces : function (objectToCheck, interfaceNames)
        {
            this.checkForLiaClass(objectToCheck);

            if(!$.isArray((interfaceNames))) {
                $lia.Exception
                    .create('The second argument must be an array')
                    .fire()
                ;
            }

            $lia.forEach(interfaceNames, function (interfaceName) {
                if ($.inArray(interfaceName, this.interfaces) < 0) {
                    var e = $lia.Exception
                        .create(
                        'Interface [ {name} ] must be implemented'
                        +' on definition of this instance'
                        ,
                        {name: interfaceName}
                    );

                    // If instance is registered we display his name in context
                    if(objectToCheck.__getInstanceName){
                        e.addContext('instanceName', objectToCheck.__getInstanceName());
                    }

                    e.addContext('instance'  , objectToCheck);
                    e.addContext('definition', objectToCheck.definer);
                    e.fire();
                } else {
                    $lia.Interface.check(interfaceName, objectToCheck);
                }
            }, this);
        },

        /**
         * @private
         * @param {$lia.Class} objectToCheck
         */
        _checkForInterfacesMethods : function (objectToCheck)
        {
            this.checkForLiaClass(objectToCheck);

            $lia.forEach(objectToCheck.definer.interfaces, function (interfaceName) {
                $lia.Interface.check(interfaceName, objectToCheck);
            }, this);
        },

        /**
         * Add a Global variable (scope of this object)
         * @param {string} name
         * @param {*}      value
         * @return {$lia.Class.definition}
         */
        addGlobalVar : function (name, value) {
            this.globalVars[name] = value;
            return this;
        },

        /**
         * Get a Global variable (scope of this object)
         * @param {string} name
         * @return {*}
         */
        getGlobalVar : function (name) {
            // TODO : Make test or Exception if the key is unknown
            return this.globalVars[name];
        },

        /**
         * Delete a Global variable (scope of this object)
         * @param {string} name
         * @return {$lia.Class.definition}
         */
        deleteGlobalVar : function (name) {
            // TODO : Make a test or Exception if the key is unknown
            delete(this.globalVars[name]);
            return this;
        },

        /**
         *
         * @param {[]}   [interfaces]
         * @param {{}[]} [inheritances]
         * @param {{}}   [primary]
         * @return {$lia.Class.definition}
         */
        define : function (interfaces, inheritances, primary) {
            this.defineInterface.apply(this, interfaces);
            this.defineInheritance.apply(this, inheritances);
            this.definePrimary(primary);
            return this;
        },

        /**
         * Defines the interface who are implemented
         * @return {$lia.Class.definition}
         */
        defineInterface : function () {
            if(arguments.length) {
                $lia.forEach(arguments, function (interfaceName) {
                    // TODO : Make an Exception if each item of arguments is not an Interface object
                    this.interfaces.push(interfaceName);
                }, this);
            }
            return this;
        },

        /**
         *
         * @return {$lia.Class.definition}
         */
        defineInheritance : function () {
            //TODO : Make an Exception if each item of arguments is not an object
            this.inheritances = arguments;
            return this;
        },

        /**
         * @private
         * @param {boolean} buildIfNot
         * @return {boolean}
         */
        _isAlreadyBuilt : function (buildIfNot) {
            if (!this.alreadyBuilt && buildIfNot) {
                this.makeExtends();
            }
            return this.alreadyBuilt;
        },

        /**
         *
         * @param {object} primary
         * @return {$lia.Class.definition}
         */
        definePrimary : function (primary) {
            //TODO : Make an Exception if primary is not an object
            this.primary = primary;
            return this;
        },

        /**
         * Make the final object who will contain all methods with inheritance done
         * @return {void}
         */
        makeExtends : function () {
            $lia.forEach(this.inheritances, function (inheritance) {
                var extended;
                // If inheritance is a LiaDefinitionClass (defined with $lia.Class.register)
                // then we take the extended properties, methods and interfaces of object
                if(!$lia.isLiaDefinitionClass(inheritance)) {
                    extended = inheritance;
                }
                else{
                    // We make sure than the class is built otherwise we force the build
                    inheritance._isAlreadyBuilt(true);

                    // We take the extended parent object (will all inheritances)
                    extended = inheritance.extended;

                    // Get an set the parent interfaces
                    $lia.forEach(inheritance.interfaces, function(interfaceName){
                        if($.inArray(interfaceName, this.interfaces) == -1){
                            this.interfaces.push(interfaceName);
                        }
                    }, this)
                }
                // We extend the extended object (apply inheritance)
                $.extend(this.extended, extended);
            }, this);

            // And finally We extend the extended object with the primary object
            $.extend(this.extended, this.primary);

            // We flag the definition like built.
            this.alreadyBuilt = true;
        }
    }
};