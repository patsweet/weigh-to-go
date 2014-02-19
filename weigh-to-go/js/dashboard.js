var app = app || {};

$(function() {
    var tracks = [
        {weight: 400.2, sunday: true, wednesday: false},
        {weight: 300.0, monday: true, tuesday: true},
        {weight: 200.2, sunday: true, wednesday: false},
        {weight: 100.0, monday: true, tuesday: true, wednesday: true, thursday: true, friday: true, saturday: true}
    ];
    // Backbone.emulateHTTP = true;
    Backbone.emulateJSON = true;
    new app.TrackingView();
});