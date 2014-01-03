SnowcapCore.Form = (function($) {

    /**
     * Form Collection view
     * Used to manage Symfony form collection type
     *
     */
    var Collection = Backbone.View.extend({
        $container: null,
        $form: null,
        $widget: null,
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
            this.$form = this.$el.parents('form');
            this.$widget = this.$el.find('[data-prototype]').first();
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

            this.trigger('form:collection:remove');
            this.$form.trigger('change');
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
            this.$form.trigger('change');
        }
    });

    /**
     * Form collection factory function
     */
    var collectionFactory = function () {
        var context = arguments[0] || 'body';
        $('[data-core=form-collection]', context).each(function (offset, container) {
            if (!$(container).data('widget')) {
                $(container).data('widget', new Collection({'el': container}));
            }
        });
    };

    var Manager = Backbone.View.extend({
        events: {
            'change': 'onChange'
        },
        initialize: function () {
            this.factories = [];
            this.hasChanged = false;
        },
        registerFactory: function(factory) {
            if(_.isFunction(factory)) {
                this.factories.push(factory);
                factory(this.$el);
            } else {
                throw "To register a widget factory into form manager is has to be a function";
            }
        },
        onChange: function (event) {
            this.hasChanged = true;
            this.updateViews();
        },
        updateViews: function () {
            _.each(this.factories, function (factory) {
                factory(this.$el);
            }, this);
        }
    });

    return {
        Manager: Manager,
        Collection: Collection,
        factories: {
            collectionFactory: collectionFactory
        },
        instances : {
            managers: []
        }
    };

})(jQuery);

jQuery(document).ready(function () {
    $('[data-core=form-manager]').each(function (i, element) {
        if (!$(element).data('widget')) {
            var manager = new SnowcapCore.Form.Manager({el: element});
            _.each(SnowcapCore.Form.factories, function(factory) {
                manager.registerFactory(factory);
            });
            SnowcapCore.Form.instances.managers.push(manager);
            $(element).data('widget', manager);
        }
    });
});
