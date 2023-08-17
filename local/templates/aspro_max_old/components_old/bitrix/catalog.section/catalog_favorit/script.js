$(document).ready(function(){
    $('.hot-wrapper-items .flexslider .js-click').click(function(){
      var _this = $(this),
        block = '',
        activeBlock = _this.closest('.items').find('.item.active');
  
      if (_this.hasClass('flex-prev')) {
        activeBlock.fadeOut(function(){
          $(this).removeClass('active');
  
          block = activeBlock.prev('.item');
          if (block.length) {
            block.fadeIn(function(){
              $(this).addClass('active');
            })
          } else {
            _this.closest('.items').find('> .item:last-of-type').fadeIn(function(){
              $(this).addClass('active');
            })
          }
        })
      } else {
        activeBlock.fadeOut(function(){
          $(this).removeClass('active');
  
          block = activeBlock.next('.item');
          if (block.length) {
            block.fadeIn(function(){
              $(this).addClass('active');
            })
          } else {
            _this.closest('.items').find('> .item:eq(0)').fadeIn(function(){
              $(this).addClass('active');
            })
          }
        })
      }
    });

    $('.hot-wrapper-items .items1').swiperight(function() {
      var _this = $(this),
        block = '',
        activeBlock = _this.find('.item.active'),
        controlNav = _this.find('.flex-control-nav a');

        controlNav.removeClass('flex-active');

        activeBlock.fadeOut(function(){
          $(this).removeClass('active');

          block = activeBlock.prev('.item');
          if (block.length) {
            block.fadeIn(function(){
              $(this).addClass('active');
              controlNav.eq($(this).index()-1).addClass('flex-active');
            })
          } else {
            _this.closest('.items').find('> .item:last-of-type').fadeIn(function(){
              $(this).addClass('active');
              controlNav.eq($(this).index()-1).addClass('flex-active');
            })
          }
        });
    });

    $('.hot-wrapper-items .items1').swipeleft(function() {
      var _this = $(this),
      block = '',
      activeBlock = _this.find('.item.active'),
      controlNav = _this.find('.flex-control-nav a');

      controlNav.removeClass('flex-active');

      activeBlock.fadeOut(function(){
        $(this).removeClass('active');

        block = activeBlock.next('.item');
        if (block.length) {
          block.fadeIn(function(){
            $(this).addClass('active');
            controlNav.eq($(this).index()-1).addClass('flex-active');
          })
        } else {
          _this.closest('.items').find('> .item:eq(0)').fadeIn(function(){
            $(this).addClass('active');
            controlNav.eq($(this).index()-1).addClass('flex-active');
          })
        }
      });
    });
});