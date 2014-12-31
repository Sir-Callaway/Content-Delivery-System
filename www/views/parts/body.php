<div id="main-body" class="body <?php echo $parentSeg;?>">
  <div class="section">
    <?php if(!$showMenu){ ?>
    <div class="container">
  <?php } elseif($showMenu && $navigationMenu != ''){ ?>
    <!-- navigation menu -->
    <aside>  
      <nav id="main_menu">
        {navigationMenu}
      </nav>
    </aside>
    <!-- end of navigation menu -->
    <?php } if($showMenu) { ?>
    <div class="primary">
    <?php ;}?>
      <div class="container">
        <!--center content-->
        <div class="printPanel">
          <a href="" onclick="printContent('printable')"><img src="/images/system/icons/printButton.png" /></a>
          <a href="" onclick="emailContent('printable','<?php echo $pageHeading;?>','<?php echo current_url();?>')"><img src="/images/system/icons/emailButton.png" /></a>
        </div>
        <div id="printable" class="<?php if($controller == 'news' || $controller == 'noticeboard') echo 'kcmnews ';if($showOnlyContent || !$showSidebar) echo "container"; else echo "primary ";?>" <?php if(!$showSidebar) echo 'style="width: 100%"';?>>
          <h1 class="pageHeading">{pageHeading}</h1>
          {centerContent}
        </div>
        <!--end of center content-->
        <!--sidebar content-->
        <?php if($showSidebar){?>   
        <aside>
            {sidebarContent}    
        </aside>
        <?php }?>
        <!--end of sidebar-->    
      </div>  
    </div>
  </div>
  <div class="section">
    {advertContent}
  </div>
</div>          
      
    
              