jQuery(function ($) {

    var CollectionForm = function (container) {
        var self = this;
        var container = $(container);
        var widget = $(container).find('[data-prototype]');
        // When the link is clicked we add the field to input another element

        self.removeElement = function(event){
            event.preventDefault();
            $(this).parent().remove();
        };

        self.addElement = function(event){
            event.preventDefault();
            var prototype = widget.attr('data-prototype');
            var form = $($.trim(prototype.replace(/__name__/g, widget.children().length)));
            var removeButton = form.find('.remove-element');
            removeButton.on('click', self.removeElement);
            widget.append(form);
            container.trigger('new_collection_item');
        };

        container.find('.add-element, [data-core=form-collection-add]').on('click', self.addElement);
        widget.find('.remove-element, [data-core=form-collection-remove]').on('click', self.removeElement);
    };
    $.fn.collectionForm = function (options) {
        return this.each(function () {
            new CollectionForm(this);
        });
    };

    $('[data-core=form-collection-container]').collectionForm();
});