//$(".left-sidebar").toggle("left");
$(".tog_left").click(function(){
    if($(".left-sidebar").width() > 0){
        $(".left-sidebar").animate({width: "0px"});
        $(".page-wrapper").animate({"margin-left": "0px"});
    }else{
        $(".left-sidebar").animate({width: "260px"});
       $(".page-wrapper").animate({"margin-left": "260px"});
    }
   
});

function myFunction(x) {
    x.classList.toggle("change");
  }

 

 
