<div class="select-top">
	<a href="javascript:;" class="categoryTop" data-cid="0" data-cname="" data-show='<?php echo empty($this->_var['cat_type_show']) ? '0' : $this->_var['cat_type_show']; ?>' data-seller='<?php echo empty($this->_var['user_id']) ? '0' : $this->_var['user_id']; ?>'>重选</a>
	<?php $_from = $this->_var['filter_category_navigation']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'navigation');if (count($_from)):
    foreach ($_from AS $this->_var['navigation']):
?>
	&gt <a href="javascript:;" class="categoryOne" data-cid="<?php echo $this->_var['navigation']['cat_id']; ?>" data-cname="<?php echo $this->_var['navigation']['cat_name']; ?>" data-url='<?php echo $this->_var['navigation']['url']; ?>' data-show='<?php echo empty($this->_var['cat_type_show']) ? '0' : $this->_var['cat_type_show']; ?>' data-seller='<?php echo empty($this->_var['user_id']) ? '0' : $this->_var['user_id']; ?>'><?php echo $this->_var['navigation']['cat_name']; ?></a>
	<?php endforeach; else: ?>
	&gt <span>请选择分类</span>
	<?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
</div>
<div class="select-list">
	<ul>
		<?php $_from = $this->_var['filter_category_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'category');if (count($_from)):
    foreach ($_from AS $this->_var['category']):
?>
		<li data-cid="<?php echo $this->_var['category']['cat_id']; ?>" data-cname="<?php echo $this->_var['category']['cat_name']; ?>" <?php if ($this->_var['category']['is_selected']): ?>class="blue"<?php endif; ?> data-url='<?php echo $this->_var['category']['url']; ?>' data-show='<?php echo empty($this->_var['cat_type_show']) ? '0' : $this->_var['cat_type_show']; ?>' data-seller='<?php echo empty($this->_var['user_id']) ? '0' : $this->_var['user_id']; ?>'>
			<em><?php if ($this->_var['filter_category_level'] == 1): ?>Ⅰ<?php elseif ($this->_var['filter_category_level'] == 2): ?>Ⅱ<?php elseif ($this->_var['filter_category_level'] == 3): ?>Ⅲ<?php else: ?>Ⅰ<?php endif; ?></em>
			<?php echo $this->_var['category']['cat_name']; ?>
		</li>
		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
	</ul>
</div>