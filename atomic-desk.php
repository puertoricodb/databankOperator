<?php
// connect to database
mysql_connect('localhost', 'databank', 'databankpr');
mysql_select_db('databank');

// process form
if(isset($_POST['id'])){
	// check if category exists
	$category_result = mysql_query("SELECT `id` FROM `categories` WHERE `category` = '".$_POST['category']."'");
	
	// assign category id
	if(mysql_num_rows($category_result)==0){
		mysql_query("INSERT INTO `categories` (`category`) VALUE ('".$_POST['category']."')");
		$category_id = mysql_insert_id();
	}else{
		$category_array = mysql_fetch_array($category_result, MYSQL_ASSOC);
		$category_id =$category_array['id'];
	}
	
	// update the atom
	mysql_query("UPDATE `atoms` SET `title` = '".$_POST['title']."', `category_id` = '".$category_id."', `status` = 'UPDATED' WHERE `id` = '".$_POST['id']."' LIMIT 1 ");
}

// look for new atoms
$result_atom = mysql_query("SELECT * FROM `atoms` WHERE `status` = 'NEW' ORDER BY RAND() LIMIT 1");
if(mysql_num_rows($result_atom)>0){
	$atom = mysql_fetch_array($result_atom, MYSQL_ASSOC);	
}else{
	$notice = "There are no more atoms to resolve.";
}

?>
<?php if(isset($notice)){echo $notice;}else{?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Atomic Desktop - An operator sets information for an atom</title>
	
	<!-- CSS -->
    <link href="css/style.css" media="screen" rel="stylesheet" type="text/css"/>
	<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
	<!--[if lt IE 9]>
	<link href="css/ie.css" media="screen" rel="stylesheet" type="text/css"/>
	<![endif]-->
	<style>
		body{
			background:#fafafa;
		}

		.wrapper{
			padding:36px;
			border:1px solid #ddd;
			border-radius:6px;
			-moz-border-radius:6px;
			-webkit-border-radius:6px;
			background:#fff;
			min-height:450px;
		}
		
		#foursquare_logo{
			width:125px;
			height:auto;
		}
		
		#foursquare li{
			background:#e1edbf;
			color:#3369b2;
			padding:5px;
			margin:2px;
			font-weight:bold;
		}
		
		#foursquare li:hover{
			background:#3369b2;
			color:white;
		}
	</style>

	<!-- JAVASCRIPT -->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
	<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyAcPqUu8vXxy9EQhDn6hwqEBTT5sXbizt4&sensor=true"></script>
	
	<!-- READY -->
	<script>
	$(document).ready(function() {

		$( "#category" ).autocomplete({
			source: "atomic-catcher.php?action=fetch_categories",
			minLength: 2,
			select: function( event, ui ) {}
		});

		var myLatlng = new google.maps.LatLng(<?=$atom['lat'];?>, <?=$atom['lng'];?>);
		var myOptions = {
			zoom: 12,
			center: myLatlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		}
		  	
		var map = new google.maps.Map(document.getElementById("map"), myOptions);

		var marker = new google.maps.Marker({
			position: myLatlng,
			map: map,
			title:"<?=$atom['title'];?>"
		});
		
		$('#venues li').live("click", function(){
			$('#title').val($(this).html());
			$('#foursquare_id').val($(this).attr('id'));
		});

		//Get foursquare places
		jQuery.ajax({
		  url: 'https://api.foursquare.com/v2/venues/explore',
		  type: 'GET',
		  dataType: 'json',
		  data: {
			ll: "<?=$atom['lat'];?>,<?=$atom['lng'];?>",
			intent:'browse',
			radius:500,
			client_id:'PH453A5331TBQX4PCMLNWW124DZVOBEHDWAJ2AZ2UWWYVTI0',
			client_secret:'VOV2IQ0NPCWFVPKNDRS5MPWQZYEMJUA5B3VVCZFEX04GG3YQ',
			v:'20110201'
			},
		  complete: function(xhr, textStatus) {
		    //called when complete
		  },
		  success: function(data, textStatus, xhr) {
			$.each(data.response.groups[0].items, function(index, value) { 
				$('#venues').append('<li id="'+value.venue.id+'">'+value.venue.name+'</li>');
			});

		  },
		  error: function(xhr, textStatus, errorThrown) {
		    //called when there is an error
		  }
		});
		

	});
	</script>
	


</head>
<body>
	<div class="wrapper clearfix">

	    <div id="contact-wrapper" class="clearfix">

	        <div class="form-wrapper clearfix">

	            <h2>Atom Identifier</h2>

	            <div class="message">
	            <?php echo !empty($error_list) ? $error_list : ''; ?>
	            </div>
	
				<form id="contact-form" method="post" accept-charset="utf-8" enctypr="multipart/form-data">

					<fieldset>
						
	                    <div class="field">
							<label for="title">Title: </label>
							<input id="title" name="title" value="<?=$atom['title'];?>" title="Give this atom a name" autofocus required="required">
						</div>

	                    <div class="field">
							<label for="category">Category: </label>
							<input id="category" name="category" />
						</div>

	                    <div class="field">
							<label for="latitude">Latitude: </label>
							<input name="latitude" value="<?=$atom['lat'];?>" readonly/>
						</div>
						
	                    <div class="field">
							<label for="longitude">Longitude: </label>
							<input name="longitude" value="<?=$atom['lng'];?>" readonly/>
						</div>
						
	                    <div class="field submit">
							<input type="hidden" name="id" value="<?=$atom['id'];?>">
							<input id="foursquare_id" type="hidden" name="foursquare_id" value="<?=$atom['foursquare_id'];?>">
							<input type="submit" value="submit">
	                    </div>
					
					</fieldset>
				</form>
			
			</div>
	
	        <div class="address-wrapper">
	            <!-- This is the container for the image -->
	            <div class="street-address">Atom View</div>
	            <div id="img-outer">
	                <div id="img"><img src="atom_snaps/<?=$atom['img'];?>" alt=""></div>
	            </div>
	
	        </div>

	        <div class="address-wrapper">
	            <!-- This is the container for the map -->
	            <div class="street-address">Atom Location</div>
	            <div id="map-outer">
	                <div id="map">&nbsp;</div>
	            </div>
			</div>
			
			<!-- Try Foursquare integration -->
			<div class="foursquare-wrapper">
	            <div class="street-address">Share electron</div>
	            <div id="map-outer" style="overflow-y:scroll">
					<div id="foursquare"><ul id="venues"></ul></div>
				</div>
				<img id="foursquare_logo" src="images/foursquare.png">
			</div>
	    </div>
	</div>


</body>
</html>
<?php } ?>