
(function ( $ ) {
$(document).ready( function() {
	$('#dPhysician').hide(); 
	$('#dSpecialist').hide(); 
	$('#dFacility').hide(); 

	$("#looking_for").change(function() {
		var val = $(this).val();
	    if(val === "facility") {
	    	$("#dPhysician").hide();
	        $("#dSpecialist").hide();
	        $("#dFacility").show();	               
	    }
	    else if(val === "specialist") {
	    	$("#dPhysician").hide();
	        $("#dSpecialist").show();
	        $("#dFacility").hide();	               
	    }
	    else if(val === "physician") {
	    	$("#dPhysician").show();
	        $("#dSpecialist").hide();
	        $("#dFacility").hide();	               
	    }
	  });

  $('#otherdropdownType').show(); 
  $('#othertextType').hide(); 

  $("#search_location_type").change(function() {
    var val = $(this).val();
      if(val === "address") {
          $("#otherdropdownType").hide();
          $("#othertextType").show();                
      }
      else if(val === "radius") {
          $("#otherdropdownType").show();
          $("#othertextType").hide();                
      }
     
    });
	});


$(document).ready(function(){
  $('#printPage').click(function(){
        var data = '<input type="button" value="Print this page" onClick="window.print()">';           
        data += '<div id="div_print">';
        data += $('#cnc-main').html();
        data += '</div>';

        myWindow=window.open('','','width=800,height=600');
        myWindow.innerWidth = screen.width;
        myWindow.innerHeight = screen.height;
        myWindow.screenX = 0;
        myWindow.screenY = 0;
        myWindow.document.write(data);
        myWindow.focus();
    });
 });


$(function() {
    $("#updateModal").modal({
        show: false
    });

    $(".update_user_button").click(function () {
        var userId = +$(this).val();
        $("#updateModal").appendTo("body").modal('show');
        $("#alert").hide();
        $("#error_name").hide();
    });
  });

    $(function() {
         var locations = [
              ['Pan Africa Market', 47.608941, -122.340145, 1],
              ['Buddha Thai & Bar', 47.613591, -122.344394, 2]
            ];

            var map = new google.maps.Map(document.getElementById('map'), {
              zoom: 13,
              center: new google.maps.LatLng(47.6145, -122.3418),
              mapTypeId: google.maps.MapTypeId.ROADMAP
            });

            var infowindow = new google.maps.InfoWindow();

            var marker, i;

            for (i = 0; i < locations.length; i++) {  
              marker = new google.maps.Marker({
                position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                map: map
              });

              google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                  infowindow.setContent(locations[i][0]);
                  infowindow.open(map, marker);
                }
              })(marker, i));
            }
       });

})(jQuery)