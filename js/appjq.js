$(document).ready(function () {

//$("li").text("xa");

    $('.vacation').on('click','button',function () {
        var vacationClicked =  $(this).closest('.vacation');
        var amount = vacationClicked.data('price');
        var price = $('<p> ' + amount + '</p>');
        vacationClicked.append(price);
        $(this).remove();
    } );


    $("#filters").on('click', '.onsale-filter', function () {
        $('.vacation').filter('.onsale').addClass('highlight');
    });

});