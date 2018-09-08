    <header class="clearfix" style="margin-bottom: 10px;">
        <nav class="navbar navbar-expand-lg fixed-top navbar-light" style="background-color: #E0E0E0;">
            <div class="row">
                <div class="col-md-1">
                    <a href="<?php url('search') ?>"><img class="d-flex align-self-start mr-3" src="<?php url('assets/img/home.png') ?>" alt="Logo" style="max-height: 46px"></a>
                </div>
                <div class="col-md-10">
                </div>
                <div class="col-md-1">
                    <a href="<?php url('user') ?>" class="btn btn-primary" title="Login"><i class="fa fa-sign-in"></i></a>
                </div>
            </div>
        </nav>
    </header>
    <div class="container">
        <div class="row row justify-content-center mb-0 vertical-center">
            <div class="col-sm-10">
                <div class="page-title mb-3">
                    <h2 class="text-center"><?php echo get_option('app_name'); ?></h2>
                </div>
                <form method="get" action="<?php url('search/proses') ?>">
                    <div class="input-group input-group-lg">
                        <input type="text" name="search" class="form-control" placeholder="Type to Search" >
                        <div class="input-group-btn">
                            <button class="btn btn-default" type="submit" style="padding-right: 28px;"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>