function saveState() {
    var a = {};
    $("#myUL li .caret ,#myUL li ul , div.crud_container").each((c, d) => {
        a[$(d).attr("id")] = $(d).attr("class");
    })
    localStorage.setItem("state", JSON.stringify(a));


    console.log("saving. state...");
}
function loadState() {
    var f = {};
    var s = localStorage.getItem("state")
    var f = JSON.parse(s);

    if (f) {
        $("#myUL li .caret ,#myUL li ul , div.crud_container").each((c, d) => {

            $(d).attr("class", f[$(d).attr("id")]);
        })
    }
}

$(".resetAll").click(function () {
    $(".image").val("");
})

function refreshEvent() {

    setTimeout(function () {
        $('.errors').fadeOut('fast');
    }, 5000);

    //$(".image").val("");

    $(".showcrud").unbind().click(function () {

        $('.crud_container').each(function (c, d) {
            if ($(d).hasClass("showForm")) {
                $(d).removeClass("showForm");
            };
        })



        $(this).siblings('.crud_container').toggleClass("showForm");


        console.log("show Crude");
        saveState();
    })

    $(".caret").each(function () {
        if ($(this).parent().find('ul').length > 0) {
            $(this).addClass("colorBlue");
        }
    })

    $(".caret").unbind().click(function (e) {
        $(".caret").removeClass("light_red");
        $(this).siblings(".nested").slideToggle().toggleClass("active", 500);
        $(this).toggleClass("caret-down");
        $(e.target).addClass("light_red");
        saveState();
        console.log("saveState");
    });


}



document.addEventListener('livewire:load', function () {
    console.log("refresh ev liveWIRE ..");

});

document.addEventListener("DOMContentLoaded", () => {
    console.log("DOMContentLoaded.....");
})



window.addEventListener("colorIfParent", function () {
    $(".caret").each(function () {
        if ($(this).parent().find('ul').length > 0) {
            $(this).addClass("colorBlue");
        }
    })

})


window.addEventListener("refreshEvent", function () {
    refreshEvent();
})

$(document).ajaxComplete(function () {
    console.log("AJajaxCompleteAX STOP");
});

document.addEventListener("livewire:onLoadCallback", function (event) {
    console.log("livewire:onLoadCallback");
});

$("input:file").change(function () {
    console.log("input:file - change");
    loadState();
});


window.addEventListener("loadClasses", function () {
    console.log("loadClasses");
    loadState();
    refreshEvent();

})

window.addEventListener("loadtabClasses", function () {
    loadTabState();
})


$("#closeFloat").click(function () {
    $('#floatDiv').css("display", "none");
})

$(".edit_node , .add_child").click(function () {
    saveState();
})



function saveTabstate() {
    var ob = {};
    $("ul#tab-button li , .tab-contents").each((x, y) => {
        ob[$(y).attr("id")] = $(y).attr("class");
    })

    localStorage.setItem("tab_state", JSON.stringify(ob));
}

function loadTabState() {
    var f = {};
    var s = localStorage.getItem("tab_state")
    var f = JSON.parse(s);

    if (f) {

        $("ul#tab-button li, .tab-contents").each((c, d) => {
            $(d).attr("class", f[$(d).attr("id")]);
        })
    }
}


var $tabButtonItem = $('#tab-button li'),
    $tabSelect = $('#tab-select'),
    $tabContents = $('.tab-contents'),
    activeClass = 'is-active';

$tabButtonItem.first().addClass(activeClass);
$tabContents.not(':first').addClass("displayNone");

$tabButtonItem.find('a').on('click', function (e) {
    var target = $(this).attr('href');
    $tabButtonItem.removeClass(activeClass);
    $(this).parent().addClass(activeClass);

    $tabSelect.val(target);

    $tabContents.addClass("displayNone");
    $(target).removeClass("displayNone");

    saveTabstate();
    e.preventDefault();
});

$tabSelect.on('change', function () {
    var target = $(this).val(),
        targetSelectNum = $(this).prop('selectedIndex');
    $tabButtonItem.removeClass(activeClass);
    $tabButtonItem.eq(targetSelectNum).addClass(activeClass);
    $tabContents.addClass("displayNone");
    $(target).removeClass("displayNone");
});





$(document).ready(function () {

    loadState();
    refreshEvent()

    // $("#saveOption").click(function () {

    //     let obj = {};
    //     let id;
    //     let len = $(".key_Val_opt").length / 2;



    //     for (var i = 1; i <= len; i++) {

    //         if (i == 1) {
    //             id = $("#option_value_" + i).val();
    //         }

    //         obj[$.trim($("#option_key_" + i).text())] =$.trim($("#option_value_" + i).val());
    //     }
    //     console.log(obj);
    //     window.livewire.emit("saveOption", obj, id);
    // })

})

window.addEventListener("loadtabClasses", function () {
    loadTabState();
})

$(document).ready(function(){
    $("#successMessage").delay(5000).slideUp(300);    
});

