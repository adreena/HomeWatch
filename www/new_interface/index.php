
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php require('header.html');?>

    <script>
      $(document).ready(function(){
  

        var id = $("li.active > a").attr('href');
        console.log(id);
        $.get("http://localhost:8888/HomeWatch/my_search/my_process.php?query="+id, function(response){
          var info={"title":"Electricity Comparison"};
            draw_bar_column(info,response,id);
        });
        
              $("#dashboard-toolbar").click(function(e){
                  var id = $(e.target).attr('href');
                  $.get("http://localhost:8888/HomeWatch/my_search/my_process.php?query="+id, function(response){
                    console.log(id);
                    if(id=="#energy-summary"){

                          var info={"title":"Energy Comparison"};
                          draw_pie(info,response,id);
                    }else if(id=="#electricity-summary"){
                          var info={"title":"Electricity Comparison"};
                          draw_bar_column(info,response,id);
                    }
                  });
              });
          
        });

      </script>
  </head>

  <body>
    
    <?php include('top-navbar.php');?>

    <div class="main-container" id="main-container">
      <script type="text/javascript">
        try{ace.settings.check('main-container' , 'fixed')}catch(e){}
      </script>

      <div class="main-container-inner">
        <a class="menu-toggler" id="menu-toggler" href="#">
          <span class="menu-text"></span>
        </a>

        <?php include('side-navbar.php');?>

        <div class="main-content">
          <?php include('breadcrumb.php');?>

            <div class="nav-search" id="nav-search">
              <form class="form-search">
                <span class="input-icon">
                  <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
                  <i class="icon-search nav-search-icon"></i>
                </span>
              </form>
            </div><!-- #nav-search -->
          </div> <!--breadcrumbs-->

          <div class="page-content">
            <!-- PAGE CONTENT BEGINS -->
            <div class="row">
              <div class="col-xs-12">
                  <?php include('dashboard.php');?>

                  <?php include('kw.php'); ?>
              </div>
            </div> <!--row--> 
            <!-- PAGE CONTENT ENDS -->        
          </div> <!--page-content-->
        </div><!-- /.main-content -->

        <?php include('setting.php');?>
      </div><!-- /.main-container-inner -->

      <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
        <i class="icon-double-angle-up icon-only bigger-110"></i>
      </a>
    </div><!-- /.main-container -->

    <?php include('basic-scripts.php');?>
  </body>
</html>
