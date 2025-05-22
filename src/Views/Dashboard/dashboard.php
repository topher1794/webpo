<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Stock Alignment Project</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Dashboard </li>
          </ol>
        </div>
      </div>
    </div>
  </div>




  <div class="content">
    <div class="container-fluid">




      <div class="row">
        <div class="col-12">

          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><b>Search</b></h3>

              <div class="card-tools">
                <div class="input-group-append">
                  <form method="post" action="modulecontrol?Dashboard&Home">
                    <input type="date" name="from" value="<?php //echo $from; 
                                                          ?>" />
                    <input type="date" name="to" value="<?php ///echo $to; 
                                                        ?>" />
                    <button type="submit" class="btn btn-default">
                      <i class="fas fa-search"></i>
                    </button>
                  </form>

                </div>
              </div>
            </div>

          </div>

        </div>
      </div>

    </div>
  </div>
</div>