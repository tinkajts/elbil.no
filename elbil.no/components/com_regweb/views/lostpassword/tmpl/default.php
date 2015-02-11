<?php
defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

$authParams = new JRegistry(JPluginHelper::getPlugin('authentication', 'regweb')->params);
?>

<style type="text/css">
    #lostpassword fieldset {
        padding: 10px;
    }
    #lostpassword button {
        padding: 5px;
    }
    #lostpassword .forgot-text {
        padding-bottom: 25px;
    }
</style>

<h1><?php  echo JText::_('COM_REGWEB_LOST_PASSWORD_TITLE'); ?></h1>

<form 	id="lostpassword"
         action="<?php echo JRoute::_('index.php?option=com_regweb&task=lostpassword.request'); ?>"
         method="post"
         class="form-validate">

    <fieldset>
        <div class="forgot-text"><?php echo $authParams->get('forgotpasstext'); ?></div>

        <?php foreach ($this->form->getFieldsets() as $fieldset): ?>
            <!--<p><?php echo JText::_($fieldset->label); ?></p>-->
            <dl>
                <?php foreach ($this->form->getFieldset($fieldset->name) as $name => $field): ?>
                    <dt><?php echo $field->label; ?></dt>
                    <dd><?php echo $field->input; ?></dd>
                <?php endforeach; ?>
            </dl>
        <?php endforeach; ?>
        <div>
            <button type="submit" class="validate"><?php echo JText::_('JSUBMIT'); ?></button>
            <?php echo JHtml::_('form.token'); ?>
        </div>
    </fieldset>

</form>