<li class="has-sub">
    <a class="js-arrow" href="#">
        <i class="<?php echo $value['icon']?>"></i><?php echo $value['title']?></a>
    <ul class="list-unstyled navbar__sub-list js-sub-list">
        <?php foreach ($value['member'] as $list){ ?>
        <li>

            <a href="<?php echo base_url('main/'.$list['url'])?>"><?php echo $list['title']?></a>

        </li>
        <?php }?>
    </ul>
</li>
