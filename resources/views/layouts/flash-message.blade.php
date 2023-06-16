<script>
window.addEventListener('load', function() {

@if ($errors->any())
@foreach ($errors->all() as $error)
$.notify({
      icon: "add_alert",
      message: "{{$error}}"

  },{
      type: "danger",
      timer: 4000,
      placement: {
          from: 'top',
          align: 'center'
      }
  });
@endforeach
@endif

@if ($message = Session::get('success'))
$.notify({
      icon: "add_alert",
      message: "{{$message}}"

  },{
      type: "success",
      timer: 4000,
      placement: {
          from: 'top',
          align: 'center'
      }
  });
@endif
@if ($message = Session::get('error'))
$.notify({
      icon: "add_alert",
      message: "{{$message}}"

  },{
      type: "danger",
      timer: 4000,
      placement: {
          from: 'top',
          align: 'center'
      }
  });
@endif

@if ($message = Session::get('fail'))
$.notify({
      icon: "add_alert",
      message: "{{$message}}"

  },{
      type: "danger",
      timer: 4000,
      placement: {
          from: 'top',
          align: 'center'
      }
  });
@endif

@if ($message = Session::get('warning'))
$.notify({
      icon: "add_alert",
      message: "{{$message}}"

  },{
      type: "warning",
      timer: 4000,
      placement: {
          from: 'top',
          align: 'center'
      }
  });
@endif

@if ($message = Session::get('info'))
$.notify({
      icon: "add_alert",
      message: "{{$message}}"

  },{
      type: "info",
      timer: 4000,
      placement: {
          from: 'top',
          align: 'center'
      }
  });
@endif

@if ($message = Session::get('status'))
$.notify({
      icon: "add_alert",
      message: "{{$message}}"

  },{
      type: "success",
      timer: 4000,
      placement: {
          from: 'top',
          align: 'center'
      }
  });
@endif

});
</script>