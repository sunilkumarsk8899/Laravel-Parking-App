@extends('layouts.app')
<style>
    .blur_exist{
        filter: blur(4px);
    }
    .hidden{
        display: none;
    }
    .visibility{
        visibility: hidden;
    }
    .select2-container {
        box-sizing: border-box;
        display: inline-block;
        margin: 0;
        position: relative;
        vertical-align: middle;
        width: 400px !important;
    }

    .d-flex{
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@section('content')
<div class="container">

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('Super Admin You are logged in!') }}
                </div>
            </div>
        </div>

        <div class="col-md-4 mt-5 text-center">
            <h2>Generate Parking In Exit Slip</h2>
            <form class="form-inline">
                <div class="form-group mx-sm-3 mb-2">

                  {{-- <input type="text" list="browsers_list" class="form-control" id="vichle_number" placeholder="Enter Exit Vichle Number" onkeyup="this.value = this.value.toUpperCase(); this.value = this.value.replace(/\s+/g, '-')">
                  <datalist id="browsers_list">
                    @foreach ($data as $item)
                        <option value="{{ $item->vehicle_number }}">
                    @endforeach
                  </datalist> --}}

                  <select name="vehicle_id" id="vehicle_id">
                    <option value="">Search Vehicle Number</option>
                    @foreach ($data as $item)
                        <option value="{{ $item->id }}">{{ $item->vehicle_number }}</option>
                    @endforeach
                  </select>

                </div>
                <button type="submit" class="btn btn-primary mb-2" data-action="get_info" id="qrBtn">Submit</button>
                <br>
                <span id="msg"></span>
              </form>
        </div>

        <div class="col-md-12 text-center d-flex" id="vehicle_detail"></div>



    </div>
</div>

<script>

$('#vehicle_id').select2();
$('#vehicle_id').on('select2:opening select2:closing', function( event ) {
    var $searchfield = $(this).parent().find('.select2-search__field');
    $searchfield.prop('disabled', true);
});


    const qrBtn = document.querySelector('#qrBtn');
    qrBtn.addEventListener('click',function(e){

        e.preventDefault();
        let id = $('#vehicle_id').val();
        if(id == ''){
            $('#msg').html('<span style="padding: 5px 85px;background: red;color: #fff;">Enter Vehical Number Required...</span>');
            return;
        }
        let _that = $(this);
        var currentdate = new Date();
        let current_time = currentdate.toLocaleString();

        let action = $(this).attr('data-action');

        _that.text('Please Wait Checking');
        if(id){
            csrfToken();
            $.ajax({
                type: "POST",
                url: "{{route('super_admin.exit_parking_paid')}}",
                data: { 'action' : action, 'id' : id, 'date_time' : current_time },
                success: function (response) {
                    _that.text('Submit');
                    console.log(response);
                    if(response.action == 'getInfo'){
                        $('#vehicle_detail').html(response.html);
                    }
                }
            });
        }

    });




</script>
@endsection
