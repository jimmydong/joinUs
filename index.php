<?php 
include('include.inc.php');
include('GoogleMap.class.php');
$me = check_login();
show_header();

$my_rank = json_decode(stripslashes($me['json_rank']), true);
$where = [];
$my_game = [];
foreach($my_rank as $game_id=>$rank){
	if(! $rank)continue;
	$where[] = " (game_id='{$game_id}' and rank='{$rank}') ";
	$my_game[$game_id] = Util::getById('game', $game_id)['name'] . "(rank: {$rank})";
}
$location = [];
if($where){
	$list = $db->fetchAll("select * from appointment where	(" . implode(' OR ', $where) . ") order by id desc");
	foreach($list as $key=>$val){
		$user = Util::getById('user', $val['user_id']);
		$game = Util::getById('game', $val['game_id']);
		$yard = Util::getById('yard', $val['yard_id']);
		
		if($val['user_id'] == $me['id']) $join = "<a href='script:;'>I called</a>";
		else $join = "<a href='javascript:;' onclick='join({$val['id']})'>Join now</a>";
		
		$location[] = [
				'label'=> $yard['name'],
				'title'=> "[{$game['name']}]<br/>date: {$val['date']}<br/>" . $join,
				'lat'=> floatval($yard['lat']),
				'lng'=> floatval($yard['lng']),
				'key'=> $key
		];
	}
}

?>
		
	<div id="app" class="container">
		<my-menu active_name="1"></my-menu>
		<br>
		<h3>Recommend Appointment</h3>
	<div id="map"></div>
	</div>
	
</body>
<script src="component/myMenu.js"></script>
<script>
	var data = {}

	var app = new Vue({
		el: '#app',
		data: data,
		methods:{
		},
		computed:{
		}
	})
</script>


<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GoogleMap::$key;?>"></script>
<script type="text/javascript">
    var locations = <?php echo json_encode($location);?>;
    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 13,
      center: new google.maps.LatLng(40.802829, -73.962616),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();

    var marker, i;

    for (i = 0; i < locations.length; i++) {  
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i]['lat'], locations[i]['lng']),
        label: {text: locations[i]['label'], color:'blue'},
        map: map
      });

      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i]['title']);
          infowindow.open(map, marker);
        }
      })(marker, i));
    }

    function join(id){
		$.post('list_handle.php?do=join', {id:id}, function(ret){
			if(ret && ret.success){
				alert('Join successfully');
			}else{
				alert('Error: ' + ret.msg);
			}
		},'JSON');
    }
  </script>
</html>