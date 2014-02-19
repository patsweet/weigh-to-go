var app = app || {};

app.ManageView = Backbone.View.extend({
    el: "#tracker",
    tagName: 'div',
    className: 'trackingContainer',
    template: _.template( $("#trackTemplate").html() ),

    events: {
        'click #addTrack': 'addTrack'
    },

    initialize: function(collection) {
        _.bindAll(this, 'render', 'addTrack');
        this.collection = collection;
        this.render();
        this.listenTo(this.collection);
    },

    render: function() {
        this.$el.html( this.template( new app.Track().toJSON() ) );
        return this;
    },
    addTrack: function( e ) {
        e.preventDefault();

        var formData = {};

        $( '#trackForm' ).find( 'input' ).each( function(i, el) {
            if ( $(el).attr('type') == 'checkbox' && $(el).is(":checked") ) {
                formData[ el.name ] = true;
            }
            if ( $(el).attr('type') != 'checkbox' && $(el).val() !== '' ) {
                formData[ el.name ] = $(el).val();
            }
        });
        this.collection.create(
            formData,
            {
                wait: true,
                error: function(model, response) {
                    var responseObj = $.parseJSON(response.responseText);
                    alert(responseObj['error']);
                },
                success: function(model, response) {
                    model.render();
                }
            }
        );
    }
});