    <!-- -->
    @extends('admin.master')
     
    @section('main')
       
        <div class="col-md-8 col-md-offset-2 form-content">
            <h3 class="heading">Package List</h3>
            
            @if (Session::has('flush_message'))
                <div class="alert alert-info">{{ Session::get('flush_message') }}</div>
            @endif
            <div class="col-md-8 pull-right form-content">
            	<a href="{{{ Config::get('app.url') }}}admin/package/addnew">
            	<div class="btn btn-default">Add New</div>
            	</a>
           	</div>

            <table id="example" class="display" cellspacing="0" width="100%">
		        <thead>
		            <tr>
		                <th>Name</th>
		                <th>Price</th>
		                <th>Create date</th>
		                <th>Edit</th>
		                <th>Delete</th>
		            </tr>
		        </thead>		       
		    </table>
		    
        </div>

    {!!HTML::style('https://cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css')!!}     
    {!!HTML::script('//code.jquery.com/jquery-1.11.3.min.js')!!} 
    {!!HTML::script('https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js')!!} 

    @if( $totalRecords > 0 )
    <script type="text/javascript">
    	$(document).ready(function() {

		    $('#example').DataTable( {
		        "processing": true,
		        "serverSide": true,
                "order": [[ 0, "desc" ]],
                "aoColumnDefs" : [ {
                    'bSortable' : false,
                    'aTargets' : [ 3, 4 ]
                } ],
		        "ajax": "{{{ Config::get('app.url')}}}admin/package/getpackagelist"
		    } );

            $(document).on('click', '.package-delete', function(){
                if( confirm('Are you sure delete package') ){
                    var paramFirst = $(this).data('id');
                    var paramSecond = $(this).data('token');
                    window.location.href = '{{{ Config::get('app.url')}}}admin/package/delete/'+paramFirst+'/'+paramSecond;
                }
            });
		} );
    </script>
    @endif

    @stop