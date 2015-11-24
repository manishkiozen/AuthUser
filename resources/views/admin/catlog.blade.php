    <!-- -->
    @extends('admin.master')
     
    @section('main')
        <div class="col-md-8 col-md-offset-2 form-content">
            <h3 class="heading">Add Catlog</h3>
            @if (Session::has('flush_message'))
                <div class="alert alert-info">{{ Session::get('flush_message') }}</div>
            @endif

            @foreach($errors->all() as $error)
                <p class="alert alert-danger">{!!$error!!}</p>
            @endforeach

            {!!Form::open(['url'=>'admin/catlog/addcatlog','class'=>'form form-horizontal','style'=>'margin-top:50px', 'files'=> true])!!}
            <div class="form-group">
                {!! Form::label('name','Name:',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::text('name',Input::old('name'),['class'=>'form-control']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('icon_file','Icon:',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::file('icon_file',Input::old('icon_file'),['class'=>'form-control']) !!}
                </div>
            </div>
            <div class="text-center">
                {!!Form::submit('Submit',['class'=>'btn btn-default'])!!}
            </div>
            {!!Form::close()!!}
        </div>
     
    @stop