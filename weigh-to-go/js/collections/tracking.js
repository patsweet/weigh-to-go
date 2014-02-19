var app = app || {};

app.TrackingCollection = Backbone.Collection.extend({
    model: app.Track,
    url: 'api/tracks',

    // // Using @Peter Lyons' answer
    // add : function(tracks) {
    //     // For array
    //     tracks = _.isArray(tracks) ? tracks.slice() : [tracks]; //From backbone code itself
    //     for (var i = 0, length = tracks.length; i < length; i++) {
    //         var track = ((tracks[i] instanceof this.model) ? tracks[i]  : new this.model(tracks[i])); // Create a model if it's a JS object

    //         // Using isDupe routine from @Bill Eisenhauer's answer
    //         var isDupe = this.any(function(_track) {
    //             return Date.parse(_track.get('track_date')) === Date.parse(track.get('track_date'));
    //         });
    //         if (isDupe) {
    //             // Up to you either return false or throw an exception or silently
    //             // ignore
    //             return false;
    //         }
    //         Backbone.Collection.prototype.add.call(this, track);
    //    }
    // }

});