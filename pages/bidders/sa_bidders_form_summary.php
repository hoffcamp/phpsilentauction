<?php


class SA_Form_BidderSummary
{
	var $bidderID;
	var $action;
	
	function __construct( $bidderID, $bidderNumber, $action = '' ){
		$this-> bidderID = $bidderID;
		$this-> bidderNumber = $bidderNumber;
		$this-> action = $action;
	}
	
	function splitItemsIntoSections( $auctionItems ){
		global $SA_Tables;
		
		$result = array();
		
		$currentEventID = get_option( 'sa-current-event', '' );
		$itemSections = $SA_Tables-> itemSections-> getAll( $currentEventID );
		$itemsByID = array();
		foreach ( $itemSections as $item ){
			$itemsByID[ $item['ID'] ] = $item;
		}
		
		foreach ( $auctionItems as $item ){
			$sectionID = $item[ 'sectionID' ];
			if ( !isset( $result[ $sectionID ] ) ){
				$result[ $sectionID ] = array(
					'title' => $itemsByID[ $item[ 'sectionID' ] ][ 'title' ],
					'items' => array()
					);
			}
			
			$result[ $sectionID ][ 'items' ][] = $item;
		}
		
		return $result;
	}
	
	function renderForm(){
		global $SA_Tables;
		
		global $SA_DIR;
		require( $SA_DIR . "/mpdf60/mpdf.php" );

		$currentEventID = get_option( 'sa-current-event', '' );
		
		$currentEventInfo = $SA_Tables-> events-> getByID( $currentEventID );
		$bidderInfo = $SA_Tables-> getBidderInfo( $currentEventID, $this-> bidderID );
		$auctionItems = $SA_Tables-> items-> getWinningBidsByBidderNumber( $this-> bidderNumber );
		
		$auctionSections = $this-> splitItemsIntoSections( $auctionItems );		
	
		$tableNumber = floor( $bidderInfo[ 'bidderNumber' ] / 10.0 );
		$ob = sa_template_Summary( $currentEventInfo, $bidderInfo, $tableNumber, $auctionSections );		
		
		$pdf = new mPDF();	
		$pdf->WriteHTML( $ob );
		$docStr = $pdf->Output( "doc.pdf", "S" );

		?>
		<div style="width:100%;height:600px;">
		<embed width=100% height=100%type="application/pdf" src="data:application/pdf;base64,<?php echo base64_encode($docStr); ?>"></embed>
		</div>
		<?php
		
		// echo $ob;
	}
	
	function processFormPost(){
		
	}
}
?>