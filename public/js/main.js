$(document).ready(function () {
    let $newName = $("#prodExAdd");
    let $newIntense = $("#prodIntenseAdd");

    $("#add").click(function ()
    {

        let new_name = $newName.val();
        let new_intensity = $newIntense.val();
        addItem(new_name, new_intensity);
    }
    );


});
function addExercise(name, intensity) {
    var isBought = false;
    var quantity = 1;
    var $newLeft = $("#copyThis").clone().appendTo("#bigLeft");
    var $newRight = $("#copyLeftEx").clone().appendTo("#prodLeft");
    $newLeft.addClass("copied");
    $newLeft.find(".exName").text(name);
    $newLeft.find(".intensity").text(intensity);
    $newRight.find("#prodLeftEx").text(name);
    $newLeft.find("#plus").click(function () {
        quantity += 1;
        $newLeft.find(".counter").text(quantity);
        $newRight.find("#prodLeftQuantity").text(quantity);
    });
    $newLeft.find("#minus").click(function () {
        if (quantity > 1) {
            quantity -= 1;
            $newLeft.find(".counter").text(quantity);
            $newRight.find("#prodLeftQuantity").text(quantity);
        }
    });
    $newLeft.find("#delete").click(function () {
        $newLeft.remove();
        $newRight.remove();
    }


    );
    $newLeft.find("#buy").click(function () {
        if (isBought == false) {
            $newRight.appendTo("#prodBought");
            $newLeft.find("#plus").prop("disabled", true);
            $newLeft.find("#minus").prop("disabled", true);
            $newLeft.find("#delete").prop("disabled", true);
            isBought = true;
            $newRight.show();
            $newLeft.find("#buy").text("Не куплено");
        } else {
            $newRight.appendTo("#prodLeft");
            $newLeft.find("#plus").prop("disabled", false);
            $newLeft.find("#minus").prop("disabled", false);
            $newLeft.find("#delete").prop("disabled", false);
            $newLeft.find("#buy").prop("disabled", false);
            isBought = false;
            $newLeft.find("#buy").text("Куплено");
        }
    });


    $newLeft.removeClass("copied");
    $newLeft.show();

}
function addProd(name, cal, fats, prot, carbo) {
    var isBought = false;
    var quantity = 1;
    var $newLeft = $("#copyThis").clone().appendTo("#bigLeft");
    var $newRight = $("#copyLeftEx").clone().appendTo("#prodLeft");
    $newLeft.addClass("copied");
    $newLeft.find(".prodName").text(name);
    $newLeft.find(".calProd").text(cal);
    $newLeft.find(".fatProd").text(fats);
    $newLeft.find(".protProd").text(prot);
    $newLeft.find(".carboProd").text(carbo);
    $newRight.find("#prodLeftEx").text(name);
    $newLeft.find("#buy").click(function () {
        $newRight.appendTo("#prodBought");
        $newRight.show();
        $newLeft.find("#buy").text("Не куплено");
    });
     $newLeft.removeClass("copied");    
    $newLeft.show();
}
;