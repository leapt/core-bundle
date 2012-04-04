jQuery(function ($) {

    var CollectionForm = function (container) {
        var self = this;
        var container = $(container);
        var button = $('<a href="#" class="btn btn-primary">+</a>');
        container.after(button);
        // When the link is clicked we add the field to input another element
        button.click(function (event) {
            event.preventDefault();
            self.addElementForm();
        });
        self.addElementForm = function(){
            var prototype = container.attr('data-prototype');
            var form = prototype.replace(/\$\$name\$\$/g, container.children().length);
            container.append(form);
        }
    };
    $.fn.collectionForm = function (options) {
        return this.each(function () {
            new CollectionForm(this);
        });
    };

    $('*[data-prototype]').collectionForm();
});