jQuery(document).ready(function($) {
    // Code that uses jQuery's $ can follow here.
$('.add_to_favorites').on('click', function(){

 document.cookie='favorite-'+$(this).attr('data-id')+ '=' + $(this).attr('data-id') + '; path=/;' ;

})

$('.remove_from_favorites').on('click', function(){

 document.cookie='favorite-'+$(this).attr('data-id')+ '=""' + '; path=/; expires: -1' ;

})


});


