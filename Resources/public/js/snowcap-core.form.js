SnowcapCore.Form = (function($) {

    /**
     * Form Collection view
     * Used to manage Symfony form collection type
     *
     */
    var Collection = Backbone.View.extend({
        $container: null,
        dataPrototype: null,
        events: {
            'click .add-element': 'addItem', // Legacy
            'click *[data-core=form-collection-add]': 'addItem',
            'click .remove-element': 'removeItem', // Legacy
            'click [data-core=form-collection-remove]': 'removeItem'
        },
        /**
         * Initialize
         *
         */
        initialize: function() {
            this.$widget = this.$el.find('[data-prototype]');
            this.dataPrototype = this.$widget.data('prototype');
        },
        /**
         * Remove a collection item
         *
         * @param event
         */
        removeItem: function(event) {
            event.preventDefault();
            var
                $target = $(event.currentTarget),
                $collectionItem;

            $collectionItem = $target.parents('[data-core=form-collection-item]');
            if(0 === $collectionItem.length) {
                $collectionItem = $target.parent();
            }
            $collectionItem.remove();
        },
        /**
         * Add a collection item
         *
         * @param event
         */
        addItem: function(event) {
            event.preventDefault();
            var $form = $($.trim(this.dataPrototype.replace(/__name__/g, this.$widget.children().length)));
            this.$widget.append($form);

            this.trigger('form:collection:add', $form);
        }
    });

    /**
     * Form collection factory function
     *
     * @param $context
     */
    var collectionFactory = function() {
        var $context = (0 === arguments.length) ? $('body') : arguments[0];
        $context.find('[data-core=form-collection]').each(function(offset, container) {
            new Collection({el: $(container)});
        });
    };

    return {
        Collection: Collection,
        collectionFactory: collectionFactory
    };

})(jQuery);

jQuery(document).ready(function() {

    SnowcapCore.Form.collectionFactory();

});