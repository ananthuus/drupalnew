(function ($, Drupal) {

  "use strict";

  Drupal.behaviors.iplessWatching = {
    attach: function (context, settings) {
      var base = this;

      $("body", context).once('ipless_watching').each(function (e) {
        base.init(settings.ipless)
            .buildUi(context)
            .setWatch();
      });
    },
    init: function (options) {

      if ($.cookie("ipless.disabled") == 1) {
        this.status = 0;
      }
      else {
        this.status = this.options.default_status;
      }

      this.options = $.extend({}, this.options, options);
      return this;
    },
    watch: function () {
      if (this.getStatus() == 0) {
        return;
      }
      var base = this;
      Drupal.ajax({
            url: "/ipless/watching",
            submit: {
              libraries: this.getLibrariesKeys(),
              time: this.getLastModifiedTime()
            },
            success: function (data) {

              $.each(data, function (i, e) {

                base.updateLastModifiedTime(e.library);

                var out = e.output.replace("public://", "");
                var href = $('link[href*="' + out + '"').attr("href");
                var url = new URL(href, window.location.href);
                $('link[href="' + href + '"').attr("href", url.pathname + "?rev=" + Date.now());
              });

              base.setError(false).setWatch();
            },
            error: function (response) {
              var error = response.responseText;
              if(response.responseJSON !== undefined && response.responseJSON.error !== undefined){
                error = response.responseJSON.error;
              }
              base.setError(error).setWatch();
            }
          }
      ).execute();
    },
    setWatch: function () {
      this.timer = setTimeout($.proxy(this.watch, this), this.options.refresh_rate);
      return this;
    },
    clearWatch: function () {
      clearTimeout(this.timer);
    },
    getLastModifiedTime: function () {
      var last_m_time = 0;
      $.each(this.getLibraries(), function (i, e) {
        if (e.last_m_time > last_m_time) {
          last_m_time = e.last_m_time;
        }
      });
      return last_m_time;
    },
    updateLastModifiedTime: function (library) {
      var base = this;
      var time = Math.floor(Date.now() / 1000);
      $.each(base.getLibraries(), function (i, e) {
        if (e.library == library) {
          base.options.libraries[i].last_m_time = time;
        }
      });
      return this;
    },
    getLibraries: function () {
      return this.options.libraries;
    },
    getLibrariesKeys: function () {
      var libraries = [];
      $.each(this.getLibraries(), function (i, e) {
        libraries.push(e.library);
      });
      return libraries;
    },
    toggleStatus: function () {
      switch (this.getStatus()) {
        case 0:
          this.status = 1;
          $.cookie("ipless.disabled", 0);
          this.setWatch();
          break;
        case 1:
          this.status = 0;
          $.cookie("ipless.disabled", 1);
          this.clearWatch();
          break;
      }
      return this;
    },
    getStatus: function () {
      return this.status;
    },
    setError: function (message) {
      if (message === false) {
        this.error = 0;
        this.error_message = "";
      }
      else {
        this.error = 1;
        this.error_message = message;
      }
      this.uiRefreshStatus();
      return this;
    },
    buildUi: function (context) {
      var base = this;
      base.$ui = $(Drupal.theme("ipless_ui"));
      $("body", context).append(base.$ui);

      base.uiRefreshStatus();

      $(".handle", base.$ui).on("click", function () {
        base.toggleStatus().uiRefreshStatus();
      });

      // Webprofiler loaded.
      if($(".sf-toolbar").length > 0){
        base.$ui.addClass("profiler-loaded");
      }

      return this;
    },
    uiRefreshStatus: function () {
      var $status = $(".status", this.$ui);
      switch (this.getStatus()) {
        case 0:
          $status.removeClass("play").addClass("pause");
          break;
        case 1:
          $status.removeClass("pause").addClass("play");
          break;
      }

      if (this.error) {
        $status.addClass("error").attr("title", this.error_message);
      }
      else {
        $status.removeClass("error").attr("title", "");
      }
      return this;
    },
    status: 0,
    error: 0,
    error_message: "",
    timer: null,
    options: {
      libraries: [],
      refresh_rate: 2000,
      default_status: 1
    }
  };

  Drupal.theme.ipless_ui = function () {
    return '<div class="ipless-ui"><div class="handle">{Less} <span class="status"></span></div></div>';
  };

})(jQuery, Drupal);
