@section('main')

<script src="https://eu-test.oppwa.com/v1/paymentWidgets.js?checkoutId={{$responseData['id']}}"></script>

<form action="{{route('offers.show',$id)}}" class="paymentWidgets" data-brands="VISA MASTER "></form>

@stop
