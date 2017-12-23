$( document ).ready(function() {

    var scrolled = false;
    $(window).scroll(function() {
        if ($(window).scrollTop() != 0 && !(scrolled)) {
            scrolled = true;
            $( "#contact" ).animate({
                marginTop: "0px"
            }, 200, function() {
                //Animation complete
            });
            $( "#header" ).animate({
                height: "30px"
            }, 200, function() {
                //Animation complete
            });
            $("#header h1").animate({
                margin: "2px 0px",
                fontSize: "20px"
            }, 200, function() {
            });
            $("#header h2").animate({
                opacity: 0,
                fontSize: "10px"
            }, 200, function() {
            });
            $("#contact").animate({
                top: "3px",
                right: "3px"
            }, 200, function() {
            });
            $("#contact img").animate({
                height: "25px",
                width: "25px"
            }, 200, function() {
            });
        } else if ($(window).scrollTop() == 0) {
            scrolled = false;
            $( "#contact" ).animate({
                marginTop: "15px"
            }, 200, function() {
                //Animation complete
                        });
            $( "#header" ).animate({
                height: "82px"
            }, 200, function() {
                //Animation complete
            });
            $("#header h1").animate({
                margin: "20px 0px",
                fontSize: "2em"
            }, 200, function() {
            });
            $("#header h2").animate({
                opacity: 1,
                fontSize: "20px"
            }, 200, function() {
            });
            $("#contact").animate({
                top: "15px",
                right: "17px"
            }, 200, function() {
            });
            $("#contact img").animate({
                height: "50px",
                width: "50px"
            }, 200, function() {
            });
        }
    });

});
