<div class="card" style="width: 18rem;">
    <div class="card-body">
      <h5 class="card-title">{{ $data->vehicle_number }}</h5>
      <h6 class="card-subtitle mb-2 text-muted">{{ ($data->status) ? 'Active' : "Inactive" }}</h6>
      <p class="card-text">Enter Time : {{ $data->start_date_time }}</p>
      {{-- <a href="#" class="card-link">Card link</a> --}}
      <button class="btn btn-success" id="pay" data-payID="{{ $data->id }}" data-action="pay" >Pay</button>
      <div id="pay_msg"></div>
    </div>
  </div>


  <script>

    const pay = document.querySelector('#pay');
    pay.addEventListener('click',function(e){

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

        _that.text('Please Wait');
        if(confirm('Are You Sure?')){
            csrfToken();
            $.ajax({
                type: "POST",
                url: "{{route('super_admin.exit_parking_paid')}}",
                data: { 'action' : action, 'id' : id, 'date_time' : current_time },
                success: function (response) {
                    _that.text('Submit');
                    console.log(response);
                    if(response.action == 'pay'){
                        if(response.status == true){
                            $('#pay').remove();
                            $('#pay_msg').html('<span style="padding: 5px 30px;background: green;color: #fff;">Successfully Exit Parking Paid</span>');
                        }else{
                            $('#pay_msg').html('<span style="padding: 5px 85px;background: red;color: #fff;">Somthing Went wrong Or Check Your Internet Connection...</span>');
                        }
                    }
                }
            });
        }

    });


  </script>
