@props([
    'color' => '#ff1d5e'
])
<div {{ $attributes->merge(['class' => 'spring-spinner flex']) }}>
  <div class="spring-spinner-part top-0 left-0">
    <div class="spring-spinner-rotator h-5 w-5"></div>
  </div>
  {{-- <div class="spring-spinner-part top-0 left-0">
    <div class="spring-spinner-rotator h-5 w-5"></div>
  </div> --}}
</div>

@push('style')
<style>
    .spring-spinner, .spring-spinner * {
      box-sizing: border-box;
    }

    .spring-spinner {
      /* height: 60px;
      width: 60px; */
    }

    .spring-spinner .spring-spinner-part {
      overflow: hidden;
      /* height: calc(60px / 2);
      width: 60px; */
    }

    .spring-spinner  .spring-spinner-part.bottom {
       transform: rotate(180deg) scale(-1, 1);
     }

    .spring-spinner .spring-spinner-rotator {
      /* width: 60px;
      height: 60px; */
      border: calc(10px / 10) solid transparent;
      border-right-color: #ffffff;
      border-top-color: #ffffff;
      border-radius: 50%;
      box-sizing: border-box;
      animation: spring-spinner-animation 3s ease-in-out infinite;
      transform: rotate(-200deg);
    }

    @keyframes spring-spinner-animation {
      0% {
        border-width: calc(60px / 20);
      }
      25% {
        border-width: calc(60px / 23.33);
      }
      50% {
        transform: rotate(110deg);
        border-width: calc(60px / 20);
      }
      75% {
        border-width: calc(60px / 23.33);
      }
      100% {
        border-width: calc(60px / 20);
      }
    }
</style>
@endpush
