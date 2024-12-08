(function($){
    $(window).on('elementor/frontend/init', function () {
        function initializeEvents() {
            // Abas
            $(".hpl-tab-item").on("click", function () {
                const tabId = $(this).data("tab");
        
                $(".hpl-tab-item").removeClass("hpl-active");
                $(".hpl-tab-content").removeClass("hpl-active");
        
                $(this).addClass("hpl-active");
                $("#" + tabId).addClass("hpl-active");
            });
        
            // Modal
            $(".hpl-buy-button").on("click", function () {
                const productName = $(this).data("product-name");
                const productPrice = $(this).data("product-price");
                const productImage = $(this).data("product-image");
                const variationName = $(this).data("variation-name");
                const addToCartUrl = $(this).data("add-to-cart");
        
                $(".hpl-modal-product-thumbnail").attr("src", productImage);
                $(".hpl-modal-product-name").text(productName);
                $(".hpl-modal-variation-name").text(variationName);
                $(".hpl-modal-product-price").html(productPrice);
                $(".hpl-modal-confirm").attr("href", addToCartUrl);
        
                $(".hpl-modal").fadeIn();
            });
        
            $(".hpl-modal-close").on("click", function () {
                $(".hpl-modal").fadeOut();
            });
        }

        elementorFrontend.hooks.addAction('frontend/element_ready/hpl_products_list.default', initializeEvents);
    });
}(jQuery));

