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
});