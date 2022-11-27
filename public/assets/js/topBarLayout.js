var $tabButtonItem = $('#tab-button li'),
    $tabSelect = $('#tab-select'),
    $tabContents = $('.tab-contents'),
    activeClass = 'is-active';

function updatEvent() {
    $(".caret").unbind().click(function (e) {

        e.stopPropagation();
        $(".caret").removeClass("light_red");
        var that = $(this);
        var sbb = $(this).siblings(".nested");
        $(e.target).addClass("light_red");


        if (sbb.length > 0) {
            sbb.slideToggle(500, function () {
                sbb.toggleClass("active");
                that.toggleClass("caret-down");
                saveState();

            });

        } else {
            saveState();
        }

        colorParents();

    });

    // $("#saveOption").click(function () {
    //     alert(0);
    //     let obj = {};
    //     let id;
    //     let len = $(".key_Val_opt").length / 2;
    //     for (var i = 1; i <= len; i++) {

    //         if (i == 1) {
    //             id = $("#option_value_" + i).val();
    //         }

    //         obj[$.trim($("#option_key_" + i).text())] =$.trim($("#option_value_" + i).val());
    //     }
    //     alert(JSON.stringify(obj));
    //     window.livewire.emit("saveOption", obj, $.trim(id));
    // })

} // end updatEvent

// function lookupEvent() {
//     $("input.lookup ,  textarea.lookup").focus(function () {
//         $("#autoComplete ul").empty();       
//          window.livewire.emit("setStyle", returnStyle($(this), "block"));       
//     })    
// }

function colorParents() {

    $(".caret").each(function () {
        if ($(this).parent().find('ul').length > 0) {
            $(this).css("color", "#10a20e");
        } else {
            $(this).removeClass("caret-down")
            $(this).css("color", "black");
        }
    })
}

// function hideAlert() {
//     setTimeout(function () {
//         $("div.alert").fadeOut(1000).remove();
//     }, 8000); // 5 secs
// }

function saveState() {
    var a = {};
    $("#myUL li .caret ,#myUL li ul").each((c, d) => {
        a[$(d).attr("id")] = $(d).attr("class");
    })
    localStorage.setItem("state", JSON.stringify(a));

    //  console.log("saving. state...");
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

window.addEventListener("load", function () {
    loadState();
    colorParents();
    updatEvent();
    
})

window.addEventListener("loadStates", function () {
    //console.log("loadStatus ...");
    loadState();
    updatEvent();
    colorParents()
    //hideAlert();
    loadTabState();
  
   
    // $("#saveOption").unbind().click(function () {
    //     let obj = {};
    //     let id;
    //     let len = $(".key_Val_opt").length / 2;
    //     for (var i = 1; i <= len; i++) {
    //         if (i == 1) {
    //             id = $("#option_value_" + i).text();
    //         }
    //         obj[$("#option_key_" + i).text()] = $("#option_value_" + i).text();
    //     }
    //      console.log(obj);
    //     window.livewire.emit("saveOption", obj, $.trim(id));
    // })
    //refreshEvent();

})

$("#createTreeTable").click(function () {
    var name = prompt("Enter Table Name :");
    if (name)
        window.livewire.emit("createTreeTable", name);
})

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

});

$tabSelect.on('change', function () {
    var target = $(this).val(),targetSelectNum = $(this).prop('selectedIndex');
    $tabButtonItem.removeClass(activeClass);
    $tabButtonItem.eq(targetSelectNum).addClass(activeClass);
    $tabContents.addClass("displayNone");
    $(target).removeClass("displayNone");
    saveTabstate();
});

function saveTabstate() {

    //console.log("saving state..");
    var ob = {};
    $("ul#tab-button li, .tab-contents").each((x, y) => {
        ob[$(y).attr("id")] = $(y).attr("class");
    })

    localStorage.setItem("tab_state", JSON.stringify(ob));

}

function loadTabState() {

    //console.log("load state..");
    var f = {};
    var s = localStorage.getItem("tab_state")
    var f = JSON.parse(s);
    if (f) {

        $("ul#tab-button li, .tab-contents").each((c, d) => {
            $(d).attr("class", f[$(d).attr("id")]);
        })
    }
}

window.addEventListener("loadTabState", function () {
    loadTabState();
})

// function returnStyle(me, display) {  
//     var hiDiv = 150;

//     var obj = {
//         width: me.outerWidth(),
//         hi: me.outerHeight(),
//         offsetTop: me.offset().top,
//         offsetLeft: me.offset().left,
//         disp: display
//     }
//     var style = `border-radius:5px;border:2px #e6e600 solid;overflow-y:auto;padding:2px;background:#ffffcc;z-index:999999;position:absolute;width:${obj.width}px;height:${hiDiv}px;left:${obj.offsetLeft}px; top:${obj.offsetTop + obj.hi}px;display:${obj.disp}`;

//     return style;

// }

window.onscroll = function() {
//$("#autoComplete").scrollTop( document.documentElement.scrollTop)

//console.log(document.documentElement.scrollTop)
}    
