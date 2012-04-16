jQuery(function ($) {

    var CollectionForm = function (container) {
        var self = this;
        var container = $(container);
        var addButton = $('<a href="#" class="btn btn-primary">+</a>');
        container.after(addButton);
        // When the link is clicked we add the field to input another element

        self.removeElementForm = function(event){
            event.preventDefault();
            $(this).parent().remove();
        };

        self.addElementForm = function(event){
            event.preventDefault();
            var prototype = container.attr('data-prototype');
            var form = $(prototype.replace(/\$\$name\$\$/g, container.children().length));
            var removeButton = form.find('.remove-element');
            removeButton.on('click', self.removeElementForm);
            form.prepend(removeButton);
            container.append(form);
        };

        addButton.on('click', self.addElementForm);
        container.find('.remove-element').on('click', self.removeElementForm);
    };
    $.fn.collectionForm = function (options) {
        return this.each(function () {
            new CollectionForm(this);
        });
    };

    $('*[data-prototype]').collectionForm();
});