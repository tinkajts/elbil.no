<?php
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.html.html.behavior' );
JHtml::_('behavior.keepalive');
?>

<style type="text/css">
    #login-form fieldset {
        padding: 10px;
    }
    #login-form .pretext {
        padding-bottom: 25px;
    }
</style>

<?php if ($type == 'logout'): ?>
    <div style="margin-bottom: 20px;">
        <?php echo JText::_('COM_REGWEB_USERBOX_YOU_ARE_LOGGED_IN') ?>
    </div>

    <form action="<?php echo JRoute::_('index.php', true, $authParams->get('usesecure')); ?>" method="post" id="logout-form">
        <button type="submit" class="button"><?php echo JText::_('JLOGOUT'); ?></button>
        <input type="hidden" name="option" value="com_users" />
        <input type="hidden" name="task" value="user.logout" />
        <?php echo JHtml::_('form.token'); ?>
    </form>
<?php else: ?>
    <form action="<?php echo JRoute::_('index.php', true, $authParams->get('usesecure')); ?>" method="post" id="login-form" >
        <?php if ($authParams->get('pretext')): ?>
            <div class="pretext">
                <p><?php echo $authParams->get('pretext'); ?></p>
            </div>
        <?php endif; ?>
        <fieldset class="userdata">
            <p id="form-login-username">
                <label for="modlgn-username"><?php echo JText::_('MOD_REGWEB_USERBOX_VALUE_USERNAME') ?></label>
                <input id="modlgn-username" type="text" name="username" class="inputbox"  size="18" />
            </p>
            <p id="form-login-password">
                <label for="modlgn-passwd"><?php echo JText::_('JGLOBAL_PASSWORD') ?></label>
                <input id="modlgn-passwd" type="password" name="password" class="inputbox" size="18"  />
            </p>
            <?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
                <p id="form-login-remember">
                    <label for="modlgn-remember">
                        <input id="modlgn-remember" type="checkbox" name="remember" class="inputbox" value="yes"/>
                        <?php echo JText::_('MOD_REGWEB_USERBOX_REMEMBER_ME') ?>
                    </label>
                </p>
            <?php endif; ?>
            <input type="submit" name="Submit" class="button" value="<?php echo JText::_('JLOGIN') ?>" />
            <input type="hidden" name="option" value="com_users" />
            <input type="hidden" name="task" value="user.login" />
            <input type="hidden" name="return" value="<?php echo $return; ?>" />
            <input type="hidden" name="regweb_login" value="1" />
            <?php echo JHtml::_('form.token'); ?>
            <a href="<?php echo JRoute::_('index.php?option=com_regweb&view=lostpassword'); ?>">
                <?php echo JText::_('MOD_REGWEB_USERBOX_FORGOT_YOUR_PASSWORD'); ?></a>
        </fieldset>
        <?php if ($authParams->get('posttext')): ?>
            <div class="posttext">
                <p><?php echo $authParams->get('posttext'); ?></p>
            </div>
        <?php endif; ?>
    </form>

<?php endif; ?>
