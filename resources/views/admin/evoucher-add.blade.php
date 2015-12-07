    <!-- -->
    @extends('admin.master')
     
    @section('main')
        <div class="col-md-8 col-md-offset-2 form-content">
            <h3 class="heading">
                Create E-vouchers
            </h3>
            
          
            @foreach($errors->all() as $error)
                <p class="alert alert-danger">{!!$error!!}</p>
            @endforeach

            {!!Form::open(['url'=>'admin/evoucher/addevoucher','class'=>'form form-horizontal','style'=>'margin-top:50px', 'files'=> true])!!}

            <div class="form-group">
                {!! Form::label('no of vouchers','No of Vouchers:',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::text('no_of_vouchers', Input::old('no_of_vouchers'), ['class'=>'form-control'] ) !!}

                </div>
            </div>

            <div class="form-group">
                {!! Form::label('price','Price:',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                        {!! Form::text('price', Input::old('price'), ['class'=>'form-control'] ) !!}
                </div>
            </div>

            <div class="text-center">
                {!!Form::submit('Submit',['class'=>'btn btn-default'])!!}
            </div>
            {!!Form::close()!!}
        </div>
     
    @stop
