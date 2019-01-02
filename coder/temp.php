<?php
global $property_adr_text;
global $property_details_text;
global $property_features_text;
global $feature_list_array;
global $use_floor_plans;
global $currency;
$where_currency = 'after';
global $post;
$title = get_the_title();
$content = get_the_content();
$content = apply_filters('the_content', $content);
$content = str_replace(']]>', ']]&gt;', $content);
$property_city = strip_tags(get_the_term_list($post->ID, 'property_city', '', ', ', ''));
$property_area = get_the_term_list($post->ID, 'property_area', '', ', ', '');
$property_size = get_post_meta($post->ID, 'property_size', true);
$property_address = esc_html(get_post_meta($post->ID, 'property_address', true));
get_template_part('/templates/download_pdf');
$show_graph_prop_page = esc_html(get_option('wp_estate_show_graph_prop_page', ''));
?>
<div class= "col-md-12 prop_details test-site">  
		<h2 class="app-title"> 
			<?php

if (get_field('street_address')):
	print the_field('street_address') . ', ';
	$property_zip = get_post_meta($post->ID, 'property_zip', true);
	if ($property_zip != ''):
		echo $property_zip . ' ' . $property_city;
	endif;
endif;
?> 

		<?php //if($title) {echo $title;}else{ echo $property_address.' '.$property_city; }

 ?> </h2>
		<!--ul class= "descriptionlist-appt secextra caps comma-list">  
		<?php

if (get_field('street_address')): ?>		
		<li class="streetfield"><?php
	the_field('street_address'); ?></li>	
		<?php
	$property_zip = get_post_meta($post->ID, 'property_zip', true);
	if ($property_zip != '')
		{ ?>
		<li class="streetfield"><?php
		echo $property_zip; ?></li>	
		<li class="streetfield"><?php
		echo $property_city; ?></li>  
		<?php
		}

endif; ?>  
		</ul-->	

		<ul class= "descriptionlist-appt secextra comma-list">
		<li class="cityfield "><?php
echo $property_address; ?> | 
		<?php //if( get_field('appttype') ):
 ?>		
		<!--li class="streetfield "><?php //echo the_field('appttype');
 ?></li-->	
		<?php //endif;  <!--li class="propsize-appt"-->
 ?>
		
		
			<?php

if (get_field('appttype')):
	echo the_field('appttype');
endif;
?> | 
		 	<?php

if ($property_size != ''):
	if (ICL_LANGUAGE_CODE == 'fi') echo str_replace('.', ',', $property_size) . 'm<sup>2<sup>';
	  else echo str_replace('.', ',', $property_size) . 'm<sup>2<sup>';
endif;
?>
		</li> 
		</ul>	

		<ul class= "descriptionlist-appt secextra caps">
		<?php

if (get_field('room_types')): ?> 
		<li class="roomtype-appt"><?php
	the_field('room_types'); ?></li>
		<?php
endif; ?>			<?php

if (get_field('livingarea')): ?>	
		<li class="roomtype-appt"><?php
	the_field('livingarea'); ?></li>
		<?php
endif; ?>				<li class="livingarea-field borderrt">				<?php
echo esc_url(get_post_meta(get_the_ID() , 'TotalArea', true)); ?>			</li>
		<li class="roomtype-field borderrt">				<?php
echo esc_url(get_post_meta(get_the_ID() , 'RoomTypes', true)); ?>			</li>
		<!--<li class="price-field borderrt">	
		<?php
wpestate_show_price($post->ID, $currency, $where_currency); ?>			</li>	--> 
		</ul>
		<ul class= "descriptionlist-appt secextra caps">
		 <?php
$terms = wp_get_post_terms($post->ID, 'property_action_category');

foreach($terms as $term_single)
	{
	$termslug = $term_single->slug; //do something here
	}

if (get_field('debt_part'))
	{ ?>	
			<li class="price-field  <?php
	echo $termslug ?>">
			<?php
	$debt_part = get_field('debt_part', $post->ID);
	$dprice = floatval(get_post_meta($post->ID, 'debt_part', true));
	$dprice = number_format($dprice, 0, ',', ' ');
	echo $dprice = $dprice . ' ' . $currency; ?>
			</li><?php
	} ?>
       </ul>
		
		<?php //if(the_field('showingdate2')):

if (get_post_meta(get_the_ID() , 'showingdate1', true)): ?>	
      <ul class="descriptionlist-appt secextra caps top-property-time">
	    <span class="" style="padding-right:5px;">Esittelyaika  <?php
	the_field('showingdate1') ?></span>
		<span class=""> klo <?php
	the_field('showingstarttime1') ?></span>
		<span class=""> - <?php
	the_field('showingendtime1') ?></span>
		<span class=""> <?php
	the_field('showingdateexplanation1'); ?></span> 
      </ul>
	    <?php
endif; ?>  
	</div>
<?php
include (locate_template('templates/agent_area.php'));
 ?>
<div class="col-md-12-agent-area">
	
	<div class="col-md-12-rm">
		<h3 class="objecttitle"><?php
_e('Description', 'wpestate'); ?></h3>
		<div id="descriptions-inner">
			<?php

if ($content != '')
	{
	print $content;
	} ?>
			<span class="cols"></span>
			<?php
$area_information = get_post_meta(get_the_ID() , 'area_information', true);

if (ICL_LANGUAGE_CODE != 'fi')
	{
	if (!empty($area_information))
		{ ?>
				    <h6><?php
		_e('Area Information', 'wpestate'); ?></h6>
				    <span class="cols"><?php
		echo $area_information; ?></span>
				<?php
		}
	} ?>
		</div>
	</div>
    
    
	<div class="col-md-12-rm" id= "appt_details">
		<h3 class="objecttitle"><?php
_e('Apartment details', 'wpestate') ?></h3>
		<?php
$appt_types = array(
	'KT' => 'Apartment building',
	'RT' => 'Townhouse',
	'OT' => 'Detached House',
	'PT' => 'Semi-detached',
	'ET' => 'Detached House',
	'LUHT' => 'Luhtitalo',
	'PUUT' => 'Wooden house section',
	'MO' => 'Cottage or villa',
	'LO' => 'Holiday share',
	'LH' => 'Vacation Apartment',
	'UL' => 'Holiday Apartment (ex.)',
	'VT' => 'Free time',
	'RTT' => 'RT plot',
	'TO' => 'Other plot',
	'AP' => 'Parking space',
	'AT' => 'Garage',
	'MAT' => 'Farm',
	'MET' => 'Forestry',
	'TOT' => 'Office Space',
	'LT' => 'Business',
	'VART' => 'Warehouse',
	'TUT' => 'Production space',
	'OKTT' => 'Detached House plot',
	'MU' => 'Other'
); ?>
		<ul class="object-data">
		   <!--MADD 
		      <?php

if (get_field('numberofrooms')): ?>
			    <li class="numberofrooms">
				<div class="list-label"><?php
	_e('Rooms', 'wpestate') ?></div>
				<div class="list-data"><?php
	the_field('numberofrooms'); ?></div>
			    </li>
		    <?php
endif; ?>		
		    <?php
$areaadditional = get_post_meta(get_the_ID() , 'areaadditional', true);

if (!empty($areaadditional))
	{ ?>
			    <li class="areaadditional">
				<div class="list-label"><?php
	_e('Additional area', 'wpestate'); ?></div>
				<div class="list-data"><?php
	echo $areaadditional; ?></div>
			    </li>	
		    <?php
	} ?> 
		    
		    <?php
$storagespace = get_post_meta(get_the_ID() , 'storagespace', true);

if (!empty($storagespace))
	{ ?>
			    <li class="storagespace">
				<div class="list-label"><?php
	_e('Storage', 'wpestate'); ?></div>
				<div class="list-data"><?php
	echo $storagespace; ?></div>
			    </li>		    
		    <?php
	} ?>
			    
		
		    <?php

if (get_field('directionofwindows'))
	{ ?>
			    <li class="directionofwindows">
				<div class="list-label"><?php
	_e('Window', 'wpestate') ?></div>
				<div class="list-data"><?php
	the_field('directionofwindows'); ?></div>
			    </li>
		    <?php
	} ?>	
			 

		    <?php

if (get_field('balcony'))
	{ ?>
			    <li class="balcony">
				<div class="list-label"><?php
	_e('Balcony', 'wpestate') ?></div>
				<div class="list-data"><?php
	the_field('balcony'); ?></div>
			    </li>
		    <?php
	} ?>	
		    
		    <?php

if (get_field('lift'))
	{ ?>
			    <li class="lift">
				<div class="list-label"><?php
	_e('Lift', 'wpestate') ?></div>
				<div class="list-data"><?php
	the_field('lift'); ?></div>
			    </li>
		    <?php
	} ?>		    
			    
		    <?php
$floor = get_post_meta(get_the_ID() , 'floor', true);

if (get_field('floor'))
	{ ?>
			    <li class="floor">
				<div class="list-label"><?php
	_e('Floors', 'wpestate') ?></div>
				<div class="list-data"><?php
	the_field('floor'); ?></div>
			    </li>
		    <?php
	} ?>
			
                   MADD -->
			
		</ul>     
		<?php
$realestatetype = get_post_meta(get_the_ID() , 'realestatetype', true);

if ($realestatetype == 'OSAKE')
	{
?>
		<h3 class="objecttitle housing-company"><?php
	_e('Housing Company', 'wpestate') ?></h3>
		
		<?php
	}
  else
	{ ?>
		<h3 class="objecttitle property"><?php
	_e('PROPERTY', 'wpestate') ?></h3>
		<?php
	} ?>
		<ul class="object-data">  
		    <?php /*   $housingcompanyname  = get_post_meta( get_the_ID(), 'housingcompanyname', true );
if ( ! empty( $housingcompanyname ) ) {?>
<li class="housingcompanyname">
<div class="list-label"><?php _e('Housing company','wpestate');?></div>
<div class="list-data"><?php echo $housingcompanyname;?></div>
</li>
<?php } */ ?> 
<!--MADD			
			
		    <?php
$parkingspace = get_post_meta(get_the_ID() , 'parkingspace', true);

if (!empty($parkingspace))
	{ ?>
			    <li class="parkingspace">
				<div class="list-label"><?php
	_e('Parking', 'wpestate'); ?></div>
				<div class="list-data"><?php
	echo $parkingspace; ?></div>
			    </li>
		    <?php
	} ?>   
			
			<?php
$commonareas = get_post_meta(get_the_ID() , 'commonareas', true);

if (!empty($commonareas))
	{ ?>
			    <li class="commonareas">
				<div class="list-label"><?php
	_e('Common areas', 'wpestate'); ?></div>
				<div class="list-data"><?php
	echo $commonareas; ?></div>
			    </li>
		    <?php
	} ?>
		
		    <?php
$basicrenovations = get_post_meta(get_the_ID() , 'basicrenovations', true);

if (!empty($basicrenovations))
	{ ?>
			    <li class="basicrenovations">
				<div class="list-label"><?php
	_e('Renovations', 'wpestate'); ?></div>
				<div class="list-data"><?php
	echo $basicrenovations; ?></div>
			    </li>
		    <?php
	} ?>
				    
		    <?php
$futurerenovations = get_post_meta(get_the_ID() , 'futurerenovations', true);

if (!empty($futurerenovations))
	{ ?>
			    <li class="futurerenovations">
				<div class="list-label"><?php
	_e('Future renovations', 'wpestate'); ?></div>
				<div class="list-data"><?php
	echo $futurerenovations; ?></div>
			    </li>
		    <?php
	} ?>	
			    
		    <?php
$disponent = get_post_meta(get_the_ID() , 'disponent', true);

if (!empty($disponent))
	{ ?>
			    <li class="disponent">
				<div class="list-label"><?php
	_e('Disponent', 'wpestate'); ?></div>
				<div class="list-data"><?php
	echo $disponent; ?></div>
			    </li>
		    <?php
	} ?>	
		
		    <?php
$realestatemanagement = get_post_meta(get_the_ID() , 'realestatemanagement', true);

if (!empty($realestatemanagement))
	{ ?>
			    <li class="realestatemanagement">
				<div class="list-label"><?php
	_e('Estate management', 'wpestate'); ?></div>
				<div class="list-data"><?php
	echo $realestatemanagement; ?></div>
			    </li>
		    <?php
	} ?>
		    
		    <?php
$antennasystem = get_post_meta(get_the_ID() , 'antennasystem', true);

if (!empty($antennasystem))
	{ ?>
			    <li class="antennasystem">
				<div class="list-label"><?php
	_e('Antenna', 'wpestate'); ?></div>
				<div class="list-data"><?php
	echo $antennasystem; ?></div>
			    </li>
		    <?php
	} ?>	
			    
			    
		
		<?php

if (get_field('honouringclause')): ?>
			    <li class="honouringclause">
				<div class="list-label"><?php
	_e('Honouring clause', 'wpestate') ?></div>
				<div class="list-data"><?php
	the_field('honouringclause'); ?></div>
			    </li>
		    <?php
endif; ?>	   
			
		    <?php
$rcenergyflag = get_post_meta(get_the_ID() , 'rc-energy-flag', true);

if (!empty($rcenergyflag))
	{ ?>
			    <li class="rcenergyflag">
				<div class="list-label"><?php
	_e('Energy flag', 'wpestate'); ?></div>
				<div class="list-data"><?php
	echo $rcenergyflag; ?></div>
			    </li>
		    <?php
	} ?>	 				
			<?php

if (get_field('municipaldevelopment'))
	{ ?>
				<li class="municipaldevelopment">
				    <div class="list-label"><?php
	_e('Municipal development', 'wpestate') ?></div>
				    <div class="list-data"><?php
	the_field('municipaldevelopment'); ?></div>
				</li>
			<?php
	} ?>    			    
		<?php

if (get_field('useofwater')): ?>
			    <li class="useofwater">
				<div class="list-label"><?php
	_e('Use of water', 'wpestate') ?></div>
				<div class="list-data"><?php
	the_field('useofwater'); ?></div>
			    </li>
		    <?php
endif; ?>	    
			    
		<?php

if (get_field('leaseholder')): ?>
			    <li class="leaseholder">
				<div class="list-label"><?php
	_e('Lease holder', 'wpestate') ?></div>
				<div class="list-data"><?php
	the_field('leaseholder'); ?></div>
			    </li>
		    <?php
endif; ?>
			
		    <?php

if (get_field('termoflease')): ?>
			    <li class="termoflease">
				<div class="list-label"><?php
	_e('Term of lease', 'wpestate') ?></div>
				<div class="list-data"><?php
	the_field('termoflease'); ?></div>
			    </li>
		    <?php
endif; ?>
		    <?php

if (get_field('sewersystem')): ?>
			    <li class="sewersystem">
				<div class="list-label"><?php
	_e('Sewer system', 'wpestate') ?></div>
				<div class="list-data"><?php
	the_field('sewersystem'); ?></div>
			    </li>
		    <?php
endif; ?>
		    <?php

if (get_field('encuberances')): ?>
			    <li class="encuberances">
				<div class="list-label"><?php
	_e('Encuberances', 'wpestate') ?></div>
				<div class="list-data"><?php
	the_field('encuberances'); ?></div>
			    </li>
		    <?php
endif; ?>
		    <?php

if (get_field('pdx_target_extra')): ?>
			    <li class="pdx_target_extra">
				<div class="list-label"><?php
	_e('Additional info', 'wpestate') ?></div>
				<div class="list-data"><?php
	the_field('pdx_target_extra'); ?></div>
			    </li>
		    <?php
endif; ?>
MADD-->			    
		    <br />
            <br />

            <?php
$proptype = '';

if (get_field('appttype'))
	{
	$appt_name = get_post_meta(get_the_ID() , 'appttype', true);
	$proptype = strip_tags($appt_types[$appt_name]);
	}

$floorlocation = get_post_meta(get_the_ID() , 'floorlocation', true);

if ($proptype == 'Detached House plot' || empty($floorlocation))
	{
	$company = 'YhtiÃ¶';
?>
				<h3 class="re-single-h3 proptype"><?php
	_e('Tontti', 'wpestate') ?></h3>
			<?php
	}
  else
	{
	$company = 'KiinteistÃ¶'; ?>
				<h3 class="re-single-h3 proptype"><?php
	_e('Rakennus', 'wpestate') ?></h3>
			<?php
	} ?>	

			<hr>
			 <?php

if (get_field('appttype'))
	{
	$appt_name = get_post_meta(get_the_ID() , 'appttype', true);
?>
			   <li class="appt_types">
				<div class="list-label"><?php
	_e('Apartment type', 'wpestate') ?></div>
				<div class="list-data"><?php
	_e($appt_types[$appt_name], 'wpestate') ?></div>
			    </li>
		    <?php
	} ?>
                           <?php

if (get_field('site_area')): ?>
                            <li class="site_area">
                                <div class="list-label"><?php
	_e('Site area', 'wpestate') ?></div>
                                <div class="list-data">
                                <?php
	$lotsize = str_replace('.', ',', get_field('site_area', $post->ID));
	echo number_format($lotsize, 2, ',', ' ');;
?> m<sup>2</<sup></div>
                            </li>
                    <?php
endif; ?>
		    <?php

if (get_field('street_address'))
	{ ?>
			    <li class="street_address">
				<div class="list-label"><?php
	_e('Address', 'wpestate') ?></div>
				<div class="list-data">
				<?php
	the_field('street_address');
	$property_zip = get_post_meta($post->ID, 'property_zip', true);
	if ($property_zip != '')
		{
		echo ", " . $property_zip;
		}

	echo " " . $property_city;
?>
				</div>
			    </li>
		    <?php
	} ?>
                     <!--MADD-->
                      <?php

if (get_field('numberofrooms')): ?>
                            <li class="numberofrooms">
                                <div class="list-label"><?php
	_e('Rooms', 'wpestate') ?></div>
                                <div class="list-data"><?php
	the_field('numberofrooms'); ?></div>
                            </li>
                    <?php
endif; ?>
                    <!--end of MADD-->
           <?php

if (get_field('room_types'))
	{ ?>
			    <li class="room_types">
				<div class="list-label"><?php
	_e('Tilat', 'wpestate') ?></div>
				<div class="list-data"><?php
	the_field('room_types'); ?></div>
			    </li>
		    <?php
	} ?>
		     <?php

if (get_field('numberofoffices')): ?>
			    <li class="numberofoffices">
				<div class="list-label"><?php
	_e('Offices', 'wpestate') ?></div>
				<div class="list-data"><?php
	the_field('numberofoffices'); ?></div>
			    </li>
		    <?php
endif; ?>
			 <?php

if (get_field('living_area')): ?>
			    <li class="living_area">
				<div class="list-label"><?php
	_e('Living area', 'wpestate') ?></div>
				<div class="list-data"><?php
	if (ICL_LANGUAGE_CODE == 'fi') echo str_replace('.', ',', get_field('living_area', $post->ID));
	  else echo str_replace('.', ',', get_field('living_area', $post->ID));
?> m<sup>2</<sup></div>
			    </li>
		    <?php
endif; ?>
		    
		    <?php

if ($property_size != '')
	{ ?>
			    <li class="property_size">
				<div class="list-label"><?php
	_e('Total area', 'wpestate') ?></div>
				<div class="list-data">
				    <?php
	if (ICL_LANGUAGE_CODE == 'fi') echo str_replace('.', ',', $property_size);
	  else echo str_replace('.', ',', $property_size);
?> m<sup>2</<sup>
				</div>
			    </li>
		    <?php
	} ?>
			<?php //$floorlocation = get_post_meta( get_the_ID(), 'floorlocation', true );

if (!empty($floorlocation))
	{ ?>
			    <li class="floorlocation">
				<div class="list-label"><?php
	_e('Floor', 'wpestate'); ?></div>
				<div class="list-data"><?php
	echo $floorlocation; ?></div>
			    </li>	
		    <?php
	} ?>
			 <?php
$Buildingyear = get_post_meta($post->ID, 'property-year', true);

if ($Buildingyear > 0)
	{
?>
			
			<li class="Buildingyear">
			<div class="list-label"><?php
	_e('Building year', 'wpestate') ?></div>
			<div class="list-data"><?php
	echo $Buildingyear; ?></div>
			</li>
		    <?php
	} ?>
			<?php

if (get_field('buildingmaterial')): ?>
			    <li class="buildingmaterial">
				<div class="list-label"><?php
	_e('Building material', 'wpestate') ?></div>
				<div class="list-data"><?php
	the_field('buildingmaterial'); ?></div>
			    </li>
		    <?php
endif; ?>
			 <?php

if (get_field('rooftype')): ?>
			    <li class="rooftype">
				<div class="list-label"><?php
	_e('Roof', 'wpestate') ?></div>
				<div class="list-data"><?php
	the_field('rooftype'); ?></div>
			    </li>
		    <?php
endif; ?>
			 <?php

if (get_field('heating')): ?>
			    <li class="heating">
				<div class="list-label"><?php
	_e('Heating', 'wpestate') ?></div>
				<div class="list-data"><?php
	the_field('heating'); ?></div>
			    </li>
		    <?php
endif; ?> 
		   <?php

if (get_field('rc-energy-class')): ?>
			    <li class="rc-energyclass">
				<div class="list-label"><?php
	_e('Energy class', 'wpestate') ?></div>
				<div class="list-data"><?php
	the_field('rc-energy-class'); ?></div>
			    </li>
		    <?php
endif; ?>	
		     <?php

if (get_field('OtherSpaceDescription')): ?>
			    <li class="OtherSpaceDescription">
				<div class="list-label"><?php
	_e('Tilat / Pinta-alat', 'wpestate') ?></div>
				<div class="list-data"><?php
	the_field('OtherSpaceDescription'); ?></div>
			    </li>
		    <?php
endif; ?>	
			 <?php
$generalcondition = get_post_meta(get_the_ID() , 'generalcondition', true);

if (!empty($generalcondition))
	{ ?>
			    <li class="generalcondition">
				<div class="list-label"><?php
	_e('Condition', 'wpestate'); ?></div>
				<div class="list-data"><?php
	echo $generalcondition; ?></div>
			    </li>
		    <?php
	} ?> 
			      <?php
$kitchenapp = get_post_meta(get_the_ID() , 'KitchenAppliances', true);

if (!empty($kitchenapp))
	{ ?>
			    <li class="kitchenapp">
				<div class="list-label"><?php
	_e('Kitchen', 'wpestate'); ?></div>
				<div class="list-data"><?php
	echo $kitchenapp; ?></div>
			    </li>	
		    <?php
	} ?>
			   <?php
$BathroomAppliances = get_post_meta(get_the_ID() , 'bath_room_appliances', true);

if (!empty($BathroomAppliances))
	{ ?>
			    <li class="BathroomAppliances">
				<div class="list-label"><?php
	_e('Bathroom', 'wpestate') ?></div>
				<div class="list-data"><?php
	echo $BathroomAppliances; ?></div>
			    </li>
		    <?php
	} ?>
                           <?php
$Floor = get_post_meta(get_the_ID() , 'floor', true);

if (!empty($Floor))
	{ ?>
                            <li class="floor">
                                <div class="list-label"><?php
	_e('Pinta-materiaalit', 'wpestate') ?></div>
                                <div class="list-data"><?php
	echo $Floor; ?></div>
                            </li>
                    <?php
	} ?>
                           <?php
$StorageSpace = get_post_meta(get_the_ID() , 'storagespace', true);

if (!empty($StorageSpace))
	{ ?>
                            <li class="floor">
                                <div class="list-label"><?php
	_e('SÃ¤ilytystilat', 'wpestate') ?></div>
                                <div class="list-data"><?php
	echo $StorageSpace; ?></div>
                            </li>
                    <?php
	} ?>


			  <?php

if (get_field('sauna'))
	{ ?>
			    <li class="sauna">
				<div class="list-label"><?php
	_e('Sauna', 'wpestate') ?></div>
				<div class="list-data"><?php
	the_field('sauna'); ?></div>
			    </li>
		    <?php
	} ?>	
			   <?php

if (get_field('becomesavailable')): ?>
			    <li class="becomesavailable">
				<div class="list-label"><?php
	_e('Available', 'wpestate') ?></div>
				<div class="list-data">
				    <?php
	$becomesavailable = get_field('becomesavailable', $post->ID);
	if (ICL_LANGUAGE_CODE == 'fi')
		{
		echo $becomesavailable;
		}

	if (ICL_LANGUAGE_CODE == 'en')
		{
		if ($becomesavailable == 'Vapautuminen sopimuksen mukaan')
			{
			echo 'Date available according to contract';
			}

		if ($becomesavailable == 'Heti vapaa')
			{
			echo 'Immediately available';
			}
		}

	if (ICL_LANGUAGE_CODE == 'sv')
		{
		if ($becomesavailable == 'Vapautuminen sopimuksen mukaan')
			{
			echo 'TilltrÃ¤de enligt kontrakt';
			}

		if ($becomesavailable == 'Heti vapaa')
			{
			echo 'Omedelbart tillgÃ¤ngligt';
			}
		}

?>
				</div>
			    </li>
		    <?php
endif; ?>	
			   <?php
$suppInfo = get_post_meta(get_the_ID() , 'SupplementaryInformation', true);

if (!empty($suppInfo))
	{ ?>
			    <li class="suppInfo">
				<div class="list-label"><?php
	_e('Additional information', 'wpestate'); ?></div>
				<div class="list-data"><?php
	echo $suppInfo; ?></div>
			    </li>
		    <?php
	} ?>
            <br />
            <br />
            <h3 class="re-single-h3"><?php
print $company; //_e('YhtiÃ¶','wpestate'); //KiinteistÃ¶
 ?></h3>
			<hr>
			<?php
$housingcompanyname = get_post_meta(get_the_ID() , 'housingcompanyname', true);

if (!empty($housingcompanyname))
	{ ?>
			    <li class="housingcompanyname">
				<div class="list-label"><?php
	_e('Housing company', 'wpestate'); ?></div>
				<div class="list-data"><?php
	echo $housingcompanyname; ?></div>
			    </li>
		    <?php
	} ?>
			<?php

if (!empty($property_city))
	{ ?>
			    <li class="property_city">
				<div class="list-label"><?php
	_e('Kunta/Kaupunki', 'wpestate') ?></div>
				<div class="list-data"><?php
	echo $property_city; ?></div>
			    </li>
		    <?php
	} ?>
		    <?php

if (!empty($property_address))
	{ ?>
			    <li class="property_address">
				<div class="list-label"><?php
	_e('KylÃ¤/Kaup.osa', 'wpestate') ?></div>
				<div class="list-data"><?php
	echo $property_address; ?></div>
			    </li>
		    <?php
	} ?>
			
			 <?php

if (get_field('realestateid'))
	{ ?>
			  <li class="realestateid">
				<div class="list-label"><?php
	_e('Real Estate ID', 'wpestate') ?></div>
				<div class="list-data"><?php
	the_field('realestateid'); ?></div>
			    </li>	
		    <?php
	} ?>
			   <?php

if (get_field('site_area')): ?>
			    <li class="site_area">
				<div class="list-label"><?php
	_e('Site area', 'wpestate') ?></div>
				<div class="list-data">
				<?php
	$lotsize = str_replace('.', ',', get_field('site_area', $post->ID));
	echo number_format($lotsize, 2, ',', ' ');;
?> m<sup>2</<sup></div>
			    </li>
		    <?php
endif; ?>
		       <?php

if (get_field('sitetype')): ?>
			    <li class="sitetype">
				<div class="list-label"><?php
	_e('Site type', 'wpestate') ?></div>
				<div class="list-data">
				<?php
	$sitetype = get_post_meta(get_the_ID() , 'sitetype', true);
	_e($sitetype, 'wpestate'); ?>
				</div>
			    </li>
		    <?php
endif; ?>

			   <?php

if (get_field('grounds')): ?>
			    <li class="grounds">
				<div class="list-label"><?php
	_e('Maasto, maaperÃ¤ ja kasvusto', 'wpestate') ?></div>
				<div class="list-data"><?php
	the_field('grounds'); ?></div>
			    </li>
		    <?php
endif; ?>
				<?php

if (get_field('buildingrights'))
	{ ?>
				<li class="buildingrights">
				    <div class="list-label"><?php
	_e('Building rights', 'wpestate') ?></div>
				    <div class="list-data"><?php
	the_field('buildingrights'); ?></div>
				</li>
			<?php
	} ?>
			 <?php

if (get_field('buildingplaninformation'))
	{ ?>
			    <li class="buildingplaninformation">
				<div class="list-label"><?php
	_e('Muilding plan info', 'wpestate') ?></div>
				<div class="list-data"><?php
	the_field('buildingplaninformation'); ?></div>
			    </li>
		    <?php
	} ?>  
		  <?php

if (get_field('pdx_property_extra')): ?>
			    <li class="pdx_property_extra">
				<div class="list-label"><?php
	_e('Real estate extra', 'wpestate') ?></div>
				<div class="list-data"><?php
	the_field('pdx_property_extra'); ?></div>
			    </li>
		    <?php
endif; ?>
            <br />
            <br />
			<h3 class="re-single-h3 asuinalue">Asuinalue</h3>
			<hr>
			  <?php

if (get_field('buildingplansituation'))
	{ ?>
			    <li class="buildingplansituation">
				<div class="list-label"><?php
	_e('Building plan', 'wpestate') ?></div>
				<div class="list-data"><?php
	the_field('buildingplansituation'); ?></div>
			    </li>
		    <?php
	} ?>
		
			<?php
$Services = get_post_meta(get_the_ID() , 'services', true);

if (!empty($Services))
	{ ?>
			    <li class="Services">
				<div class="list-label"><?php
	_e('Services', 'wpestate') ?></div>
				<div class="list-data"><?php
	echo $Services; ?></div>
			    </li>
		    <?php
	} ?>
		    <?php

if (get_field('connections')): ?>
			    <li class="connections">
				<div class="list-label"><?php
	_e('Connections', 'wpestate') ?></div>
				<div class="list-data"><?php
	the_field('connections'); ?></div>
			    </li>
		    <?php
endif; ?>	
		    <br />
            <br />
			<h3 class="re-single-h3 kustannustiedot">Kustannustiedot</h3>
			<hr>	
			<?php

if (get_field('estate_tax')): ?>
			    <li class="estate-tax">
				<div class="list-label"><?php
	_e('KiinteistÃ¶vero', 'wpestate') ?></div>
				<div class="list-data"><?php
	the_field('estate_tax'); ?></div>
			    </li>
		    <?php
endif; ?>	
			<?php

if (get_field('other_fees')): ?>
			    <li class="other_fees">
				<div class="list-label"><?php
	_e('Muut kustannukset', 'wpestate') ?></div>
				<div class="list-data"><?php
	the_field('other_fees'); ?></div>
			    </li>
		    <?php
endif; ?>	
		     <br />	
		     <br />
		     <h3 class="re-single-h3 Muuta">Muuta</h3>
			<hr>
			
		   <?php
$terms = wp_get_post_terms($post->ID, 'property_action_category');

foreach($terms as $term_single)
	{
	$termslug = $term_single->slug; //do something here
	}

if (get_field('debt_part'))
	{ ?>	
			<li class="price-field  <?php
	echo $termslug ?>">
				<div class="list-label"><?php
	_e('Velaton myyntihinta', 'wpestate') ?>:</div>
			<div class="list-data"><?php
	$debt_part = get_field('debt_part', $post->ID);
	$dprice = floatval(get_post_meta($post->ID, 'debt_part', true));
	$dprice = number_format($dprice, 0, ',', ' ');
	echo $dprice = $dprice . ' ' . $currency; ?></div>
			</li><?php
	} ?>		
		     <br />		  
		  		 		
		</ul>
		<?php /*
<br />
<h3 class="objecttitle"><?php _e('Price and living fees','wpestate')?></h3>

<ul>
<?php	if( get_field('sales_price') ){?>
<li class="<?php echo $termslug;?> sales_price">
<div class="list-label"><?php _e('Sales price','wpestate')?></div>
<div class="list-data">
<?php
$sales_price =   get_field('sales_price', $post->ID);
$price     = floatval( get_post_meta($post->ID, 'sales_price', true) );
$price     = number_format($price,0,',',' ');
echo $price = $price . ' ' . $currency;
?>
</div>
</li>
<?php } ?>

<?php	$property_price  =   get_post_meta($post->ID, 'property_price', true);
if( $property_price ){?>
<li class="property_price">
<div class="list-label"><?php _e('Unencumbered sales price','wpestate')?></div>
<div class="list-data">
<?php
$property_price     = number_format($property_price,0,',',' ');
echo $property_price = $property_price . ' ' . $currency;
?>
</div>
</li>
<?php } ?>

<?php	if( get_field('debtpart') ){?>
<li class="<?php echo $termslug;?> debtpart">
<div class="list-label"><?php _e('Debt part','wpestate')?>:</div>
<div class="list-data">
<?php
$DebtPart =   get_field('debtpart', $post->ID);

$price     = floatval( get_post_meta($post->ID, 'debtpart', true) );
$price     = number_format($price,0,',',' ');
echo $price = $price . ' ' . $currency;
?>
</div>
</li>
<?php } ?>

<?php	$maintenancefee  = get_post_meta( get_the_ID(), 'maintenancefee', true );
if ( ! empty( $maintenancefee ) ) {
$maintenancefee     = number_format($maintenancefee,0,',',' ');
$maintenancefee =  $maintenancefee . ' ' . $currency;
?>
<li class="maintenancefee">
<div class="list-label"><?php _e('Manitenance fee','wpestate');?></div>
<div class="list-data"><?php echo $maintenancefee;?></div>
</li>
<?php }?>

<?php	$financingfee  = get_post_meta( get_the_ID(), 'financingfee', true );
if ( ! empty( $financingfee ) ) {
$financingfee     = number_format($financingfee,0,',',' ');
$financingfee =  $financingfee . ' ' . $currency;
?>
<li class="financingfee">
<div class="list-label"><?php _e('Financing fee','wpestate');?></div>
<div class="list-data"><?php echo $financingfee;?></div>
</li>
<?php }?>

<?php	$waterfee  = get_post_meta( get_the_ID(), 'waterfee', true );
if ( ! empty( $waterfee ) ) {?>
<li class="waterfee">
<div class="list-label"><?php _e('Water fee','wpestate');?></div>
<div class="list-data"><?php echo $waterfee.' '.$currency;?></div>
</li>
<?php }?>

<?php	$cleaningfee  = get_post_meta( get_the_ID(), 'cleaningfee', true );
if ( ! empty( $cleaningfee ) ) {?>
<li class="cleaningfee">
<div class="list-label"><?php _e('Cleaning fee','wpestate');?></div>
<div class="list-data"><?php echo $cleaningfee.' '.$currency;?></div>
</li>
<?php }?>

<?php	$electricuse  = get_post_meta( get_the_ID(), 'electricuse', true );
if ( ! empty( $electricuse ) ) {?>
<li class="electricuse">
<div class="list-label"><?php _e('Electricity fee','wpestate');?></div>
<div class="list-data"><?php echo $electricuse.' '.$currency;?></div>
</li>
<?php }?>
<?php	$oiluse  = get_post_meta( get_the_ID(), 'electricuse', true );
if ( ! empty( $oiluse ) ) {?>
<li class="oiluse">
<div class="list-label"><?php _e('Oil fee','wpestate');?></div>
<div class="list-data"><?php echo $oiluse.' '.$currency;?></div>
</li>
<?php }?>

<?php	$otherfees  = get_post_meta( get_the_ID(), 'otherfees', true );
if ( ! empty( $otherfees ) && $mortgages != 0 ) {?>
<li class="otherfees">
<div class="list-label"><?php _e('Other fees','wpestate');?></div>
<div class="list-data"><?php echo $otherfees;?></div>
</li>
<?php }?>

<?php	$mortgages  = get_post_meta( get_the_ID(), 'mortgages', true );
if ( ! empty( $mortgages ) && $mortgages != 0 ) {?>
<li class="mortgages">
<div class="list-label"><?php _e('Mortgages','wpestate');?></div>
<div class="list-data">	<?php echo $mortgages;?></div>
</li>
<?php } ?>
</ul>
*/ ?>
	    <div style="display: none;">
		<?php

if (get_field('showingdate1') || get_field('showingdate2') || get_field('moreinfourl') || get_field('virtualpresentation') || get_field('video_link'))
	{ ?>
		<h3 class="objecttitle presentation"><?php
	_e('Presentation', 'wpestate') ?></h3>
		
		<?php
	if (get_field('showingdate1'))
		{ ?>
		    <ul class="showingdate1">
			<li>
			    <div class="list-label"><?php
		_e('Presentation Time', 'wpestate'); ?></div>  
			    <div class="list-data">	
				<span class=""><?php
		the_field('showingdate1') ?></span>
				<span class=""><?php
		the_field('showingstarttime1') ?></span>
				<span class=""><?php
		the_field('showingendtime1') ?></span>
				<span class=""><?php
		the_field('showingdateexplanation1'); ?></span>
			    </div>
			</li>
		    <?php
		}

	if (get_field('showingdate2'))
		{ ?>
			<li>
			    <div class="list-label"><?php
		_e('Presentation Time', 'wpestate'); ?></div>  
			    <div class="list-data">	
				<?php
		_e('Presentation Time', 'wpestate'); ?>  
				<span class=""><?php
		the_field('showingdate2') ?></span>
				<span class=""><?php
		the_field('showingstarttime2') ?></span>
				<span class=""><?php
		the_field('showingendtime2') ?></span>
				<span class=""><?php
		the_field('showingdateexplanation2'); ?></span>
			    </div>	
			</li>
		    </ul>
		<?php
		} ?>
		<ul>
		    <?php
	if (get_field('moreinfourl'))
		{ ?>
			<li class="moreinfourl">
			    <div class="list-label"><?php
		_e('More information', 'wpestate') ?></div>
			    <div class="list-data"><a href="<?php
		the_field('moreinfourl'); ?>" target="_blank"><?php
		the_field('moreinfourl'); ?></a></div>
			</li>
		    <?php
		} ?>
		    
		    
		    <?php
	if (get_field('virtualpresentation'))
		{ ?>
		    <li class="virtualpresentation">
			    <div class="list-label"><?php
		_e('Virtual presentation', 'wpestate') ?></div>
			    <div class="list-data"><a href="<?php
		the_field('virtualpresentation'); ?>" target="_blank"><?php
		the_field('virtualpresentation'); ?></a></div>
		    </li>
		    <?php
		} ?>
		    
		    <?php
	if (get_field('video_link'))
		{ ?>
		    <li class="video_link">
			    <div class="list-label"><?php
		_e('Video presentation', 'wpestate') ?></div>
			    <div class="list-data"><a href="<?php
		the_field('video_link'); ?>" target="_blank"><?php
		the_field('video_link'); ?></a></div>
		    </li>
		    <?php
		} ?>
		    
		    
		</ul> 
		</div>   
		<?php
	} ?>
		<?php
include (locate_template('templates/agent_area2.php'));
 ?>
	</div>
	
	<div class="col-md-12-rm" id="appt_times" style="display: none;">

		<?php

if (get_field('showingdate1') || get_field('showingdate2'))
	{ ?>
		<h3 class="objecttitle"><?php
	_e('Presentation', 'wpestate') ?></h3>
		
		<?php
	if (get_field('showingdate1'))
		{ ?>
		    <ul class="showingdate1">
			<li>
			    <div class="list-label"><?php
		_e('Presentation Time', 'wpestate'); ?></div>  
			    <div class="list-data">	
				<span class=""><?php
		the_field('showingdate1') ?></span>
				<span class=""><?php
		the_field('showingstarttime1') ?></span>
				<span class=""><?php
		the_field('showingendtime1') ?></span>
				<span class=""><?php
		the_field('showingdateexplanation1'); ?></span>
			    </div>
			</li>
		    <?php
		}

	if (get_field('showingdate2'))
		{ ?>
			<li>
			    <div class="list-label"><?php
		_e('Presentation Time', 'wpestate'); ?></div>  
			    <div class="list-data">	
				<?php
		_e('Presentation Time', 'wpestate'); ?>  
				<span class=""><?php
		the_field('showingdate2') ?></span>
				<span class=""><?php
		the_field('showingstarttime2') ?></span>
				<span class=""><?php
		the_field('showingendtime2') ?></span>
				<span class=""><?php
		the_field('showingdateexplanation2'); ?></span>
			    </div>	
			</li>
		    </ul>
		<?php
		}
	} ?>	
	</div>
    
    
</div>            
<?php
$prpg_slider_type_status = esc_html(get_option('wp_estate_global_prpg_slider_type', ''));
$local_pgpr_slider_type_status = get_post_meta($post->ID, 'local_pgpr_slider_type', true);

if (($local_pgpr_slider_type_status == 'global' && $prpg_slider_type_status == 'full width header') || $local_pgpr_slider_type_status == 'full width header')
	{
?>
    <div class="panel-group property-panel" id="accordion_prop_map">  
        <div class="panel panel-default">
            <div class="panel-heading">
                <a data-toggle="collapse" data-parent="#accordion_prop_map" href="#collapsemap">
                    <h4 class="panel-title margintop" id="prop_ame"><?php
	_e('Map', 'wpestate'); ?></h4>
                  
                </a>
            </div>
            <div id="collapsemap" class="panel-collapse collapse in">
              <div class="panel-body">
              <?php
	print do_shortcode('[property_page_map propertyid="' . $post->ID . '"][/property_page_map]') ?>
              </div>
            </div>
        </div>
    </div> 


    <?php
	}

?>

<!-- Walkscore -->    

<?php
$walkscore_api = esc_html(get_option('wp_estate_walkscore_api', ''));

if ($walkscore_api != '')
	{ ?>

    
<div class="panel-group property-panel" id="accordion_walkscore">  
    <div class="panel panel-default">
        <div class="panel-heading">
            <a data-toggle="collapse" data-parent="#accordion_walkscore" href="#collapseFour">
                <?php
	print '<h4 class="panel-title" id="prop_ame">' . __('WalkScore', 'wpestate') . '</h4>';
?>
            </a>
        </div>

        <div id="collapseFour" class="panel-collapse collapse in">
            <div class="panel-body">
                <?php
	wpestate_walkscore_details($post->ID); ?>
            </div>
        </div>
    </div>
</div>  



       
<?php
	}

?>


<?php // floor plans

if ($use_floor_plans == 1)
	{
?>

<div class="panel-group property-panel" id="accordion_prop_floor_plans">  
    <div class="panel panel-default">
        <div class="panel-heading">
            <a data-toggle="collapse" data-parent="#accordion_prop_floor_plans" href="#collapseflplan">
                <?php
	print '<h4 class="panel-title" id="prop_ame">' . __('Floor Plans', 'wpestate') . '</h4>';
?>
            </a>
        </div>

        <div id="collapseflplan" class="panel-collapse collapse in">
            <div class="panel-body">
                <?php
	print estate_floor_plan($post->ID); ?>
            </div>
        </div>
    </div>
</div>  


<?php
	}

?>


<?php

if ($show_graph_prop_page == 'yes')
	{
?>
    <div class="panel-group property-panel" id="accordion_prop_stat">
        <div class="panel panel-default">
           <div class="panel-heading">
               <a data-toggle="collapse" data-parent="#accordion_prop_stat" href="#collapseSeven">
                <h4 class="panel-title"><?php
	_e('Page Views Statistics', 'wpestate'); ?></h4>    
               </a>
           </div>
           <div id="collapseSeven" class="panel-collapse collapse in">
             <div class="panel-body">
                <canvas id="myChart"></canvas>
             </div>
           </div>
        </div>            
    </div>    
    <script type="text/javascript">

    // <![CDATA[

        jQuery(document).ready(function(){
             wpestate_show_stat_accordion();
        });

    
    // ]]>

    </script>
<?php
	}

?>
