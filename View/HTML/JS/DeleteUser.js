$(document).ready(function () {

    $(".btn-modal").click(function () {
        $(".recuadro-modal").fadeIn(200);
    });

    $("#cancel").click(function () {
        $(".recuadro-modal").fadeOut(200);
        $("#passwd-baja").val("");
    });

    $(".recuadro-modal").click(function(e) {
        if ($(e.target).is(".recuadro-modal")) {
            $(".recuadro-modal").fadeOut(200);
            $("#passwd-baja").val("");
        }
    });

});