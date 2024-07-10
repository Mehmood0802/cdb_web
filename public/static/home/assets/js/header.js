$(document).ready(function() {
  $("#fixed-header").click(function() {
    // 使用animate平滑滚动到顶部，duration参数设置滚动动画的持续时间（单位：毫秒）
    $("html, body").animate({ scrollTop: 0 }, "slow");
    // 可选：如果需要，可以在这里阻止按钮的默认行为，尽管对于这种场景通常不需要
    return false;
  });
  $(window).scroll(function() {
    if ($(this).scrollTop() > 1) { // 如果滚动超过100px
      $(".boxbig").css({position: 'fixed'}); // 显示返回顶部按钮
    } else {
      $(".boxbig").css({position: 'relative'}); // 否则隐藏
    }
    if ($(this).scrollTop() > 300) { // 如果滚动超过100px
      $("#fixed-header").show(); // 显示返回顶部按钮
    } else {
      $("#fixed-header").hide(); // 否则隐藏
    }
  });
  // 使用正则表达式检查User-Agent中是否包含移动设备的关键字
  var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
  
  $('.menu-item').hide()
  $('.m10').hide()
  if (isMobile) {
      $('.menu').on('click', function () {
          $('.menu-item').toggle()
          $('.m10').toggle()
          $('.m9').toggle()
          console.log($('.menu-item'))
      });
      $(window).scroll(function() {
          $('.menu-item').hide()
          $('.m10').hide()
          $('.m9').show()
      });
      // 如果是移动设备，执行相应的代码
      console.log("当前设备是移动设备");
    } else {
      // 如果不是移动设备，执行相应的代码
      console.log("当前设备不是移动设备");
    }
  });