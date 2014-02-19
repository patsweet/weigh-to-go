var app = app || {};

app.Track = Backbone.Model.extend({
    defaults: {
        tuesday: false,
        wednesday: false,
        thursday: false,
        friday: false,
        saturday: false,
        sunday: false,
        monday: false,
        weight: 0.0,
        track_date: new Date()
    }
});