$lia.Exception = {

    /**
     * @param {string} [msg]
     * @param {{}}     [vars]
     * @return {$lia.Exception.builder}
     */
    'create' : function(msg, vars){
        return new this.builder(msg, vars);
    },

    /**
     * @constructor
     * @param {string} msg
     * @param {{}}     vars
     * @return {$lia.Exception.builder}
     */
    'builder' : function(msg, vars){
        /**
         * @private
         * @type {string}
         */
        this.msg = '';

        /**
         * @private
         * @type {{}}
         */
        this.vars = null;

        /**
         * @private
         * @type {[]}
         */
        this.context = [];

        /**
         * @param {{}} vars
         * @return {$lia.Exception.builder}
         */
        this.setVars = function(vars){
            if($.isPlainObject(vars)){
                this.vars = vars;
            }
            return this;
        };

        /**
         * @param {string} msg
         * @param {{}} vars
         * @return {$lia.Exception.builder}
         */
        this.setMessage = function(msg, vars){
            if(msg) {
                this.msg = msg;
            }
            this.setVars(vars);
            return this;
        };
        this.setMessage(msg, vars);

        /**
         *
         * @param {string} label
         * @return {$lia.Exception.builder}
         */
        this.setLabel = function(label){
            this.label = label ? label : 'Error';
            return this;
        };

        /**
         * @return {string}
         */
        this.getLabel = function(){
            return this.label ? this.label : 'Error';
        };

        /**
         * @return {string}
         */
        this.getMessage = function(){
            return this.vars
                ? this.msg.format(this.vars)
                : this.msg
                ;
        };

        /**
         * @param {string} key
         * @param {*} value
         * @return {$lia.Exception.builder}
         */
        this.addContext = function(key, value){
            this.context.push([key, value]);
            return this;
        };

        ///**
        // * @param {{} | []} context
        // * @return {$lia.Exception.builder}
        // */
        //this.setContext = function(context){
        //    if($.isPlainObject(context)
        //        || $.isArray(context)) {
        //        this.context = context;
        //    }
        //    return this;
        //};

        this.fire = function() {
            if($lia.Debug.debugMode){
                this._fireDebugMode();
                msg = '[ Stopping running script ]';
            } else {
                msg = this.getMessage();
            }
            // We fire native exception for simulate an exit;
            $lia.Debug.fireNativeError(msg);
        };

        /**
         * @private
         */
        this._fireDebugMode = function(){
            // TODO : Check if console exist
            console.group(this.getLabel());
            console.error(this.getMessage());
            if(this.context.length) {
                $lia.Debug.fireGroup('Context', this.context);
            }
            console.groupEnd();
        }
    }
};