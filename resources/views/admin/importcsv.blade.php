@extends('layouts.admin')

@section('content')

<div class="modal-content col-md-6" style="margin-left : auto; margin-right : auto">
  <div class="modal-header">
      <h4>Import CSV Customer</h4>
  </div>

  <div class="alert alert-danger">PLEASE BE CAREFUL IF YOU PERFORM IMPORT USING THIS FUNCTION, IT WOULD RETURN ALL DATA SUBSCRIBERS TO LIST_ID = 1 </div>

  <div class="modal-body">
      <div class="form-group">
          <form id="importform">
               <div class="form-group row">
                  <label for="name" class="col-md-4 col-form-label text-md-right">Import</label>
                  <div class="col-md-6">
                      <input type="file" class="form-control" name="csv_file" />
                  </div>
              </div>
              <div class="form-group row mt-2">
                  <label for="name" class="col-md-4 col-form-label text-md-right"></label>
                  <div class="col-md-6"><button type="submit" class="btn btn-warning">Import</button>
                  </div>
              </div>
          </form>
      </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){
      csvImport();
  });
  
 function csvImport()
      {
        $("#importform").on('submit',function(e){
            e.preventDefault();
            var data = new FormData($(this)[0]);
            $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
            });
            $.ajax({
                type : 'POST',
                url : "{{route('importcustomercsv')}}",
                data : data,
                contentType: false,
                processData: false,
                success : function(result){
                  alert('File has bee imported!');
                }
            });/* end ajax */
        });
      }
</script>
@endsection