<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Notification</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f4f5f7;
            color: #333333;
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid #e1e4e8;
        }
        .email-header {
            background-color: #1a1f2c;
            padding: 32px;
            text-align: center;
        }
        .email-header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 600;
            letter-spacing: -0.5px;
        }
        .email-body {
            padding: 32px;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            border-radius: 20px;
            letter-spacing: 0.5px;
            margin-bottom: 24px;
            background-color: {{ $action === 'created' ? '#e6f4ea' : '#e8f0fe' }};
            color: {{ $action === 'created' ? '#137333' : '#1a73e8' }};
        }
        .product-title {
            font-size: 20px;
            font-weight: 600;
            margin-top: 0;
            margin-bottom: 8px;
            color: #111111;
        }
        .product-desc {
            font-size: 15px;
            color: #666666;
            line-height: 1.6;
            margin-bottom: 24px;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
            border-top: 1px solid #ededed;
        }
        .meta-table td {
            padding: 14px 0;
            border-bottom: 1px solid #ededed;
            font-size: 15px;
        }
        .meta-label {
            color: #777777;
            width: 30%;
        }
        .meta-value {
            color: #111111;
            font-weight: 500;
            text-align: right;
        }
        .price-tag {
            color: #2e7d32;
            font-weight: 700;
        }
        .email-footer {
            background-color: #fafafa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #999999;
            border-top: 1px solid #ededed;
        }
    </style>
</head>
<body>

    <div class="email-container">
        <div class="email-header">
            <h1>System Generated</h1>
        </div>

        <div class="email-body">
            <span class="status-badge">
                Product {{ $action }}
            </span>

            <h2 class="product-title">{{ $product->name }}</h2>
            <p class="product-desc">{{ $product->description ?: 'No description provided for this product.' }}</p>

            <table class="meta-table">
                <tr>
                    <td class="meta-label">Category</td>
                    <td class="meta-value">{{ $product->category->name ?? 'Unassigned' }}</td>
                </tr>
                <tr>
                    <td class="meta-label">Price</td>
                    <td class="meta-value price-tag">${{ number_format($product->price, 2) }}</td>
                </tr>
            </table>
        </div>

        <div class="email-footer">
            This is an automated notification from your catalog platform.
        </div>
    </div>

</body>
</html>
