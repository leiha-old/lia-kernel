$lia.Debug = {
    'debugMode'    : true,
    'consoleTypes' : ['log','info','warn','error'],

    /**
     * @param {boolean} enable
     */
    'enableDebugMode' : function(enable){
        this.debugMode = !!enable;
    },

    /**
     * @param {string} label
     * @param {*}      data
     */
    'fireGroup' : function(label, data){
        // TODO : Check if console exist
        console.group(label);
        switch ($.type(data)) {
            case 'object' :
                $lia.forEach(data, function (v, i) {
                    console.log(i, v);
                });
                break;
            case 'array'  :
                console.table(data);
                break;
            default       :
                console.log(data);
                break;
        }
        console.groupEnd();
    },

    /**
     * @param {string} msg
     */
    'fireNativeError' : function(msg){
        throw new Error(msg);
    }
};