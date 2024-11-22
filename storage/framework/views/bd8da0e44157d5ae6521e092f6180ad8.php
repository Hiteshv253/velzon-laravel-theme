<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('translation.orders'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(URL::asset('build/libs/sweetalert2/sweetalert2.min.css')); ?>" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('components.breadcrumb', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="orderList">
                <div class="card-header border-0">
                    <div class="row align-items-center gy-3">
                        <div class="col-sm">
                            <h5 class="card-title mb-0">User History</h5>
                        </div>
                        <div class="col-sm-auto">
                            <div class="d-flex gap-1 flex-wrap">
                                <a type="button" class="btn btn-success add-btn"  href="<?php echo e(route('users.create')); ?>" id="create-btn">
                                    <i class="ri-add-line align-bottom me-1"></i> Create User
                                </a>
                                <button type="button" class="btn btn-info">
                                    <i class="ri-file-download-line align-bottom me-1"></i> Import
                                </button>
                                <div class="dropdown">
                                    <a href="#" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    Export
                                    </a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#">PDF</a>
                                        <a class="dropdown-item" href="#">Excel</a>
                                    </div>
                                </div>

                                <button class="btn btn-soft-danger" id="remove-actions1" onClick="deleteMultiple()">
                                    <i class="ri-delete-bin-2-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body border border-dashed border-end-0 border-start-0" >
                    <form>
                        <div class="row g-3">
                            <div class="col-xxl-5 col-sm-6">
                                <div class="search-box">
                                    <input type="text" class="form-control search"
                                       placeholder="Search for User Name, Email ID or something...">
                                    <i class="ri-search-line search-icon"></i>
                                </div>
                            </div>
                        <!--end col-->
                            <div class="col-xxl-2 col-sm-6" style="display: none;">
                                <div>
                                    <input type="text" class="form-control" data-provider="flatpickr"
                                       data-date-format="d M, Y" data-range-date="true" id="demo-datepicker"
                                       placeholder="Select date">
                                </div>
                            </div>
                        <!--end col-->
                            <div class="col-xxl-2 col-sm-4" style="display: none;">
                                <div>
                                    <select class="form-control" data-choices data-choices-search-false
                                        name="choices-single-default" id="idStatus">
                                        <option value="">Status</option>
                                        <option value="all" selected>All</option>
                                        <option value="Pending">Pending</option>
                                        <option value="Inprogress">Inprogress</option>
                                        <option value="Cancelled">Cancelled</option>
                                        <option value="Pickups">Pickups</option>
                                        <option value="Returns">Returns</option>
                                        <option value="Delivered">Delivered</option>
                                    </select>
                                </div>
                            </div>
                        <!--end col-->
                            <div class="col-xxl-2 col-sm-4" style="display: none;">
                                <div>
                                    <select class="form-control" data-choices data-choices-search-false
                                        name="choices-single-default" id="idPayment">
                                        <option value="">Select Payment</option>
                                        <option value="all" selected>All</option>
                                        <option value="Mastercard">Mastercard</option>
                                        <option value="Paypal">Paypal</option>
                                        <option value="Visa">Visa</option>
                                        <option value="COD">COD</option>
                                    </select>
                                </div>
                            </div>
                        <!--end col-->
                            <div class="col-xxl-1 col-sm-4" style="display: none;">
                                <div>
                                    <button type="button" class="btn btn-primary w-100" onclick="SearchData();"> <i
                                    class="ri-equalizer-fill me-1 align-bottom"></i>
                                    Filters
                                </button>
                            </div>
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </form>
            </div>

            <div class="card-body pt-0">
                <div>
                    <ul class="nav nav-tabs nav-tabs-custom nav-success mb-3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active All py-3" data-bs-toggle="tab" id="All" href="#home1"
                               role="tab" aria-selected="true">
                                <i class="ri-store-2-fill me-1 align-bottom"></i> All Users (<?php echo  count($users);?>)
                            </a>
                        </li>
                    </ul>

                    <div class="table-responsive table-card mb-1">
                        <table class="table table-nowrap align-middle" id="orderTable">
                            <thead class="text-muted table-light">
                                <tr class="text-uppercase">
                                    <th scope="col" style="width: 25px;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="checkAll"
                                                   value="option">
                                        </div>
                                    </th>
                                    <th class="sort" data-sort="id">ID</th>
                                    <th class="sort" data-sort="customer_name">Name</th>
                                    <th class="sort" data-sort="product_name">Email ID</th>
                                    <th class="sort" data-sort="date">Roles</th>
                                    <th class="sort" data-sort="city">Action</th>
                                </tr>
                            </thead>

                            <tbody class="list form-check-all">
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <th scope="row">
                                            <div class="form-check"> 
                                                <input class="form-check-input" type="checkbox" name="selected_row" data-id="<?php echo e($user->id); ?>"
                                                   value="<?php echo e($user->id); ?>">
                                            </div>
                                        </th>
                                        <td class="id1"><a href="apps-ecommerce-order-details" class="fw-medium link-primary"><?php echo e($loop->iteration); ?></a></td>
                                        <td class="customer_name"><?php echo e($user->name); ?></td>
                                        <td class="product_name"><?php echo e($user->email); ?></td>
                                        <td class="date"><?php echo e(implode(",",$user->getRoleNames()->toArray())); ?>

                                        </td>
                                        <td>
                                            <ul class="list-inline hstack gap-2 mb-0">
                                                <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="View">
                                                    <a href="apps-ecommerce-order-details" class="text-primary d-inline-block">
                                                        <i class="ri-eye-fill fs-16"></i>
                                                    </a>
                                                </li>
                                                <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                    <a href="<?php echo e(route('users.edit', $user->id)); ?>" class="text-primary d-inline-block">
                                                        <i class="ri-pencil-fill fs-16"></i>
                                                    </a>
                                                </li>
                                                <li style="display: none;" class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Remove">
                                                    <a data-id="<?php echo e($user->id); ?>" class="text-danger d-inline-block remove-item-btn" data-bs-toggle="modal" href="#deleteOrder">
                                                        <i class="ri-delete-bin-5-fill fs-16"></i>
                                                    </a>
                                                </li>


                                            </ul>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                        <div class="noresult" style="display: none">
                            <div class="text-center">
                                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                           colors="primary:#405189,secondary:#0ab39c" style="width:75px;height:75px">
                                </lord-icon>
                                <h5 class="mt-2">Sorry! No Result Found</h5>
                                <p class="text-muted">We've searched more than 150+ Orders We did
                                    not find any
                                    orders for you search.</p>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <div class="pagination-wrap hstack gap-2">
                            <a class="page-item pagination-prev disabled" href="#">
                                Previous
                            </a>
                            <ul class="pagination listjs-pagination mb-0"></ul>
                            <a class="page-item pagination-next" href="#">
                                Next
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade flip" id="deleteOrder" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body p-5 text-center">
                                <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                                           colors="primary:#405189,secondary:#f06548"
                                           style="width:90px;height:90px"></lord-icon>
                                <div class="mt-4 text-center">
                                    <h4>You are about to delete a user ?</h4>
                                    <p class="text-muted fs-15 mb-4">Deleting your user will remove
                                        all of your information from our database.</p>
                                    <div class="hstack gap-2 justify-content-center remove">
                                        <button class="btn btn-link link-success fw-medium text-decoration-none"
                                                data-bs-dismiss="modal" id="deleteRecord-close"><i
                                                class="ri-close-line me-1 align-middle"></i>
                                            Close</button>

                                        <button class="btn btn-danger" id="delete-record" onclick="single_delete();">Yes,
                                            Delete It</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end modal -->
            </div>
        </div>

    </div>
    <!--end col-->
</div>
<!--end row-->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script src="<?php echo e(URL::asset('build/libs/list.js/list.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('build/libs/list.pagination.js/list.pagination.min.js')); ?>"></script>

<!--ecommerce-customer init js -->
<script src="<?php echo e(URL::asset('build/js/pages/ecommerce-order.init.js')); ?>"></script>

<script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>
<script>
// Delete Multiple Records
function deleteMultiple() {

            var checkbox_array = [];
            var token = $("meta[name='csrf-token']").attr("content");
            $.each($("input[name='selected_row']:checked"), function () {
                checkbox_array.push($(this).data("id"));
            });
            // console.log(checkbox_array);
            if (typeof checkbox_array !== 'undefined' && checkbox_array.length > 0) {

                 Swal.fire({
                    title: "Are you sure?",
                    text: "You will not be able to recover these record!",
                    icon: 'warning',
                    showCancelButton: true,
                    dangerMode: true,
                    
                    showCloseButton: true
                     
                }).then(function (result) {
                     if (result.value) {
                      $("#overlay").fadeIn(300);　
                        $.ajax({
                                    url: '<?php echo e(route('users.multi_delete')); ?>',
                                type: 'POST',
                                data: {
                                    "_token": token,
                                    ids: checkbox_array,
                                }
                                }).done(function() {
                                     document.getElementById("checkAll").checked = false;
                                     setTimeout(function(){
                                        $("#overlay").fadeOut(300);
                                     },500);   
                                   Toastify({ text: "Your data has been deleted.", duration: 3000 }).showToast();
                                    window.location.reload(1);
                                });
                                            
                    } else {
                        document.getElementById("checkAll").checked = false;
//                        document.getElementsByClass("form-check-input").checked = false;
                            Toastify({ text: "Your Record is safe", duration: 30000 }).showToast();
                    }
                });
            } else {
                 Swal.fire({
                    title: "0 Row selected!",
                    text: "Select any record from the list",
                    icon: 'warning',
                });
            }

       }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\velzon-laravel-10-main\resources\views/users/index.blade.php ENDPATH**/ ?>