<?php
defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

$fields = $this->fieldsConfig;
?>

<style type="text/css">
    #regweb_profile_form fieldset {
        padding: 10px;
    }

    #regweb_profile_form .field-box {
        float: left;
        margin-right: 20px;
    }

    #regweb_profile_form dl {
        margin-bottom: 5px;
    }

    #regweb_profile_form button {
        padding: 5px;
    }
</style>

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
            <?php if ($fields['membernumber']['show']): ?>
                <dl>
                    <dt><?php echo $fields['membernumber']['label'];?></dt>
                    <dd><?php echo htmlspecialchars($this->data['membernumber']);?></dd>
                </dl>
            <?php endif;?>

            <!-- Firstname, lastname -->
            <?php foreach (array('firstname', 'lastname') as $key): ?>
                <?php $fieldConfig = $fields[$key];?>
                <?php if (!$fieldConfig['show'] && !$fieldConfig['edit']) { continue; }?>
                <div class="field-box">
                    <dl>
                        <dt>
                            <?php echo $fieldConfig['label'];?>
                            <?php if ($fieldConfig['edit'] == '1' && $this->fields[$key]->required): ?>*<?php endif;?>
                        </dt>
                        <?php if ($fieldConfig['edit']):?>
                            <dd><?php echo $this->fields[$key]->input;?></dd>
                        <?php elseif ($fieldConfig['show']):?>
                            <dd><?php echo htmlspecialchars($this->data[$key]);?></dd>
                        <?php endif;?>
                    </dl>
                </div>
            <?php endforeach; ?>
            <div style="clear:both;"></div>

            <!-- Address1, address2, postalcode -->
            <?php foreach (array('address1', 'address2', 'postalcode') as $key): ?>
                <?php $fieldConfig = $fields[$key];?>
                <?php if (!$fieldConfig['show'] && !$fieldConfig['edit']) { continue; }?>
                <div class="field-box">
                    <dl>
                        <dt>
                            <?php echo $fieldConfig['label'];?>
                            <?php if ($fieldConfig['edit'] == '1' && $this->fields[$key]->required): ?>*<?php endif;?>
                        </dt>
                        <?php if ($fieldConfig['edit']):?>
                            <dd><?php echo $this->fields[$key]->input;?></dd>
                        <?php elseif ($fieldConfig['show']):?>
                            <dd><?php echo htmlspecialchars($this->data[$key]);?></dd>
                        <?php endif;?>
                    </dl>
                </div>
            <?php endforeach; ?>
            <div style="clear:both;"></div>

            <!-- Phone1, phone2, mobile -->
            <?php foreach (array('phone1', 'phone2', 'mobile') as $key): ?>
                <?php $fieldConfig = $fields[$key];?>
                <?php if (!$fieldConfig['show'] && !$fieldConfig['edit']) { continue; }?>
                <div class="field-box">
                    <dl>
                        <dt>
                            <?php echo $fieldConfig['label'];?>
                            <?php if ($fieldConfig['edit'] == '1' && $this->fields[$key]->required): ?>*<?php endif;?>
                        </dt>
                        <?php if ($fieldConfig['edit']):?>
                            <dd><?php echo $this->fields[$key]->input;?></dd>
                        <?php elseif ($fieldConfig['show']):?>
                            <dd><?php echo htmlspecialchars($this->data[$key]);?></dd>
                        <?php endif;?>
                    </dl>
                </div>
            <?php endforeach; ?>
            <div style="clear:both;"></div>

            <!-- Email -->
            <?php foreach (array('email') as $key): ?>
                <?php $fieldConfig = $fields[$key];?>
                <?php if (!$fieldConfig['show'] && !$fieldConfig['edit']) { continue; }?>
                <div class="field-box">
                    <dl>
                        <dt>
                            <?php echo $fieldConfig['label'];?>
                            <?php if ($fieldConfig['edit'] == '1' && $this->fields[$key]->required): ?>*<?php endif;?>
                        </dt>
                        <?php if ($fieldConfig['edit']):?>
                            <dd><?php echo $this->fields[$key]->input;?></dd>
                        <?php elseif ($fieldConfig['show']):?>
                            <dd><?php echo htmlspecialchars($this->data[$key]);?></dd>
                        <?php endif;?>
                    </dl>
                </div>
            <?php endforeach; ?>
            <div style="clear:both;"></div>

            <!-- Birthdate -->
            <?php foreach (array('birthdate') as $key): ?>
                <?php $fieldConfig = $fields[$key];?>
                <?php if (!$fieldConfig['show'] && !$fieldConfig['edit']) { continue; }?>
                <div class="field-box">
                    <dl>
                        <dt>
                            <?php echo $fieldConfig['label'];?>
                            <?php if ($fieldConfig['edit'] == '1' && $this->fields[$key]->required): ?>*<?php endif;?>
                        </dt>
                        <?php if ($fieldConfig['edit']):?>
                            <dd><?php echo $this->fields[$key]->input;?></dd>
                        <?php elseif ($fieldConfig['show']):?>
                            <dd><?php echo htmlspecialchars($this->data[$key]);?></dd>
                        <?php endif;?>
                    </dl>
                </div>
            <?php endforeach; ?>
            <div style="clear:both;"></div>

            <!-- Password -->
            <?php foreach (array('password') as $key): ?>
                <?php $fieldConfig = $fields[$key];?>
                <?php if (!$fieldConfig['show'] && !$fieldConfig['edit']) { continue; }?>
                <div class="field-box">
                    <dl>
                        <dt><?php echo $fieldConfig['label'];?></dt>
                        <dd><?php echo $this->fields[$key]->input;?></dd>
                    </dl>
                </div>
                <div class="field-box">
                    <dl>
                        <dt><?php echo $fieldConfig['repeat_label'];?></dt>
                        <dd><?php echo $this->fields['password2']->input;?></dd>
                    </dl>
                </div>
            <?php endforeach; ?>
            <div style="clear:both;"></div>

            <dl>
                <?php foreach ($this->fieldsConfig as $key => $fieldConfig):?>
                    <?php
                    // Skip already handled fields
                    if (in_array($key, array(
                        'membernumber',
                        'firstname', 'lastname',
                        'address1', 'address2', 'postalcode',
                        'phone1', 'phone2', 'mobile',
                        'email', 'birthdate', 'password'
                    ))) { continue; }
                    ?>

                    <?php if (!$fieldConfig['show'] && !$fieldConfig['edit']) { continue; }?>
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
            <div>
                <button type="submit" class="validate"><span><?php echo JText::_('JSUBMIT');?></span></button>
                <a href="<?php echo JRoute::_('');?>"><?php echo JText::_('JCANCEL');?></a>
                <input type="hidden" name="option" value="com_regweb"/>
                <input type="hidden" name="task" value="profile.save"/>
                <?php echo JHtml::_('form.token'); ?>
            </div>
        </fieldset>

    </form>

</div>