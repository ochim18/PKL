    <header class="clearfix" style="margin-bottom: 10px;">
        <nav class="navbar navbar-expand-lg fixed-top navbar-light" style="background-color: #E0E0E0;">
            <div class="row">
                <div class="col-md-1">
                    <a href="<?php url('search') ?>"><img class="d-flex align-self-start mr-3" src="<?php url('assets/img/home.png') ?>" alt="Logo" style="max-height: 46px"></a>
                </div>
                <div class="col-md-6">
                    <form method="get" action="<?php url('search/proses') ?>">
                        <div class="input-group input-group-lg">
                            <input type="text" name="search" class="form-control" placeholder="Type to Search">
                            <div class="input-group-btn">
                                <button class="btn btn-default" type="submit" style="padding-right: 28px"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-4"></div>
                <div class="col-md-1">
                    <a href="<?php url('user') ?>" class="btn btn-primary" title="Login"><i class="fa fa-sign-in"></i></a>
                </div>
            </div>
        </nav>
    </header>
    <div class="body">
        <div class="container-fluid">
            <div class="page-title">
            </div>
            <div class="page-content">
                <div class="row d-flex justify-content-center">
                    <div class="col-md-7">
                    <?php if(isset($data_buku)): foreach ($data_buku as $no => $item): ?>
                        <div>
                            <a href="<?php url('search/doc_view/'.$item->buku_id) ?>" title="<?php echo $item->judul; ?>"><h5><?php echo ucwords(strtolower(substr($item->judul, 0, 50)))." . . ."; ?></h5></a>
                            <p style="font-size: small;"><?php echo substr($item->abstract, 0, 200)." ..."; ?></p><br><br>
                        </div>
                    <?php endforeach ?>
                    <?php elseif (!isset($data_buku)):?>
                        <h3>NOT FOUND</h3>
                    <?php endif ?>
                    </div>
                    <div class="col-md-5"></div>
                </div>
            </div>
        </div>
    </div>