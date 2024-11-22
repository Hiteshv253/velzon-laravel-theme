
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ucfirst($breadcrumbs[0]['name'])}}</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ $breadcrumbs[1]['link'] }}">{{ ucfirst($breadcrumbs[1]['name']) }}</a></li>


                    <li class="breadcrumb-item"><a href="{{ $breadcrumbs[2]['link'] }}">{{ ucfirst($breadcrumbs[2]['name']) }}</a></li>

                </ol>
            </div>

        </div>
    </div>
</div>
