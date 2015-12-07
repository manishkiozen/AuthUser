    <!-- -->
    @extends('admin.master')
     
    @section('main')
        <div class="col-md-8 col-md-offset-2 form-content">
            <h3 class="heading">
                @if( $update )
                    Update Package
                @else
                    Add Package
                @endif
            </h3>
            
          
            @foreach($errors->all() as $error)
                <p class="alert alert-danger">{!!$error!!}</p>
            @endforeach

            @if( $update )
                {!!Form::open(['url'=>'admin/package/updatePackage/'.$record[0]->id.Session::get('secure_id'),'class'=>'form form-horizontal','style'=>'margin-top:50px', 'files'=> true])!!}
            @else
                {!!Form::open(['url'=>'admin/package/addpackage','class'=>'form form-horizontal','style'=>'margin-top:50px', 'files'=> true])!!}
            @endif    

            <div class="form-group">
                {!! Form::label('name','Name:',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                    
                    @if( $update )    
                        {!! Form::text('name', $record[0]->name , ['class'=>'form-control'] ) !!}
                    @else
                        {!! Form::text('name', Input::old('name'), ['class'=>'form-control'] ) !!}
                    @endif

                </div>
            </div>

            <div class="form-group">
                {!! Form::label('price','Price:',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                    
                    @if( $update )    
                        {!! Form::text('price', $record[0]->price , ['class'=>'form-control'] ) !!}
                    @else
                        {!! Form::text('price', Input::old('price'), ['class'=>'form-control'] ) !!}
                    @endif

                </div>
            </div>

            <div class="form-group">
                {!! Form::label('Has Product','&nbsp;',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                    
                    @if( $update )  
                        @if ( $record[0]->has_product == '1')
                            {!! Form::checkbox('has_product', '1', true) !!}  
                        @else
                            {!! Form::checkbox('has_product', '1', false) !!}  
                        @endif
                    @else
                        {!! Form::checkbox('has_product', '1', false) !!}
                    @endif
                    Has Product (Optional)
                </div>
            </div>
            

            <div class="text-center">
                @if( $update )
                    {!!Form::submit('Update',['class'=>'btn btn-default'])!!}
                @else
                    {!!Form::submit('Submit',['class'=>'btn btn-default'])!!}
                @endif    
            </div>
            {!!Form::close()!!}
        </div>
     
    @stop
