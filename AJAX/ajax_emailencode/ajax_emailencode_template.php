<html>
	<head>
		<title>AJAX'd emailencode</title>
	</head>
	<body>

		Content....

		<script type="text/javascript">
		jQuery(document).ready(function($) {

			$('.filter').change(function() {

				$.get('/_inc/ajax.serve.php', {
					'series': 'serve',
					'key': 'category',
					'val': skillset
				}, function(o) {
					var response = o.split('<!--split-->');
					if (response[0] == '') {
						$('#opps').html('<p>No opportunities could be found for the selected filtering option.</p>');
					} else {
						$('#opps').html(response[0]);
						$("#opps").find("script").each(function() {
							eval($(this).text()); // execute code as Javascript
						});
					}
				});

			}); // get()

		}); // ready()
		</script>


	</body>
</html>
