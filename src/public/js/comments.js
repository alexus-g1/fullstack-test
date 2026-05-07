(function ($) {
  "use strict";

  var state = {
    sort: "id",
    dir: "desc",
    page: 1,
  };

  var $list = $("#comments-list");
  var $form = $("#comment-form");
  var $status = $("#form-status");
  var $overlay = $("#loading-overlay");
  var $message = $overlay.find(".loading-message");

  var csrfTokenName = $('meta[name="csrf-token-name"]').attr("content");

  var loadingDepth = 0;
  var loadingTimer = null;
  var SHOW_DELAY_MS = 200;

  function showLoading(message) {
    loadingDepth += 1;
    $message.text(message || "Подождите…");

    if (loadingTimer === null && !$overlay.hasClass("is-visible")) {
      loadingTimer = setTimeout(function () {
        loadingTimer = null;
        if (loadingDepth > 0) {
          $overlay.addClass("is-visible").attr("aria-hidden", "false");
        }
      }, SHOW_DELAY_MS);
    }
  }

  function hideLoading() {
    loadingDepth = Math.max(0, loadingDepth - 1);
    if (loadingDepth === 0) {
      if (loadingTimer !== null) {
        clearTimeout(loadingTimer);
        loadingTimer = null;
      }
      $overlay.removeClass("is-visible").attr("aria-hidden", "true");
    }
  }

  function getCsrfHash() {
    return $('meta[name="csrf-token-hash"]').attr("content");
  }

  function setCsrfHash(hash) {
    if (hash) {
      $('meta[name="csrf-token-hash"]').attr("content", hash);
    }
  }

  function isValidEmail(value) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(String(value).trim());
  }

  function clearFieldErrors() {
    $form.find("[data-error-for]").text("");
    $form.find(".is-invalid").removeClass("is-invalid");
  }

  function showFieldError(field, message) {
    $form.find('[name="' + field + '"]').addClass("is-invalid");
    $form.find('[data-error-for="' + field + '"]').text(message);
  }

  function showFieldErrors(errors) {
    clearFieldErrors();
    $.each(errors, function (field, message) {
      showFieldError(field, message);
    });
  }

  function reloadList(message) {
    showLoading(message || "Загружаем список…");

    return $.ajax({
      url: "/comments/list",
      method: "GET",
      data: { sort: state.sort, dir: state.dir, page: state.page },
      dataType: "json",
    })
      .done(function (resp) {
        if (resp && resp.success) {
          $list.html(resp.html);
          if (typeof resp.page === "number") {
            state.page = resp.page;
          }
        }
      })
      .always(function () {
        hideLoading();
      });
  }

  $("#sort-field").on("change", function () {
    state.sort = $(this).val();
    state.page = 1;
    reloadList();
  });

  $("#sort-dir").on("change", function () {
    state.dir = $(this).val();
    state.page = 1;
    reloadList();
  });

  $list.on("click", ".js-page", function (e) {
    e.preventDefault();
    var $li = $(this).closest("li");
    if ($li.hasClass("disabled") || $li.hasClass("active")) {
      return;
    }
    var href = $(this).attr("href") || "";
    var match = href.match(/[?&]page=(\d+)/);
    state.page = match ? parseInt(match[1], 10) : 1;
    reloadList();
  });

  $list.on("click", ".js-delete", function () {
    var $btn = $(this);
    var id = parseInt($btn.data("id"), 10);
    if (!id) {
      return;
    }

    var data = {};
    data[csrfTokenName] = getCsrfHash();

    $btn.prop("disabled", true);
    showLoading("Удаляем комментарий…");

    $.ajax({
      url: "/comments/delete/" + id,
      method: "POST",
      data: data,
      dataType: "json",
    })
      .done(function (resp) {
        setCsrfHash(resp && resp.csrf_hash);
        if (resp && resp.success) {
          reloadList("Обновляем список…");
        } else {
          alert((resp && resp.message) || "Не удалось удалить комментарий.");
          $btn.prop("disabled", false);
        }
      })
      .fail(function (xhr) {
        var resp = xhr.responseJSON;
        setCsrfHash(resp && resp.csrf_hash);
        alert((resp && resp.message) || "Ошибка при удалении.");
        $btn.prop("disabled", false);
      })
      .always(function () {
        hideLoading();
      });
  });

  $form.on("submit", function (e) {
    e.preventDefault();
    clearFieldErrors();
    $status.text("");

    var name = $form.find('[name="name"]').val().trim();
    var text = $form.find('[name="text"]').val().trim();

    var clientErrors = {};
    if (!name) {
      clientErrors.name = "Укажите email.";
    } else if (!isValidEmail(name)) {
      clientErrors.name = "Введите корректный email.";
    }
    if (!text) {
      clientErrors.text = "Текст комментария обязателен.";
    }

    if (Object.keys(clientErrors).length > 0) {
      showFieldErrors(clientErrors);
      return;
    }

    var $submit = $form.find('button[type="submit"]');
    $submit.prop("disabled", true);
    showLoading("Сохраняем комментарий…");

    var payload = { name: name, text: text };
    payload[csrfTokenName] = getCsrfHash();

    $.ajax({
      url: "/comments",
      method: "POST",
      data: payload,
      dataType: "json",
    })
      .done(function (resp) {
        setCsrfHash(resp && resp.csrf_hash);
        if (resp && resp.success) {
          $form[0].reset();
          $status.text(resp.message || "Готово.").css("color", "#28a745");
          state.page = 1;
          reloadList("Обновляем список…").always(function () {
            setTimeout(function () {
              $status.text("");
            }, 2000);
          });
        }
      })
      .fail(function (xhr) {
        var resp = xhr.responseJSON;
        setCsrfHash(resp && resp.csrf_hash);
        if (resp && resp.errors) {
          showFieldErrors(resp.errors);
        } else {
          $status.text((resp && resp.message) || "Ошибка отправки.").css("color", "#dc3545");
        }
      })
      .always(function () {
        $submit.prop("disabled", false);
        hideLoading();
      });
  });
})(jQuery);
