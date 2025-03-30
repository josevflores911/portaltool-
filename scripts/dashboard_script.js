function defer(method) {
  if (window.jQuery) method();
  else setTimeout(function () {
    defer(method);
  }, 50);
}

defer(function () {
  (function ($) {

    var currentPage = 1;
    var totalPages = $('.resource-slider-item').length;
    var frameWidth = $('.resource-slider-frame').width();
    var itemWidth = $('.resource-slider-item').width();

    //console.log(currentPage + '-' + totalPages);

    function doneResizing() {
      var totalScroll = $('.resource-slider-frame').scrollLeft();
      var difference = totalScroll % itemWidth;
      if (difference !== 0) {
        $('.resource-slider-frame').animate({
          scrollLeft: '-=' + difference
        }, 500, function () {
          // check arrows
          checkArrows();
        });
      }
    }

    function checkArrows() {
      var totalWidth = Math.round(totalPages * itemWidth); // Arredonde para um número inteiro
      var totalScroll = Math.round($('.resource-slider-frame').scrollLeft()); // Arredonde para um número inteiro

      if (totalScroll === 0) {
        $(".prev").css("visibility", "hidden");
      } else {
        $(".prev").css("visibility", "visible");
      }

      // Verifique se o totalScroll está próximo do valor máximo possível de scroll
      if (totalWidth - totalScroll <= Math.round(frameWidth)) { // Arredonde para um número inteiro
        $(".next").css("visibility", "hidden");
      } else {
        $(".next").css("visibility", "visible");
      }

      // Calcule a página com base na posição atual do scroll
      currentPage = Math.ceil((totalScroll + Math.round(frameWidth)) / Math.round(frameWidth)); // Arredonde para um número inteiro

      // Verifique se estamos no primeiro ou último slide
      if (currentPage === 1) {
        $(".prev").css("visibility", "hidden");
      } else if (currentPage === totalPages) {
        $(".next").css("visibility", "hidden");
      }
    }



    $('.arrow').on('click', function () {
      var $this = $(this),
        speed = 500;
      if ($this.hasClass('prev')) {
        if (currentPage > 1) {
          currentPage--;
          $('.resource-slider-frame').animate({
            scrollLeft: '-=' + frameWidth
          }, speed, function () {
            // check arrows
            checkArrows();
          });
        }
      } else if ($this.hasClass('next')) {
        if (currentPage < totalPages) {
          currentPage++;
          $('.resource-slider-frame').animate({
            scrollLeft: '+=' + frameWidth
          }, speed, function () {
            // check arrows
            checkArrows();
          });
        }
      }
    });

    $(window).on("load resize", function () {
      frameWidth = $('.resource-slider-frame').width();
      itemWidth = $('.resource-slider-item').width();
      checkArrows();
      $('#resource-slider .resource-slider-item').each(function (i) {
        var $this = $(this),
          left = itemWidth * i;
        $this.css({
          left: left
        });
      });
    });

    var resizeId;
    $(window).resize(function () {
      clearTimeout(resizeId);
      resizeId = setTimeout(doneResizing, 500);
    });

  })(jQuery);
});
