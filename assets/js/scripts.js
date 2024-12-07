jQuery(document).ready(function ($) {
    // Abas
    $(".hpl_tab-item").on("click", function () {
        const tabId = $(this).data("tab");

        $(".hpl_tab-item").removeClass("hpl_active");
        $(".hpl_tab-content").removeClass("hpl_active");

        $(this).addClass("hpl_active");
        $("#" + tabId).addClass("hpl_active");
    });

    // Modal
    $(".hpl_buy-button").on("click", function () {
        const productName = $(this).data("product-name");
        const productPrice = $(this).data("product-price");
        const productImage = $(this).data("product-image");
        const variationName = $(this).data("variation-name");
        const addToCartUrl = $(this).data("add-to-cart");

        $(".hpl_modal-product-thumbnail").attr("src", productImage);
        $(".hpl_modal-product-name").text(productName);
        $(".hpl_modal-variation-name").text(variationName);
        $(".hpl_modal-product-price").html(productPrice);
        $(".hpl_modal-confirm").attr("href", addToCartUrl);

        $(".hpl_modal").fadeIn();
    });

    $(".hpl_modal-close").on("click", function () {
        $(".hpl_modal").fadeOut();
    });
});
