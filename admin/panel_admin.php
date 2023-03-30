                <?php

                include('../class/handler_admin.php');

				$object = new Handler;

				if(!$object->is_login())
				{
				    header("location:".$object->base_url."");
				}

                if($_SESSION['type'] != 'Admin')
                {
                    header("location:".$object->admin_url);
                }

                include('header.php');

                ?>

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Panel</h1>

                    <!-- Content Row -->
                    <div class="row row-cols-5">
                        
                        <div class="col mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Doktoranci</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $object->doktoranci_wszyscy(); ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-solid fa-user fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
						
                       
                        <div class="col mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Promotorzy</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $object->promotorzy_wszyscy(); ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-regular fa-id-card fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col mb-4">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Zarezerwowane Prace Dyplomowe</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $object->prace_zarezerwowane(); ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Wszystkie Prace</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $object->prace_wszystkie(); ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-solid fa-scroll fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Zarejestrowani Studenci</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $object->studenci_wszyscy(); ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 

                <?php
                include('footer.php');
                ?> 
				