@extends('admin.layouts.master')

@section('content')

    <!-- Page-Title -->

    <div class="row">
        <div class="col-xs-8">
            <div class="bg-picture card-box">
                <div class="row">
                    <div class="col-xs-6 col-md-4 col-sm-4">
                        <h3 class="page-title">بيانات المنتج</h3>
                    </div>
            
                    <div class="m-t-15 col-xs-6 col-md-8 col-sm-8 text-right">
                        <button type="button" class="btn btn-custom  waves-effect waves-light" onclick="window.history.back();return false;"> رجوع <span class="m-l-5"><i class="fa fa-reply"></i></span>
                        </button>
                    </div>
                   
                </div>
                <div class="profile-info-name">
                    <div class="profile-info-detail">

                        <div class="panel-body">

                            <div class="col-lg-6 col-xs-12">
                                <label>اسم المنتج :</label>
                                <p>{{ $product->name }}</p>
                            </div>

                            <div class="col-lg-6 col-xs-12">
                                <label>حالة المنتج :</label>
                                <p>{{ $product->is_available == 1 ? 'متاح' : 'غير متاح' }}</p>
                            </div>
                            
                            <div class="col-lg-12 col-xs-12">
                                <label> وصف المنتج :</label>
                                <p>{{ $product->description }}</p>
                            </div>

                            <div class="col-lg-6 col-xs-12">
                                <label>القسم الرئيسى :</label>
                                <p>{{ category($product->category_id) }}</p>
                            </div>

                            <div class="col-lg-6 col-xs-12">
                                <label>القسم الفرعى :</label>
                                <p>{{ category($product->subcategory_id) }}</p>
                            </div>
                            
                            

                            <div class="col-lg-6 col-xs-12">
                                <label>سعر المنتج :</label>
                                <p>{{ $product->price }}</p>
                            </div>
                            

                            <div class="col-lg-6 col-xs-12">
                                <label> وحدة القياس للسعر :</label>
                                @php $unit = \App\MeasurementUnit::find($product->measurement_id) @endphp
                                <p>{{ $unit->name }}</p>
                            </div>

                        </div>
                    </div>
                    <!-- end card-box-->


                </div>
            </div>
        </div>

        <div class="col-lg-4">
                <div class="card-box" style="overflow: hidden;">
                    <h4 class="header-title m-t-0 m-b-30">صورة المنتج</h4>
                    <div class="form-group">
                        <div class="col-sm-12">
                            
                            
                            <input type="file" readonly name="image" class="dropify" data-max-file-size="6M" data-default-file="{{ request()->root() . '/files/products/'. $product->image }}" data-show-remove="false"
                                   data-allowed-file-extensions="png gif jpg jpeg"
                                   data-errors-position="outside"
                            />

                        </div>
                    </div>

                </div>
              
            </div><!-- end col -->

    </div>


@endsection
