var continueReel = 1;
new fullpage('#fullpage',{
    autoScrolling:true,
    fitToSectionDelay: 100,
    scrollingSpeed: 500,
    touchSensitivity: 10,
    navigation:false,
    normalScrollElements:'#ModalInfo,.normal-scroll',
    onLeave: function(origin, destination, direction){
        //$(".my-player").get(0).paused();
        //console.log(origin);
        //console.log(direction);
        //fullpage_api.moveSectionUp();
        if (destination.isLast==true) {
            if(continueReel==1){
                nextReel();
            }
        }else{
            autoplayf();
        }
    },
});

function autoplayf(){
    setTimeout(function(){
        $(".section.active .play-video").click();
    },500)
}

function nextReel(){
    console.log("Cargando...");
    $.ajax({
        type: 'POST',
        dataType: "html",
        url: base_url+'tienda/nextReel',
        data: "idActual="+idActual,
        success: function(res){
            if (res!="") {
                fullpage_api.moveSectionUp();
                $("#final").remove();
                $("#fullpage").append(res);
                fullpage_api.moveSectionDown();
                $("#fullpage").append('<div id="final" style="padding:20px 10px;text-align:center;background-color:#000;color:#fff;" class="section fp-section fp-table active fp-auto-height fp-completely">Cargando...</div>');
                autoplayf();
            }else{
                fullpage_api.moveSectionUp();
                $("#final").remove();
                $("#fullpage").append('<div id="final" style="padding:20px 10px;text-align:center;background-color:#000;color:#fff;" class="section fp-section fp-table active fp-auto-height fp-completely">No hay más productos que mostrar.</div>');
                continueReel=0;
                fullpage_api.moveSectionDown();
                autoplayf();
            }
        },
        error: function(){
            console.log("problemas de red");
        }
    }); //AJAX
}


$(document).ready(function(){
$("#fullpage").append('<div id="final" style="padding:20px 10px;text-align:center;background-color:#000;color:#fff;" class="section fp-section fp-table active fp-auto-height">Cargando...</div>');
});

var lastScrollTop = 0;
$(window).scroll(function(event){
   var st = $(this).scrollTop();
   if (st > lastScrollTop){
       console.log("Scroll Down");
    } else {
        console.log("Scroll Top");
   }
   lastScrollTop = st;
});

function loaded()
{
   /* var v = document.getElementById('my-player');
    var r = v.buffered;
    var total = v.duration;

    var start = r.start(0);
    var end = r.end(0);

    //$("#progressB").progressbar({value: (end/total)*100});
    var progreso = (end/total)*100;
    console.log(progreso);

    if (progreso > 35) {
        $(".loading-video").addClass("d-none");
        $(".play-video").removeClass("d-none");
    }
    setTimeout(function(){
        $(".loading-video").addClass("d-none");
        $(".play-video").removeClass("d-none");
    },10000); */
}

$('#my-player').bind('progress', function()
{
    loaded();
}
);
var videoC = 0;
function controlvideo(elem,ocul){
    if ($(elem).get(0).paused==true) {
        $(elem).get(0).play();
        $(ocul).addClass("d-none");
    }else{
        $(elem).get(0).pause();
        $(ocul).removeClass("d-none");
    }
}
function modalInfo(idc){
  console.log("Cargando...");
  $.ajax({
      type: 'POST',
      dataType: "html",
      url: base_url+'tienda/modalInfo',
      data: "id="+idc,
      success: function(res){
        $("#content-modal").html(res);
        $("#ModalInfo").modal("show");
      },
      error: function(){
          console.log("problemas de red");
      }
  }); //AJAX
}

function removMut(){
    $("video").removeAttr("muted");
    $(".section.active .play-video span").addClass("d-none");
}

$(window).on("load", removMut);