
$(document).ready(function(){

  var $loading = $('#loadingDiv').hide();
  $(document)
    .ajaxStart(function () {
      $loading.show();
    })
    .ajaxStop(function () {
      $loading.hide();
    });


  $("#profile-link-button").click(function(){
    var profile_link = $("#profile-link").val();

   
    $.ajax({
      url: 'ajax.php',
      type: 'POST',
      data: {profileLink:profile_link},
      success: function(result){
      $("#profile-link-response").html(result);
        $("#profile-link-row").hide();
      }
    });
    

  });
});






