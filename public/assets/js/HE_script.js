// This File core of HTML Editor;
var selected;
var selection;
var wrapHtml;
var bw = 4; //border width
var lockpreview = true;
var copiesHtml;
var copiesPointer=0;
var copiesArr=[];
var activeArea;
var fileInput;
window.global_fun;
window.timer;
var url="http://127.0.0.1:8000/";


const regex = /([\.\w,\s]+)\s*?{([\w\n\s:#;%-]+)}/gmi;
let htmlEditor, cssEditor, jsEditor ,compEditor;

var states = ["htmlviewer", "cssviewer", "jsviewer", "compoWindow","elemsWrap"];


$('body').on("click",".heightAuto",function(){
    var  el =  $(this).closest(".sliding");
    autoHeight = el.get(0).scrollHeight;
    var eh = el.height()
    console.log(el.height());

    if( eh <= 86 ){
        el.animate({"height":autoHeight},400);
        $(this).html("-");
    }else{
        el.animate({"height":"85px"},400)
        $(this).html("+");
    }

    setTimeout(()=>saveStateOfEles(),400);  
  })
  
  

jQuery.fn.fadeOutAndRemove = function(speed){
    $(this).fadeOut(speed,function(){
        $(this).remove();
    })
}

document.addEventListener("getStateOfEle", function () {
    getStateOfEle();
})   

function scrollLeaf (id){
    $(".caret").removeClass("light_red");

    if($.trim(id) &&  !isNaN(id)) {

    $(`#li-${id}`).addClass("light_red")
  //  $(`#li-${id}`).addClass("light_red").parent().parent().addClass("active");
    var ser = $(`#li-${id}`).parent().attr("data-decode");

    var splArr = ser.split("_");

    //console.log(splArr);
    if(splArr.length > 1){
        for (var i = 0; i<(splArr.length-1) ;i++){
            $(`#li-${splArr[i]}`).parent().parent().addClass("active");
        }
    }    
    setTimeout(()=>
    $("#treeWrap").animate({scrollTop:$(`#li-${id}`).offset().top - $("#treeWrap").offset().top + $("#treeWrap").scrollTop()} ,1000)
    ,100);

 } 
   
}

function saveStateOfEles() {

   
    states.forEach((id) => {
        localStorage.setItem(id, $("#" + id).attr("style"));
    })

    $(".sliding,.floatDiv").each(function(){
        localStorage.setItem(this.id, this.getAttribute('style'));
    })

    $(".floatDiv").each(function(){
        localStorage.setItem(this.id, this.getAttribute('style'));
    })

    console.log(localStorage);
}

function getStateOfEle() {
    states.forEach((id) => {
        $("#" + id).attr("style", localStorage.getItem(id))
    })

    $(".sliding , .floatDiv").each(function(){

       $("#" + this.id).attr("style", localStorage.getItem(this.id));   
      
       var el =  $("#" + this.id);
    
      
       var eh = el.height();

        if( eh <= 86 ){
            el.find(".heightAuto").html("+");
          
        }else{
            el.find(".heightAuto").html("-");
        }
       
    })

 //   toast("GetSatate...");
}

 fileInput = document.getElementById("document_attachment_doc");

// fileInput.addEventListener('change', () => {
//   form.submit();
// });


  

// window.addEventListener('paste', e => {
//   fileInput = document.getElementById("inp_1000");
//   fileInput.files = e.clipboardData.files;
//   var event = new Event('change');
//   fileInput.dispatchEvent(event);
// });


window.addEventListener('moveTop', (event)=> {  
    scrollLeaf(event.detail.id); 
})  


window.addEventListener('hideTopDiv', (event)=> { 
    setTimeout(() => {
        $("#topMsg").fadeOutAndRemove('slow');
    }, 8000);     

}) 



// function delay(callback, ms) {
//     var timer = 0;
//     return function() {
//       var context = this, args = arguments;
//       clearTimeout(timer);
//       timer = setTimeout(function () {
//         callback.apply(context, args);
//       }, ms || 0);
//     };
//   }


//create function debounce to avoid multiple calls
// function debounce(func, wait, immediate) {
//     var timeout;
//     return function() {
//         var context = this, args = arguments;
//         var later = function() {
//             timeout = null;
//             if (!immediate) func.apply(context, args);
//         };
//         var callNow = immediate && !timeout;
//         clearTimeout(timeout);
//         timeout = setTimeout(later, wait);
//         if (callNow) func.apply(context, args);
//     };  
// }




// $(document).on("input" , ".formAttrKey, .formAttrVal" ,function(){
    
    
//     var $obj = {};   


//     $(".formAttrKey").each(function(){
//         $obj[$(this).val()] =  $(this).next('.formAttrVal').val();       
//      })

//     clearTimeout(timer);
//     timer = setTimeout(()=>{ 
//       window.livewire.emit("saveFormAttr", $obj); 
//      }, 2000);

//     });

// $(document).on("click" , "#addfield" , function(){
//     console.log(11); 
//     alert(10)
// })


// $(document).on("click" , ".field" , function(e){
//      e.preventDefault();
//     var id = $(this).attr("data-id");
//     fileInput = document.getElementById("document_attachment_doc");
//     window.livewire.emit("removePhotoFromArr",id);
// } )




// function refreshLiveMode (){
   
//     lookupEvent();
// }
// Refresh Event in Html editor Mode 

// $("body").on("paste",".key_Val_opt",function(e){
    
//   $(this).text($(this).text().replace(/<[^>]+>/g, ''));
// })

// $("body").on("mouseenter mouseover", ".el , .el input[type]", function(e) {

//  if($("#mode").text()=="Html"){

//     e.stopPropagation();
//     if ($(".HE_border").length > 0)
//         $(this).removeClass("HE_border")

//     if (!$(this).hasClass("HE_border_click"))
//         $(this).addClass("HE_border");

//     $("#eleInfo").val($(this).attr("class"));

//  }
    
// });

// $("body").on("mouseout", ".el , .el input[type]", function(e) { 

//     $(this).removeClass("HE_border");
// })   

// $("body").on("click", ".el , .el input[type]", function(e) {    
//     if($("#mode").text()=="Html"){
//     e.stopPropagation();
//     selected=$(this);
//     $(this).removeClass("HE_border");
//     $(".el , .el input[type]").removeClass("HE_border_click");
//     $(this).addClass("HE_border_click");
//     $("#selctedele").val(selected.attr("class"));
//     // find class in sideeditor now disable
//     // getStyleOfSelected(selected.attr("class"));
//     var str = selected.attr("id");
//     // console.log(typeof );

//     if (typeof str !== "string") {
//         str = " (No Id)";
//     }
//     $("#addId").text("Add ID - " + str);   
//  }
// }) 

   $("#htmlviewer").draggable({ handle: "#headerHtml", stop: () => saveStateOfEles() });
   $("#cssviewer").draggable({ handle: "#headerCss", stop: () => saveStateOfEles() });
   $("#jsviewer").draggable({ handle: "#headerJs", stop: () => saveStateOfEles() });
   $("#compoWindow").draggable({ handle: "#headerComp", stop: () => saveStateOfEles() });

    $("#htmlviewer").resizable().resizable('destroy').resizable();
    $("#cssviewer").resizable().resizable('destroy').resizable();
    $("#jsviewer").resizable().resizable('destroy').resizable();
    $("#compoWindow").resizable().resizable('destroy').resizable();

    //bring window to front
    $(".wind").click(function () {
        $(".wind").css("z-index", "90");
        $(this).css("z-index", "100");
    });


$("#fileNameNow").change(function(){
    location.href =  "http://127.0.0.1:8000/editorExpress/" + $(this).val(); 
 })

$(".saveState").click(function () {
    setTimeout(()=>saveStateOfEles(),400);   
  });


// $(".addElement").click(function () {

//     if($("#mode").text()=="Html"){
  
//         var t = $(this).text();
        
//         var checkBefore= $("#beforeFlag").is(':checked');
//         var checkPrepend= $("#preFlag").is(':checked');
//         var checkAfter= $("#afterFlag").is(':checked');

//         var el="";   

//         if (t == "c-fluid") t = "container-fluid";

//         if (t == "image") {
//             el = '<img src="http://localhost/easyPanel/storage/app/images/temp.png" class="img-fluid el" >';       
//         }
//         else if (t == "span") {
//             el ='<span class="el el_border_' + bw + '" >Span</span>';
//         }
//         else if (t == "text") {
//             el ='<input type="text" class="form-control el el_border_' + bw + '" >';
//         }
//         else if (t == "button") {
//             el ='<input type="button" class="btn el el_border_' + bw + '" value="btn" >';
//         }
//         else if (t == "section") {
//             el ='<div class="' + t + " " + t + '_class el el_border_' + bw + '" ></div>';  
            
//             //example about add group of html
//         }else if(t == "fullsec"){
//             el = `<div class="section section_class el el_border_${bw}">
//             <div class="container container_class el el_border_${bw}">    
//                 <div class="row row_class el el_border_${bw} ">
//                     <div class="col-12 col-12_class el el_border_${bw}"></div>
//                 </div>
//             </div>
//         </div>`;
//         } else if (t == "card"){

//             el= `<div class="card el el_border_${bw}">                
//                 <div class="card-header el el_border_${bw}"> 
//                 <span style="font-size: 22px" class="el el_border_${bw}" >Card Header</span>
//                 </div>
//                 <div class="card-body el el_border_${bw}">
//                    <h5 class="card-title el el_border_${bw}">This Card title treatment</h5>
//                    <p class="el el_border_${bw}" > This is some text within a card body. <p>
//                 </div>
//             </div>`;           
//         }else if (t=="carsoul"){
           
//             el=`<div id="carouselExampleControls" class="carousel slide el el_border_${bw}" data-ride="carousel">
//             <div class="carousel-inner el">
//               <div class="carousel-item active el el_border_${bw}">
//                 <img src="..." class="d-block w-100" alt="...">
//               </div>
//               <div class="carousel-item el el_border_${bw}">
//                 <img src="..." class="d-block w-100" alt="...">
//               </div>
//               <div class="carousel-item el el_border_${bw}">
//                 <img src="..." class="d-block w-100" alt="...">
//               </div>
//             </div>
//             <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
//               <span class="carousel-control-prev-icon" aria-hidden="true"></span>
//               <span class="sr-only">Previous</span>
//             </a>
//             <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
//               <span class="carousel-control-next-icon" aria-hidden="true"></span>
//               <span class="sr-only">Next</span>
//             </a>
//           </div>`;

//         }else {
//             el ='<div class="' + t + " " + t + '_class el el_border_' + bw + '" ></div>';
//         }
      
//         selected =  selected !== undefined ? selected : $("#wrapper div:first");

//         // console.log(selected);
//         // selected.css("background" , "red");

//         //alert(el);
     
//         if(checkPrepend){
//             selected.prepend(el);
//         }else if(checkBefore){
//             $(el).insertBefore($(selected));    
//         }else if(checkAfter){
//             $(el).insertAfter($(selected));    
//         }else{
//             selected.append(el);
//         }

       
//        // saveChangeHtml  prevent from refresh  ? so we late refresh after it
     
//     setTimeout(function(){       
         
//          saveStateOfEles();
//     } , 2000);
//       saveChangeHtml();
//       //  copiesArr.push($("#wrapper").html());
//       //  copiesPointer = copiesArr.length;
//       //   window.livewire.emit("savePage", $("#wrapper").html(), $("#jsEditor").val(), $("#cssEditor").val(), $("#fileNameNow").val());
//     }else{

//         toast("You can add elemnent in Html Mode Only")
//     }

// })

//----------------------------------------------------
// yellow point
// $("#cssEditor").on("input propertychange", function () {

//     if (selected) {
//         selected.removeClass("HE_border_click");
//         selected.addClass("selectedBefore");
//         $("#styleSheet").html($("#cssEditor").val());
//     }
// })

// $("#selctedele").on("input propertychange", function () {

//     if (selected) {
//         selected.removeClass("HE_border_click");
//         selected.attr("class", $(this).val() + " selectedBefore");       
//     }
// });

// $("#selctedele").blur(function () {
   
//         selected.removeClass("selectedBefore");       
//         saveChangeHtml();
  
// });


// $("#cssEditor ,#selctedele").blur(function () {

//     if (selected) {
//         selected.removeClass("selectedBefore");
//         selected.addClass("HE_border_click");

//     }
// })

// $("#imageSrc").click(function (e) {


//     var src = prompt("Enter Image Source (Src) :", selected.attr("src"));

//     if (src) {

//         selected.attr("src", src)

//         //  $("#fileNameNow").val(fName).change();


//     }

// });


//---------------------------------------------

// $("#jsEditor").on("input propertychange", function () {

//     try {
//         eval($("#jsEditor").val());
//     } catch (e) {
//         if (e instanceof SyntaxError) {
//             console.log(e.message);
//         }
//     }

//     $("#scriptSheet").html($("#jsEditor").val());

// })

$("#removeTempBorder").click(function (e) {
    if ($("#mode").text() == "Html") {
    removeTempBorder();
    }else{
        toast("Set Html Mode Firstly");
    }
})   

$("#editHtml").click(function (e) {

    if ($("#mode").text() == "Html") {

     
        $("#htmlviewer").show();
      //  $("#TaViewer").val($.trim($("#wrapper").html()));
      htmlEditor.setValue($.trim($("#wrapper").html()))
      setTimeout(function() {
        htmlEditor.refresh();
       },10);
         
        
    } else {
        toast("You Can Edit Html in  Mode  of HTML ");
    }
});

$("#setHtml").click(function (e) {
  //  $("#wrapper").html($("#TaViewer").val());  
    $("#wrapper").html(htmlEditor.getValue());    
        
    saveChangeHtml();
});
//set Html



// function commentBlade(html){

// var htmlwithdirective = html.replace(/({{[$\w]+}})/gm, "<!--\$1-->");

// return  htmlwithdirective.replace(/(@[$\w();\s\+=<]+)$/gm, "<!--\$1-->");

    
// }

// function saveChangeHtml (){

// if ($("#mode").text() == "Html") {
//     window.livewire.emit("saveChangeHtml",style_html($.trim($("#wrapper").html())));
//     }
// }

// function saveChangeHtmlAndView (mode){   

//     window.livewire.emit("saveChangeHtmlAndView",style_html($.trim($("#wrapper").html())) , mode );    
     
// }

// function removeTempBorder(){
// selection.removeClass("el_border_0 el_border_1 el_border_2 el_border_3 el_border_3 el_border_4 el_border_5 el_border_6 el_border_7 el_border_8");
// saveChangeHtml();

// }


// $("#savechange").click(function(){

//   if ($("#mode").text() == "Html") {
//     saveChangeHtml ();
//   }

// })



    
$("#setCss").click(function () {

    $("#styleSheet").html(cssEditor.getValue()); 
    $("#cssViewers").val(cssEditor.getValue()); 

    var obj ={};

     obj["css"] = cssEditor.getValue();
     obj["fileName"] = $("#fileNameNow").val();

     //alert(JSON.stringify(obj));

    fetch(url+'saveConfiqCss', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(obj)
    })
    
       
});

$("#setJs").click(function (e) {  // runCodee();  
   
    var obj ={};
    obj["js"] = jsEditor.getValue();
    obj["fileName"] = $("#fileNameNow").val();
  
    fetch(url+'saveConfiqJs', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(obj)
    })

})


$("#editjs").click(function (e) {
    $("#jsviewer").show();
    // to kill spical  events  replace code and 
   // jsEditor.setValue($("#JsViewers").val());
    setTimeout(function() {
        jsEditor.refresh();
        },100);
   
});

$("#editcss").click(function () {
   
   $("#cssviewer").show();
   setTimeout(function() {
    cssEditor.refresh();
    },100);

//    cssEditor.setValue($("#cssViewers").val());
//    setTimeout(function() {
//     cssEditor.refresh();
//     },100);
   //  $(".CodeMirror-code").trigger("click");
 //  cssEditor.setValue($("#cssEditor").val());
   
   
});

$("#editComp").click(function (e) {
     $("#compoWindow").show();
     compEditor.setValue($("#compViewersArea").val());
     setTimeout(function() {
        compEditor.refresh();
    },100);
    // compEditor.refresh;
});

$("#closeComp").click(function (e) {
      $("#compoWindow").hide();
    
});

$("#closeCssEditor").click(function (e) {
    $("#cssviewer").hide(); 

});
$("#closeJsEditor").click(function (e) {
    $("#jsviewer").hide();
   
});


$("#closeEditor").click(function (e) {
    $("#htmlviewer").hide();   
});

$(".icon_menu").click(function (e) { 
    e.preventDefault();
    $("#sideBar").slideToggle("slow","swing",function(){
      if($("#sideBar").is(":visible")){
        $("body").animate({"margin-left":"205px"})
      }else
      $("body").animate({"margin-left":"5px"})
    });
});

$("#sideBar").mouseenter(function(e){
  // Cancel the event for this element
   e.stopPropagation();
 if($("#sideBar").width()<=205){
       $("body").animate({"margin-left":"405px"})
    $("#sideBar").animate({"width":"405px"})
 }
  
}).mouseleave(function(){
 // setTimeout(() => {
    $("body").animate({"margin-left":"205px"})
    $("#sideBar").animate({"width":"205px"})
//  }, 4000);
})

// $(document).keydown(function (e) {

//     if (document.activeElement.tagName != "TEXTAREA") {

//         if (e.keyCode == 46) {
//             //console.log(document.activeElement.tagName);
//             if($("#mode").text()=="Html"){
//                 var res = confirm("هل تريد حذف العنصر المنتقى ؟؟");
//                 if (res) {

//                     selected.remove();
//                     saveChangeHtml();
//                 }
//             }
//         }

//         if (e.keyCode == 107) {


//             //var now_bw = bw;
//             var next_bw = bw + 1;

//             if (next_bw <= 8) {

//                 $(".el , .el input[type]").each((index, el) => {
//                     // $(el).removeClass("el_border"+now_bw) ; 
//                     $(el).removeClass("el_border_0 el_border_1 el_border_2 el_border_3 el_border_3 el_border_4 el_border_5 el_border_6 el_border_7 el_border_8");
//                     $(el).addClass("el_border_" + next_bw);
//                     $("#view_var").text(next_bw);
                    

//                 })
//                 if (bw + 1 <= 8) bw++;
//                 saveChangeHtml();
//             }


//         }

//         if (e.keyCode == 109) {

//             var now_bw = bw;
//             var prev_bw = bw - 1;

//             if (prev_bw > -1) {

//                 $(".el , .el input[type]").each((index, el) => {

//                     ///   if ($(el).hasClass("el_border_"+ now_bw)){    

//                     // $(el).removeClass("el_border_"+now_bw)
//                     $(el).removeClass("el_border_0 el_border_1 el_border_2 el_border_3 el_border_3 el_border_4 el_border_5 el_border_6 el_border_7 el_border_8");
//                     $("#view_var").text(prev_bw);
//                     if (prev_bw !== 0)
//                         $(el).addClass("el_border_" + prev_bw)
                      

//                 })
//             }

//             if (bw > 0) bw--;
//             saveChangeHtml()
//         }

//     }
// });

//=====================================================================



// document.addEventListener("showAlert", function () {  
//    // $("#scriptSheet").html($("#jsEditor").val());
//    // $("#styleSheet").html($("#cssViewers").val());

//   if($("#mode").text() =="Livewire"){
//        setTimeout(() => {
//         console.log("Okkk..")
//         runJsCode(jsEditor.getValue())
//     }, 2000); 
//   }

// })



//========================================================


$("#saveComponent").click(function () {
    window.livewire.emit("saveComponent", compEditor.getValue());
 
})

// function redo(){

//     $("#wrapper").$(copiesArr[copiesPointer]);    
//     copiesPointer = copiesPointer>0?copiesPointer--:copiesPointer;
// }

// function endo(){
    
//     copiesPointer = copiesPointer+1<copiesArr.length?copiesPointer++:copiesPointer;
//     $("#wrapper").$(copiesArr[copiesPointer]);    
    
// }


// $("#copyele").click(function () {
//     copiesHtml = selected[0].outerHTML;
//     copiesHtml = copiesHtml.replaceAll("HE_border_click" ,"");
// })

// $("#Append").click(function () {   
   
//     selected.append(copiesHtml);
//     saveChangeHtml();
//    // runJs()

// })
// $("#pastAfter").click(function () {

//     $(copiesHtml).insertAfter($(selected));
//      saveChangeHtml();
  
// })
// $("#prepend").click(function () {   
 
//     selected.prepend(copiesHtml);
//      saveChangeHtml();
   
//  })

//  $("#insertBefore").click(function () {   
//     $(copiesHtml).insertBefore($(selected));
//     saveChangeHtml();   // mht: send html to server
//  })

$("#newComponent").click(function () {

    var fName = prompt("Enter File Name");


    if (fName) {

        window.livewire.emit("CreateComponent", fName);

        //  $("#fileNameNow").val(fName).change();


    }
})




$("#changeValue").click(function () {
    var val = prompt("Enter Value :", selected.attr("value"));

    selected.attr("value", val)
    saveChangeHtml();

})
$("#changeText").click(function () {
    var val = prompt("Enter Text :", selected.text()); 
    var result = val.localeCompare(selected.text());
    if(result!==val){
        selected.text(val);
        saveChangeHtml();
    }    
})

$("#addClass").click(function () {

    let cl;

    navigator.clipboard.readText().then(clipText => {

        cl = prompt("Enter Text :", clipText);

        if (cl) {
            selected.addClass(cl);
            saveChangeHtml();
        }
    })

})




$("#addId").click(function (e) {

    var val = prompt("Enter id :", selected.attr("id"));
    if (val){
        selected.attr("id", val);
        saveChangeHtml();
      }
});

//------------------------------------------

// $("#viewHtml").click(function () {

//     window.livewire.emit("viewHtml", []);
    
//     setTimeout(() => {
//     $("#wrapper").find("*").each((index, ele) => {
//             $(ele).addClass("el");
//      }) 
//     stopJsCode();
//     saveChangeHtml();   
//     }, 1000); 
// });

// // VIEWlIVE
// $("#viewLive").click(function () {    
//     window.livewire.emit("viewLive", []);   
//     setTimeout(function(){
//     runJsCode();  
//    // toast("live Mode");
//    // refreshLiveMode();
//    }, 2000)

//    // save to blade file 
//    //viewLive : render as Live wire
 
// });


//--------------------------------------------------------

function setEditorStyle() {

    // htmlEditor = CodeMirror.fromTextArea(document.getElementById("TaViewer"), {
    //     lineNumbers: true,
    //     tabSize: 4,
    //     indentUnit: 4,
    //     mode: "xml"
    // });

    // htmlEditor.setOption("theme", "abcdef");


    jsEditor = CodeMirror.fromTextArea(document.getElementById("JsViewers"), {
        lineNumbers: true,
        tabSize: 4,
        mode: "javascript"
    });
    jsEditor.setOption("theme", "abcdef");

    
    cssEditor = CodeMirror.fromTextArea(document.getElementById("cssViewers"), {
        lineNumbers: true,
        tabSize: 4,
        mode: "css"
    });

    cssEditor.setOption("theme", "abcdef");

    // compEditor = CodeMirror.fromTextArea(document.getElementById("compViewersArea"), {
    //     lineNumbers: true,
    //     tabSize: 4,
    //     mode: "javascript"
    // });
    // compEditor.setOption("theme", "abcdef");


    cssEditor.setValue($("#cssViewers").val());  
    jsEditor.setValue($("#JsViewers").val());  
   
  //$("#editHtml , #editjs , #editcss").trigger("click");

   $("#editjs , #editcss").trigger("click");

    getStateOfEle();

    //toast("ok events");
}

$("#addElement").click(function (e) {

    //   $("#elemsWrap").css("display" ,"flex");
    $("#elemsWrap").
    css("display", "flex").css("opacity", "1");

});

$("#hide_ele").click(function (e) {
    $("#elemsWrap").css("display","none");
})


window.onfocus = function() {
    // if($("#mode").text="Html")
    // window.livewire.emit("viewHtml", []);
    // toast("the page is refreshed ..")
};

function runJsCode (code){ 

    if(window.global_fun !== undefined ){

        delete window.global_fun;
        window.global_fun =""; 

        console.log("remove global_fun")
       }
         
  window.global_fun = new Function(code);  
  global_fun();
}

function stopJsCode(){    
  delete  window.global_fun;
  window.global_fun = "";    
 }

//  $("body").on("click",".EditRow",function() {
//     var obj = {};  
//     var id= $(this).attr("data-id");
//     var row= $(this).attr("data-row");
//     var eles = $(this).parent().parent().children();    
    
//     eles.each((i,el)=>{  

//         if($(el).find("input[type=text]").length>0)
//          {            
//               obj[$(el).find("input[type=text]").attr("data-col")]=$(el).find("input[type=text]").val();    
//          }

//          if($(el).find("input[type=hidden]").length>0)
//          {            
//               obj[$(el).find("input[type=hidden]").attr("data-col")]=$(el).find("input[type=hidden]").val();    
//          }

//           if($(el).find("textarea").length>0)
//          {            
//               obj[$(el).find("textarea").attr("data-col")]=$(el).find("textarea").val();    
//           }
       
//         }) 
//           console.log(obj);
//           window.livewire.emit("EditRow", id   ,obj, row );      
        
//   });

/*
  $("body").on("focus",".treeleaf",function(){

     $(this).css("background-color","yellow");

  })

*/




function init() {

   

  
 
    // $("body").append('<input type="text" class="form-control" >');

  
  
    setTimeout(function () {
        //$("#scriptSheet").html($("#JsViewers").val());
       // eval($("#JsViewers").val());

        $("#styleSheet").html($("#cssViewers").val());

        setEditorStyle();
        runJsCode(jsEditor.getValue());
        getStateOfEle();
        $(".topMsg").fadeOutAndRemove('fast');
       
       // eval($("#jsViewers").val());
       // $("#wrapper").html(commentBlade($("#TaViewer").val()));


       

    }, 2000);

}




init();