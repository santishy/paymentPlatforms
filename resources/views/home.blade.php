@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>
                <div class="card-body">
                  <form class="" action="index.html" method="post" id="paymentForm">
                    @csrf
                    <div class="row">
                      <div class="col-auto">
                        <label for="">How much you want to pay?</label>
                        <input type="numeric"
                               name="value"
                               min="5"
                               class="form-control"
                               step="0.1"
                               value="{{mt_rand(500,100000) / 100 }}"
                               required>
                        <small class="form-text text-muted">
                          Use values with up to two decimal positions, using dot "."
                        </small>
                      </div>
                      <div class="col-auto">
                        <label for="">Currencies</label>
                        <select class="custom-select" name="currency" required>
                          @foreach ($currencies as $currency)
                            <option value="{{$currency->iso}}">
                              {{strtoupper($currency->iso)}}
                            </option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="row mt-3">
                      <label for="">Select desired payment platform?</label>
                      <div class="form-group">
                        <div class="btn-group btn-group btn-group-toggle">

                        </div>
                      </div>
                    </div>
                    <div class="text-center mt-3">
                      <button type="submit" id="payButton" class="btn btn-primary btn-lg">Pay</button>
                    </div>
                  </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
