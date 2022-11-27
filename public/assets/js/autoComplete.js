function returnStyle(me, display) {  
    var hiDiv = 150;
    var mt =parseInt( $("body").css("margin-top"))
    var ml = parseInt($("body").css("margin-left"))

    var rect = $(me)[0].getBoundingClientRect();
  
    var obj = {
        width: $(me).outerWidth(),
        hi: $(me).outerHeight(),
        // offsetTop:rect.y + mt,
        // offsetLeft:rect.x - ml,

        offsetTop:$(me).offset().top,
        offsetLeft:$(me).offset().left,
     
         disp: display
    }
    var style = `width:${obj.width}px;height:${hiDiv}px;left:${obj.offsetLeft}px; top:${obj.offsetTop + obj.hi}px;display:${obj.disp}`;

    return style;

}


function lookupEvent() 
{    
   // toast("lookup refreshed");

    $("input.lookup ,  textarea.lookup").focus(function () {  

      
       window.livewire.emit("setStyle", returnStyle(this, "block") , $(this).attr("id") , $(this).attr("data-lookup") , $(this).val() );       
    })  
    
    $("input.lookup ,  textarea.lookup").on("input" ,function () {  
   //    toast("setstyle");
        window.livewire.emit("setStyle", returnStyle(this, "block") , $(this).attr("id") , $(this).attr("data-lookup") , $(this).val() );       
     }) 

    $("input.lookup ,  textarea.lookup").blur(function () {     

        window.livewire.emit("closeFloat");       
    })   
}


