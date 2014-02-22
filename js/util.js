// General Utilities
// Copyright 2013, Adarsh Uppula

String.prototype.trunc = String.prototype.trunc ||
    function (n) {
        return this.length > n ? this.substr(0, n - 1) + '...' : this;
    };

String.prototype.trunce = String.prototype.trunce ||
    function (n) {
        return this.length > n ? this.substr(0, n - 1) + '...' : this + "...";
    };


function getEpoch() {
    return (new Date).getTime();
}

function vardefined(v) {
    return typeof v !== 'undefined';
}

jQuery.fn.visible = function() {
    return this.css('visibility', 'visible');
};

jQuery.fn.invisible = function() {
    return this.css('visibility', 'hidden');
};

jQuery.fn.visibilityToggle = function() {
    return this.css('visibility', function(i, visibility) {
        return (visibility == 'visible') ? 'hidden' : 'visible';
    });
};