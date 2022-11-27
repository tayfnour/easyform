var $tabButtonItem = $('#tab-button li'),
  $tabSelect = $('#tab-select'),
  $tabContents = $('.tab-contents'),
  activeClass = 'is-active';

$(".sidebar-dropdown > a").click(function () {
  $(".sidebar-submenu").slideUp(200);
  if (
    $(this)
      .parent()
      .hasClass("active")
  ) {
    $(".sidebar-dropdown").removeClass("active");
    $(this)
      .parent()
      .removeClass("active");
  } else {
    $(".sidebar-dropdown").removeClass("active");
    $(this)
      .next(".sidebar-submenu")
      .slideDown(200);
    $(this)
      .parent()
      .addClass("active");
  }
});

$("#close-sidebar").click(function () {
  $(".page-wrapper").removeClass("toggled");
});
$("#show-sidebar").click(function () {
  $(".page-wrapper").addClass("toggled");
});

//const form = document.getElementById("new_document_attachment");
const fileInput = document.getElementById("document_attachment_doc");

// fileInput.addEventListener('change', () => {
//   form.submit();
// });

window.addEventListener('paste', e => {
  fileInput.files = e.clipboardData.files;
  var event = new Event('change');
  fileInput.dispatchEvent(event);
});



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
  saveTabstate();
});

function saveTabstate() {

  console.log("saving state..");
  var ob = {};
  $("ul#tab-button li , .tab-contents").each((x, y) => {
      ob[$(y).attr("id")] = $(y).attr("class");
  })

  localStorage.setItem("tab_state", JSON.stringify(ob));
}

function loadTabState() {

  console.log("load state..");

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

$(".selected_column").change(function(){
  saveTabstate();
  refreshEvent();
})

function refreshEvent(){

  // $("#saveOption").unbind().click(function () {

  
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

}

$(document).ready(function () {
  loadTabState();
})
