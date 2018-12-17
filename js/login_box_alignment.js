$(document).ready(function () {
   var deviceScreenHeight = $(window).height();
   var loginBoxHeight = $('div.login-box').height();

   $('div.login-box').css('margin-top', function () {
      var marginTop = (deviceScreenHeight / 2) - (loginBoxHeight / 2);
      return marginTop;
   });
   $('div.login-box').css('margin-bottom', function () {
      var marginTop = ((deviceScreenHeight / 2) - (loginBoxHeight / 2));
      return marginTop;
   });
});


