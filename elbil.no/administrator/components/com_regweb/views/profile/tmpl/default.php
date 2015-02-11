<?php
defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>

<?php if ($this->pageTitle != ''):?><h1><?php echo $this->pageTitle;?></h1><?php endif;?>

<div class="profile-edit">
	<?php echo $this->infoText; ?>
	
	<form 	id="regweb_profile_form"
		action="<?php echo JRoute::_('index.php?option=com_regweb&task=profile.save'); ?>"
		method="post"
		class="form-validate"
		enctype="multipart/form-data">

		<fieldset id="users-profile-core">
			<legend><?php echo $this->formTitle; ?></legend>
			<dl>
				<?php foreach ($this->fieldsConfig as $key => $fieldConfig):?>
					<?php if (!$fieldConfig['show'] && !$fieldConfig['edit']) { continue; }?>
					<?php if ($key == 'password'):?>
						<?php if ($fieldConfig['edit']):?>
							<dt><?php echo $fieldConfig['label'];?></dt>
							<dd><?php echo $this->fields[$key]->input;?></dd>
							
							<dt><?php echo $fieldConfig['repeat_label'];?></dt>
							<dd><?php echo $this->fields['password2']->input;?></dd>
						<?php endif;?>
						<?php continue; ?>
					<?php endif;?>
					<dt>
						<?php echo $fieldConfig['label'];?>
						<?php if ($fieldConfig['edit'] == '1' && $this->fields[$key]->required): ?>*<?php endif;?>
					</dt>
					<?php if ($fieldConfig['edit']):?>
						<dd><?php echo $this->fields[$key]->input;?></dd>
					<?php elseif ($fieldConfig['show']):?>
						<dd><?php echo htmlspecialchars($this->data[$key]);?></dd>
					<?php endif;?>
				<?php endforeach;?>
			</dl>
		</fieldset>
		
		<div>
			<button type="submit" class="validate"><span><?php echo JText::_('JSUBMIT');?></span></button>
			<a href="<?php echo JRoute::_('');?>"><?php echo JText::_('JCANCEL');?></a>
			<input type="hidden" name="option" value="com_regweb"/>
			<input type="hidden" name="task" value="profile.save"/>
			<?php echo JHtml::_('form.token'); ?>
		</div>
		
	</form>
	
</div>