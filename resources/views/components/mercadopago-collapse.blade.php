@push('styles')

@endpush

<label class="mt-3">Details Card</label>

<div class="form-group form-row">
  <div class="col-5">
    <input type="text" id="cardNumber" placeholder="Card Number" class="form-control" data-checkout="cardNumber" l>
  </div>
  <div class="col-1">
  </div>
  <div class="col-2">
    <input type="text"  data-checkout="securityCode" class="form-control" placeholder="CVC">
  </div>
  <div class="col-1">
    <input type="text" class="form-control" data-checkout="cardExpiretionMonth" placeholder="MM">
  </div>
  <div class="col-1">
    <input type="text" class="form-control" data-checkout="cardExpiretionYear">
  </div>
</div>
<div class="form-group form-row">
  <div class="col-5">
    <input type="text" class="form-control" data-checkout="cardholderName" placeholder="YY">
  </div>
  <div class="col-5">
    <input type="email" class="form-control" data-checkout="cardholderEmail" placeholder="email@example.com">
  </div>
</div>
<div class="form-gropu form-row">
  <div class="col-2">
    <select class="custom-select" data-checkout="docType">
    </select>
  </div>
  <div class="col-3">
    <input type="text" class="form-control" data-checkout="docNumber" placeholder="Document">
  </div>
</div>
<input type="hidden" name="paymentMethodId" id="paymentMethodId" value="">
@push('scripts')
  <script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>

  <script>
    window.Mercadopago.setPublishableKey("{{config("services.mercadopago.key")}}");
    function getBin(){
      const bin = document.getElementById('cardNumber')
      return bin.value.substring(0,6);;
    }
    function setCardNetwork(){
      window.Mercadopago.getPaymentMethod({
        'bin':getBin()
      },setPaymentMethodInfo)
    }
    function setPaymentMethodInfo(status,response){
      if(status == 200){
        const paymentMethodId = document.getElementById('paymentMethodId');
        paymentMethodId.value = response[0].id;
      }
    }
  </script>

@endpush
