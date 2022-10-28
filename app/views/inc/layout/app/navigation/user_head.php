<?php
    $con = new Database();
    $menuitems = getusermenuitems($con->dbh,(int)$_SESSION['userid']);
    $menuicons = [
        'Admin' => 'uil-shield-check',
        'Stock Management' => 'uil-exchange',
        'Students' => 'uil-graduation-hat',
        'Sales' => 'uil-dollar-sign',
        'Exams' => 'uil-clipboard-notes',
        'Finance' => 'uil-moneybag-alt',
        'Reports' => 'uil-receipt-alt'
    ];
?>
<?php foreach ($menuitems as $menuitem) : ?>
<li class="side-nav-item">
    <a data-bs-toggle="collapse" href="#sidebar<?php echo str_replace(' ','',$menuitem);?>" aria-expanded="false" aria-controls="sidebar<?php echo str_replace(' ','',$menuitem);?>" class="side-nav-link">
        <i class="<?php echo $menuicons[$menuitem];?>"></i>
        <span> <?php echo $menuitem;?> </span>
        <span class="menu-arrow"></span>
    </a>
    <div class="collapse" id="sidebar<?php echo str_replace(' ','',$menuitem);?>">
        <ul class="side-nav-second-level">
            <?php if(!hassubmenus($con->dbh,$menuitem)) : ?>
                <?php foreach(getmodulemenuitems($con->dbh,(int)$_SESSION['userid'],$menuitem) as $item) : ?>
                    <li>
                        <a href="<?php echo URLROOT;?>/<?php echo $item->Path;?>"><?php echo ucwords($item->FormName);?></a>
                    </li>
                <?php endforeach ;?>
            <?php else : ?>
                <?php $submenus = getsubmenuitems($con->dbh,$menuitem,(int)$_SESSION['userid']) ;?>
                <?php foreach($submenus as $submenu) : ?>
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebar<?php echo str_replace(' ','',$submenu);?>" aria-expanded="false" aria-controls="sidebar<?php echo str_replace(' ','',$submenu);?>">
                            <span> <?php echo $submenu;?> </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebar<?php echo str_replace(' ','',$submenu);?>">
                            <ul class="side-nav-third-level">
                                <?php foreach(getsubmenunavitems($con->dbh,(int)$_SESSION['userid'],$menuitem,$submenu) as $item) : ?>
                                    <li>
                                        <a href="<?php echo URLROOT;?>/<?php echo $item->Path;?>"><?php echo ucwords($item->FormName);?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</li>

<?php endforeach; ?>