@extends('layouts.vendor.app')

@section('title',__('Food Bulk Import'))

 
@section('content')
    <div class="content container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('vendor.dashboard')}}">{{trans('Dashboard')}}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page"><a
                        href="{{route('vendor.food.list')}}">{{__('foods')}}</a>
                </li>
                <li class="breadcrumb-item">{{__('Bulk import')}} </li>
            </ol>
        </nav>
        <h1 class="text-capitalize">{{__('Foods bulk import')}}</h1>
        <!-- Content Row -->
        <div class="row">
            <div class="col-12">
                <div class="jumbotron pt-1" style="background: white">
                    <h3>Instructions : </h3>
                    <p>1. Download the format file and fill it with proper data.</p>

                    <p>2. You can download the example file to understand how the data must be filled.</p>

                    <p>3. Once you have downloaded and filled the format file, upload it in the form below and
                        submit.</p>

                    <p> 4. After uploading foods you need to edit them and set image and variations.</p>

                    <p> 5. You can get category id from their list, please input the right ids.</p>

                    <p> 6. Don't forget to fill all the fields </p>
                    
                </div>
            </div>

            <div class="col-md-12">
                <form class="product-form" action="{{route('vendor.food.bulk-import')}}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="card mt-2 rest-part">
                        <div class="card-header">
                            <h4>{{__('Import Food`s File')}}</h4>
                            <a href="{{asset('public/assets/restaurant_panel/foods_bulk_format.xlsx')}}" download=""
                               class="btn btn-secondary">{{__('Download Format')}}</a>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="file" name="products_file">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-footer">
                        <div class="row">
                            <div class="col-md-12" style="padding-top: 20px">
                                <button type="submit" class="btn btn-primary">{{__('Save')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')

@endpush
