@extends('admin.layouts.master')
@section('title','تعديل السؤال')
@section('content')

    <div id="messageError"></div>
    <form data-parsley-validate novalidate method="POST" action="{{ route('faqs.update', $faq->id) }}"
          enctype="multipart/form-data">
    {{ csrf_field() }}
    {{ method_field('PUT') }}

    <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="btn-group pull-right m-t-15">
                    <a href="{{ route('faqs.index') }}" type="button" class="btn btn-custom waves-effect waves-light"
                       aria-expanded="false"> مشاهدة الأسئلة المتكررة
                        <span class="m-l-5">
                        <i class="fa fa-backward"></i>
                    </span>
                    </a>

                </div>
                <h4 class="page-title">الأسئلة المتكررة</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card-box">
                    <h4 class="header-title m-t-0 m-b-30"> تعديل السؤال : {{ $faq->question }} </h4>

                    <div class="form-group{{ $errors->has('question') ? ' has-error' : '' }}">
                        <label for="userName">السؤال*</label>
                        <input type="text" name="question" parsley-trigger="change" required
                               placeholder=" ادخل اسم المدينة..." class="form-control title"
                               value="{{ $faq->question or old('question') }}"
                               id="userName">

                        @if ($errors->has('question'))
                            <span class="help-block">
                                <strong>{{ $errors->first('question') }}</strong>
                           </span>
                        @endif

                    </div>

                    <div class="form-group{{ $errors->has('answer') ? ' has-error' : '' }}">
                        <label for="userName"> الجواب*</label>
                        <textarea class="form-control description" name="answer" value="{{ $faq->answer }}" placeholder="اجواب" data-parsley-required-message="هذا الحقل الزامى" data-parsley-maxlength="1000" data-parsley-maxlength-message="يجب الا يزيد الحقل عن 1000 حرف">{!! $faq->answer !!}</textarea>
                        <p class="help-block" id="error_userName"></p>
                        @if($errors->has('answer'))
                            <p class="help-block">
                                {{ $errors->first('answer') }}
                            </p>
                        @endif
                    </div>

                    <div class="form-group text-right m-b-0">
                        <button class="btn btn-primary waves-effect waves-light" type="submit"> حفظ البيانات
                        </button>
                        <button onclick="window.history.back();return false;"
                                class="btn btn-default waves-effect waves-light m-l-5"> إلغاء
                        </button>
                    </div>

                </div>
            </div><!-- end col -->

        </div>
        <!-- end row -->
    </form>


@endsection

@section('scripts')
    <script>
        //CKEDITOR.replace( 'answer' );
    </script>
@endsection
