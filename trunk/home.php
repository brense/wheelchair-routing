<div id="wrapper">
	<div id="logo"><img src="img/drempelvrij.png" alt="drempelvrij" /></div>
	<div id="top"><img src="img/logo.png" alt="logo" /></div>
	<div id="menu">
		<h1>navigatie</h1>
		<form action="map/" method="get">
			<ul id="homeMenu">
				<li class="menuItem">
					<label for="from">Van</label>
				</li>
				<li class="menuInput">
					<input type="text" name="from" id="from" value="Calandstraat 7" />
				</li>
				<li class="menuItem">
					<label for="via1">Via</label>
				</li>
				<li class="menuItem">
					<a href="" class="addVia">Voeg via adres toe...</a>
				</li>
				<li class="menuItem">
					<label for="to">Naar</label>
				</li>
				<li class="menuInput">
					<input type="text" name="to" id="to" value="Calandstraat 7" />
				</li>
				<li class="menuInput">
					<div class="radio">
						<label for="type">Snelste</label>
						<input type="radio" name="type" id="typeShortest" value="shortest" />
						<label for="type">Beste</label>
						<input type="radio" name="type" id="typeBest" value="best" checked="checked" />
					</div>
				</li>
				<li class="menuItem">
					<input type="submit" name="submit" id="submit" value="plan route" />
				</li>
			</ul>
		</form>
	</div>
</div>
<script type="text/javascript">
$('#homeMenu').delegate('.addVia', 'click', function(e){
	e.preventDefault();
	$('.addVia').parent().before('<li class="menuInput"><input type="text" name="via" id="via" class="via" value="" /></li>');
});

$('form').bind('submit', function(e){
	e.preventDefault();
	var from = $(this).find('#from').val().replace(' ', '+')+',+Rotterdam,+Nederland';
	var via = '';
	$.each($('input.via'), function(i, v){
		via = via+'&via['+i+']='+$(v).val().replace(' ', '+')+',+Rotterdam,+Nederland';
	});
	var to = $(this).find('#to').val().replace(' ', '+')+',+Rotterdam,+Nederland';
	var type = $("input[@name=type]:checked").val();
	window.location = 'map/?from='+from+via+'&to='+to+'&type='+type;
});
</script>