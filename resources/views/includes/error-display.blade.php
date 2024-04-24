<div class="error-content-wrap" style="margin-left: 250px;">
  <div class="content">
    {{-- show error mssage ajax--}}
    <div class="alert alert-danger container1 mt-4" role="alert" id="error-container" style="display:none;">
    </div>
    {{-- show success mssage ajax--}}
    <div class="alert alert-success container1 mt-4" role="alert" id="success-container" style="display:none;">
    </div>
    {{--  server side success display--}}
    @if (session()->has('success'))
      <div class="alert alert-success">
          @if(is_array(session('success')))
              <ul>
                  @foreach (session('success') as $message)
                      <li>{{ $message }}</li>
                  @endforeach
              </ul>
          @else
              {{ session('success') }}
          @endif
      </div>
    @endif
    {{--  server side error display--}}
    @if (session()->has('error'))
      <div class="alert alert-danger">
          @if(is_array(session('error')))
              <ul>
                  @foreach (session('error') as $message)
                      <li>{{ $message }}</li>
                  @endforeach
                  {{--  @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                  @endforeach  --}}
              </ul>
          @else
              {{ session('error') }}
          @endif
      </div>
    @endif

    {{--  server side error display--}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
  </div>
</div>
