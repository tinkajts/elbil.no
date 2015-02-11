<?php /* Smarty version Smarty-3.0.6, created on 2014-11-04 15:21:30
         compiled from "./templates/fullscreen.tpl" */ ?>
<?php /*%%SmartyHeaderCode:20861653404d506b12551b09-00071808%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '57facba04cebb744f39fc40dd09cf2a39d551caf' => 
    array (
      0 => './templates/fullscreen.tpl',
      1 => 1414963944,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '20861653404d506b12551b09-00071808',
  'function' => 
  array (
  ),
  'cache_lifetime' => 60,
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
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
			<?php  $_smarty_tpl->tpl_vars['kontakt'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('arrKontak')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['kontakt']->iteration=0;
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['kontakt']->key => $_smarty_tpl->tpl_vars['kontakt']->value){
 $_smarty_tpl->tpl_vars['kontakt']->iteration++;
?>
				<h3>Kontakt <?php echo $_smarty_tpl->tpl_vars['kontakt']->iteration;?>
: <?php if ($_smarty_tpl->tpl_vars['kontakt']->value['inUse']==1){?><span style="color: red;">Opptatt</span><?php }else{ ?><span style="color: green;">Ledig</span><?php }?></h3>
				<small>Sist oppdatert: <?php echo $_smarty_tpl->tpl_vars['kontakt']->value['lastUpdated'];?>
</small>
				<div class="ravn-status-kontakt-liste-wrapper">
					<ul class="ravn-status-kontakt-liste">
						<li class="listheader">Andre detaljer:</li>
						<li>Siste lading avsluttet: <?php echo $_smarty_tpl->tpl_vars['kontakt']->value['lastChargeFinished'];?>
</li>
						<li>Spenning på stikkontakt: <?php echo $_smarty_tpl->tpl_vars['kontakt']->value['volts'];?>
 V</li>
						<li>Strømtrekk på stikkontakt: <?php echo $_smarty_tpl->tpl_vars['kontakt']->value['wattsUsed'];?>
 watt</li>
						<li>Målerstand: <?php echo $_smarty_tpl->tpl_vars['kontakt']->value['totalKwhUsed'];?>
 Kwh</li> 
						<li>Gjennomsnittlig ladetid: <?php echo $_smarty_tpl->tpl_vars['kontakt']->value['avgTimeUsed'];?>
</li>
						<li>Gjennomsnittlig strømforbruk pr. lading <?php echo $_smarty_tpl->tpl_vars['kontakt']->value['avgKWHUsedPrChargeing'];?>
 kWh</li>
					</ul>
				</div>				
			<?php }} ?>
	</div>	
</body>
</html>