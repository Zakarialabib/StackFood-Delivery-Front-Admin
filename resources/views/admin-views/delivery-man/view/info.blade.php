@extends('layouts.admin.app')

@section('title',__('Delivery Man Preview'))

@section('content')
    <div class="content container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item" aria-current="page">{{__('Deliveryman view')}}</li>
            </ol>
        </nav>
        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-6">
                    <h1>{{__('Deliveryman preview')}}</h1>
                </div>
                @if($dm->application_status == 'approved')
                <div class="col-6">
                    <a href="{{url()->previous()}}" class="btn btn-primary float-right">
                        <i class="tio-back-ui"></i> {{__('back')}}
                    </a>
                </div>

                <div class="js-nav-scroller hs-nav-scroller-horizontal">
                    <!-- Nav -->
                    <ul class="nav nav-tabs page-header-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" href="{{route('admin.delivery-man.preview', ['id'=>$dm->id, 'tab'=> 'info'])}}"  aria-disabled="true">{{__('info')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('admin.delivery-man.preview', ['id'=>$dm->id, 'tab'=> 'transaction'])}}"  aria-disabled="true">{{__('transaction')}}</a>
                        </li>
                    </ul>
                    <!-- End Nav -->
                </div>
                @else
                <div class="col-md-6">
                    <div class="hs-unfold float-right">
                        <a class="btn btn-primary text-capitalize font-weight-bold"
                        onclick="request_alert('{{route('admin.delivery-man.application',[$dm['id'],'approved'])}}','{{__('You want to approve this application')}}')"
                            href="javascript:">{{__('Approve')}}</a>
                        @if($dm->application_status !='denied')
                        <a class="btn btn-danger text-capitalize font-weight-bold" 
                        onclick="request_alert('{{route('admin.delivery-man.application',[$dm['id'],'denied'])}}','{{__('You want to deny this application')}}')"
                            href="javascript:">{{__('deny')}}</a>
                        @endif
                    </div>
                </div>

                @endif
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Card -->
        <div class="card mb-3 mb-lg-5 mt-2">
            <div class="card-header">
                <h4 class="page-header-title">
                    {{$dm['f_name'].' '.$dm['l_name']}}
                    
                    (@if($dm->zone) 
                        {{$dm->zone->name}} 
                    @else {{__('Zone deleted')}} 
                    @endif ) 
                    @if($dm->application_status=='approved')
                        @if($dm['status']) 
                            @if($dm['active']) 
                                <label class="badge badge-soft-primary">{{__('online')}}</label> 
                            @else 
                                <label class="badge badge-soft-danger">{{__('offline')}}</label> 
                            @endif  
                        @else 
                        <span class="badge badge-danger">{{__('suspended')}}</span> 
                        @endif
                    
                    @else
                    <label class="badge badge-soft-{{$dm->application_status=='pending'?'info':'danger'}}">{{__(''.$dm->application_status)}}</label>
                    @endif
                </h4>
                <!-- <a  href="javascript:"  onclick="request_alert('{{route('admin.delivery-man.earning',[$dm['id'],$dm->earning?0:1])}}','{{$dm->earning?__('Want to disable earnings'):__('Want to enable earnings')}}')" class="btn {{$dm->earning?'btn-danger':'btn-success'}}">
                         {{$dm->earning?__('Disable earning'):__('Enable earning')}}
                </a> -->
                @if($dm->application_status=='approved')
                <a  href="javascript:"  onclick="request_alert('{{route('admin.delivery-man.status',[$dm['id'],$dm->status?0:1])}}','{{$dm->status?__('You want to suspend this deliveryman'):__('You want to unsuspend this deliveryman')}}')" class="btn {{$dm->status?'btn-danger':'btn-success'}}">
                        {{$dm->status?__('Suspend this delivery man'):__('Unsuspend this delivery man')}}
                </a>
                @endif
                <div class="hs-unfold float-right">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                            {{__('Type')}} ({{$dm->earning?__('freelancer'):__('Salary based')}})
                        </button>
                        <div class="dropdown-menu text-capitalize" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item {{$dm->earning?'active':''}}"
                            onclick="request_alert('{{route('admin.delivery-man.earning',[$dm['id'],1])}}','{{__('Want to enable earnings')}}')"
                                href="javascript:">{{__('freelancer')}}</a>
                            <a class="dropdown-item {{$dm->earning?'':'active'}}"
                            onclick="request_alert('{{route('admin.delivery-man.earning',[$dm['id'],0])}}','{{__('Want to disable earnings')}}')"
                                href="javascript:">{{__('Salary based')}}</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Body -->
            <div class="card-body">
                <div class="row align-items-md-center gx-md-5">
                    <div class="col-md-auto mb-3 mb-md-0">
                        <div class="d-flex align-items-center">
                            <img class="avatar avatar-xxl avatar-4by3 mr-4"
                                 onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                                 src="{{asset('storage/app/public/delivery-man')}}/{{$dm['image']}}"
                                 alt="Image Description">
                            <div class="d-block">
                                <h4 class="display-2 text-dark mb-0">{{count($dm->rating)>0?number_format($dm->rating[0]->average, 2, '.', ' '):0}}</h4>
                                <p> of {{$dm->reviews->count()}} {{__('Reviews')}}
                                    <span class="badge badge-soft-dark badge-pill ml-1"></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md">
                        <ul class="list-unstyled list-unstyled-py-2 mb-0">

                        @php($total=$dm->reviews->count())
                        <!-- Review Ratings -->
                            <li class="d-flex align-items-center font-size-sm">
                                @php($five=\App\CentralLogics\Helpers::dm_rating_count($dm['id'],5))
                                <span
                                    class="mr-3">5 star</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{$total==0?0:($five/$total)*100}}%;"
                                         aria-valuenow="{{$total==0?0:($five/$total)*100}}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="ml-3">{{$five}}</span>
                            </li>
                            <!-- End Review Ratings -->

                            <!-- Review Ratings -->
                            <li class="d-flex align-items-center font-size-sm">
                                @php($four=\App\CentralLogics\Helpers::dm_rating_count($dm['id'],4))
                                <span class="mr-3">4 star</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{$total==0?0:($four/$total)*100}}%;"
                                         aria-valuenow="{{$total==0?0:($four/$total)*100}}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="ml-3">{{$four}}</span>
                            </li>
                            <!-- End Review Ratings -->

                            <!-- Review Ratings -->
                            <li class="d-flex align-items-center font-size-sm">
                                @php($three=\App\CentralLogics\Helpers::dm_rating_count($dm['id'],3))
                                <span class="mr-3">3 star</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{$total==0?0:($three/$total)*100}}%;"
                                         aria-valuenow="{{$total==0?0:($three/$total)*100}}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="ml-3">{{$three}}</span>
                            </li>
                            <!-- End Review Ratings -->

                            <!-- Review Ratings -->
                            <li class="d-flex align-items-center font-size-sm">
                                @php($two=\App\CentralLogics\Helpers::dm_rating_count($dm['id'],2))
                                <span class="mr-3">2 star</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{$total==0?0:($two/$total)*100}}%;"
                                         aria-valuenow="{{$total==0?0:($two/$total)*100}}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="ml-3">{{$two}}</span>
                            </li>
                            <!-- End Review Ratings -->

                            <!-- Review Ratings -->
                            <li class="d-flex align-items-center font-size-sm">
                                @php($one=\App\CentralLogics\Helpers::dm_rating_count($dm['id'],1))
                                <span class="mr-3">1 star</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{$total==0?0:($one/$total)*100}}%;"
                                         aria-valuenow="{{$total==0?0:($one/$total)*100}}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="ml-3">{{$one}}</span>
                            </li>
                            <!-- End Review Ratings -->
                        </ul>
                    </div>
                </div>
            </div>
            <!-- End Body -->
        </div>
        <!-- End Card -->
        
        <div class="row my-3">
            <!-- Earnings (Monthly) Card Example -->
            <div class="for-card col-sm-4 col-6 mb-2">
                <div class="card for-card-body-2 shadow h-100  badge-primary ">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="font-weight-bold  text-uppercase for-card-text mb-1">
                                    {{__('total delivered orders')}}
                                </div>
                                <div
                                    class="for-card-count">{{$dm->orders->count()}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Collected Cash Card Example -->
            <div class="for-card col-sm-4 col-6 mb-2">
                <div class="card r shadow h-100 for-card-body-4  badge-dark">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div
                                    class=" for-card-text font-weight-bold  text-uppercase mb-1">{{__('Cash in hand')}}</div>
                                <div
                                    class="for-card-count">{{\App\CentralLogics\Helpers::format_currency($dm->wallet?$dm->wallet->collected_cash:0.0)}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Earning Card Example -->
            <div class="for-card col-sm-4 col-6 mb-2">
                <div class="card r shadow h-100 for-card-body-4  badge-info">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div
                                    class=" for-card-text font-weight-bold  text-uppercase mb-1">{{__('Total earning')}} </div>
                                <div
                                    class="for-card-count">{{\App\CentralLogics\Helpers::format_currency($dm->wallet?$dm->wallet->total_earning:0.00)}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Card -->
        <div class="card">
            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table id="datatable" class="table table-borderless table-thead-bordered table-nowrap card-table"
                       data-hs-datatables-options='{
                     "columnDefs": [{
                        "targets": [0, 3, 6],
                        "orderable": false
                      }],
                     "order": [],
                     "info": {
                       "totalQty": "#datatableWithPaginationInfoTotalQty"
                     },
                     "search": "#datatableSearch",
                     "entries": "#datatableEntries",
                     "pageLength": 25,
                     "isResponsive": false,
                     "isShowPaging": false,
                     "pagination": "datatablePagination"
                   }'>
                    <thead class="thead-light">
                    <tr>
                        <th>{{__('reviewer')}}</th>
                        <th>{{__('review')}}</th>
                        <th>{{__('attachment')}}</th>
                        <th>{{__('Date')}}</th>
                    </tr>
                    </thead>

                    <tbody>

                    @foreach($reviews as $review)
                        <tr>
                            <td>
                                <a class="d-flex align-items-center"
                                   href="{{route('admin.customer.view',[$review['user_id']])}}">
                                    <div class="avatar avatar-circle">
                                        <img class="avatar-img" width="75" height="75"
                                             onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                                             src="{{asset('storage/app/public/profile/'.$review->customer->image)}}"
                                             alt="Image Description">
                                    </div>
                                    <div class="ml-3">
                                    <span class="d-block h5 text-hover-primary mb-0">{{$review->customer['f_name']." ".$review->customer['l_name']}} <i
                                            class="tio-verified text-primary" data-toggle="tooltip" data-placement="top"
                                            title="{{__('Verified Customer')}}"></i></span>
                                        <span class="d-block font-size-sm text-body">{{$review->customer->email}}</span>
                                    </div>
                                </a>
                            </td>
                            <td>
                                <div class="text-wrap" style="width: 18rem;">
                                    <div class="d-flex mb-2">
                                        <label class="badge badge-soft-info">
                                            {{$review->rating}} <i class="tio-star"></i>
                                        </label>
                                    </div>

                                    <p>
                                        {{$review['comment']}}
                                    </p>
                                </div>
                            </td>
                            <td>
                                @foreach(json_decode($review['attachment'],true) as $attachment)
                                    <img width="100" onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'" src="{{asset('storage/app/public')}}/{{$attachment}}">
                                @endforeach
                            </td>
                            <td>
                                {{date('d M Y '.config('timeformat'),strtotime($review['created_at']))}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- End Table -->

            <!-- Footer -->
            <div class="card-footer">
                <!-- Pagination -->
                <div class="row justify-content-center justify-content-sm-between align-items-sm-center">
                    <div class="col-12">
                        {!! $reviews->links() !!}
                    </div>
                </div>
                <!-- End Pagination -->
            </div>
            <!-- End Footer -->
        </div>
        <!-- End Card -->
    </div>
@endsection

@push('script_2')
<script>
    function request_alert(url, message) {
        Swal.fire({
            title: '{{__('Are you sure')}}',
            text: message,
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: 'default',
            confirmButtonColor: '#FC6A57',
            cancelButtonText: '{{__('no')}}',
            confirmButtonText: '{{__('yes')}}',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                location.href = url;
            }
        })
    }
</script>
@endpush