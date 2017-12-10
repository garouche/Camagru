window.onload =  function(){
    var imgContainer = document.getElementsByClassName("galeryImgContainer");
    var displayImgContainer = document.querySelector(".displayImgContainer");

    if (displayImgContainer){
        displayImgContainer.style.visibility = "visible";
    }

    if (imgContainer) {
        for (var i = 0; i < imgContainer.length; i++) {
            imgContainer[i].addEventListener("click", function () {
                console.log(this.getElementsByClassName("displayImage")[0].submit());
            });
            imgContainer[i].style.cursor = "pointer";
        }
        ;
    }


}