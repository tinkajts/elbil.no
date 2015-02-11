<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Software (http://extstore.com). All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die();

$model = $this->getModel();
$total_other_answer = $model->getTotalOtherAnswer($this->item->id);

if ($this->item->params->get('graph_type', 0) == 1) {
	// get votes max
	$max_votes	= 0;

	foreach ($this->item->answers as $answer) {
		if ($answer->votes > $max_votes) {
			$max_votes	= $answer->votes;
		}
	}
}

if($this->item->params->get('display_other_answer', 0) == 1) {
	$this->item->answers = $model->getAllAnswers($this->item->id);
}

?>

<div class="sl_advpoll_result <?php echo ($this->item->params->get('result_display_type', 1) == 0 ? 'sl_hide_result' : ''); ?>">
	<?php if ($this->item->params->get('show_result', 1)) : ?>
	<div class="sl_advpoll_date">
		<?php echo $this->message; ?>
	</div>
	<div class="sl_advpoll_question">
		<?php echo $this->item->title; ?>
	</div>

<?php if ($this->item->params->get('graph_type') == 2 || $this->item->params->get('graph_type') == 3) : // Google Chart ?>
	<?php
	$data	= array("['Answer', 'Votes']");

	foreach ($this->item->answers as $answer) {
		$data[]	= "['" . addslashes($answer->title) . "',	$answer->votes]";
	}

	if($this->item->params->get('other_answer', 0) == 1 && $this->item->params->get('display_other_answer', 0) == 0 && $total_other_answer > 0) {
		$data[]	= "['" . addslashes('Other') . "',	$total_other_answer]";
	}

	if ($this->item->params->get('graph_type') == 2) {
		$chartType	= 'PieChart';
	} else if ($this->item->params->get('graph_type') == 3) {
		$chartType	= 'BarChart';
	}
	?>

	<div id="advpolls-gchart" style="<?php echo $this->item->params->get('result_display_type') == 0 ? 'width: 100%;' : '';?>"></div>

	<script>
		var data	= google.visualization.arrayToDataTable([
			<?php echo implode(', ', $data); ?>
		]);

		var option	= {
			is3D: true,
			chartArea: {height: 300}
			<?php if (!$this->item->params->get('show_votes', 1)) : ?>
				<?php echo ', tooltip: {trigger: \'none\'}'; ?>
			<?php endif; ?>
		};

		var chart = new google.visualization.<?php echo $chartType; ?>(document.getElementById('advpolls-gchart'));
		chart.draw(data, option);
	</script>

<?php else : ?>

	<ul class="sl_advpoll_graph">
		<?php $total_percent_default = 0;
			  $total_calculated_percent_default = 0;
		?>

		<?php foreach ($this->item->answers as $answer) : ?>
			<?php $percent	= $this->item->total_votes ? round(100 * $answer->votes / $this->item->total_votes, 2) : 0; ?>
			<?php $calculated_percent	= $this->item->params->get('graph_type', 0) == 0 ? $percent : ($max_votes ? round(100 * $answer->votes / $max_votes, 2) : 0); ?>

			<?php $total_percent_default += $percent;?>

			<li>
				<div class="sl_advpoll_answer_title">
					<?php echo $answer->title; ?>
				</div>
				<div class="sl_advpoll_answer_graph" title="<?php echo $percent; ?>%" data-percent="<?php echo $calculated_percent; ?>%">
					<div class="sl_advpoll_line_container">
						<div class="sl_advpoll_full_line">
							<div class="sl_advpoll_line" style="width: <?php echo $calculated_percent; ?>%"></div>
						</div>
						<div class="sl_advpoll_percent" style="width: <?php echo $calculated_percent; ?>%">
							<?php echo $percent; ?>%
						</div>
					</div>
				</div>

				<?php if ($this->item->params->get('show_votes')) : ?>
				<div class="sl_advpoll_answer_votes">
					<?php
						if ($answer->votes  == 0) {
							$text	= 'COM_SL_ADVPOLL_ZERO_VOTES';
						} else {
							if ($answer->votes > 0) {
								$text	= ($answer->votes ==1) ? 'COM_SL_ADVPOLL_ONE_VOTES' : 'COM_SL_ADVPOLL_N_VOTES';
							}
						}
					?>
					<?php echo JText::sprintf($text, $answer->votes); ?>
				</div>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>

		<?php if($this->item->params->get('display_other_answer', 0) == 0) : ?>
			<?php $total_calculated_percent_default = $this->item->params->get('graph_type', 0) == 0 ? (100 - $total_percent_default) : round(100 * $total_other_answer / $max_votes, 2); ?>
			<li>
				<div class="sl_advpoll_answer_title">
					<?php echo JText::sprintf('COM_SL_ADVPOLL_OTHER_ANSWERS') ?>
				</div>

				<div class="sl_advpoll_answer_graph" title="<?php echo 100 - $total_percent_default; ?>%" data-percent="<?php echo $total_calculated_percent_default; ?>%">
					<div class="sl_advpoll_line_container">
						<div class="sl_advpoll_full_line">
							<div class="sl_advpoll_line" style="width: <?php echo $total_calculated_percent_default; ?>%"></div>
						</div>
						<div class="sl_advpoll_percent" style="width: <?php echo $total_calculated_percent_default; ?>%">
							<?php echo 100 - $total_percent_default; ?>%
						</div>
					</div>
				</div>

				<?php if ($this->item->params->get('show_votes')) : ?>
				<div class="sl_advpoll_answer_votes">
					<?php
						if ($total_other_answer == 0) {
							$text	= 'COM_SL_ADVPOLL_ZERO_VOTES';
						} else {
							if ($total_other_answer > 0) {
								$text	= ($total_other_answer ==1) ? 'COM_SL_ADVPOLL_ONE_VOTES' : 'COM_SL_ADVPOLL_N_VOTES';
							}
						}
					?>
					<?php echo JText::sprintf($text, $total_other_answer); ?>
				</div>
				<?php endif; ?>

			</li>
		<?php endif; ?>

	</ul>
<?php endif; ?>

<?php if ($this->item->params->get('show_votes')) : ?>
	<div class="sl_advppoll_total">
		<?php echo JText::_('COM_SL_ADVPOLL_TOTAL'); ?>
		<span>
			<?php if ($this->item->total_votes <= 1) : ?>
				<?php echo JText::sprintf('COM_SL_ADVPOLL_N_VOTE', $this->item->total_votes); ?>
			<?php else : ?>
				<?php echo JText::sprintf('COM_SL_ADVPOLL_N_VOTES', $this->item->total_votes); ?>
			<?php endif; ?>
		</span>
	</div>
<?php endif; ?>

	<?php else : ?>
	<div class="sl_advpoll_msg">
		<div class="sl_advpoll_title">
			<?php echo $this->item->title; ?>
		</div>
		<div class="sl_advpoll_message">
			<?php echo $this->message; ?>
		</div>
		<div class="sl_advpoll_buttons">
			<button class="sl_advpoll_button" onclick="jQuery.fancybox.close();">
				<?php echo JText::_('COM_SL_ADVPOLL_CLOSE'); ?>
			</button>
		</div>
	</div>
	<?php endif; ?>
</div>