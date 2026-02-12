@extends('layouts.mtheme1')
@push('style')
    <style>

     @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');




.flight_Boarding {
    padding-top: 142px;
    padding-bottom: 100px;
}

.passanger_country span {
    color: #000;
}
.passanger_country h4{
      color: #000;
}
.flightclass p {
    color: #000;
    margin-bottom: 10px;
}
.passanger_date span {
   color: #000;
}
.flight_ticket {
   background-position: center !important;
    background-repeat: no-repeat  !important;
    /*background-color: rgb(255 228 162)  !important;*/
    background-color:rgb(253 241 213) !important;
    background-blend-mode: overlay  !important;
    background-size: cover  !important;
    display: flex;
    border-bottom: 18px solid #e56013;
    box-shadow: 0px 3px 3px 2px #1a1a1a73;
   gap: 20px;
   margin-bottom:20px;
}


.pree_bording{
     background-position: center !important;
    background-repeat: no-repeat  !important;
    /*background-color: rgb(255 228 162)  !important;*/
    background-color:rgb(253 241 213) !important;
    background-blend-mode: overlay  !important;
    background-size: cover  !important;
   padding: 20px;
}
.flight_content_left {
    width: 100%;
    padding: 20px;
}

.flight_inner_content {
    display: flex;
    justify-content: space-between;
}

.flight_content_right {
    width: 30%;
    border-left: 4px dotted #e56013;
    /* padding: 8px; */
}

img.flight_logo {
    width: 70px;
    margin-left: 10px;
}

.company_flight_heading {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.company_aerplane_name {
    margin-left: 20px;
}

.company_aerplane i {
    font-size: 42px;
    color: #e56013;
    transform: rotate(360deg);
}

.company_aerplane_name h3 {
    color: #e56013;
    margin: 0px;
    font-weight: 700;
    font-size: 24px;
}

.passenger_name span {
    font-size: 18px;
    color: #000;
}

.passenger_name {
    margin-bottom: 30px;
}



.passenger_name_ticket {
    display: flex;
    align-items: center;
    gap: 80px;
    margin-bottom: 30px;
}

.passanger_country h4 {
    margin-top: 10px;
    text-transform: uppercase;
    font-weight: 600;
}

.allflight {
    padding: 10px;
}



/* .flight_inner_left {
    flex: 1;
}

.flight_inner_right {
    flex: 1;
} */


.flight_fly {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.flight_fly h3 {
    color: #e56013;
    margin: 0px;
    font-weight: 700;
    font-size: 28px;
    text-shadow: 1px 1px #e56040;
}

.flight_namee span {
    color:#000;
}

.flightclass h5 {
    font-size: 17px;
    color:#000;
}

.flight_account_image {
    margin-bottom: 20px;
}

.flight_account_co {
    background: #e56013;
    padding: 10px;
    border-radius: 4px;
    color: #fff;
    font-weight: 600;
    text-align: center;
}

.flight_account_co p {
    margin: 0px;
}

.flight_name_top {
    background: #e56013;
    padding: 16px;
    text-align: center;
    /* margin: 0px; */
}

.flight_name_top h4 {
    color: #fff;
    margin: 0px;
    font-size: 20px;
}

.flight_namee {
    margin-top: 10px;
    margin-bottom: 30px;
}

.ticket_booking_country {
    display: flex;
    align-items: center;
    /* justify-content: space-around; */
    gap: 45px;
}

.tickt_in span {
    color: #000;
    text-transform: capitalize;
    /* margin-bottom: 10px; */
}

.tickt_in h6 {
    font-weight: 800;
    font-size: 24px;
    color:#000;
    text-transform: capitalize;
    margin-top: 10px;
}

hr.ticket_line {
    width: 89%;
    /* padding: 10px; */
    opacity: 1 !important;
    color: #e56013;
    margin: 13px;
}

.ticket_booking_country_gate {
    display: flex;
    align-items: center;
    /* justify-content: space-around; */
    gap: 45px;
}

.tickt_in_gate span {
    color: #000;
    text-transform: capitalize;
}

.tickt_in_gate h6 {
    font-weight: 800;
    font-size: 16px;
    text-transform: capitalize;
    margin-top: 10px;
    color:#000;
}

.ticket_logo_ceneter {
    position: absolute;
    left: 50%;
    top: 50%;
    z-index: -1;
    transform: translate(-50%, -50%);
}

.flight_content_right {
    position: relative;
    z-index: 9;
}

.ticket_logo_ceneter img {
    width: 80px;
    filter: opacity(0.1);
}




.flight_term_content {
    display: flex;
    width: 100%;
    /* padding: 20px; */
}

.flight_team_bording {
    width: 30%;
    border-right: 3px dotted #e56013;
}

.flight_bording_content {
    background: #e56013;
    padding: 10px;
    text-align: center;
    /* font-size: 20px; */
}

.flight_bording_content h4 {
    font-size: 18px;
    font-weight: 600;
}
span.pas_n {
    font-size: 16px;
    color: #000;
}
span.pas_n {
    font-size: 16px;
    color: #000;
}

.pree_gate {
    display: flex;
    align-items: center;
    justify-content: end;
    gap: 45px;
    margin: 30px 0px;
    border-top: 1px solid #e56013;
    /* text-align: end; */
    padding: 10px 0px;
    border-bottom: 1px solid #e56013;
}

.pree_gate_india span {
    color: #000;
    text-transform: capitalize;
}

.pree_gate_india h6 {
    font-weight: 800;
    font-size: 16px;
    text-transform: capitalize;
    margin-top: 10px;
    color: #000;
}

.pree_footer {
    /* display: flex; */
    /* align-items: center; */
}

.image_pree {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.pree_india h6 {
    font-weight: 800;
    font-weight: 800;
    font-size: 16px;
    text-transform: uppercase;
    /* margin-top: 10px; */
    color: #000;
}

.pree_india {
    text-align: center;
}

.pree_india span {
    color: #000;
    text-transform: capitalize;
    display: inline-block;
    margin: 10px 0px;
}

.flight_team_conditions{
     background-position: center !important;
    background-repeat: no-repeat  !important;
    /*background-color: rgb(255 228 162)  !important;*/
    background-color:rgb(70 30 6) !important;
    background-blend-mode: overlay  !important;
    background-size: cover  !important;
   padding: 20px 40px;
}
.flight_c {
    display: flex;
    align-items: center;
    justify-content: end;
}

.company_aerplane_term i {
    color: #fff;
    font-size: 32px;
}

.company_aerplane_nameteam {
    margin-left: 15px;
}

.company_aerplane_nameteam h3 {
    color: #fff;
}

.term_ac h4 {
    color: #fff;
    font-size: 20px;
    font-weight: 700;
}

.term_ac ul {
    padding: 0px;
    margin: 0px;
    /* list-style: none; */
}

.term_ac li {
    color: #fff;
    line-height: 19px;
    font-size: 13px;
}

.flight_team_conditions {
    width: 70%;
}
    </style>
@endpush
@section('content')


     <div class="flight_Boarding" >
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="flight_ticket" style="background: url({{asset('mtheme1/assets/images/map.jpg')}});">
              <div class="flight_content_left">
                <div class="flight_inner_content">
                  <div class="flight_inner_left">
                    <div class="company_flight_heading">
                      <div class="company_aerplane">
                        <i class="fa-solid fa-plane"></i>
                      </div>
                      <div class="company_aerplane_name">
                        <h3>S2PAY FLIGHT</h3>
                      </div>
                    </div>
                    <div class="passenger_name">
                   
                           @if($flyinvestmentData)
                                <span >Name of Passanger: <b>{{ $flyinvestmentData->user->name }}</b></span> 
                            @else
                                <span>Name of Passanger: </span> 
                            @endif
                    </div>
                    <div class="passenger_name_ticket">
                      <div class="passanger_country">
                        <span>From</span>
                        <h4>india</h4>
                      </div>
                      <div class="passanger_date">
                        @if($flyinvestmentData)
                            <span>Initiated Date:<b>{{$flyinvestmentData->created_at}}</b></span>
                        @else
                            <span>Initiated Date:<b></b></span>
                        @endif
                      </div>
                    </div>
                    <div class="passenger_name_ticket">
                      <div class="passanger_country">
                        <span>To</span>
                        <h4>india</h4>
                      </div>
                      <div class="passanger_date">
                      @if($flyinvestmentData)
                   
                    
                        <span>Remaining time to get Ticket: 
                            <b id="countdown"> seconds</b>
                        </span>
                    @else
                        <span>Remaining time to get Ticket: <b>N/A</b></span>
                    @endif

                      </div>
                    </div>
                  </div>
                  <div class="flight_inner_right">
                    <div class="flight_fly">
                      <h3>FLY600</h3>
                      <img
                        src="{{asset('mtheme1/assets/images/S2_pay1.png')}}"
                        alt="images"
                        class="flight_logo"
                      />
                    </div>
                    <div class="flightclass">
                      <h5>CLASS</h5>
                      <p>ANNOUNCED SOON</p>
                    </div>
                    <div class="flight_account_image">
                      <img src="{{asset('mtheme1/assets/images/size_numebr.png')}}" />
                    </div>
                    <div class="flight_account_co">
                      <p>
                        YOU CAN BOOK TICKET<br />
                        FROM OUR S2PAY APP
                      </p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="flight_content_right">
                <div class="flight_name_top">
                     <h4>S2PAY FLY600</h4>
                </div>
                <div class="allflight">
                <div class="flight_namee">
                    @if($flyinvestmentData)
                        <span >Name of Passanger: <b>{{ $flyinvestmentData->user->name }}</b></span> 
                    @else
                        <span>Name of Passanger: </span> 
                    @endif
                </div>
                <div class="ticket_booking_country">
                    <div class="tickt_in">
                        <span>from</span>
                        <h6>india</h6>
                    </div>
                    <div class="tickt_in">
                        <span>to</span>
                        <h6>india</h6>
                    </div>
                   
                </div>
                <hr class="ticket_line"></hr>
                <div class="ticket_booking_country_gate">
                    <div class="tickt_in_gate">
                        <span>Gate</span>
                        <h6>TBA</h6>
                    </div>
                    <div class="tickt_in_gate">
                        <span>Seat</span>
                        <h6>TBA</h6>
                    </div>
                    <div class="tickt_in_gate">
                        <span>Flight</span>
                        <h6>TBA</h6>
                    </div>
                </div>
                </div>
                <div class="ticket_logo_ceneter">
                    <img src="{{asset('mtheme1/assets/images/S2_pay1.png')}}">
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="flight_term">
                    <div class=flight_term_content>
                            <div class="flight_team_bording">
                                <div class="flight_bording_content">
                                    <h4>PRE BOARDING PASS</h4>
                                </div>
                                <div class="pree_bording" style="background: url({{asset('mtheme1/assets/images/map.jpg')}});">
                                    
                                    @if($flyinvestmentData)
                                        <span class="pas_n">Name of Passanger: <b>{{ $flyinvestmentData->user->name }}</b></span> 
                                    @else
                                        <span class="pas_n">Name of Passanger </span> 
                                    @endif
                                    <div class="pree_gate">
                                        <div class="pree_gate_india">
                                            <span>gate</span>
                                            <h6>TBA</h6>
                                        </div>
                                        <div class="pree_gate_india">
                                            <span>gate</span>
                                            <h6>TBA</h6>
                                        </div>
                                        <div class="pree_gate_india">
                                            <span>gate</span>
                                            <h6>TBA</h6>
                                        </div>
                                        
                                    </div>
                                     <div class="pree_footer">
                                    <div class="image_pree">
                                        <img src="{{asset('mtheme1/assets/images/size_numebr.png')}}" />
                                        <div class="pree_india">
                                            <h6>india</h6>
                                            <span>to</span>
                                             <h6>india</h6>
                                        </div>
                                    </div>
                                </div>
                                </div>
                               
                            </div>
                            <div class="flight_team_conditions" style="background: url({{asset('mtheme1/assets/images/3439375_61772.jpg')}});">
                        <div class="flight_c">
                                    <div class="company_aerplane_term">
                                <i class="fa-solid fa-plane"></i>
                              </div>
                              <div class="company_aerplane_nameteam">
                                <h3>S2PAY FLIGHT</h3>
                              </div>
                        </div>
                        <div class="term_ac">
                            <h4>TERMS & CONDITION</h4>
                            <ul>
                                <li>Booking of ticket must be from S2PAY APP</li>
                                <li>You are eligible for Ticket Booking, once timer on your Initiating Virtual Ticket
end's</li>
<li>Platform charges applicable at the time of Booking (Nominal charges).
</li>
<li>One person is allowed on one ticket.
</li>
<li>One ticket can be used only once</li>
<li>Add Departure and Arival locations and ticket will be confirmed any date in
10 days span from date of booking</li>
                            </ul>
                        </div>
                    </div>
                    </div>                
                    
                    </div>
            </div>
        </div>
      </div>
    </div>
       
 @push('scripts')   
<script>
  // alert("{{$flyinvestmentData->created_at}}");
    var duedate =new Date("{{$flyinvestmentData->created_at}}").getTime();
    const countDownDate = new Date(duedate + 365 * 24 * 60 * 60 * 1000).getTime();
    alert(countDownDate);
   // var countDownDate = duedate.setDate(duedate.getDate() + 365);
// Set the date we're counting down to
 
 
var x = setInterval(function() {

  // Get today's date and time
  var now = new Date().getTime();
   
  // Find the distance between now and the count down date
  var distance = countDownDate - now;

//   // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

//   // Display the result in the element with id="demo"
  document.getElementById("countdown").innerHTML = days + " DAYS " + hours + " HRS "
  + minutes + " MIN ";
 
  // If the count down is finished, write some text
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("countdown").innerHTML = "EXPIRED";
  }
}, 1000);
 
   
</script>

@endpush

        
@endsection