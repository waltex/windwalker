<?php
/**
 * Part of Windwalker project. 
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

$fieldsets = $data->form->getFieldsets();
?>
<div class="row-fluid">
	<div class="span12">
		<?php echo $this->loadTemplate('fieldset', array('fieldset' => $fieldsets['rules'], 'class' => 'form-horizontal')); ?>
	</div>
</div>