$(document).ready(function() {

  //чекбокс
  $('.filter-checkbox').change(function() {
    var selectedFilters = []; 
    $('.filter-checkbox:checked').each(function() {
       selectedFilters.push($(this).val());
       console.log(selectedFilters)
     });
     if (selectedFilters.length === 0) {
      $('.item').show();
    } else {
      $('.item').each(function() {
         var item = $(this);
         console.log(item);
         var hasFilter = false;
         for (var i = 0; i < selectedFilters.length; i++) {
          if (item.hasClass(selectedFilters[i])) {
            hasFilter = true;
            break;
          }
        }
        if (hasFilter) {
          item.show();
        } else {
          item.hide();
        }
      });
    };
  });


})