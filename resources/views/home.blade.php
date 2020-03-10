@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>
                <div class="card-body">
                  <form class="" action="{{route('pay')}}" method="post" id="paymentForm">
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
                      <div class="col">
                        <label for="">Select desired payment platform?</label>
                        <div class="form-group" id="toggler">
                          <div class="btn-group btn-group btn-group-toggle" data-toggle="buttons">
                            @foreach ($paymentPlatforms as $platform)
                              <label class="btn btn-outline-secondary m-2 p-2 rounded">
                                <input type="radio"
                                       name="payment_platform"
                                       value="{{$platform->id}}"
                                       data-toggle="collapse"
                                       data-target="#{{$platform->name}}Collapse"
                                       required>
                                <img src="{{asset($platform->image)}}" class="img-thumbnail" alt="">
                              </label>
                            @endforeach
                          </div>
                            @foreach($paymentPlatforms as $platform)
                            <div id="{{$platform->name}}Collapse"
                                 data-parent="#toggler"
                                 class="collapse">
                              @includeif('components.' . strtolower($platform->name) . '-collapse')
                            </div>
                            @endforeach
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
