;(function ($) {
    function toggleFullScreen() {
        if (!document.fullscreenElement && // alternative standard method
            !document.mozFullScreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement) { // current working methods
            if (document.documentElement.requestFullscreen) {
                document.documentElement.requestFullscreen();
            } else if (document.documentElement.msRequestFullscreen) {
                document.documentElement.msRequestFullscreen();
            } else if (document.documentElement.mozRequestFullScreen) {
                document.documentElement.mozRequestFullScreen();
            } else if (document.documentElement.webkitRequestFullscreen) {
                document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
            }
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            }
        }
    }

    var toast_index = 0,
        timer = [];

    toast = function (str, backColor, forColor) {

        if ((!backColor) && (!forColor)) {
            backColor = "black";
            forColor = "white";
        }

        toast_index += 1;
        var tag = '<div class="toasting shadowAll" id="toast_' + toast_index + '" style="background:' + backColor + ';color:' + forColor + '" >'
        tag += '<div class="" style="margin-bottom:5px;border-bottom:1px solid #fff" ><span  id="' + toast_index + '"  class="fab fa-algolia stopTimer"  aria-hidden="true"  style="cursor:pointer ;color:#ffeb3b" ></span><span class="" style="color:#ffeb3b">&nbspتثبيت</span><span style="float:left;color:#ffeb3b" >' + toast_index + '</span></div>';
        tag += str + "</div>";
        var hii = $(tag).appendTo("body").height();
        var wii = $("#toast_" + toast_index).outerWidth();
        var wiw = $(window).width();
        var newLeft = Math.max(0, ((wiw - wii) / 2) + $(window).scrollLeft()) + "px";

        $("#toast_" + toast_index).css({
            top: $(window).scrollTop() + 10 + 'px'
        });
        $("#toast_" + toast_index).animate({ left: newLeft }, "slow").delay(5000).animate({ left: "10px" }, "slow");
        //$(tag).()

        var topy = 10;
        //  $($(".toast:not(:last)").get().reverse()).each(function () {

        $(".toast:not(:last)").each(function () {

            $(this).animate({ "top": $(window).scrollTop() + hii + topy + 20 + "px" });
            topy = topy + $(this).height() + 20;
            /*
             var po = $(this).position();
             $(this).animate({"top": $(window).scrollTop() + po.top + hii + 20 + "px"});
             */
        })

       // console.log(toast_index);

        showToast(toast_index);
    }

    showToast = function (i) {

        timer[i] = setTimeout(function () {
            $('#toast_' + i).fadeOut(500, function () {
                $('#toast_' + i).remove();
                arrangeWarning();
            });
        }, 4000);
        $(".stopTimer").click(function () {
            var timerNum = $(this).attr("id");
            clearTimeout(timer[timerNum]);
            $(this).css("color", "red").removeClass('glyphicon glyphicon-pushpin stopTimer').addClass('glyphicon glyphicon-remove removeWarning');
            $(this).next("span").text(" " + "حذف").css("color", "red");
            $(".removeWarning").click(function () {
                var timerNum = $(this).attr("id");
                $("#toast_" + timerNum).fadeOut(300, function () {
                    $("#toast_" + timerNum).remove();
                    arrangeWarning()
                });
            })
        })
    }


    arrangeWarning = function () {
        var topy = 10;
        // $($(".toast").get().reverse()).each(function () {
        $(".toast").each(function () {
            $(this).animate({ "top": $(window).scrollTop() + topy + "px" });
            topy = topy + $(this).height() + 20;
        })
    }
   

    getRandomColor = () => {
        var r = 255 * Math.random() | 0,
            g = 255 * Math.random() | 0,
            b = 255 * Math.random() | 0;
        return 'rgb(' + r + ',' + g + ',' + b + ')';
    }
}(jQuery));    