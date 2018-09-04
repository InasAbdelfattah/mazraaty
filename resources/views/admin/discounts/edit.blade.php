@extends('admin.layouts.master')

@section('content')
    <form method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data" data-parsley-validate
          novalidate>
    {{ csrf_field() }}
    {{ method_field('PUT') }}

    <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <button type="button" class="btn btn-custom  waves-effect waves-light" style="float: left; margin-right: 15px;" onclick="window.history.back();return false;"> رجوع <span class="m-l-5"><i
                                    class="fa fa-reply"></i></span>
                    </button>
                <h4 class="page-title">تعديل منتج</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card-box">
                   
                    <h4 class="header-title m-t-0 m-b-30">بيانات المنتج</h4>


                    <div class="col-xs-6">
                        <div class="form-group{{ $errors->has('category_id') ? ' has-error' : '' }}">
                            <label for="pass1">القسم الرئيسى*</label>
                            
                            <select class="form-control" id="cat" name="category_id" required data-parsley-required-message="هذا الحقل الزامى" >
                                <option value="" selected disabled>برجاء اختيار القسم الرئيسى</option>
                                @if(count($cates) > 0)
                                    @foreach($cates as $cat)
                                       
                                        <option value="{{$cat->id}}" id="subcate{{$cat->id}}" data-subcats=
                                    "{{$cat->subcats}}" {{$product->category_id == $cat->id ? 'selected' : ''}}>{{$cat->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            
                            @if($errors->has('category_id'))
                                <p class="help-block">{{ $errors->first('category_id') }}</p>
                            @endif
                            
                        </div>
                    </div>
                    
                    <div class="col-xs-6">
                        <div class="form-group{{ $errors->has('subcategory_id') ? ' has-error' : '' }}">
                        <label for="pass1">القسم الفرعى *</label>
                        <select class="form-control" name="subcategory_id" id="sub_cat" required data-parsley-required-message="هذا الحقل الزامى" >
                          
                        @if(category($product->subcategory_id))  
                            <option value="{{$product->subcategory_id}}">{{ category($product->subcategory_id) }}</option>
                        @endif
                            
                        </select>
                        
                        @if($errors->has('subcategory_id'))
                            <p class="help-block">{{ $errors->first('subcategory_id') }}</p>
                        @endif
                        
                    </div>
                    </div>

                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="userName"> اسم المنتج*</label>
                            <input class="form-control" type="text" name="name" value="{{ $product->name }}" placeholder="" data-parsley-required-message="هذا الحقل الزامى" data-parsley-maxlength="50" data-parsley-maxlength-message="يجب الا يزيد الحقل عن 50 حرف">
                            <p class="help-block" id="error_userName"></p>
                            @if($errors->has('name'))
                                <p class="help-block">
                                    {{ $errors->first('name') }}
                                </p>
                            @endif
                        </div>

                    </div>

                    <div class="col-xs-6">
                        <div class="form-group{{ $errors->has('is_available') ? ' has-error' : '' }}">
                            <label for="pass1"> حالة المنتج*</label>
                            <select class="form-control" name="is_available">
                                <option value="1" {{$product->is_available == 1 ? 'selected' : ''}}>متاح</option>
                                <option value="0" {{$product->is_available == 0 ? 'selected' : ''}}>غير متاح</option>
                            </select>
                        </div> 
                    </div>

                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="userName"> وصف المنتج*</label>
                            <textarea class="form-control description" name="description" value="{{ $product->description }}" placeholder="الوصف" data-parsley-required-message="هذا الحقل الزامى" data-parsley-maxlength="1000" data-parsley-maxlength-message="يجب الا يزيد الحقل عن 1000 حرف">{{ $product->description }}</textarea>
                            <p class="help-block" id="error_userName"></p>
                            @if($errors->has('description'))
                                <p class="help-block">
                                    {{ $errors->first('description') }}
                                </p>
                            @endif
                        </div>
                    </div>


                    <div class="col-xs-6">
                        <div class="form-group{{ $errors->has('measurement_id') ? ' has-error' : '' }}">
                            <label for="userName"> سعر المنتج*</label>
                            <input class="form-control" type="text" name="price" value="{{ $product->price }}" placeholder="سعر المنتج" data-parsley-required-message="هذا الحقل الزامى" data-parsley-maxlength="10" data-parsley-maxlength-message="يجب الا يزيد الحقل عن 10 حرف" min="1" data-parsley-min-message="اقل سعر مسموح به هو 1">
                            <p class="help-block" id="error_userName"></p>
                            @if($errors->has('price'))
                                <p class="help-block">
                                    {{ $errors->first('price') }}
                                </p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-xs-6">
                        <div class="form-group{{ $errors->has('measurement_id') ? ' has-error' : '' }}">
                            <label for="pass1">وحدة القياس *</label>
                            <select class="form-control" name="measurement_id" required data-parsley-required-message="هذا الحقل الزامى" >
                                <option value="" selected disabled>وحدة القياس ..</option>
                                @if(count($measurements) > 0)
                                @foreach($measurements as $measurement)
                                        <option value="{{$measurement->id}}" {{ $measurement->id == $product->measurement_id ? 'selected' : ''}} >{{$measurement->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            
                            @if($errors->has('measurement_id'))
                                <p class="help-block">{{ $errors->first('measurement_id') }}</p>
                            @endif
                        </div>
                    </div>
                    

                    
                    <div class="form-group text-right m-t-20">
                        <button class="btn btn-primary waves-effect waves-light m-t-20" type="submit">
                            حفظ البيانات
                        </button>
                        <button onclick="window.history.back();return false;" type="reset"
                                class="btn btn-default waves-effect waves-light m-l-5 m-t-20">
                            إلغاء
                        </button>
                    </div>

                </div>
            </div><!-- end col -->

            <div class="col-lg-4">
                <div class="card-box" style="overflow: hidden;">
                    <h4 class="header-title m-t-0 m-b-30">الصورة </h4>
                    <div class="form-group">
                        <div class="col-sm-12">
                            
                            
                            <input type="hidden" value="{{ $product->image }}" name="oldImage"/>
                            <input type="file" name="image" class="dropify" data-max-file-size="6M"
                                   data-default-file="{{ request()->root() . '/files/products/'. $product->image }}" data-show-remove="false"
                                   data-allowed-file-extensions="png gif jpg jpeg"
                                   data-errors-position="outside"
                            />

                        </div>
                    </div>

                </div>
              
            </div><!-- end col -->
        </div>
        <!-- end row -->
    </form>
@endsection

@section('scripts')
    <script>
        $("#cat").change(function(){
console.log('cat');
            $("#sub_cat").html('');

            var id = $(this).val();
            var data = $("#subcate"+id).data("subcats");
            console.log(id);
            console.log('subcat',data);
            var subCats = $(this).data("subcats");
            
            var sub_cats = $(this).attr("data-subcats");
            
            console.log(sub_cats);
            //var json = JSON.parse(sub_cats)
            $("#sub_cat").append('<option value="">برجاء الاختيار</option');
            $.each(data, function(key, val) {

                $("#sub_cat").append('<option value="' + val.id + '">' + val.name + '</option>');

            });

        });
        
    </script>
@endsection