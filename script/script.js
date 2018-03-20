window.onload = function() {
    var publish = document.querySelector("#publishButton");
    var listFilter = document.getElementsByClassName("stickCursor");
    var posX = 0;
    var posY = 0;
    var pic = document.querySelector("#pic2");
    var streamVid = true;
    var upPic = document.querySelector("#upPic");

    navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.msGetUserMedia || navigator.oGetUserMedia || navigator.mozGetUserMedia;
    if (navigator.getUserMedia) {
        navigator.getUserMedia({video: true}, handleVideo, videoError);
    }

    function handleVideo(stream) {
        var video = document.querySelector("#videoElement");

        if (!publish) {
            document.querySelector("#pic").style.visibility = "hidden";
            addClickFilter();
        }

        if (!upPic && !publish) {
            video.src = window.URL.createObjectURL(stream);
            streamVid = true;
            uploadPic();
            document.querySelector("#takePic").addEventListener("click", function (ev) {
                takePicture();
            });
        }
        else {
            if (publish)
                deactivateFilter();
            document.querySelector("#takePic").style.visibility = "hidden";
            document.querySelector("#uploadPic").style.visibility = "hidden";
            if (document.querySelector("#buttonUpPic")) {
                document.querySelector("#buttonUpPic").onclick = function (ev) {
                    document.querySelector("#finalUpWidth").value = document.querySelector(".filterPos").width;
                    document.querySelector("#finalUpHeight").value = document.querySelector(".filterPos").height;
                    document.querySelector("#finalUpFilter").value = document.querySelector(".filterPos").src;
                    document.querySelector("#finalUpPic").value = document.querySelector("#upPic").src;
                    document.querySelector("#upFilterPos").value = posX + ";" + posY;
                };
            }
        }
    }

    function videoError() {
        streamVid = false;
        if (!upPic && !publish) {
            document.querySelector("#pic").style.visibility = "hidden";
            deactivateFilter();
            uploadPic();
        }
        else {
            handleVideo(null);
        }
        // upload.style.backgroundColor = "transparent";
        // upload.disabled = false;
        // upload.style.cursor = "pointer";
    }

    function uploadPic() {
        var upload = document.querySelector(".input-file");

        upload.onchange = function () {
            var size = document.querySelector(".input-file").files[0].size;

            if (size <= 2097152) {
                document.querySelector("#tmpUpWidth").value = document.querySelector(".videoContent").clientWidth;
                document.querySelector("#tmpUpHeight").value = document.querySelector(".videoContent").clientHeight;
                document.querySelector("#uploadForm").submit();
            }
            else {
                alert("Fichier trop gros.");
                upload.value = null;
            }
        };
    }

    function deactivateFilter() {
        for (var i = 0; i < listFilter.length; i++) {
            listFilter[i].disabled = true;
            listFilter[i].style.cursor = "default";
            listFilter[i].style.backgroundColor = "lightgray";
        }
    }

    function addClickFilter() {
        for (var i = 0; i < listFilter.length; i++) {
            listFilter[i].addEventListener("click", function (ev) {
                if (!pic || upPic) {
                    deactiveTakePic();
                    deactiveFinalizeMount();
                    placeImage(this);
                }
            });
        }
    }

    function takePicture() {
        var vid = document.querySelector("#videoElement");
        var window = document.querySelector(".videoContent");
        var height = vid.clientHeight;
        var width = vid.clientWidth;
        var canvas = document.querySelector("canvas");
        var image = document.querySelector("#pic");

        canvas.width = window.clientWidth;
        canvas.height = window.clientHeight;
        canvas.getContext('2d').drawImage(vid, 0, 0, width, height);
        var data = canvas.toDataURL('image/png', 1);
//    image.setAttribute('src', data);
        image.alt = "ok";
        document.querySelector("#filterWidth").value = document.querySelector(".filterPos").width;
        document.querySelector("#filterHeight").value = document.querySelector(".filterPos").height;
        document.querySelector("#finalFilter").value = document.querySelector(".filterPos").src;
        document.querySelector("#finalPic").value = data;
        document.querySelector("#finalFilterPos").value = posX + ";" + posY;
        // document.getElementById("imgUrl").value = data;
    }

    function placeImage(src) {
        var vid = document.querySelector("video");
        var img = document.querySelector(".filterPos");
        var init = false;

        function modifImg(ev) {
            var xpos = ev.clientX;
            var ypos = ev.clientY;
            var offX = ev.offsetX;
            var offY = ev.offsetY;

            img.style.left = xpos + "px";
            img.style.top = ypos + "px";
            img.addEventListener("click", function () {
                if (!upPic) {
                    activeTakePic();
                }
                else {
                    activeFinalizeMount();
                }

                posX = (ev.offsetX ? ev.offsetX : ev.layerX);
                posY = (ev.offsetY ? ev.offsetY : ev.layerY);
                vid.style.cursor = "default";
                img.style.cursor = "default";
                init = true;
            });
        }

        if (!img)
            img = document.createElement("img");
        img.className = "filterPos";
        img.src = src.getElementsByTagName("img")[0].src;
        img.style.position = "absolute";
        img.style.left = 0;
        img.style.width = "16vw";
        document.body.appendChild(img);
        vid.addEventListener("mousemove", function (ev) {
            if (!init) {
                var elementToChange = document.getElementsByTagName("video")[0];
                elementToChange.style.cursor = "url('" + src.getElementsByTagName("img")[0].src + "'), help";
                modifImg(ev);
            }
        }, false);

    }

    function deactiveFinalizeMount() {
        var buttonuppic = document.querySelector("#buttonUpPic");
        if (buttonuppic) {
            buttonuppic.disabled = true;
            buttonuppic.style.cursor = "default";
            buttonuppic.style.backgroundColor = "lightgray";
        }
    }

    function activeFinalizeMount() {
        var buttonuppic = document.querySelector("#buttonUpPic");

        if (buttonuppic) {
            buttonuppic.disabled = false;
            buttonuppic.style.cursor = "pointer";
            buttonuppic.style.backgroundColor = "Transparent";
        }
    }

    function activeTakePic() {
        var takepicbutton = document.querySelector("#takePic");
        if (takepicbutton) {
            takepicbutton.disabled = false;
            takepicbutton.style.cursor = "pointer";
            takepicbutton.style.backgroundColor = "Transparent";
        }
    }

    function deactiveTakePic() {
        var takepicbutton = document.querySelector("#takePic");

        if (takepicbutton) {
            takepicbutton.disabled = true;
            takepicbutton.style.cursor = "default";
            takepicbutton.style.backgroundColor = "lightgray";
        }
    }

    if (document.querySelector(".upError")) {
        alert(document.querySelector(".upError").innerHTML);
    }

    if (publish) {
        publish.addEventListener("click", function (ev) {
            document.querySelector("#Pic2").value = document.querySelector("#pic").src;
        });
    }
}