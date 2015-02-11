<?php
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.html.html.behavior' );
JHtml::_('behavior.keepalive');
?>

<?php if (!$this->user->get('guest')): ?>
    <h1><?php echo JText::_('COM_REGWEB_YOU_ARE_LOGGED_IN') ?></h1>
	
	<form action="<?php echo JRoute::_('index.php', true, false); ?>" method="post" id="logout-form">
		<button type="submit" class="button"><?php echo JText::_('JLOGOUT'); ?></button>
		<input type="hidden" name="option" value="com_users" />
		<input type="hidden" name="task" value="user.logout" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
<?php else: ?>
	
	<script src="http://codeorigin.jquery.com/jquery-1.10.2.min.js"></script>
	
	<script>
		if (typeof jQuery === 'undefined') {
			alert('jQuery is required');
		}
		jQuery(function($) {
			var REGWEB_SELECTLOGIN = $('#regweb_login');
			var defaultLogin = $('#default_login');

			function updateDisplay() {
				REGWEB_SELECTLOGIN.hide();
				defaultLogin.hide();
				var choosen = $('#login_chooser input:radio[name=login_chooser]:checked').val();
				if (choosen == 'regweb') {
					REGWEB_SELECTLOGIN.show();
				} else if (choosen == 'default') {
					defaultLogin.show();
				}
			}
			updateDisplay();
			
			$('#login_chooser input:radio[name=login_chooser]').change(function() {
				updateDisplay();
			});
			
		})(jQuery);
	</script>
	
	<h1><?php  echo JText::_('COM_REGWEB_LOGIN_TITLE'); ?></h1>
	
	<div id="login_chooser">
		<form>
			<input type="radio" name="login_chooser" value="regweb" id="login_chooser_regweb" checked="checked">
			<label for="login_chooser_regweb">Medlem</label>
			<input type="radio" name="login_chooser" value="default" id="login_chooser_default">
			<label for="login_chooser_default">Lokallag</label>
		</form>
	</div>
	
	<div id="regweb_login" style="display:none;">
		<form action="<?php echo JRoute::_('index.php', true, false); ?>" method="post" id="login-form" >
			<fieldset class="userdata">
			<p id="form-login-username">
				<label for="modlgn-username"><?php echo JText::_('COM_REGWEB_SELECTLOGIN_REGWEB_VALUE_USERNAME') ?></label>
				<input id="modlgn-username" type="text" name="username" class="inputbox"  size="18" />
			</p>
			<p id="form-login-password">
				<label for="modlgn-passwd"><?php echo JText::_('JGLOBAL_PASSWORD') ?></label>
				<input id="modlgn-passwd" type="password" name="password" class="inputbox" size="18"  />
			</p>
			<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
			<p id="form-login-remember">
				<label for="modlgn-remember"><?php echo JText::_('COM_REGWEB_SELECTLOGIN_REGWEB_REMEMBER_ME') ?></label>
				<input id="modlgn-remember" type="checkbox" name="remember" class="inputbox" value="yes"/>
			</p>
			<?php endif; ?>
			<input type="submit" name="Submit" class="button" value="<?php echo JText::_('JLOGIN') ?>" />
			<input type="hidden" name="option" value="com_users" />
			<input type="hidden" name="task" value="user.login" />
			<input type="hidden" name="return" value="<?php echo ($this->return != '') ?
															$this->return :
															base64_encode('index.php?option=com_regweb&view=profile'); ?>" />
			<input type="hidden" name="regweb_login" value="1" />
			<?php echo JHtml::_('form.token'); ?>
			</fieldset>
			<ul>
				<li>
					<a href="<?php echo JRoute::_('index.php?option=com_regweb&view=lostpassword'); ?>">
					<?php echo JText::_('COM_REGWEB_SELECTLOGIN_REGWEB_FORGOT_YOUR_PASSWORD'); ?></a>
				</li>
			</ul>
		</form>
	</div>
	
	<div id="default_login" style="display:none;">
		<form action="<?php echo JRoute::_('index.php', true, false); ?>" method="post" id="login-form" >
			<fieldset class="userdata">
			<p id="form-login-username">
				<label for="modlgn-username"><?php echo JText::_('COM_REGWEB_SELECTLOGIN_DEFAULT_VALUE_USERNAME') ?></label>
				<input id="modlgn-username" type="text" name="username" class="inputbox"  size="18" />
			</p>
			<p id="form-login-password">
				<label for="modlgn-passwd"><?php echo JText::_('JGLOBAL_PASSWORD') ?></label>
				<input id="modlgn-passwd" type="password" name="password" class="inputbox" size="18"  />
			</p>
			<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
			<p id="form-login-remember">
				<label for="modlgn-remember"><?php echo JText::_('COM_REGWEB_SELECTLOGIN_DEFAULT_REMEMBER_ME') ?></label>
				<input id="modlgn-remember" type="checkbox" name="remember" class="inputbox" value="yes"/>
			</p>
			<?php endif; ?>
			<input type="submit" name="Submit" class="button" value="<?php echo JText::_('JLOGIN') ?>" />
			<input type="hidden" name="option" value="com_users" />
			<input type="hidden" name="task" value="user.login" />
			<input type="hidden" name="return" value="<?php echo ($this->return != '') ?
															$this->return :
															base64_encode('index.php'); ?>" />
			<?php echo JHtml::_('form.token'); ?>
			</fieldset>
			<ul>
				<li>
					<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
					<?php echo JText::_('COM_REGWEB_SELECTLOGIN_DEFAULT_FORGOT_YOUR_PASSWORD'); ?></a>
				</li>
			</ul>
		</form>
	</div>

<?php endif; ?>
