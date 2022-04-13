@component('mail::message')
    # {{ __('Report Order') }}
    {{ __('From date') }}: {{ $fromDate }}
    {{ __('To date') }}: {{ $toDate }}

@component('mail::table')
    @php
        $num = 1;
        $total = 0;
    @endphp
    | {{ __('#') }} | {{ __('Create at') }} | {{ __('Order Code') }} | {{ __('User') }} | {{ __('Price') }} |
    |:-----------------:|:------------------------:|:-------------------------:|:---------------------:|:---------------------:|
    @foreach ($reports as $report)
        @php
            $total += $report->total_price;
        @endphp
    | {{ $num++ }} | {{ $report->created_at }} | {{ $report->order_code }} | {{ $report->user->name }} | {{ number_format($report->total_price) }} đ |
    @endforeach
@endcomponent
<h3 style="float: right; background: green; padding: 5px 10px; color: white">
    {{ __('Subtotal') }} : {{ number_format($total) }} đ
</h3>
@endcomponent
