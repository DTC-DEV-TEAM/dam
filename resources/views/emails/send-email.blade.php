<!DOCTYPE html>
<html>
<head>
    <title>Digits Asset Management System</title>
</head>
<body>
    
<p>Hi<span style="font-weight: 700;">&nbsp;{{ $infos['assign_to'] }}</span></p>

<p>You have changes in the Item Details, Please see details below.</p>

    <div class="row" style="display:flex;">
        <div class="col1" style="margin-right: 600px">
        <p><span style="font-weight: 700;">REF#:&nbsp;</span>
            <span style="text-align: center;">{{ $infos['reference_number'] }}</span>
            </p>
        </div>
    </div>

    <table border="1" width="100%" style="text-align: center;">
        <tbody>
        <tr>
            <th class="control-label col-md-2">Item Description:</th>
            <td class="col-md-4">{{$infos['item_description']}}</td>     
        </tr>

        <tr>
            <th class="control-label col-md-2">Brand:</th>
            <td class="col-md-4">{{$infos['brand']}}</td>
        </tr>
        <tr>
            <th class="control-label col-md-2">Model:</th>
            <td class="col-md-4">{{$infos['model']}}</td>
        </tr>
        <tr>
            <th class="control-label col-md-2">Size:</th>
            <td class="col-md-4">{{$infos['size']}}</td>
        </tr>
        <tr>
            <th class="control-label col-md-2">Actual Color:</th>
            <td class="col-md-4">{{$infos['actual_color']}}</td>
        </tr>
        <tr>
            <th class="control-label col-md-2">Quantity:</th>
            <td class="col-md-4">{{$infos['quantity']}}</td>
        </tr>
        </tbody>
    </table>

</body>
</html>