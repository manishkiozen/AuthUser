    <!-- -->
    @extends('admin.master')
     
    @section('main')
        <div class="col-md-8 col-md-offset-2 form-content">
            <h3 class="heading">
                @if( $update )
                    Update Catlog
                @else
                    Add Catlog
                @endif
            </h3>
            
          
            @foreach($errors->all() as $error)
                <p class="alert alert-danger">{!!$error!!}</p>
            @endforeach

            @if( $update )
                {!!Form::open(['url'=>'admin/catlog/updateCatlog/'.$record[0]->id.Session::get('secure_id'),'class'=>'form form-horizontal','style'=>'margin-top:50px', 'files'=> true])!!}
                <input type="hidden" name="_old_img" value="{{{ $record[0]->icon_file }}}" >
            @else
                {!!Form::open(['url'=>'admin/catlog/addcatlog','class'=>'form form-horizontal','style'=>'margin-top:50px', 'files'=> true])!!}
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
                {!! Form::label('icon_file','Icon:',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::file('icon_file',Input::old('icon_file'), ['class'=>'form-control']) !!}
                </div>

                @if( $update )
                    @if( $record[0]->icon_file )
                    <div class="col-sm-8" style="margin-top:20px;">
                        <img src="{{{ Config::get('app.url').$record[0]->icon_file }}}" height="50" width="50" />
                    </div>
                    @endif
                @endif

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