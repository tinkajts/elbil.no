<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">    
<html xmlns="http://www.w3.org/1999/xhtml" lang="nb-no" xml:lang="nb-no">
	<head><title>Ladestasjonsstatus</title>
		<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
		<meta http-equiv="refresh" content="60;url=http://elbil.no/forhandlere/ravn.php?mode=full" />
		<link rel="stylesheet" href="/forhandlere/style/ravnGadget.css" type="text/css" media="screen"/>
	</head>
	<body>		
	<div class="ravn-status-box-full">
		<div class="ravn-status-box-full-lesmer">
			<p>Online status og rapport for ladestasjonen <a href="http://www.ladestasjoner.no/2010/06/madla-amfi-stavanger.html" title="Les mer om Amfi Madla på Ladestasjoner.no" target="_blank">Amfi Madla</a>, Madlakrossen 7, 4042 Hafrsfjord<br/>
			Les mer <a href="http://elbil.no/elbilfakta/teknologi/294-ravnen-vaaker" title="Les mer i artikkel" target="_parent">her</a> om hvordan dette fungerer.</p>
		</div>
			{foreach $arrKontak as $kontakt}
				<h3>Kontakt {$kontakt@iteration}: {if $kontakt.inUse eq 1}<span style="color: red;">Opptatt</span>{else}<span style="color: green;">Ledig</span>{/if}</h3>
				<small>Sist oppdatert: {$kontakt.lastUpdated}</small>
				<div class="ravn-status-kontakt-liste-wrapper">
					<ul class="ravn-status-kontakt-liste">
						<li class="listheader">Andre detaljer:</li>
						<li>Siste lading avsluttet: {$kontakt.lastChargeFinished}</li>
						<li>Spenning på stikkontakt: {$kontakt.volts} V</li>
						<li>Strømtrekk på stikkontakt: {$kontakt.wattsUsed} watt</li>
						<li>Målerstand: {$kontakt.totalKwhUsed} Kwh</li> 
						<li>Gjennomsnittlig ladetid: {$kontakt.avgTimeUsed}</li>
						<li>Gjennomsnittlig strømforbruk pr. lading {$kontakt.avgKWHUsedPrChargeing} kWh</li>
					</ul>
				</div>				
			{/foreach}
	</div>	
</body>
</html>