<div class="content-wrapper">



    <section class="content-header">
        <div class="container-fluid">
            <h2 class="text-center display-4">Search</h2>
        </div>
    </section>


    <link rel="stylesheet" href="../assets/css/table.css">
    <section class="content">

        <div class="container-fluid">


            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <form action="modulecontrol?SearchResults&Transactions" method="post">
                        <div class="input-group input-group-lg">
                            <input name="searchTerm" type="search" class="form-control form-control-lg" placeholder="Type your keywords here" value="<?php echo  $searchTerm; ?>">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-lg btn-default">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
           

        </div>

    </section>
</div>