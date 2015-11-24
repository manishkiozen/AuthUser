    <!-- -->
    @extends('admin.master')
     
    @section('main')
       
        <div class="col-md-8 col-md-offset-2 form-content">
            <h3 class="heading">Catlog List</h3>
            
            <table id="example" class="display" cellspacing="0" width="100%">
		        <thead>
		            <tr>
		                <th>Name</th>
		                <th>Icon</th>
		                <th>Create date</th>
		            </tr>
		        </thead>		       
		    </table>
		    
        </div>

    {!!HTML::style('https://cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css')!!}     
    {!!HTML::script('//code.jquery.com/jquery-1.11.3.min.js')!!} 
    {!!HTML::script('https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js')!!} 

    <script type="text/javascript">
    	$(document).ready(function() {
		    $('#example').DataTable( {
		        "processing": true,
		        "serverSide": true,
		        "ajax": "getcatloglist"
		    } );
		} );
    </script>
    @stop