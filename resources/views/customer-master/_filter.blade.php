<div id="accordion">
    <div class="card">
        <div class="card-header" id="headingOne">
            <h5 class="mb-0">
                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    Customer Filter
                </button>
            </h5>
        </div>
        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 col-md-3">
                        <select id="branch" name="branch_id" class="form-control" required="">
                            <option value="" >Select Branch</option>
                            @foreach($branchs as $key=>$branch)
                            @if($key ==0)
                            @php continue; @endphp
                            @endif
                            <option value="{{$key}}"  {{( isset($search_branch) && ($key == $search_branch) )?'selected':''}}  >{{$branch}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <select id="customer_id" multiple    name="customer_id[]" class="form-control" required>
                            @foreach($search_branchs_customers as $key=>$customer)
                            <option value="{{$key}}"  {{( in_array($key,$search_customer) )?'selected':''}}  >{{$customer}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <input type="text" name="contract_start" value="{{$search__sdate}}"  id="search_start_date" class="form-control datepicker" placeholder="Enter Start Date" autocomplete="off" autofocus="off">
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <input type="text" name="contract_end" value="{{$search__edate}}" id="search_end_date" class="form-control datepicker" placeholder="Enter End Date" autocomplete="off" autofocus="off">
                    </div>
                    <div class="col-sm-6 col-md-1">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                    <div class="col-sm-6 col-md-1">
                        <a class="btn btn-primary" href="{{route('customer.index')}}">Reset</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>