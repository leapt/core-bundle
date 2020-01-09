jQuery(function ($) {

    //Track Pageview events
    $(document).ready(function(event){
        $('*[data-track=Pageview]').on('click', function (event) {
            var destination = $(this).attr('href');
            event.preventDefault();
            try {
                _gaq.push(['_trackPageview', $(this).attr('data-action')]);
                setTimeout(function(){
                    window.location.href = destination;
                }, 100);
            } catch (err) {}
        });
    })
});