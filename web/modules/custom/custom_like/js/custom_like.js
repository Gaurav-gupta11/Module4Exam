(function ($, Drupal) {
  Drupal.behaviors.customLike = {
    attach: function (context, settings) {
      $(".like-button", context).click(function () {
        var nodeId = $(this).data("nodeId");
        $.ajax({
          url: "/custom_like/like/" + nodeId,
          method: "POST",
        });
      });
    },
  };
})(jQuery, Drupal);
