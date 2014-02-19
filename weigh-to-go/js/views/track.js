var app = app || {};

app.TrackView = Backbone.View.extend({
    tagName: 'div',
    className: 'trackContainer',
    template: _.template( $( '#trackingTemplate' ).html() ),

    initialize: function(attrs) {
        _.bindAll(this, 'render');
    },

    render: function() {
        this.$el.html( this.template( this.model.toJSON() ) );
        return this;
    }
});