
    </div><!-- /.container -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
  	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
  	
  	<script>
  		function orderSubmit() {
  			document.getElementById("orderSubmitInfo").innerHTML = '<div class=\"panel panel-success\">' +
						'<div class=\"panel-heading\">' +
							'Success!' +
						'</div>' +
						'<div class=\"panel-body\">' +
							'Your order is being placed. Please wait for a confirmation.' +
						'</div>' +
				'</div>';
  			document.getElementById("orderForm").submit();
  		}
  		
  	</script>

</body></html>