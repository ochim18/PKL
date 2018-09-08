    <header class="clearfix" style="margin-bottom: 10px;">
        
    </header>
    <div class="body">
        <div class="container-fluid">
            <div class="page-title">
                <h2 class="text-center"><?php echo $post['judul']?></h2>
            </div>
            <div class="page-content mb-3">
                <div class="row d-flex justify-content-center">
                    <div class="col-md-11">
                        <ul class="nav nav-pills nav-justified">
                            <li class="active"><a data-toggle="tab" href="#home">Abstract</a></li>
                            <li><a data-toggle="tab" href="#menu1">Pengarang</a></li>
                            <li><a data-toggle="tab" href="#menu2">Keyword</a></li>
                        </ul>

                    <div class="tab-content">
                        <div id="home" class="tab-pane fade in active">
                            <div class="row" style="padding: 40px;">
                                <div class="col-md-8">
                                    <p><b>Abstract : </b><?php echo $post['abstract']?></p><br>
                                    <p><b>Date of Publication : </b><?php echo $post['created_at']; ?></p><br>
                                    <p><b>NO ISSN : </b><?php echo $post['no_issn']; ?></p><br>                          
                                </div>
                                <div class="col-md-2">
                                    <a id="trigger" type="button" class="btn btn-default" style="width: 100%; border-color: navy;">Open Document</a>
                                    <div id="dialog" style="display:none;">
                                        <div>
                                            <embed id="nocopy" src="<?php url("uploads"); echo '/'.$post['data'] ?>#toolbar=0" width="800px" height="2100px" />
                                        </div>
                                    </div> 
                                </div>
                            </div>
                        </div>
                        <div id="menu1" class="tab-pane fade">
                            <div style="padding: 40px;">
                                <p><b>Pengarang : </b><?php echo $post['pengarang']?></p>
                            </div>
                        </div>
                        <div id="menu2" class="tab-pane fade">
                            <div style="padding: 40px;">
                                <p><b>Keyword : </b><?php echo $post['keyword']?></p>
                            </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-1">
                    </div>
                </div>
            </div>
        </div>
    </div>