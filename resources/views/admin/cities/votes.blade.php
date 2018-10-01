@extends('admin.layouts.master')
@section('title','التصويت على المدن')

@section('content')

    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            
            <h4 class="page-title">التصويت على المدن</h4>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <div class="dropdown pull-right">
                    <!-- <a href="{{ route('cities.create') }}" class="btn btn-custom waves-effect waves-light">إضافة مدينة<span class="m-l-5">
                        <i class="fa fa-plus"></i></span>
                    </a> -->

                    <button type="button" class="btn btn-custom  waves-effect waves-light" style="float: left; margin-right: 15px;" onclick="window.history.back();return false;"> رجوع <span class="m-l-5"><i
                                    class="fa fa-reply"></i></span>
                    </button>
                </div>



                <h4 class="header-title m-t-0 m-b-30">مشاهدة التصويت على المدن</h4>

                <table id="datatable-fixed-header" class="table table-striped table-hover table-condensed"
                       style="width:100%">
                    <thead>
                    <tr>
                        <th> م </th>
                        <th>اسم المدينة</th>
                        <th>عدد التصويتات</th>
                    </tr>
                    </thead>
                    <tbody>

                        @php $i = 1 ; @endphp 
                    @foreach($cities as $row)
                        <tr>

                            <td> {{$i++}} </td>
                            <td> {{ $row->city_name }} </td>
                            <td> {{ $row->vote_count }} </td>
                            
                        </tr>
                    @endforeach
                    </tbody>
                </table>


            </div>
        </div><!-- end col -->

    </div>
    <!-- end row -->
@endsection



