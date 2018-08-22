function fun(id){
  // alert(id);
  document.getElementById(id).style.display = "block";
  slider(id);
}

function closediv(id){
  document.getElementById(id).style.display = "none";
}

function slider(id){
  var sliderImages = document.querySelectorAll("#s"+id),
      arrowLeft = document.querySelector("#arrow-left"+id),
      arrowRight = document.querySelector("#arrow-right"+id),
      current = 0;

      // Clear all images
      function reset() {
        for (var i = 0; i < sliderImages.length; i++) {
          sliderImages[i].style.display = "none";
        }
      }

      // Init slider
      function startSlide() {
        reset();
        sliderImages[0].style.display = "block";
      }

      // Show prev
      function slideLeft() {
        reset();
        sliderImages[current - 1].style.display = "block";
        current--;
      }

      // Show next
      function slideRight() {
        reset();
        sliderImages[current + 1].style.display = "block";
        current++;
      }

      // Left arrow click
      arrowLeft.addEventListener("click", function() {
        if (current === 0) {
          current = sliderImages.length;
        }
        slideLeft();
      });

      // Right arrow click
      arrowRight.addEventListener("click", function() {
        if (current === sliderImages.length - 1) {
          current = -1;
        }
        slideRight();
      });
      startSlide();
}

function upload(){
  document.getElementById('progress').style.display = "block";
  document.getElementById('f1').action = "uploadall.php";
  document.getElementById('f1').submit();
}

function download(){
  document.getElementById('f1').action = "downloadall.php";
  document.getElementById('f1').submit();
}

function singleProgress(id){
  document.getElementById('p'+id).style.display = "block";
}
