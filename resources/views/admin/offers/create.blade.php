@extends('admin.layouts.master')

@section('content')
    <form method="POST" action="{{ route('offers.store') }}" enctype="multipart/form-data" data-parsley-validate
          novalidate>
    {{ csrf_field() }}

    <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                
                <h4 class="page-title">إضافة عرض</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card-box">
                   
                    <h4 class="header-title m-t-0 m-b-30">بيانات العرض</h4>

                    @php 
                    if(old('category_id') != null){
                        $old_cats = \App\Category::where('parent_id',old('category_id'))->get();
                    }else{
                        $old_cats = null;
                    }

                    if(old('subcategory_id') != null){
                        $old_products = \App\Product::where('subcategory_id',old('subcategory_id'))->get();
                    }else{
                        $old_products = null;
                    }
                    @endphp
                    
                    <div class="form-group{{ $errors->has('category_id') ? ' has-error' : '' }}">
                        <label for="cat">القسم الرئيسى*</label>
                        <select class="form-control" name="category_id" id="cat" required data-parsley-required-message="هذا الحقل الزامى" >
                            <option value="" selected disabled>برجاء اختيار القسم الرئيسى</option>
                            @if(count($cates) > 0)
                                @foreach($cates as $cat)
                                    <option value="{{$cat->id}}" id="subcate{{$cat->id}}" data-subcats=
                                    "{{$cat->subcats}}" {{$cat->id == old('category_id') ? 'selected':''}}>{{$cat->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        
                        @if($errors->has('category_id'))
                            <p class="help-block">{{ $errors->first('category_id') }}</p>
                        @endif
                        
                    </div>
                    
                    
                    <div class="form-group{{ $errors->has('subcategory_id') ? ' has-error' : '' }}">
                        <label for="sub_cat">القسم الفرعى *</label>
                        <select class="form-control" name="subcategory_id" id="sub_cat" required data-parsley-required-message="هذا الحقل الزامى" >
                            @if($old_cats)
                            @forelse($old_cats as $old_cat)
                                <option value="{{$old_cat->id}}" {{$old_cat->id == old('subcategory_id') ? 'selected' : ''}}> {{$old_cat->name}}</option>
                            @empty
                                <option value="" selected disabled>لا يوجد </option>
                            @endforelse
                            @else
                                <option value="" selected disabled>القسم الفرعى</option>
                            @endif
                        </select>
                        
                        @if($errors->has('subcategory_id'))
                            <p class="help-block">{{ $errors->first('subcategory_id') }}</p>
                        @endif
                        
                    </div>

                    <div class="form-group{{ $errors->has('product_id') ? ' has-error' : '' }}">
                        <label for="product">المنتج *</label>
                        <select class="form-control" name="product_id" id="product" required data-parsley-required-message="هذا الحقل الزامى" >
                            @if($old_products)
                            @forelse($old_products as $old_product)
                                <option value="{{$old_product->id}}" {{$old_product->id == old('product_id') ? 'selected' : ''}}> {{$old_product->name}}</option>
                            @empty
                                <option value="" selected disabled>لا يوجد </option>
                            @endforelse
                            @else
                                <option value="" selected disabled>المنتج</option>
                            @endif
                        </select>
                        
                        @if($errors->has('product_id'))
                            <p class="help-block">{{ $errors->first('product_id') }}</p>
                        @endif
                        
                    </div>

                    <!-- <div class="form-group">
                        <label for="userName"> عنوان العرض*</label>
                        <input class="form-control" type="text" name="name" value="{{ old('name') }}" placeholder="عنوان العرض" data-parsley-required-message="هذا الحقل الزامى" data-parsley-maxlength="15" data-parsley-maxlength-message="يجب الا يزيد الحقل عن 15 حرف">
                        <p class="help-block" id="error_userName"></p>
                        @if($errors->has('name'))
                            <p class="help-block">
                                {{ $errors->first('name') }}
                            </p>
                        @endif
                    </div>
                    
                    <div class="form-group">
                        <label for="userName"> وصف العرض*</label>
                        <textarea class="form-control description" name="description" value="{{ old('description') }}" placeholder="الوصف" data-parsley-required-message="هذا الحقل الزامى" data-parsley-maxlength="1000" data-parsley-maxlength-message="يجب الا يزيد الحقل عن 1000 حرف">{{ old('description') }}</textarea>
                        <p class="help-block" id="error_userName"></p>
                        @if($errors->has('description'))
                            <p class="help-block">
                                {{ $errors->first('description') }}
                            </p>
                        @endif
                    </div> -->

                    <div class="form-group">
                        <label for="userName"> سعر المنتج فى العرض*</label>
                        <input class="form-control" type="text" name="price" value="{{ old('price') }}" placeholder="سعر المنتج" data-parsley-required-message="هذا الحقل الزامى" data-parsley-maxlength="10" data-parsley-maxlength-message="يجب الا يزيد الحقل عن 10 حرف" min="1" data-parsley-min-message="اقل سعر مسموح به هو 1">
                        <p class="help-block" id="error_userName"></p>
                        @if($errors->has('price'))
                            <p class="help-block">
                                {{ $errors->first('price') }}
                            </p>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="userName"> عدد الوحدات*</label>
                        <input class="form-control" type="text" name="amount" value="{{ old('amount') }}" placeholder="عدد الوحدات" data-parsley-required-message="هذا الحقل الزامى" data-parsley-maxlength="10" data-parsley-maxlength-message="يجب الا يزيد الحقل عن 10 حرف" min="1" data-parsley-min-message="اقل عدد مسموح به هو 1">
                        <p class="help-block" id="error_userName"></p>
                        @if($errors->has('amount'))
                            <p class="help-block">
                                {{ $errors->first('amount') }}
                            </p>
                        @endif
                    </div>

                    <!-- <div class="form-group{{ $errors->has('measurement_id') ? ' has-error' : '' }}">
                        <label for="pass1">وحدة القياس *</label>
                        <select class="form-control" name="measurement_id" required data-parsley-required-message="هذا الحقل الزامى" >
                            <option value="" selected disabled>وحدة القياس ..</option>
                            @if(count($measurements) > 0)
                                @foreach($measurements as $measurement)
                                    <option value="{{$measurement->id}}" {{$measurement->id == old('measurement_id') ? 'selected' : ''}}>{{$measurement->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        
                        @if($errors->has('measurement_id'))
                            <p class="help-block">{{ $errors->first('measurement_id') }}</p>
                        @endif
                    </div> -->

                    <div class="form-group">
                        <label for="pass1"> حالة العرض*</label>
                        <select class="form-control" name="is_available">
                            <option value="1" selected>متاح</option>
                            <option value="0">غير متاح</option>
                        </select>
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

            <!-- <div class="col-lg-4">
                <div class="card-box" style="overflow: hidden;">
                    <h4 class="header-title m-t-0 m-b-30">الصورة </h4>
                    <div class="form-group">
                        <div class="col-sm-12">
                                    
                            <input type="hidden" name="oldImage"/>
                            <input type="file" name="image" class="dropify" data-max-file-size="6M"  data-show-remove="false"
                                   data-allowed-file-extensions="png gif jpg jpeg"
                                   data-errors-position="outside"/>
                        </div>
                    </div>

                </div>
              
            </div> --><!-- end col -->
        </div>
        <!-- end row -->
    </form>
@endsection
@section('scripts')
    <script>
        $("#cat").change(function(){
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
                var a = val.products;
                console.log('a:',a);
                $("#sub_cat").append('<option value="' + val.id + '" id="prod'+val.id+'" data-products='+a+'>' + val.name + '</option>');
            });
        });

        //$('body').on('change', '#sub_cat', function () {
        $("#sub_cat").change(function(){
            $("#product").html('');
            var id = $(this).val();
            var data1 = $("#prod"+id).data("products");
            console.log(id);
            console.log('test test test');
            console.log(data1);
            // var json = JSON.parse(data);
            // console.log('dd',json);
            $("#product").append('<option value="">برجاء الاختيار</option');
            $.each(data1, function(key, val) {
                $("#product").append('<option value="' + val.id + '">' + val.name + '</option>');
            });
        });
      
    </script>
@endsection