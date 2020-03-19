@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Complete the security steps</div>

                <div class="card-body">
                    <p>You need follow some steps with your bank to Complete
                        this payment let's do it.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
  <script src="https://js.stripe.com/v3/"></script>
  <script>

    const stripe = Stripe('{{config('services.stripe.key')}}');
    stripe.handleCardAction("{{$clientSecret}}")
          .then(res => {
            if(res.error){
              window.location.replace("{{route('cancelled')}}")
            }
            else {
                window.location.replace("{{route('approval')}}")
            }
          })
  </script>
@endpush

@endsection
