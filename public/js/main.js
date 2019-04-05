let exist = false;
let existE = false;
$(document).ready(function () {
  
    let $newName = $("#prodNameAdd");
    let $newCall = $("#prodCallAdd");
    let $newFats = $("#prodFatsAdd");
    let $newProt = $("#prodProtAdd");
    let $newCarbo = $("#prodCarboAdd");

   


});
function addExercise(name, intensity, id) {
    var $newLeft = $("#copyThis").clone().appendTo("#bigLeft");
    var $newRight = $("#copyLeftEx").clone();
    $newLeft.addClass("copied");
    $newRight.attr('id', id);
    $newLeft.attr('id', id);
    $newLeft.find(".exName").text(name);
    $newLeft.find(".intensity").text(intensity);
    $newRight.find("#prodLeftEx").text(name);
    $newLeft.find("#buy").click(function () {
        if(existE==false){
        $newRight.appendTo("#prodLeft");
        $newRight.find("#currProdId").attr('value', id);
        $newRight.show();
        existE = true;
        }
    });


    $newLeft.removeClass("copied");
    $newLeft.show();

}
function addProd(name, cal, fats, prot, carbo,id) {
    var $newLeft = $("#copyThis").clone().appendTo("#bigLeft");
    var $newRight = $("#copyLeftEx").clone();
    $newLeft.addClass("copied");
    $newRight.attr('id', id);
    $newLeft.attr('id', id);
    $newLeft.addClass(id.toString());
    $newLeft.find(".prodName").text(name);
    $newLeft.find(".calProd").text(cal);
    $newLeft.find(".fatProd").text(fats);
    $newLeft.find(".protProd").text(prot);
    $newLeft.find(".carboProd").text(carbo);
    $newRight.find("#prodLeftEx").text(name);
    $newLeft.find("#buy").click(function () {
        if(exist==false){
        $newRight.appendTo("#prodLeft");
        $newRight.find("#currProdId").attr('value', id);
        $newRight.show();
        exist = true;
        }

    });


    $newLeft.removeClass("copied");
    $newLeft.show();
}

