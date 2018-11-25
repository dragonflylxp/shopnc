<ul>
        <?php $_from = $this->_var['cat_store_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');if (count($_from)):
    foreach ($_from AS $this->_var['cat']):
?>
    <li>
        <div class="jOneLevelarea user_temp_one">
            <div class="jTwoLevel">
                <span class="square_box"></span>
                 <a href="<?php echo $this->_var['cat']['url']; ?>" target="_blank"><?php echo $this->_var['cat']['cat_name']; ?></a>
            </div>
            <div class="s_b">
                <?php $_from = $this->_var['cat']['child_tree']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'tree');if (count($_from)):
    foreach ($_from AS $this->_var['tree']):
?>
                <a href="<?php echo $this->_var['tree']['url']; ?>" target="_blank"><?php echo $this->_var['tree']['name']; ?></a>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
        </div>
    </li>
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</ul>