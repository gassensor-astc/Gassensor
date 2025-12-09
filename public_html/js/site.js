$(function() {
  console.log('site.js app');

  //https://getbootstrap.com/docs/5.1/components/tooltips/#example-enable-tooltips-everywhere
  // var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  // tooltipTriggerList.map(function (tooltipTriggerEl) {
  //   return new bootstrap.Tooltip(tooltipTriggerEl)
  // })

  // var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
  // popoverTriggerList.map(function (popoverTriggerEl) {
  //   return new bootstrap.Popover(popoverTriggerEl)
  // })

  function getNews() {
    let csrfToken =  $("meta[name='csrf-token']").attr("content");

    $.ajax({
      url: 'ajax/mainnews',
      method: "GET",
      dataType: "json",
      success: (resp) => {
        //console.log(resp);
        $('#main_news').html(resp.html);
      }
    })
  }

  function initAjaxActiveForm(formSelector, config, success, err) {
    try {
      yii2AjaxRequest(formSelector, config,
        success,
        (error) =>  {
          console.log('error', error);
        }
      );
    } catch(e) {
      if (e instanceof ReferenceError) {
        console.log('catch', e.message);
        return;
      } else if ('The reference to form element is invalid!' === e) {
        console.log('catch', e, formSelector);
        return;
      }

      throw e;
    }
  }

  var selector1 = '.add-product-to-cart-wrap';
  var modalWrapAddCart = $('.modal', selector1);
  var inputCount = $('td input.cart-item-count');

  function changeCount(sender) {
    $.ajax({
      url: '/cart/set-count',
      data: {
        id: $(sender).data('id'),
        count: $(sender).val()
      },
      success: (resp) => {
        console.log(resp);
      }
    })

  }

  function refreshCartCount() {
    $('#cartTotalNum .val').load('/cart/get-total-count');
  }

  $('.btn-continue', modalWrapAddCart).on('click', () => {
    refreshCartCount();
  });

  inputCount.change((e) => {
    console.log('change!', $(e.target).val());
    changeCount(e.target);
  });

  initAjaxActiveForm(selector1 + ' form',
    {},
    (resp) => {
      console.log('resp', resp);

      if (resp.status != 200) {
        alert(resp.data.message);
        return;
      }

      var modal = bootstrap.Modal.getInstance(modalWrapAddCart);

      inputCount.val(resp.data.count).data('id', resp.data.id);

      modal.show();
    }
  );

  var selector2 = '.widget-gaz-converter form';
  initAjaxActiveForm(selector2,
    {resetForm: false},
    (success) => {
    console.log('success', success);

    if (success.status != 200) {
      alert(success.data.message);
      return;
    }

    for (const [key, value] of Object.entries(success.data.attributes)) {
      console.log(`${key}: ${value}`);
      $('input[name="GazConverterForm[' + key + ']"][type="text"]', selector2).val(value);
    }
  });

  $('input[type="text"]', selector2).click(function() {
    console.log('click!', this, $(this).data('type'));
    var radio = $("input:radio[value='" + $(this).data('type') + "']", selector2);
    radio.prop("checked", true);
  });

  const filterWrap = $('.filter-wrap'),
        filterForm = $('form', filterWrap);

  function getPopover(owner, html) {
    return owner.popover({
      trigger: 'focus',
      html: true,
      content: html
    }).on('hidden.bs.popover', function() {
      console.log('hidden.bs.popover!', this);
      $(owner).popover('dispose');
    });

  }

  function sendFilter(sender) {
    const url = filterForm.attr('action');
    console.log(url, filterForm.serialize());

    $(sender).popover('dispose');//destroy if exists

    $.ajax({
      type: "POST",
      url: filterForm.attr('action'),
      data: filterForm.serializeArray(),
      success: function(resp) {
        console.log(resp);

        getPopover($(sender), resp).popover('show');
      }
    })

  }

  $('input,select', filterForm).change(function() {
    console.log('change!');
    sendFilter(this);
  });

  function setCookie(name, value, days) {
    let expires = "";
    if (days) {
      let date = new Date();
      date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
      expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
  }

  function getCookie(name) {
    let matches = document.cookie.match(new RegExp("(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"));
    return matches ? decodeURIComponent(matches[1]) : undefined;
  }


  function checkCookies() {
    let cookieNote = document.getElementById('cookie_note');
    let cookieBtnAccept = cookieNote.querySelector('.cookie_accept');

    // Если куки cookies_policy нет или она просрочена, то показываем уведомление
    if (!getCookie('cookies_policy')) {
      cookieNote.classList.add('show');
    }

    // При клике на кнопку устанавливаем куку cookies_policy на один год
    cookieBtnAccept.addEventListener('click', function () {
      setCookie('cookies_policy', 'true', 365);
      cookieNote.classList.remove('show');
    });
  }

  checkCookies();

  if (window.location.pathname === "/") {
    getNews();
  }
});