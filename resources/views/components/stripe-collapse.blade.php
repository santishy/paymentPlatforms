@push('styles')
  <style media="screen">
      /**
    * The CSS shown here will not be introduced in the Quickstart guide, but shows
    * how you can use CSS to style your Element's container.
    */
    .StripeElement {
    box-sizing: border-box;

    height: 40px;

    padding: 10px 12px;

    border: 1px solid transparent;
    border-radius: 4px;
    background-color: white;

    box-shadow: 0 1px 3px 0 #e6ebf1;
    -webkit-transition: box-shadow 150ms ease;
    transition: box-shadow 150ms ease;
    }

    .StripeElement--focus {
    box-shadow: 0 1px 3px 0 #cfd7df;
    }

    .StripeElement--invalid {
    border-color: #fa755a;
    }

    .StripeElement--webkit-autofill {
    background-color: #fefde5 !important;
    }
  </style>
@endpush

<label class="mt-3">Details Card</label>
<div id="card-element">
</div>
<div class="form-text text-muted" id="card-errors" role="alert">
</div>
<input type="hidden" name="payment_method" id="payment-method">

@push('scripts')
  <script src="https://js.stripe.com/v3/"></script>
  <script>

    const stripe = Stripe('{{config('services.stripe.key')}}');
    const elements = stripe.elements({locale:'en'});
    const cardElement = elements.create('card');
    cardElement.mount('#card-element')
  </script>
  <script>
    const form = document.getElementById('paymentForm');
    const payButton = document.getElementById('payButton');

    payButton.addEventListener("click", async(e) => {

      e.preventDefault();
      const {paymentMethod,error} = await stripe.createPaymentMethod(

        'card',cardElement,{
          billing_details:{
            'name':"{{auth()->user()->name}}",
            'email' : "{{auth()->user()->email}}"
          }
        });
        if(error){
          const displayErrors = document.getElementById('card-errors');
          displayErrors.textContent = error.message;
        }else{
          document.getElementById('payment-method').value = paymentMethod.id;
        }
    })
  </script>
@endpush
