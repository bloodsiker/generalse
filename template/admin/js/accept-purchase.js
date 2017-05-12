var json = [{"so":"121212","postavshchik":"Lenovo","author":"Подтягивается с сессии","sklad":"Склад 4","otdel":"Продаж","istochnik":"Dismantlign","startDate":"14/12/2016","endDate":"01/12/2016","valuta":"USD","comment":"121212","details":[{"partNumber":"11111111","nameProduct":"Lenovo a1000 23 233","edm":"шт","col":"12","price":"2.00","summ":"24.00","in":"0","out":"0"},{"partNumber":"2222222","nameProduct":"Lenovo a1000 23 233","edm":"шт","col":"3","price":"323.00","summ":"969.00","in":"0","out":"0"},{"partNumber":"3333333","nameProduct":"Lenovo a1000 23 233","edm":"шт","col":"12","price":"23.00","summ":"276.00","in":"0","out":"0"},{"partNumber":"55555555","nameProduct":"Lenovo a1000 23 233","edm":"шт","col":"1","price":"45.00","summ":"45.00","in":"0","out":"0"}],"status":"Не принята"},{"so":"121212","postavshchik":"Lenovo","author":"Подтягивается с сессии","sklad":"Склад 4","otdel":"Продаж","istochnik":"Dismantlign","startDate":"14/12/2016","endDate":"01/12/2016","valuta":"USD","comment":"121212","details":[],"status":"Не принята"},{"so":"121212","postavshchik":"Lenovo","author":"Подтягивается с сессии","sklad":"Склад 4","otdel":"Продаж","istochnik":"Dismantlign","startDate":"14/12/2016","endDate":"01/12/2016","valuta":"USD","comment":"121212","details":[],"status":"Не принята"}]

var IN_CHECKOUTS = json;
console.log(IN_CHECKOUTS);

// рендер корзин
renderCheckouts();

function renderCheckouts() {
   
   $('.accept-purchase tbody').html(function() {
      return IN_CHECKOUTS.map(function(el, index) {
         var bgc;
            if (el.status == 'Не принята') {
                bgc = 'background-color:rgba(244, 67, 54, 0.21)'
            } else bgc = 'background-color:rgba(76, 175, 80, 0.27);'
         return `<tr so-number='`+ el.so +`' data-index='`+ index +`' style='`+
            bgc
         +`'>
                     <td>` + el.so + `</td>` +
                     `<td>` + el.postavshchik + `</td>` +
                     `<td>` + el.author + `</td>` +
                     `<td>` + el.sklad + `</td>` +
                     `<td>` + el.otdel + `</td>` +
                     `<td>` + el.istochnik + `</td>` +
                     `<td>` + el.startDate + `</td>` +
                     `<td>` + el.endDate + `</td>` +
                     `<td>` + el.status + `</td>` +
                     `<td>` + el.comment + `</td>` +
                     `<td>
                        <button class="check-cout" data-index="`+index+`">
                           <i class="fi-check"></i>
                        </button>
                     </td>
                  </tr>`
      });
   });
}

// подтвердить корзину
$('body').on('click', '.check-cout', function(e) {
   var dataIndex = $(this).attr('data-index');
   IN_CHECKOUTS[dataIndex].status = 'Принята';
   console.log(IN_CHECKOUTS[dataIndex].status);
   $(this).parents('tr').addClass('green');
   renderCheckouts(); // перерендерить
});


// =========================================================


var dataIndex;

// открыть корзину
$('body').on('dblclick', '.accept-purchase table tbody tr', function() {
   $('.add-checkout-box').slideUp();

   dataIndex = $(this).attr('data-index');
   var so = $(this).attr('so-number');
   $('#checkout-item').foundation('open');
   $('#checkout-item h3').text('Информация по номеру покупки: ' + so);
   $("[check='true']").removeAttr('check');
   renderCheckoutItem(dataIndex); // рендер корзины, кидаем индекс
});

// рендер корзины
function renderCheckoutItem(dataIndex) {
   
   $('.added-checkout-list tbody').html(function() {
      var checkoutTable = '';
      IN_CHECKOUTS[dataIndex].details.map(function(index, elem) {
          checkoutTable = checkoutTable + `<tr pre-index='`+dataIndex+`' data-index='`+elem+`'>
                        <td>` + index.partNumber + `</td>
                        <td>` + index.nameProduct + `</td>
                        <td>` + index.edm + `</td>
                        <td class="col" contenteditable>` + index.col + `</td>
                        <td class="price" contenteditable>` + index.price + `</td>
                        <td class="summ" >` + index.summ + `</td>
                        <td >` + index.in + `</td>
                        <td >` + index.out + `</td>
                        <td><button class="check-item" data-index="`+ elem +`"><i class="fi-check"></i></button></td>
                     </tr>`
      });
      return checkoutTable;

   });
}


// выбор поля в таблице по клику
$(document).mouseup(function(e) {
   if ($('.accept-purchase table tbody tr').has(e.target).length === 0 && $('.field-result-search table tbody tr').has(e.target).length === 0) {
      $('tr').removeClass('blue').removeAttr('check');
   } else {
      $('tr').removeClass('blue').removeAttr('check');
      $('.field-result-search table tbody tr').has(e.target).addClass('blue').attr('check', 'true');
      $('.accept-purchase table tbody tr').has(e.target).addClass('blue').attr('check', 'true');
   }
});

// Фильтр button open
$('body').on('click', '#filter-button', function(e) {
   if ($(".filter").is(":hidden")) {
      $(".filter").show("slow");
   } else {
      $(".filter").slideUp();
   }
});

// Фильтр по номеру
$(document).ready(function() {
   $("#filter-number").keyup(function() {
      var filter = $(this).val(),
         count = 0;
      $(".checkout table td:first-child").each(function() {
         if ($(this).text().search(new RegExp(filter, "i")) < 0) {
            $(this).parent('tr').fadeOut();
         } else {
            $(this).parent('tr').show();
            count++;
         }
      });
   });
});

// Фильтр по дате
$(document).ready(function() {
   $("#filter-date").click(function() {
      var filter = $(this).val(),
         count = 0;
      $(".checkout table td:nth-child(6)").each(function() {
         if ($(this).text().search(new RegExp(filter, "i")) < 0) {
            $(this).parent('tr').fadeOut();
         } else {
            $(this).parent('tr').show();
            count++;
         }
      });
   });
});

// Фильтр по складу
$(document).ready(function() {
   $("#filter-stock").keyup(function() {
      var filter = $(this).val(),
         count = 0;
      $(".checkout table td:nth-child(4)").each(function() {
         if ($(this).text().search(new RegExp(filter, "i")) < 0) {
            $(this).parent('tr').fadeOut();
         } else {
            $(this).parent('tr').show();
            count++;
         }
      });
   });
});

// Фильтр по автору
$(document).ready(function() {
   $("#filter-author").keyup(function() {
      var filter = $(this).val(),
         count = 0;
      $(".checkout table td:nth-child(3)").each(function() {
         if ($(this).text().search(new RegExp(filter, "i")) < 0) {
            $(this).parent('tr').fadeOut();
         } else {
            $(this).parent('tr').show();
            count++;
         }
      });
   });
});