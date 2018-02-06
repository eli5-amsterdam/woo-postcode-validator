(function ($) {
  "use strict";

  // Blur handler for fields
  $("#billing_postcode, #shipping_postcode").live("blur.wooCommercePostcodeValidator", function (e) {
    var type, streetHander1, streetHandler2, cityHandler;
    var isDutchOrder = false;

    // Check target
    if (e.target.id === "billing_postcode") {
      type = "billing";
      streetHander1 = "#billing_street_name";
      streetHandler2 = "#billing_address_1";
      cityHandler = "#billing_city";
    } else if (e.target.id === "shipping_postcode") {
      type = "shipping";
      streetHander1 = "#shipping_street_name";
      streetHandler2 = "#shipping_address_1";
      cityHandler = "#shipping_city";
    }

    // Check if this is a Dutch order
    if (type === "billing") {
      if ($("#billing_country").val() === "NL") {
        isDutchOrder = true;
      }
    } else if (type === "shipping") {
      if ($("#shipping_country").val() === "NL") {
        isDutchOrder = true;
      }
    }

    // Add loading class
    if (isDutchOrder) {
      $(streetHander1 + "," + streetHandler2 + "," + cityHandler).addClass("woocommerce-postcode-validator-loading");

      // Get address from API
      $.ajax({
        url: woocommerce_postcode_validator_ajax_url,
        type: "GET",
        data: {
          "action": "get_woocommerce_postcode_validation",
          "postcode": $("#" + e.target.id).val()
        },
        success: function (response) {
          // Enter data in fields
          $(streetHander1).val(response.data.street_name);
          $(streetHandler2).val(response.data.street_name);
          $(cityHandler).val(response.data.city_name);
          $("#" + e.target.id).val(response.data.postal_code);
        },
        error: function (xhr) {
          // Something went wrong
        },
        complete: function () {
          // Remove load handler
          $(streetHander1 + "," + streetHandler2 + "," + cityHandler).removeClass("woocommerce-postcode-validator-loading");
        }
      })
    }
  });
})(jQuery);
