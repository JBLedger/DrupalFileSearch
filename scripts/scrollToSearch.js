/*

    This script scrolls the content tag (trId) to the search criteria tag (searchTextId)

*/
function scrollData (searchTextId, trId) {
    
    /*
    Because thisfunction isinitiated from a link reference, the 
    default behaviour (linking to a new page) needs to be prevented.
    */
    event.preventDefault();
    
    //setup required variables (contents and search criteria)
    var $container = $(document.getElementById(trId));
    var $scrollTo = $(document.getElementById(searchTextId));
    
    //perform the animated scroll
    $container.animate({ scrollTop: $scrollTo.offset().top - $container.offset().top + $container.scrollTop() - 190});
    
}

