function downloadpage(url,multiple) {
  var show = 0;
  if(multiple=='multiple'){
    var temp = -1;
    var checks = $('input[type="checkbox"]:checked').map(function(){
      temp = $(this).val();
    }).get();
    if(temp != -1){
      var checks = $('input[type="checkbox"]:checked').map(function(){
        url = url + 'q[]=' + $(this).val() + '&';
      }).get();
    }else{
      show = 1;
      alert('Please select Atleast one Album.');
    }
  }
  if(show == 0){
    $('#downloadmodal').modal({
      backdrop: 'static',
      keyboard: false
    });
    document.getElementById('modelclose').style.display = "none";
    document.getElementById('downloadbutton').style.display = "none";
    document.getElementById('modelclosebutton').style.display = "none";
    document.getElementById('loading').style.display = "block";
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById('modelclose').style.display = "block";
        document.getElementById('downloadbutton').style.display = "block";
        document.getElementById('modelclosebutton').style.display = "block";
        document.getElementById('loading').style.display = "none";
      }
    };
    xhttp.open("GET", ""+url, true);
    xhttp.send();
  }
}

function uploadimage(url,multiple) {
  var show = 0;
  if(multiple=='multiple'){
    var temp = -1;
    var checks = $('input[type="checkbox"]:checked').map(function(){
      temp = $(this).val();
    }).get();
    if(temp != -1){
      var checks = $('input[type="checkbox"]:checked').map(function(){
        url = url + 'q[]=' + $(this).val() + '&';
      }).get();
    }else{
      show = 1;
      alert('Please select Atleast one Album.');
    }
  }
  if(show == 0){
    $('#uploadmodal').modal({
      backdrop: 'static',
      keyboard: false
    });
    document.getElementById('modelcloseupload').style.display = "none";
    document.getElementById('loadingupload').style.display = "block";
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById('loadingupload').style.display = "none";
        document.getElementById('uploadmodelclosebutton').style.display = "block";
        document.getElementById('uploadtext').innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", ""+url, true);
    xhttp.send();
  }
}