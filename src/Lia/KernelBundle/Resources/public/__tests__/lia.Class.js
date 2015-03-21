
// Define one interface
$lia.Interface.register('Ajax.scheduler.subscriber', ['getName']);

// Register a class
scheduler = $lia.Class.register('Ajax.scheduler');

// Define an object who will can be instantiated like a $Lia.Class
scheduler.definePrimary({
    'store'   : {},
    'iterate' : function(){

    },
    'set'    : function(subscriber){
        this.__checkForInterfaces(subscriber, ['Ajax.scheduler.subscriber']);

        this.store[subscriber.getName()] = subscriber;
    }
});

// Register another class
subscriber = $lia.Class.register('Ajax.scheduler.subscriber');

// Define implementation of one interface
subscriber.defineInterface('Ajax.scheduler.subscriber');

// Define an object who will can be instantiated like a $Lia.Class
subscriber.definePrimary({
    'geztName' : function(){}
});

// Exemple using two class
scheduler.create().set(subscriber.create());