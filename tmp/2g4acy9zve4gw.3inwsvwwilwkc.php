<div class="navbar">
    <div class="navbar-inner">
        <ul class="nav">
 
            <li <?php if ($page_head == 'Customer List'): ?>class="active"<?php endif; ?>><a href="<?php echo $BASE.'/'; ?>"><i class="icon-th icon-black"></i> Read</a></li>
            <li <?php if ($page_head == 'Create User'): ?>class="active"<?php endif; ?>><a href="<?php echo $BASE.'/customer/create'; ?>"><i class="icon-plus-sign icon-black"></i> Create</a></li>
 
            <?php if ($page_head == 'Update Customer'): ?>
            <li class="active"><a href="javascript:void(0);"><i class="icon-plus-sign icon-black"></i> Update</a></li>
            <?php endif; ?>
        </ul>
    </div>
</div>