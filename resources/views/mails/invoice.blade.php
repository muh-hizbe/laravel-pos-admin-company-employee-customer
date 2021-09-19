<!DOCTYPE html>
<html>
<head>
    <title>Invoice - {{ $details['product'] }}</title>
</head>
<body style="background-color: rgb(250, 250, 250);">

<center>
<h2 class="font-size: 1.875rem; line-height: 2.25rem; font-weight: 700;">
    Invoice - {{ $details['product'] }}
</h2>
</center>

<div style="width:100%; padding: 1rem;">
    <div style="max-width:100%; text-align:center; background-color: rgb(255, 255, 255); drop-shadow(0 1px 2px rgba(148, 148, 148, 0.349)); border-radius: 0.375rem; padding: 1rem;">
        <table style="border: 1px solid rgb(136, 136, 136); border-collapse: collapse; border-radius: 0.375rem; width: 100%;">
            <thead>
                <tr style="text-align: center; border-bottom: 1px rgb(112, 112, 112) solid;">
                    <th>Product</th>
                    <th>Price</th>
                    <th>Discount Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr style="text-align: center; border-bottom: 1px rgb(112, 112, 112) solid;">
                    <td>{{ $details['product'] }}</td>
                    @isset($details['discount_price'])
                        <td style="text-decoration: line-through; color: red;">@money($details['price'])</td>
                        <td>@money($details['discount_price'])</td>
                        <td>{{ $details['quantity'] }}</td>
                        <td>{{ $details['quantity']*$details['discount_price'] }}</td>
                    @else
                        <td>@money($details['price'])</td>
                        <td>-</td>
                        <td>{{ $details['quantity'] }}</td>
                        <td>{{ $details['quantity']*$details['price'] }}</td>
                    @endisset
                </tr>
            </tbody>
        </table>
    </div>

    <p>Congratulations! Your packets has been paid.</p>
    <p>We have so happy <b>{{ $details['name']}}</b></p>

    <p>
        <span>Best,</span>
        <br>
        <br>
    </p>
    <p>
        <strong>{{ config('app.name') }}</strong>
    </p>
</div>

</body>
</html>
