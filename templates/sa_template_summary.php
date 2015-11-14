<?php 

function sa_template_Summary( $currentEventInfo, $bidderInfo, $tableNumber, $auctionSections ){
	$bidderTotal = 0;
	
	ob_start();

	?>
	<style>
.gradient {
	border:0.1mm solid #220044; 
	background-color: #f0f2ff;
	background-gradient: linear #c7cdde #f0f2ff 0 1 0 0.5;
}
h4 {
	font-family: sans;
	font-weight: bold;
	margin-top: 1em;
	margin-bottom: 0.5em;
}
div {
	padding:1em; 
	margin-bottom: 1em;
	text-align:justify; 
}
.bottomcenter { position: absolute; 
	overflow: visible;
	margin-left:auto;
	margin-right:auto;
	width:80%;	
	
	bottom: 35px; 	
	padding: 1.5em;	
	margin: 0;
}

</style>
<body>

	<div style="font-family:Arial;">
		
		<h1>MOEDA Gala Guest Summary</h1>
		
		<table class="form-table" width="100%" >	

		<tr>
		<td scope="row"><?php echo htmlspecialchars( $bidderInfo[ 'name' ] ); ?></td>
		<td>&nbsp;</td>
		</tr>		
		
		<tr>
		<td scope="row" colspan="2">&nbsp;</td>
		</tr>
		
		<tr>
		<td scope="row">
			<label for="name"><strong>Bidder Number:</strong></label>
			<?php echo htmlspecialchars( $bidderInfo[ 'bidderNumber' ] ); ?>
		</td>
		<td>
			<label for="name"><strong>Table Number:</strong></label>
			<?php echo htmlspecialchars( $tableNumber ); ?>
		</td>
		</tr>		
		
		<tr>
		<td scope="row" colspan="2"><hr /></td>
		</tr>
		
		<tr>
		<td scope="row">
			<label for="name"><strong>Item</strong></label>			
		</td>
		<td>
			<label for="name"><strong>Amount</strong></label>			
		</td>
		</tr>		

		<?php foreach ( $auctionSections as $sect ): ?>			
			<?php foreach ( $sect[ 'items' ] as $item ): ?>
				<?php $bidderTotal += $item[ 'winningBid' ]; ?>
				<tr>
				<td scope="row"><label for="name"><i><?php echo $item[ 'title' ]; ?></i></label></td>
				<td>
					<p><?php echo sprintf( "$%.2f", $item[ 'winningBid' ] ); ?></p>
				</td>
				</tr>
			<?php endforeach; ?>
			
			<tr>
			<td scope="row" colspan="2">&nbsp;</td>
			</tr>
			
		<?php endforeach; ?>
		<tr>
		<td scope="row" colspan="2">&nbsp;</td>
		</tr>
		
		<tr>
		<td scope="row"><label for="total"><strong>Total</strong></label></td>
		<td><?php echo sprintf( "$%.2f", $bidderTotal ); ?></td>
		</tr>

		<tr>
		<td scope="row" colspan="2">&nbsp;</td>
		</tr>
		
		<tr>
		<td scope="row" colspan="2">&nbsp;</td>
		</tr>
		
		</table>
		
		<div style="font-size:12px;">
		<p>
			PAYMENT METHOD:			
		</p>
		
		<p>
		____CASH<br />
		____CHECK NUMBER_____<br />
		____CREDIT CARD
		</p>
		</div>
		
		
	</div>
	
<div class="bottomcenter">
	<i>
	MOEDA is recognized by the Internal Revenue Service as a section 501(c)(3) public charity.  As such, your contribution is tax deductible for federal income tax purposes in accordance with section 170.  No goods or services have been provided to you by MOEDA in consideration, in whole or in part, for your donation.
	</i>
</div>
</body>
<?php

	return ob_get_clean();		
	
	$html = '
<style>
.gradient {
	border:0.1mm solid #220044; 
	background-color: #f0f2ff;
	background-gradient: linear #c7cdde #f0f2ff 0 1 0 0.5;
}
h4 {
	font-family: sans;
	font-weight: bold;
	margin-top: 1em;
	margin-bottom: 0.5em;
}
div {
	padding:1em; 
	margin-bottom: 1em;
	text-align:justify; 
}
.myfixed1 { position: absolute; 
	overflow: visible; 
	left: 0; 
	bottom: 0; 
	border: 1px solid #880000; 
	background-color: #FFEEDD; 
	background-gradient: linear #dec7cd #fff0f2 0 1 0 0.5;  
	padding: 1.5em; 
	font-family:sans; 
	margin: 0;
}
.myfixed2 { position: fixed; 
	overflow: auto; 
	right: 0;
	bottom: 0mm; 
	width: 65mm; 
	border: 1px solid #880000; 
	background-color: #FFEEDD; 
	background-gradient: linear #dec7cd #fff0f2 0 1 0 0.5;  
	padding: 0.5em; 
	font-family:sans; 
	margin: 0;
	rotate: 90;
}
</style>

<body>


<div class="myfixed1">1 Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo.</div>

<div class="myfixed2">2 Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo.</div>

</body>

';

	return $html;
}

?>