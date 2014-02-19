var app = app || {};

app.TrackingView = Backbone.View.extend({
    el: '#tracking',

    initialize: function() {
        _.bindAll(this, 'render', 'renderTrack', 'addTrack');
        this.collection = new app.TrackingCollection();
        this.collection.fetch({reset: true});
        this.listenTo( this.collection, 'add', this.renderTrack );
        this.listenTo( this.collection, 'reset', this.render );
        new app.ManageView(this.collection);
    },

    render: function() {
        this.collection.each(function( item ){
            this.renderTrack( item );
        }, this);
    },
    renderTrack: function( item ) {
        var trackView = new app.TrackView({
            model: item
        });
        this.$el.append( trackView.render().el );
    },

    addTrack: function( e ) {
        e.preventDefault();
        $( '#addTrack div' ).children( 'input' ).each( function(i, el) {
            if ( $(el).attr('type') == 'checkbox' && $(el).is(":checked") ) {
                console.log(el);
            }
        });
    }

});