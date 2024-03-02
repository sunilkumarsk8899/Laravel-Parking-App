@extends('layouts.app')
<style>
    .blur_exist{
        filter: blur(4px);
    }
    .hidden{
        display: none;
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
            <form class="form-inline">
                <div class="form-group mx-sm-3 mb-2">
                  {{-- <label for="vichle_number" class="sr-only">Enter Vichle Number</label> --}}
                  <input type="text" class="form-control" id="vichle_number" placeholder="Enter Vichle Number" onkeyup="this.value = this.value.toUpperCase(); this.value = this.value.replace(/\s+/g, '-')">
                </div>
                <button type="submit" class="btn btn-primary mb-2" id="qrBtn">Submit</button> <br>
                <span id="msg"></span>
              </form>
        </div>

        <div class="col-md-4 text-center qrcode_div" id="qrcode_div" >
            {{-- <img src="" alt=""> --}}
            <div id="qrcode" style="padding: 10px;height:auto;width:65px;position: absolute;"></div>
            <span id="exist_msg" class="hidden" style="position: relative;right: 2.4rem;top: 9rem;">
                <button class="btn btn-danger delete_btn" id="" data-delete_id="">Delete</button>
                <button class="btn btn-success pay_btn" id="" data-pay_id="">Pay</button>

                <select name="hour" id="hour">
                    <option value="">Select Hour</option>
                    {{ $i=1 }}
                    @while ($i <= 24)
                        <option value="{{ $i*50 }}">{{ $i }}</option>
                        {{ $i++ }}
                    @endwhile
                </select>

            </span>
        </div>

        <div class="col-md-4 qrcode_div_info" id="qrcode_div_info">
            <div class="qrtext"></div>
        </div>


    </div>
</div>

<script>
    const qrBtn = document.querySelector('#qrBtn');
    qrBtn.addEventListener('click',function(e){

        e.preventDefault();
        let qrText = $('#vichle_number').val();
        if(qrText == ''){
            $('#msg').html('<span style="padding: 5px 85px;background: red;color: #fff;">Enter Vehical Number Required...</span>');
            return;
        }
        let _that = $(this);
        var currentdate = new Date();
        let current_time = currentdate.toLocaleString();

        _that.text('Please Wait');
        var qrcode = new QRCode( "qrcode",{
            text: `${'Vichle Number '+qrText+' Time'+current_time}`,
            width: 300,
            height: 300,
            colorDark : "#000000",
            colorLight : "#FFFFFF",
            correctLevel : QRCode.CorrectLevel.M
        });
        if(qrcode){
            $('.qrtext').html(` <span>Vehical Number </span><p>${qrText}</p> </br> <span>Current Time </span><p>${current_time}</p> `);
            csrfToken();
            $.ajax({
                type: "POST",
                url: "{{route('super_admin.store_vehical_record')}}",
                data: { 'vehical' : qrText, 'date_time' : current_time },
                success: function (response) {
                    _that.text('Submit');
                    console.log(response);
                    if(response.status == true){
                        $('#msg').html('<span style="padding: 5px 85px;background: green;color: #fff;">Successfully Genrate Parking Slip</span>');
                    }else if(response.status == 2){
                        $('#msg').html('<span style="padding: 5px 85px;background: red;color: #fff;">This Vechical Not Pay Parking</span>');
                        $('.qrcode_div img').addClass('blur_exist');
                        $(".delete_btn").attr("data-delete_id", response.id);
                        $(".pay_btn").attr("data-pay_id", response.id);
                        $("#exist_msg").removeClass('hidden');
                    }else{
                        $('#msg').html('<span style="padding: 5px 85px;background: red;color: #fff;">Somthing Went wrong Or Check Your Internet Connection...</span>');
                    }

                }
            });
        }

    });


    /*
     * delete
    */
   document.querySelector('.delete_btn').addEventListener('click',function(){

    let vehicleNumber = $(this).attr('data-delete_id');
    let confirm_check = confirm('Are you sure ?');
    if(confirm_check){
        $.ajax({
            type: "POST",
            url: "{{ route('super_admin.delete_vehical_record') }}",
            data: { 'id' : vehicleNumber },
            success: function (response) {
                console.log(response);
                if(response.status == true){
                    $('#msg').html('<span style="padding: 5px 85px;background: green;color: #fff;">Delete Vehical Parking Record</span>');
                    $("#qrcode_div_info").load(location.href + " #qrcode_div_info");
                    setTimeout(() => {
                        $("#qrcode_div").load(location.href + " #qrcode_div");
                    }, 1000);
                }else{
                    $('#msg').html('<span style="padding: 5px 85px;background: red;color: #fff;">Somthing Went Wrong & Check Your Internet Connection</span>');
                }
            }
        });
    }

   });


   /**
    * pay
   */
  document.querySelector('.pay_btn').addEventListener('click',function(){
    let id = $(this).attr('data-pay_id');
    let hour = $('#hour').val();
    console.log(id,hour);
    if(hour != ''){
        if(confirm("Are you sure?")){
            $.ajax({
                type: "POST",
                url: "{{ route('super_admin.pay_vehical_record') }}",
                data: { 'id' : id,'hour_paid' : hour,'msg' : 'late paid','paid_status' : 'late paid' },
                success: function (response) {
                    console.log(response);
                }
            });
        }
    }else{
        alert('Please Select Hour');
    }
  });

</script>
@endsection
