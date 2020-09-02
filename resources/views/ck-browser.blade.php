<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
	<meta name="csrf-token" content = "{{ csrf_token() }}">
     <title>Files & Images</title>

	<script type="text/javascript" src="{{asset('assets/js/jquery-3.2.1.min.js')}}"></script>
	<script src="{{ asset('/assets/js/app.js') }}"></script>

	<!-- Styles -->
    <link href="{{ asset('/assets/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/css/waku.css') }}" rel="stylesheet"> 

    <script>
        // Helper function to get parameters from the query string.
        function getUrlParam( paramName ) {
            var reParam = new RegExp( '(?:[\?&]|&)' + paramName + '=([^&]+)', 'i' );
            var match = window.location.search.match( reParam );

            return ( match && match.length > 1 ) ? match[1] : null;
        }
        // Simulate user action of selecting a file to be returned to CKEditor.
        function returnFileUrl( url ) {
            var funcNum = getUrlParam( 'CKEditorFuncNum' );
            var fileUrl = url;
            window.opener.CKEDITOR.tools.callFunction( funcNum, fileUrl );
            window.close();
        }
    </script>
</head>
<body>

	<div class="col-md-12 row">
	@foreach($data as $row)
		<div class="col-lg-3 text-center">
			<div class="ck-border mt-5">
				<img class="ck-browser" onclick="returnFileUrl('{{url('/public/ckeditor/'.$folder.'')}}/{{$row}}')" src="{{url('/public/ckeditor/'.$folder.'')}}/{{$row}}" />
			</div>
			<div class="mt-2"><button class="del btn btn-danger btn-sm" data="{{$row}}">Delete</button></div>
		</div>
	@endforeach
	</div>
   <!--<button onclick="returnFileUrl()">Select File</button>-->
   
   <script type="text/javascript">
		$(document).ready(function(){
			 deleteImage();
		});
   
		function deleteImage()
		{
			$(".del").click(function(e){
				var conf = confirm('Are you sure want to delete this file?');
				e.preventDefault();
				var filename = $(this).attr('data');

				if(conf == true){
					$.ajax({
						header : $("meta[name='csrf-token']").attr('content'),
						type : 'GET',
						url : "{{route('ckdelete')}}",
						data : {'filename' : filename},
						dataType : 'json',
						success : function(response){
							alert(response.msg);
							location.reload(true);
						}
					});/*end ajax*/
				} else {
					return false;
				}
			});
		}
   </script>
   
</body>
</html>