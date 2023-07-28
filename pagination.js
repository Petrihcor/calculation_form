//товары
$(document).ready(function() {
  var products = [ // Статический массив объектов товаров
    { id: 1, name: 'Аква ИС1', type:'prom', img:'<img src="/img/Rectangle101.png">', description:'Ингибитор осадкообразования (антискалант) для обратного осмоса, ингибитор коррозии'},
    { id: 2, name: 'Аква ИС2', type:'kons', img:'<img src="/img/Rectangle101.png">', description:'Ингибитор осадкообразования (антискалант) для обратного осмоса, ингибитор коррозии'},
    { id: 3, name: 'Аква ИС3', type:'osad', img:'<img src="/img/Rectangle101.png">', description:'Ингибитор осадкообразования (антискалант) для обратного осмоса, ингибитор коррозии'},
    { id: 4, name: 'Аква ИС1', type:'prom', img:'<img src="/img/Rectangle101.png">', description:'Ингибитор осадкообразования (антискалант) для обратного осмоса, ингибитор коррозии'},
    { id: 5, name: 'Аква ИС2', type:'kons', img:'<img src="/img/Rectangle101.png">', description:'Ингибитор осадкообразования (антискалант) для обратного осмоса, ингибитор коррозии'},
    { id: 6, name: 'Аква ИС3', type:'osad', img:'<img src="/img/Rectangle101.png">', description:'Ингибитор осадкообразования (антискалант) для обратного осмоса, ингибитор коррозии'},
    { id: 7, name: 'Аква ИС1', type:'prom', img:'<img src="/img/Rectangle101.png">', description:'Ингибитор осадкообразования (антискалант) для обратного осмоса, ингибитор коррозии'},
    { id: 8, name: 'Аква ИС2', type:'kons', img:'<img src="/img/Rectangle101.png">', description:'Ингибитор осадкообразования (антискалант) для обратного осмоса, ингибитор коррозии'},
    { id: 9, name: 'Аква ИС3', type:'osad', img:'<img src="/img/Rectangle101.png">', description:'Ингибитор осадкообразования (антискалант) для обратного осмоса, ингибитор коррозии'},
  ];

  var currentPage = 1; // Текущая страница
  var itemsPerPage = 6; // Количество товаров на странице

  // Функция для загрузки товаров на выбранную страницу
  function loadProducts(page) {
    // Определите индексы начала и конца для выбранной страницы
    var startIndex = (page - 1) * itemsPerPage;
    var endIndex = startIndex + itemsPerPage;

    // Получите товары для выбранной страницы
    var pageProducts = products.slice(startIndex, endIndex);

    // Очистите содержимое элемента с id "product-list"
    $('#product-list').empty();

    // Отобразите товары для выбранной страницы
    $.each(pageProducts, function(index, product) {
      var productCard = $('<li class="col-4 item ' + product.type + '">' +
        '<div class="card">' + '<h3>' + product.name + '</h3>' 
        + product.img + 
        '<p>' + product.description + 'p' + 
        '<a href="#">Выбрать обьем</a>'
        + '</div>' +
        '</li>');

      // Добавьте карточку товара в элемент с id "product-list"
      $('#product-list').append(productCard);
    });
  }

  // Функция для отображения пагинации
  function renderPagination(totalPages) {
    // Очистите содержимое элемента с id "pagination"
    $('#pagination').empty();

    // Создайте элементы пагинации для каждой страницы
    for (var i = 1; i <= totalPages; i++) {
      var pageLink = $('<a href="#">' + i + '</a>');

      // Добавьте обработчик события клика на каждую страницу
      pageLink.click(function() {
        var page = $(this).text(); // Получите номер выбранной страницы
        currentPage = parseInt(page); // Обновите текущую страницу
        loadProducts(currentPage); // Загрузите товары для выбранной страницы
      });

      // Добавьте элемент пагинации в элемент с id "pagination"
      $('#pagination').append(pageLink);
    }
  }

  // Загрузите и отобразите первую страницу товаров при загрузке страницы
  loadProducts(currentPage);

  // После загрузки товаров получите общее количество страниц и отобразите пагинацию
  var totalItems = products.length; // Общее количество товаров
  var totalPages = Math.ceil(totalItems / itemsPerPage); // Общее количество страниц
  renderPagination(totalPages);
});
